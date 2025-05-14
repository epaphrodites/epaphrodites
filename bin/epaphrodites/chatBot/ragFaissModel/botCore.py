import os
import warnings
import numpy as np
import faiss
from sentence_transformers import SentenceTransformer

# Ignorer les avertissements non critiques pour une sortie plus propre
warnings.filterwarnings("ignore")

# Déterminer quelle API LangChain utiliser
try:
    from langchain_ollama import OllamaLLM
    USE_NEW_API = True
except ImportError:
    from langchain_community.llms import Ollama
    USE_NEW_API = False

# Configuration des constantes
EMBEDDING_MODEL = "all-MiniLM-L6-v2"
BASE_DIR = os.path.abspath(os.path.dirname(__file__))
INDEX_FILE = os.path.join(BASE_DIR, "../../../database/datas/vector-data/faiss_index.idx")
METADATA_FILE = os.path.join(BASE_DIR, "../../../database/datas/vector-data/chunks_metadata.npy")

class BotCore:
    def __init__(self):
        """Initialise le moteur de recherche sémantique et le modèle de langage."""
        self.embedding_model = SentenceTransformer(EMBEDDING_MODEL)
        self.index = faiss.read_index(INDEX_FILE)
        data = np.load(METADATA_FILE, allow_pickle=True).item()
        self.chunks_text = data["chunks_text"]
        self.chunks_metadata = data["chunks_metadata"]
        
        # Initialisation du modèle LLM
        if USE_NEW_API:
            self.llm = OllamaLLM(model="llama3:8b")
        else:
            self.llm = Ollama(model="llama3:8b")
    
    def search(self, query: str, top_k: int = 5) -> list:
        """Effectue une recherche sémantique à partir d'une requête."""
        query_embedding = self.embedding_model.encode([query])
        distances, indices = self.index.search(query_embedding, top_k)
        results = []
        for i, idx in enumerate(indices[0]):
            if idx < len(self.chunks_text):
                results.append({
                    "chunk": self.chunks_text[idx],
                    "metadata": self.chunks_metadata[idx],
                    "distance": distances[0][i]
                })
        return results
    
    def create_prompt(self, query: str, context: str) -> str:
        """Crée un prompt pour le modèle LLM basé sur le contexte et la requête."""
        return f"""Tu es un assistant IA qui répond aux questions en utilisant uniquement les informations fournies dans le contexte ci-dessous. Si la réponse ne se trouve pas dans le contexte, dis simplement que tu ne sais pas.

CONTEXTE: {context}

QUESTION: {query}

RÉPONSE:"""
    
    def generate_answer(self, query: str, context: str) -> str:
        """Génère une réponse à partir du contexte et de la requête."""
        prompt = self.create_prompt(query, context)
        try:
            if hasattr(self.llm, 'stream'):
                full_response = ""
                for chunk in self.llm.stream(prompt):
                    full_response += chunk
                return full_response
            return self.llm.invoke(prompt)
        except Exception as e:
            return f"Erreur lors de la génération de la réponse : {str(e)}"
    
    def stream_answer(self, query: str):
        """Génère une réponse en mode streaming."""
        try:
            results = self.search(query)
            context = "\n\n".join([
                f"Document: {res['metadata']['filename']}\n{res['chunk']}"
                for res in results
            ])
            prompt = self.create_prompt(query, context)
            
            if hasattr(self.llm, 'stream'):
                for chunk in self.llm.stream(prompt):
                    yield chunk
            else:
                yield self.llm.invoke(prompt)
        except Exception as e:
            yield f"Une erreur est survenue : {str(e)}"
    
    def ask(self, query: str) -> str:
        """Génère une réponse complète à partir d'une question."""
        try:
            results = self.search(query)
            context = "\n\n".join([
                f"Document: {res['metadata']['filename']}\n{res['chunk']}"
                for res in results
            ])
            return self.generate_answer(query, context)
        except Exception as e:
            return f"Une erreur est survenue : {str(e)}"