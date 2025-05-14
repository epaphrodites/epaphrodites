# ask_question.py
import os
import numpy as np
import faiss
from langchain_community.llms import Ollama
from sentence_transformers import SentenceTransformer

BASE_DIR = os.path.abspath(os.path.dirname(__file__))
INDEX_FILE = os.path.abspath(os.path.join(BASE_DIR, "../../../database/datas/vector-data/faiss_index.idx"))
METADATA_FILE = os.path.abspath(os.path.join(BASE_DIR, "../../../database/datas/vector-data/chunks_metadata.npy"))
EMBEDDING_MODEL = "all-MiniLM-L6-v2"

class LocalQA:
    def __init__(self):
        self.embedding_model = SentenceTransformer(EMBEDDING_MODEL)
        self.index = faiss.read_index(INDEX_FILE)
        data = np.load(METADATA_FILE, allow_pickle=True).item()
        self.chunks_text = data["chunks_text"]
        self.chunks_metadata = data["chunks_metadata"]
        self.llm = Ollama(model="llama3:8b")

    def search(self, query: str, top_k: int = 5):
        query_embedding = self.embedding_model.encode([query])
        distances, indices = self.index.search(query_embedding, top_k)
        results = []
        for i, idx in enumerate(indices[0]):
            results.append({
                "chunk": self.chunks_text[idx],
                "metadata": self.chunks_metadata[idx],
                "distance": distances[0][i]
            })
        return results

    def generate_answer(self, query: str, context: str) -> str:
        prompt = f"""Tu es un assistant IA qui répond aux questions en utilisant uniquement les informations fournies dans le contexte ci-dessous.
Si la réponse ne se trouve pas dans le contexte, dis simplement que tu ne sais pas.

CONTEXTE:
{context}

QUESTION: {query}

RÉPONSE:"""

        print("\nRéponse:", end=" ", flush=True)
        full_response = ""
        for chunk in self.llm.stream(prompt):
            print(chunk, end="", flush=True)
            full_response += chunk
        print()
        return full_response

    def ask(self, query: str):
        results = self.search(query)
        context = "\n\n".join([f"Document: {res['metadata']['filename']}\n{res['chunk']}" for res in results])
        answer = self.generate_answer(query, context)
        print("\n📚 Sources utilisées :")
        for res in results:
            print("-", res["metadata"]["source"])

if __name__ == "__main__":
    qa = LocalQA()
    while True:
        question = input("\nPosez votre question (ou tapez 'q' pour quitter) : ")
        if question.lower() == "q":
            break
        qa.ask(question)
