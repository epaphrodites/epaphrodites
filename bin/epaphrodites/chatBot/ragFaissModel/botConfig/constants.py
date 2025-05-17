import os

BATCH_SIZE = 32
CHUNK_SIZE = 500
CHUNK_OVERLAP = 50
TOP_K_RESULTS = 5
LLM_MODEL = "mistral:7b"
EMBEDDING_MODEL = os.environ.get('EMBEDDING_MODEL', "all-MiniLM-L6-v2")

BASE_DIR = os.path.abspath(os.path.dirname(__file__))

TEXT_DIR = os.environ.get('TEXT_DIR', os.path.abspath(os.path.join(BASE_DIR, "../../../../static/docs/base-data/")))
DATA_DIR = os.environ.get('DATA_DIR', os.path.abspath(os.path.join(BASE_DIR, "../../../database/datas/vector-data")))

INDEX_FILE = os.path.join(DATA_DIR, "faiss_index.idx")
METADATA_FILE = os.path.join(DATA_DIR, "chunks_metadata.npy")