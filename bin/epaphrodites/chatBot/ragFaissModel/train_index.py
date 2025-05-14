# train_index.py

import os
import re
import faiss
import numpy as np
from pathlib import Path
from typing import List, Dict, Tuple
from sentence_transformers import SentenceTransformer
from tqdm import tqdm
import signal
import sys

# Gestion du crash type Segmentation Fault
def sigsegv_handler(signum, frame):
    print("❌ Crash détecté : Segmentation fault pendant l'encodage. Vérifie PyTorch ou le modèle utilisé.")
    sys.exit(1)

signal.signal(signal.SIGSEGV, sigsegv_handler)

# Chemins absolus
BASE_DIR = os.path.abspath(os.path.dirname(__file__))
TEXT_DIR = os.path.abspath(os.path.join(BASE_DIR, "../../../../static/docs/base-data"))
DATA_DIR = os.path.abspath(os.path.join(BASE_DIR, "../../../database/datas/vector-data"))

# Paramètres
CHUNK_SIZE = 500
CHUNK_OVERLAP = 50
EMBEDDING_MODEL = "sentence-transformers/paraphrase-MiniLM-L3-v2"  # ou "all-MiniLM-L6-v2"
BATCH_SIZE = 32

# Fichiers générés
INDEX_FILE = os.path.join(DATA_DIR, "faiss_index.idx")
METADATA_FILE = os.path.join(DATA_DIR, "chunks_metadata.npy")

class IndexTrainer:
    def __init__(self, text_dir: str = TEXT_DIR):
        self.text_dir = text_dir
        self.embedding_model = SentenceTransformer(EMBEDDING_MODEL)
        self.chunks_text = []
        self.chunks_metadata = []

    def extract_text_from_files(self) -> List[Dict]:
        all_docs = []
        text_files = list(Path(self.text_dir).glob("**/*.txt"))

        for text_path in tqdm(text_files, desc="Lecture fichiers"):
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
                print(f"Erreur lors de la lecture de {text_path}: {e}")
        return all_docs

    def chunk_documents(self, documents: List[Dict]) -> Tuple[List[str], List[Dict]]:
        for doc in tqdm(documents, desc="Découpage des documents"):
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

    def create_embeddings(self) -> np.ndarray:
        embeddings = []
        for i in tqdm(range(0, len(self.chunks_text), BATCH_SIZE), desc="Création des embeddings"):
            batch = self.chunks_text[i:i + BATCH_SIZE]
            try:
                batch_embeddings = self.embedding_model.encode(
                    batch,
                    show_progress_bar=False,
                    normalize_embeddings=True,
                    convert_to_numpy=True
                )
                embeddings.append(batch_embeddings)
            except Exception as e:
                print(f"❌ Erreur pendant l'encodage du batch {i}: {e}")
                sys.exit(1)
        return np.vstack(embeddings)

    def build_and_save_index(self, embeddings: np.ndarray):
        dimension = embeddings.shape[1]
        index = faiss.IndexFlatL2(dimension)
        index.add(embeddings)

        os.makedirs(DATA_DIR, exist_ok=True)
        faiss.write_index(index, INDEX_FILE)
        np.save(METADATA_FILE, {
            "chunks_text": self.chunks_text,
            "chunks_metadata": self.chunks_metadata
        })

    def run(self):
        docs = self.extract_text_from_files()
        self.chunk_documents(docs)
        embeddings = self.create_embeddings()
        self.build_and_save_index(embeddings)
        print(f"\n✅ Index sauvegardé dans {DATA_DIR}")

if __name__ == "__main__":
    trainer = IndexTrainer()
    trainer.run()
