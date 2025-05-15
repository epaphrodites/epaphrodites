import os
import sys
import warnings
warnings.filterwarnings("ignore") 
import numpy as np
import faiss
from sentence_transformers import SentenceTransformer 
sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/botConfig/')
from constants import EMBEDDING_MODEL, INDEX_FILE, METADATA_FILE, LLAMA_MODEL

try:
    from langchain_ollama import OllamaLLM
    USE_NEW_API = True
except ImportError:
    from langchain_community.llms import Ollama
    USE_NEW_API = False

class botCore:
    def __init__(self):
        self.embedding_model = SentenceTransformer(EMBEDDING_MODEL)
        self.index = faiss.read_index(INDEX_FILE)
        data = np.load(METADATA_FILE, allow_pickle=True).item()
        self.chunks_text = data["chunks_text"]
        self.chunks_metadata = data["chunks_metadata"]
        
        if USE_NEW_API:
            self.llm = OllamaLLM(model=LLAMA_MODEL)
        else:
            self.llm = Ollama(model=LLAMA_MODEL)
    
    def search(self, query: str, top_k: int = 5):
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
        return f"""Tu es un assistant IA qui répond aux questions en utilisant uniquement les informations fournies dans le contexte ci-dessous. Si la réponse ne se trouve pas dans le contexte, dis simplement que tu ne sais pas.

CONTEXTE: {context}

QUESTION: {query}

RÉPONSE:"""
    
    def generate_answer(self, query: str, context: str) -> str:
        prompt = self.create_prompt(query, context)
        
        try:
            if hasattr(self.llm, 'stream'):
                full_response = ""
                for chunk in self.llm.stream(prompt):
                    full_response += chunk
                return full_response
            else:
                return self.llm.invoke(prompt)
        except Exception as e:
            return f"Erreur lors de la génération de la réponse: {str(e)}"
    
    def stream_answer(self, query: str):
        """Génère une réponse en mode streaming, retournant des morceaux de texte."""
        try:
            results = self.search(query)
            context = "\n\n".join([
                f"Document: {res['metadata']['filename']}\n{res['chunk']}"
                for res in results
            ])
            
            prompt = self.create_prompt(query, context)
            
            # Vérifier si le modèle supporte le streaming
            if hasattr(self.llm, 'stream'):
                for chunk in self.llm.stream(prompt):
                    yield chunk
            else:
                # Fallback pour les modèles sans streaming
                yield self.llm.invoke(prompt)
        except Exception as e:
            yield f"Une erreur est survenue: {str(e)}"
    
    def ask(self, query: str):
        """Génère une réponse complète à partir d'une question."""
        try:
            results = self.search(query)
            context = "\n\n".join([
                f"Document: {res['metadata']['filename']}\n{res['chunk']}"
                for res in results
            ])
            answer = self.generate_answer(query, context)
            return answer
        except Exception as e:
            return f"Une erreur est survenue: {str(e)}"