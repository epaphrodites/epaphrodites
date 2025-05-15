import os
import sys
import re
import numpy as np
import traceback
from pathlib import Path
from typing import List, Dict, Tuple, Optional
from tqdm import tqdm
import gc

os.environ["TOKENIZERS_PARALLELISM"] = "false"

sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/botConfig/')
from constants import EMBEDDING_MODEL, TEXT_DIR, DATA_DIR, INDEX_FILE, METADATA_FILE, BATCH_SIZE, CHUNK_SIZE, CHUNK_OVERLAP

class IndexTrainer:
    def __init__(self, text_dir: str = TEXT_DIR):
        self.text_dir = text_dir
        self.chunks_text = []
        self.chunks_metadata = []

    def load_embedding_model(self):
        """Charger le modèle d'embedding avec gestion d'erreur"""
        try:
            from sentence_transformers import SentenceTransformer
            print(f"Chargement du modèle d'embedding: {EMBEDDING_MODEL}")
            self.embedding_model = SentenceTransformer(EMBEDDING_MODEL)
            print("✅ Modèle d'embedding chargé avec succès")
            return True
        except Exception as e:
            print(f"❌ Erreur lors du chargement du modèle d'embedding: {e}")
            traceback.print_exc()
            return False

    def extract_text_from_files(self) -> List[Dict]:
        """Extraire le texte des fichiers avec une meilleure gestion d'erreur"""
        all_docs = []
        
        if not os.path.exists(self.text_dir):
            print(f"❌ Le répertoire {self.text_dir} n'existe pas!")
            return all_docs
        
        all_files = os.listdir(self.text_dir)
        print(f"Nombre de fichiers trouvés dans le répertoire: {len(all_files)}")
            
        text_paths = []
        
        # Méthode simplifiée pour trouver les fichiers .txt
        for file in all_files:
            file_path = os.path.join(self.text_dir, file)
            if os.path.isfile(file_path) and file.lower().endswith('.txt'):
                text_paths.append(Path(file_path))
        
        if not text_paths:
            print(f"⚠️ Aucun fichier texte (.txt) trouvé dans {self.text_dir}")
            return all_docs
            
        print(f"Fichiers texte à traiter: {len(text_paths)}")
        
        for text_path in tqdm(text_paths, desc="Lecture des fichiers"):
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
                    else:
                        print(f"⚠️ Fichier vide: {text_path}")
            except Exception as e:
                print(f"❌ Erreur de lecture {text_path}: {e}")
        
        print(f"Nombre de documents extraits: {len(all_docs)}")
        return all_docs

    def chunk_documents(self, documents: List[Dict]) -> Tuple[List[str], List[Dict]]:
        """Découper les documents en chunks avec une meilleure gestion de la mémoire"""
        if not documents:
            print("❌ Aucun document à découper en chunks")
            return self.chunks_text, self.chunks_metadata
            
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
                    
                    # Gestion de l'overlap
                    if CHUNK_OVERLAP > 0 and len(current_chunk) > CHUNK_OVERLAP:
                        # Calculer l'overlap basé sur les mots plutôt que les caractères
                        words = current_chunk.split()
                        overlap_word_count = min(len(words), CHUNK_OVERLAP // 5)  # Approximation: 5 caractères par mot
                        current_chunk = " ".join(words[-overlap_word_count:]) + " " + sentence
                    else:
                        current_chunk = sentence
            
            # Ajouter le dernier chunk s'il n'est pas vide
            if current_chunk:
                self.chunks_text.append(current_chunk.strip())
                self.chunks_metadata.append({**metadata, "chunk_id": len(self.chunks_text)})
        
        print(f"Nombre total de chunks créés: {len(self.chunks_text)}")
        return self.chunks_text, self.chunks_metadata

    def create_embeddings(self) -> Optional[np.ndarray]:
        """Créer les embeddings avec une meilleure gestion de la mémoire et des erreurs"""
        if not self.chunks_text:
            print("❌ Aucun chunk à encoder. Impossible de créer des embeddings.")
            return None
        
        # Vérifier que le modèle d'embedding est chargé
        if not hasattr(self, 'embedding_model'):
            success = self.load_embedding_model()
            if not success:
                return None
            
        try:
            embeddings = []
            print(f"Création des embeddings pour {len(self.chunks_text)} chunks en batches de {BATCH_SIZE}")
            
            for i in tqdm(range(0, len(self.chunks_text), BATCH_SIZE), desc="Création des embeddings"):
                batch = self.chunks_text[i:i + BATCH_SIZE]
                try:
                    batch_embeddings = self.embedding_model.encode(
                        batch,
                        show_progress_bar=False,
                        convert_to_numpy=True,
                        normalize_embeddings=True  # Normalisation recommandée pour FAISS
                    )
                    embeddings.append(batch_embeddings)
                    
                    # Force garbage collection après chaque batch pour libérer la mémoire
                    if i % (BATCH_SIZE * 5) == 0:
                        gc.collect()
                        
                except Exception as e:
                    print(f"❌ Erreur lors de l'encoding du batch {i} à {i+len(batch)}: {e}")
                    traceback.print_exc()
                    continue
            
            if not embeddings:
                print("❌ Aucun embedding créé.")
                return None
                
            print(f"Nombre de batches d'embeddings créés: {len(embeddings)}")
            
            # Concaténer tous les embeddings
            try:
                all_embeddings = np.vstack(embeddings)
                print(f"Dimension finale des embeddings: {all_embeddings.shape}")
                return all_embeddings
            except Exception as e:
                print(f"❌ Erreur lors de la concaténation des embeddings: {e}")
                traceback.print_exc()
                return None
                
        except Exception as e:
            print(f"❌ Erreur générale lors de la création des embeddings: {e}")
            traceback.print_exc()
            return None

    def build_and_save_index(self, embeddings: np.ndarray) -> bool:
        """Construire et sauvegarder l'index FAISS avec une meilleure gestion d'erreur"""
        if embeddings is None or embeddings.size == 0:
            print("❌ Aucun embedding à indexer. Index non créé.")
            return False
        
        try:
            import faiss
            print("Création de l'index FAISS...")
            
            dimension = embeddings.shape[1]
            print(f"Dimension des embeddings: {dimension}")
            
            # Utiliser un index plus simple pour éviter les erreurs de segmentation
            try:
                # IndexFlatL2 est plus stable que les index plus complexes
                index = faiss.IndexFlatL2(dimension)
                index.add(embeddings.astype(np.float32))  # Convertir explicitement en float32
                print("✅ Index FAISS créé avec succès")
            except Exception as e:
                print(f"❌ Erreur lors de la création de l'index: {e}")
                traceback.print_exc()
                return False
            
            # Sauvegarder l'index
            try:
                faiss.write_index(index, INDEX_FILE)
                print(f"✅ Index sauvegardé dans {INDEX_FILE}")
            except Exception as e:
                print(f"❌ Erreur lors de la sauvegarde de l'index: {e}")
                traceback.print_exc()
                return False
            
            # Sauvegarder les métadonnées
            try:
                metadata_dict = {
                    "chunks_text": self.chunks_text,
                    "chunks_metadata": self.chunks_metadata
                }
                np.save(METADATA_FILE, metadata_dict)
                print(f"✅ Métadonnées sauvegardées dans {METADATA_FILE}")
                return True
            except Exception as e:
                print(f"❌ Erreur lors de la sauvegarde des métadonnées: {e}")
                traceback.print_exc()
                return False
                
        except ImportError:
            print("❌ Module FAISS non disponible. Veuillez l'installer avec 'pip install faiss-cpu' ou 'pip install faiss-gpu'")
            return False
        except Exception as e:
            print(f"❌ Erreur inattendue: {e}")
            traceback.print_exc()
            return False

    def run(self) -> bool:
        """Exécuter le processus complet avec gestion d'erreur"""
        try:
            # Étape 1: Charger le modèle d'embedding
            print("1. Chargement du modèle d'embedding...")
            if not self.load_embedding_model():
                return False
            
            # Étape 2: Extraire les textes
            print("\n2. Extraction des textes des fichiers...")
            docs = self.extract_text_from_files()
            if not docs:
                print("❌ Aucun document trouvé. Arrêt du processus.")
                return False
                
            # Étape 3: Découper en chunks
            print("\n3. Découpage des documents en chunks...")
            self.chunk_documents(docs)
            if not self.chunks_text:
                print("❌ Aucun chunk créé. Arrêt du processus.")
                return False
                
            # Étape 4: Créer les embeddings
            print("\n4. Création des embeddings...")
            embeddings = self.create_embeddings()
            if embeddings is None:
                print("❌ Échec de la création des embeddings. Arrêt du processus.")
                return False
            
            # Étape 5: Construire et sauvegarder l'index
            print("\n5. Construction et sauvegarde de l'index FAISS...")
            success = self.build_and_save_index(embeddings)
            if not success:
                print("❌ Échec de la construction de l'index. Arrêt du processus.")
                return False
            
            print("\n✅ Processus terminé avec succès!")
            return True
            
        except Exception as e:
            print(f"❌ Erreur générale: {e}")
            traceback.print_exc()
            return False

if __name__ == "__main__":
    try:
        print("=" * 80)
        print("Démarrage du processus d'indexation RAG avec FAISS")
        print("=" * 80)
        
        trainer = IndexTrainer()
        success = trainer.run()
        
        print("\n" + "=" * 80)
        if success:
            print("✅ L'indexation a été réalisée avec succès!")
        else:
            print("❌ L'indexation a échoué. Veuillez consulter les erreurs ci-dessus.")
        print("=" * 80)
        
    except Exception as e:
        print("=" * 80)
        print(f"❌ Exception non gérée: {e}")
        traceback.print_exc()
        print("=" * 80)
        sys.exit(1) 