
import os
import sys
import time
import numpy as np
import traceback
from typing import List, Dict, Tuple, Any, Union
sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/botConfig/')
from constants import LLM_MODEL, EMBEDDING_MODEL, TOP_K_RESULTS
import ollama

os.environ["TOKENIZERS_PARALLELISM"] = "false"

BASE_DIR = os.path.abspath(os.path.dirname(__file__))

INDEX_FILE = os.path.join(BASE_DIR, "../../../database/datas/vector-data/faiss_index.idx")
METADATA_FILE = os.path.join(BASE_DIR, "../../../database/datas/vector-data/chunks_metadata.npy")

class BotCore:

    _instances = {}
    
    @classmethod
    def get_instance(cls, user_id="default"):

        if user_id not in cls._instances:
            
            cls._instances[user_id] = cls()
            
        return cls._instances[user_id]
    
    @classmethod
    def cleanup_sessions(cls, max_sessions=10, idle_timeout=3600):

        current_time = time.time()
        
        inactive_sessions = []
        
        for user_id, instance in cls._instances.items():
            
            if instance.last_access_time and (current_time - instance.last_access_time > idle_timeout):
                
                inactive_sessions.append(user_id)
        
        for user_id in inactive_sessions:

            del cls._instances[user_id]
        
        if len(cls._instances) > max_sessions:
            
            sorted_instances = sorted(
                
                [(user_id, inst.last_access_time) for user_id, inst in cls._instances.items()],
                
                key=lambda x: x[1] if x[1] is not None else 0
            )
            
            to_remove = sorted_instances[:len(sorted_instances) - max_sessions]
            
            for user_id, _ in to_remove:
              
                del cls._instances[user_id]
    
    def __init__(self):
        self.embedding_model = None
        self.index = None
        self.chunks_text = []
        self.chunks_metadata = []
        self.is_initialized = False
        self.ollama_client = None
        self.last_access_time = time.time()
        
        try:
            self.initialize()
        except Exception as e:
            print(f"❌ Automatic initialization error: {e}", file=sys.stderr)
    
    def initialize(self) -> bool:
        try:
            if not os.path.exists(INDEX_FILE):
                print(f"❌ The index file {INDEX_FILE} don't exist.")
                return False
                
            if not os.path.exists(METADATA_FILE):
                print(f"❌ The metadata file {METADATA_FILE} don't exist.")
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
            
            try:
                self.ollama_client = ollama.Client()

                self.ollama_client.pull(LLM_MODEL)
                
            except Exception as e:
                print(f"❌ Initialization error: {e}")
                traceback.print_exc()
                return False
            
            self.is_initialized = True
            
            return True
            
        except Exception as e:
            print(f"❌ Error during initialization: {e}")
            traceback.print_exc()
            return False
    
    def search(self, query: str, top_k: int = TOP_K_RESULTS) -> List[Dict[str, Any]]:
        if not self.is_initialized:
            print("❌ The engine is not initialized. Run initialize() first.")
            return []
        
        try:
            query_embedding = self.embedding_model.encode(
                query,
                convert_to_numpy=True,
                normalize_embeddings=True
            ).reshape(1, -1).astype(np.float32)
            
            distances, indices = self.index.search(query_embedding, min(top_k, len(self.chunks_text)))
            
            results = []
            for i, (dist, idx) in enumerate(zip(distances[0], indices[0])):
                if idx >= 0 and idx < len(self.chunks_text):
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
            print(f"❌ Search error: {e}")
            traceback.print_exc()
            return []
    
    def format_results(self, results: List[Dict[str, Any]], with_metadata: bool = True) -> str:
        if not results:
            return "No result found"
        
        formatted_results = []
        for result in results:
            result_str = f"🔍 Result #{result['rank']} (Score: {result['similarity_score']:.4f})\n"
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
    
    def generate_response(self, query: str, results: List[Dict[str, Any]], stream=False ):
        
        if not results:
            return "I can't find any relevant information to answer this question."
        
        try:
            context = "\n".join([result["text"] for result in results[:TOP_K_RESULTS]])
            
            prompt = f"""
            You are an AI assistant. Answer the following question based on the provided context. If the context does not contain enough information, use your general knowledge, but clearly indicate that you are supplementing. Provide a clear and concise answer.
            **Question** : {query}
            **Context** :
            {context}
            **Answer** :
            """
            
            if stream:

                return self.ollama_client.generate(model=LLM_MODEL, prompt=prompt, stream=True)
            else:

                response = self.ollama_client.generate(model=LLM_MODEL, prompt=prompt)
                
                return response["response"].strip()
            
        except Exception as e:
            print(f"❌ Error during generation: {e}")
            traceback.print_exc()
            return f"Error generating response: {str(e)}"
    
    def ask(self, question: str, stream=False):
        try:

            self.last_access_time = time.time()
            
            if not self.is_initialized:
                success = self.initialize()
                if not success:
                    return "Search engine initialization failed."
            
            results = self.search(question)
            
            if not results:
                return "I can't find any relevant information to answer this question."
            
            return self.generate_response(question, results, stream=stream)
            
        except Exception as e:
            print(f"Error in ask(): {e}", file=sys.stderr)
            return "I can't answer that question at the moment."