# train_index.py
import os
import sys
import re
import faiss
import numpy as np
from pathlib import Path
from typing import List, Dict, Tuple
from sentence_transformers import SentenceTransformer
from tqdm import tqdm

sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/botConfig/')
from constants import EMBEDDING_MODEL, TEXT_DIR, DATA_DIR, INDEX_FILE, METADATA_FILE, BATCH_SIZE, CHUNK_SIZE, CHUNK_OVERLAP

class IndexTrainer:
    def __init__(self, text_dir: str = TEXT_DIR):
        self.text_dir = text_dir
        self.embedding_model = SentenceTransformer(EMBEDDING_MODEL)
        self.chunks_text = []
        self.chunks_metadata = []
        print(f"Recherche de fichiers dans: {os.path.abspath(self.text_dir)}")

    def extract_text_from_files(self) -> List[Dict]:
        all_docs = []
        text_files = list(Path(self.text_dir).glob("**/*.txt"))
        
        if not text_files:
            print(f"⚠️ Aucun fichier texte trouvé dans {self.text_dir}")
            print(f"Chemin absolu: {os.path.abspath(self.text_dir)}")
            return all_docs
            
        print(f"Nombre de fichiers trouvés: {len(text_files)}")
        
        for text_path in tqdm(text_files, desc="Reading files"):
            try:
                with open(text_path, 'r', encoding='utf-8') as file:
                    text = file.read().strip()
                    if text:
                        all_docs.append({
                            "content": text,
                            "metadata": {
                                "source": str(text_path),
                                "filename": text_path.name
                            }
                        })
            except Exception as e:
                print(f"Error reading {text_path}: {e}")
        
        if not all_docs:
            print("⚠️ Aucun document n'a été extrait des fichiers")
        else:
            print(f"Documents extraits: {len(all_docs)}")
        
        return all_docs

    def chunk_documents(self, documents: List[Dict]) -> Tuple[List[str], List[Dict]]:
        for doc in tqdm(documents, desc="Chunking documents"):
            text = re.sub(r'\s+', ' ', doc["content"])
            metadata = doc["metadata"]
            sentences = re.split(r'(?<=[.!?])\s+', text)
            current_chunk = ""
            for sentence in sentences:
                if len(current_chunk) + len(sentence) <= CHUNK_SIZE:
                    current_chunk += " " + sentence if current_chunk else sentence
                else:
                    self.chunks_text.append(current_chunk.strip())
                    self.chunks_metadata.append({**metadata, "chunk_id": len(self.chunks_text)})
                    current_chunk = current_chunk[-CHUNK_OVERLAP:] + " " + sentence if CHUNK_OVERLAP > 0 else sentence
            if current_chunk:
                self.chunks_text.append(current_chunk.strip())
                self.chunks_metadata.append({**metadata, "chunk_id": len(self.chunks_text)})
        
        print(f"Nombre total de chunks créés: {len(self.chunks_text)}")
        return self.chunks_text, self.chunks_metadata

    def create_embeddings(self) -> np.ndarray:
        if not self.chunks_text:
            print("❌ Aucun chunk à encoder. Impossible de créer des embeddings.")
            return np.array([])
            
        embeddings = []
        for i in tqdm(range(0, len(self.chunks_text), BATCH_SIZE), desc="Creating embeddings"):
            batch = self.chunks_text[i:i + BATCH_SIZE]
            batch_embeddings = self.embedding_model.encode(batch)
            embeddings.extend(batch_embeddings)  # Use extend instead of append for individual embeddings
        
        if not embeddings:
            print("❌ Aucun embedding créé.")
            return np.array([])
            
        print(f"Nombre d'embeddings créés: {len(embeddings)}")
        return np.vstack(embeddings) if len(embeddings) > 0 else np.array([])

    def build_and_save_index(self, embeddings: np.ndarray):
        if embeddings.size == 0:
            print("❌ Aucun embedding à indexer. Index non créé.")
            return
            
        dimension = embeddings.shape[1]
        index = faiss.IndexFlatL2(dimension)
        index.add(embeddings)
        
        os.makedirs(DATA_DIR, exist_ok=True)
        faiss.write_index(index, INDEX_FILE)
        
        # Save metadata and chunks
        metadata_dict = {
            "chunks_text": self.chunks_text,
            "chunks_metadata": self.chunks_metadata
        }
        np.save(METADATA_FILE, metadata_dict)
        print(f"Index et métadonnées sauvegardés dans {DATA_DIR}")

    def run(self):
        docs = self.extract_text_from_files()
        if not docs:
            print("❌ Aucun document trouvé. Impossible de continuer.")
            return
            
        self.chunk_documents(docs)
        if not self.chunks_text:
            print("❌ Aucun chunk de texte créé. Impossible de continuer.")
            return
            
        print(f"Chunks créés: {len(self.chunks_text)}")
        embeddings = self.create_embeddings()
        
        if embeddings.size > 0:
            self.build_and_save_index(embeddings)
            print(f"\nIndex saved......................................................................✅")
        else:
            print("❌ Impossible de créer l'index: aucun embedding créé.")

if __name__ == "__main__":
    trainer = IndexTrainer()
    trainer.run()