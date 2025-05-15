import os
import sys
import warnings
import numpy as np
import faiss
from sentence_transformers import SentenceTransformer
import logging
import timeout_decorator

sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/botConfig/')

from constants import EMBEDDING_MODEL, INDEX_FILE, METADATA_FILE

logging.basicConfig(level=logging.DEBUG, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

warnings.filterwarnings("ignore")

# Déterminer quelle API LangChain utiliser
try:
    from langchain_ollama import OllamaLLM
    USE_NEW_API = True
except ImportError:
    from langchain_community.llms import Ollama
    USE_NEW_API = False

class BotCore:
    
    def __init__(self):
        """Initialise le moteur de recherche sémantique et le modèle de langage."""
        logger.debug("Initializing BotCore")
        self.embedding_model = SentenceTransformer(EMBEDDING_MODEL)
        self.index = faiss.read_index(INDEX_FILE)
        data = np.load(METADATA_FILE, allow_pickle=True).item()
        self.chunks_text = data["chunks_text"]
        self.chunks_metadata = data["chunks_metadata"]
        
        # Initialisation du modèle LLM
        logger.debug(f"Initializing LLM with API: {'new' if USE_NEW_API else 'old'}")
        if USE_NEW_API:
            self.llm = OllamaLLM(model="llama3:8b")
        else:
            self.llm = Ollama(model="llama3:8b")
    
    def search(self, query: str, top_k: int = 3) -> list:
        """Effectue une recherche sémantique à partir d'une requête."""
        logger.debug(f"Searching for query: {query}")
        try:
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
            logger.debug(f"Found {len(results)} search results")
            return results
        except Exception as e:
            logger.error(f"Error in search: {str(e)}")
            return []
    
    def create_prompt(self, query: str, context: str) -> str:
        """Crée un prompt pour le modèle LLM basé sur le contexte et la requête."""
        prompt = f"""Tu es un assistant IA qui répond aux questions en utilisant uniquement les informations fournies dans le contexte ci-dessous. Si la réponse ne se trouve pas dans le contexte, dis simplement que tu ne sais pas.

CONTEXTE: {context}

QUESTION: {query}

RÉPONSE:"""
        logger.debug(f"Created prompt with context length: {len(context)} characters")
        return prompt
    
    def generate_answer(self, query: str, context: str) -> str:
        """Génère une réponse à partir du contexte et de la requête."""
        prompt = self.create_prompt(query, context)
        try:
            if hasattr(self.llm, 'stream'):
                logger.debug("Using LLM stream method")
                full_response = ""
                for chunk in self.llm.stream(prompt):
                    logger.debug(f"Generated chunk: {chunk}")
                    full_response += chunk
                return full_response
            logger.debug("Using LLM invoke method")
            return self.llm.invoke(prompt)
        except Exception as e:
            logger.error(f"Error generating answer: {str(e)}")
            return f"Erreur lors de la génération de la réponse : {str(e)}"
    
    @timeout_decorator.timeout(60, timeout_exception=TimeoutError)
    def stream_answer(self, query: str):
        """Génère une réponse en mode streaming avec un timeout."""
        logger.debug(f"Streaming answer for query: {query}")
        try:
            results = self.search(query)
            if not results:
                logger.warning("No search results found")
                yield "Aucun résultat trouvé pour la requête."
                return
            
            context = "\n\n".join([
                f"Document: {res['metadata']['filename']}\n{res['chunk']}"
                for res in results
            ])
            logger.debug(f"Context length: {len(context)} characters")
            prompt = self.create_prompt(query, context)
            
            if hasattr(self.llm, 'stream'):
                logger.debug("Streaming with LLM stream method")
                for chunk in self.llm.stream(prompt):
                    # Nettoyer le chunk pour gérer l'encodage
                    chunk = chunk.encode('utf-8', errors='ignore').decode('utf-8')
                    logger.debug(f"Streaming chunk: {chunk}")
                    yield chunk
            else:
                logger.debug("Falling back to invoke for streaming")
                yield self.llm.invoke(prompt)
        except TimeoutError:
            logger.error("Timeout in stream_answer after 60 seconds")
            yield "Erreur : La génération de la réponse a pris trop de temps."
        except Exception as e:
            logger.error(f"Error in stream_answer: {str(e)}")
            yield f"Une erreur est survenue : {str(e)}"
    
    def ask(self, query: str) -> str:
        """Génère une réponse complète à partir d'une question."""
        logger.debug(f"Asking query: {query}")
        try:
            results = self.search(query)
            context = "\n\n".join([
                f"Document: {res['metadata']['filename']}\n{res['chunk']}"
                for res in results
            ])
            return self.generate_answer(query, context)
        except Exception as e:
            logger.error(f"Error in ask: {str(e)}")
            return f"Une erreur est survenue : {str(e)}"