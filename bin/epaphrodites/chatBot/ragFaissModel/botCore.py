import os
import sys
import numpy as np
import traceback
from typing import List, Dict, Tuple, Any, Union

# Configuration des chemins et constantes
os.environ["TOKENIZERS_PARALLELISM"] = "false"

# Définir directement les constantes dans ce fichier
EMBEDDING_MODEL = "all-MiniLM-L6-v2"
BASE_DIR = os.path.abspath(os.path.dirname(__file__))
INDEX_FILE = os.path.join(BASE_DIR, "../../../database/datas/vector-data/faiss_index.idx")
METADATA_FILE = os.path.join(BASE_DIR, "../../../database/datas/vector-data/chunks_metadata.npy")
TOP_K_RESULTS = 5

class BotCore:

    def __init__(self):
        self.embedding_model = None
        self.index = None
        self.chunks_text = []
        self.chunks_metadata = []
        self.is_initialized = False
        
        # Initialiser automatiquement à la création de l'instance
        try:
            self.initialize()
        except Exception as e:
            print(f"❌ Erreur d'initialisation automatique: {e}", file=sys.stderr)
    
    def initialize(self) -> bool:
        try:

            if not os.path.exists(INDEX_FILE):
                print(f"❌ Le fichier d'index {INDEX_FILE} n'existe pas.")
                return False
                
            if not os.path.exists(METADATA_FILE):
                print(f"❌ Le fichier de métadonnées {METADATA_FILE} n'existe pas.")
                return False
            
            try:
                from sentence_transformers import SentenceTransformer
                self.embedding_model = SentenceTransformer(EMBEDDING_MODEL)
            except Exception as e:
                traceback.print_exc()
                return False
        
            try:
                import faiss
                self.index = faiss.read_index(INDEX_FILE)

            except Exception as e:

                traceback.print_exc()
                return False
            
            try:
                metadata_dict = np.load(METADATA_FILE, allow_pickle=True).item()
                self.chunks_text = metadata_dict.get("chunks_text", [])
                self.chunks_metadata = metadata_dict.get("chunks_metadata", [])
            except Exception as e:
                traceback.print_exc()
                return False
            
            self.is_initialized = True
            return True
            
        except Exception as e:
            print(f"❌ Erreur lors de l'initialisation: {e}")
            traceback.print_exc()
            return False
    
    def search(self, query: str, top_k: int = TOP_K_RESULTS) -> List[Dict[str, Any]]:
        if not self.is_initialized:
            print("❌ Le moteur n'est pas initialisé. Exécutez initialize() d'abord.")
            return []
        
        try:
            # Encoder la requête
            query_embedding = self.embedding_model.encode(
                query,
                convert_to_numpy=True,
                normalize_embeddings=True
            ).reshape(1, -1).astype(np.float32)
            
            # Rechercher les chunks les plus similaires
            distances, indices = self.index.search(query_embedding, min(top_k, len(self.chunks_text)))
            
            # Préparer les résultats
            results = []
            for i, (dist, idx) in enumerate(zip(distances[0], indices[0])):
                if idx >= 0 and idx < len(self.chunks_text):  # Vérifier que l'indice est valide
                    # Convertir la distance L2 en score de similarité (plus petit = plus similaire, donc inverser)
                    similarity_score = 1.0 / (1.0 + dist)
                    
                    result = {
                        "rank": i + 1,
                        "chunk_id": idx,
                        "similarity_score": similarity_score,
                        "text": self.chunks_text[idx],
                        "metadata": self.chunks_metadata[idx] if idx < len(self.chunks_metadata) else {"source": "unknown"}
                    }
                    results.append(result)
            
            return results
            
        except Exception as e:
            print(f"❌ Erreur lors de la recherche: {e}")
            traceback.print_exc()
            return []
    
    def format_results(self, results: List[Dict[str, Any]], with_metadata: bool = True) -> str:
        if not results:
            return "Aucun résultat trouvé."
        
        formatted_results = []
        for result in results:
            result_str = f"🔍 Résultat #{result['rank']} (Score: {result['similarity_score']:.4f})\n"
            result_str += f"{'—' * 50}\n"
            
            if with_metadata:
                metadata = result.get('metadata', {})
                source = metadata.get('source', 'inconnu')
                result_str += f"📄 Source: {source}\n"
                result_str += f"{'—' * 50}\n"
            
            text = result.get('text', '')
            if len(text) > 500:
                text = text[:500] + "..."
            
            result_str += f"{text}\n"
            result_str += f"{'—' * 50}\n"
            formatted_results.append(result_str)
        
        return "\n".join(formatted_results)
    
    def generate_response(self, query: str, results: List[Dict[str, Any]]) -> str:
        if not results:
            return "Je ne trouve pas d'information pertinente pour répondre à cette question."
        
        return f"Voici la réponse la plus pertinente à votre question:\n\n{results[0]['text']}"
    
    def ask(self, question: str) -> Union[str, Dict[str, Any]]:

        try:
            # S'assurer que le moteur est initialisé
            if not self.is_initialized:
                success = self.initialize()
                if not success:
                    return "Échec de l'initialisation du moteur de recherche."
            
            # Effectuer la recherche
            results = self.search(question)
            
            if not results:
                return "Je ne trouve pas d'information pertinente pour répondre à cette question."
            
            return results[0]['text']
            
        except Exception as e:

            print(f"Erreur dans ask(): {e}", file=sys.stderr)
            return "Je ne peux pas répondre à cette question pour le moment."
