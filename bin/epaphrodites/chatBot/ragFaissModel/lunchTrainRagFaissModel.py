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

sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../..')))

from bin.epaphrodites.chatBot.ragFaissModel.botConfig.constants import EMBEDDING_MODEL, TEXT_DIR, INDEX_FILE, METADATA_FILE, BATCH_SIZE, CHUNK_SIZE, CHUNK_OVERLAP

class IndexTrainer:
    def __init__(self, text_dir: str = TEXT_DIR):
        self.text_dir = text_dir
        self.chunks_text = []
        self.chunks_metadata = []

    def load_embedding_model(self):
        try:
            from sentence_transformers import SentenceTransformer
            print(f"Loading embedding model: {EMBEDDING_MODEL}")
            self.embedding_model = SentenceTransformer(EMBEDDING_MODEL)
            print("✅ Embedding model loaded successfully")
            return True
        except Exception as e:
            print(f"❌ Error loading embedding model: {e}")
            traceback.print_exc()
            return False

    def extract_text_from_files(self) -> List[Dict]:
        """Extract text from files with improved error handling"""
        all_docs = []
        
        if not os.path.exists(self.text_dir):
            print(f"❌ Directory {self.text_dir} does not exist!")
            return all_docs
        
        all_files = os.listdir(self.text_dir)
        print(f"Number of files found in directory: {len(all_files)}")
            
        text_paths = []
        
        for file in all_files:
            file_path = os.path.join(self.text_dir, file)
            if os.path.isfile(file_path) and file.lower().endswith('.txt'):
                text_paths.append(Path(file_path))
        
        if not text_paths:
            print(f"⚠️ No text files (.txt) found in {self.text_dir}")
            return all_docs
            
        print(f"Text files to process: {len(text_paths)}")
        
        for text_path in tqdm(text_paths, desc="Reading files"):
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
                        print(f"⚠️ Empty file: {text_path}")
            except Exception as e:
                print(f"❌ Error reading {text_path}: {e}")
        
        print(f"Number of documents extracted: {len(all_docs)}")
        return all_docs

    def chunk_documents(self, documents: List[Dict]) -> Tuple[List[str], List[Dict]]:
        """Split documents into chunks with improved memory management"""
        if not documents:
            print("❌ No documents to chunk")
            return self.chunks_text, self.chunks_metadata
            
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
                    
                    # Handle overlap
                    if CHUNK_OVERLAP > 0 and len(current_chunk) > CHUNK_OVERLAP:
                        # Calculate overlap based on words rather than characters
                        words = current_chunk.split()
                        overlap_word_count = min(len(words), CHUNK_OVERLAP // 5)  # Approximation: 5 characters per word
                        current_chunk = " ".join(words[-overlap_word_count:]) + " " + sentence
                    else:
                        current_chunk = sentence
            
            # Add the last chunk if not empty
            if current_chunk:
                self.chunks_text.append(current_chunk.strip())
                self.chunks_metadata.append({**metadata, "chunk_id": len(self.chunks_text)})
        
        print(f"Total number of chunks created: {len(self.chunks_text)}")
        return self.chunks_text, self.chunks_metadata

    def create_embeddings(self) -> Optional[np.ndarray]:
        """Create embeddings with improved memory and error handling"""
        if not self.chunks_text:
            print("❌ No chunks to encode. Cannot create embeddings.")
            return None
        
        # Check if embedding model is loaded
        if not hasattr(self, 'embedding_model'):
            success = self.load_embedding_model()
            if not success:
                return None
            
        try:
            embeddings = []
            print(f"Creating embeddings for {len(self.chunks_text)} chunks in batches of {BATCH_SIZE}")
            
            for i in tqdm(range(0, len(self.chunks_text), BATCH_SIZE), desc="Creating embeddings"):
                batch = self.chunks_text[i:i + BATCH_SIZE]
                try:
                    batch_embeddings = self.embedding_model.encode(
                        batch,
                        show_progress_bar=False,
                        convert_to_numpy=True,
                        normalize_embeddings=True  # Normalization recommended for FAISS
                    )
                    embeddings.append(batch_embeddings)
                    
                    # Force garbage collection after every batch to free memory
                    if i % (BATCH_SIZE * 5) == 0:
                        gc.collect()
                        
                except Exception as e:
                    print(f"❌ Error encoding batch {i} to {i+len(batch)}: {e}")
                    traceback.print_exc()
                    continue
            
            if not embeddings:
                print("❌ No embeddings created.")
                return None
                
            print(f"Number of embedding batches created: {len(embeddings)}")
            
            # Concatenate all embeddings
            try:
                all_embeddings = np.vstack(embeddings)
                print(f"Final embedding dimensions: {all_embeddings.shape}")
                return all_embeddings
            except Exception as e:
                print(f"❌ Error concatenating embeddings: {e}")
                traceback.print_exc()
                return None
                
        except Exception as e:
            print(f"❌ General error creating embeddings: {e}")
            traceback.print_exc()
            return None

    def build_and_save_index(self, embeddings: np.ndarray) -> bool:
        """Build and save FAISS index with improved error handling"""
        if embeddings is None or embeddings.size == 0:
            print("❌ No embeddings to index. Index not created.")
            return False
        
        try:
            import faiss
            print("Creating FAISS index...")
            
            dimension = embeddings.shape[1]
            print(f"Embedding dimension: {dimension}")
            
            # Use a simpler index to avoid segmentation faults
            try:
                # IndexFlatL2 is more stable than complex indices
                index = faiss.IndexFlatL2(dimension)
                index.add(embeddings.astype(np.float32))  # Explicitly convert to float32
                print("✅ FAISS index created successfully")
            except Exception as e:
                print(f"❌ Error creating index: {e}")
                traceback.print_exc()
                return False
            
            # Save the index
            try:
                faiss.write_index(index, INDEX_FILE)
                print(f"✅ Index saved to {INDEX_FILE}")
            except Exception as e:
                print(f"❌ Error saving index: {e}")
                traceback.print_exc()
                return False
            
            # Save metadata
            try:
                metadata_dict = {
                    "chunks_text": self.chunks_text,
                    "chunks_metadata": self.chunks_metadata
                }
                np.save(METADATA_FILE, metadata_dict)
                print(f"✅ Metadata saved to {METADATA_FILE}")
                return True
            except Exception as e:
                print(f"❌ Error saving metadata: {e}")
                traceback.print_exc()
                return False
                
        except ImportError:
            print("❌ FAISS module not available. Please install with 'pip install faiss-cpu' or 'pip install faiss-gpu'")
            return False
        except Exception as e:
            print(f"❌ Unexpected error: {e}")
            traceback.print_exc()
            return False

    def run(self) -> bool:
        """Execute the complete process with error handling"""
        try:
            # Step 1: Load embedding model
            print("1. Loading embedding model...")
            if not self.load_embedding_model():
                return False
            
            # Step 2: Extract texts
            print("\n2. Extracting text from files...")
            docs = self.extract_text_from_files()
            if not docs:
                print("❌ No documents found. Stopping process.")
                return False
                
            # Step 3: Chunk documents
            print("\n3. Chunking documents...")
            self.chunk_documents(docs)
            if not self.chunks_text:
                print("❌ No chunks created. Stopping process.")
                return False
                
            # Step 4: Create embeddings
            print("\n4. Creating embeddings...")
            embeddings = self.create_embeddings()
            if embeddings is None:
                print("❌ Failed to create embeddings. Stopping process.")
                return False
            
            # Step 5: Build and save index
            print("\n5. Building and saving FAISS index...")
            success = self.build_and_save_index(embeddings)
            if not success:
                print("❌ Failed to build index. Stopping process.")
                return False
            
            print("\n✅ Process completed successfully!")
            return True
            
        except Exception as e:
            print(f"❌ General error: {e}")
            traceback.print_exc()
            return False

if __name__ == "__main__":
    try:
        print("=" * 80)
        print("Starting RAG indexing process with FAISS")
        print("=" * 80)
        
        trainer = IndexTrainer()
        success = trainer.run()
        
        print("\n" + "=" * 80)
        if success:
            print("✅ Indexing completed successfully!")
        else:
            print("❌ Indexing failed. Please check the errors above.")
        print("=" * 80)
        
    except Exception as e:
        print("=" * 80)
        print(f"❌ Unhandled exception: {e}")
        traceback.print_exc()
        print("=" * 80)
        sys.exit(1)