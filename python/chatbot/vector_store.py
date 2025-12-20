import faiss
import numpy as np
import os
import json
from sentence_transformers import SentenceTransformer

class VectorStore:
    def __init__(self, index_path, model_name='all-MiniLM-L6-v2'):
        self.index_path = index_path
        self.model = SentenceTransformer(model_name)
        self.index = None
        self.texts = []
        self._load_index()

    def _load_index(self):
        if os.path.exists(self.index_path):
            self.index = faiss.read_index(self.index_path)
            with open(self.index_path + ".txt", "r", encoding="utf-8") as f:
                self.texts = [line.strip() for line in f.readlines()]
        else:
            self.index = None
            self.texts = []

    def build_index(self, knowledge_base_path):
        with open(knowledge_base_path, 'r', encoding='utf-8') as f:
            kb = json.load(f)
        texts = [item['text'] for item in kb]
        embeddings = self.model.encode(texts)
        dim = embeddings.shape[1]
        index = faiss.IndexFlatL2(dim)
        index.add(np.array(embeddings, dtype=np.float32))
        faiss.write_index(index, self.index_path)
        with open(self.index_path + ".txt", "w", encoding="utf-8") as f:
            for t in texts:
                f.write(t.replace('\n', ' ') + "\n")
        self.index = index
        self.texts = texts

    def search(self, query_vec, top_k=3):
        if self.index is None:
            return [], []
        D, I = self.index.search(np.array([query_vec]).astype(np.float32), top_k)
        results = []
        scores = []
        for idx, score in zip(I[0], D[0]):
            if 0 <= idx < len(self.texts):
                results.append(self.texts[idx])
                scores.append(score)
        return results, scores