from sentence_transformers import SentenceTransformer
import numpy as np
import json
import os
import re
from datetime import datetime

class Retriever:
    def __init__(self, vector_store, model_name='all-MiniLM-L6-v2', db_path='data/db/user_data.json'):
        self.vector_store = vector_store
        self.model = SentenceTransformer(model_name)
        self.db_path = db_path

        # Ensure the directory exists
        os.makedirs(os.path.dirname(self.db_path), exist_ok=True)

    def retrieve(self, query, top_k=3):
        if self._contains_contact(query):
            self._store_contact(query)
            # Only return the fixed message, don't pass query to model/vector search
            return "Thank you! Our team will get in touch with you soon."
        # Otherwise, proceed as normal
        query_vec = self.model.encode([query])[0]
        docs, _ = self.vector_store.search(query_vec, top_k=top_k)
        return docs

    def _contains_contact(self, query):
        email_pattern = r'[\w\.-]+@[\w\.-]+\.\w+'
        phone_pattern = r'((\+92|0092)?\s?-?3\d{2}\s?-?\d{7})'
        return re.search(email_pattern, query) or re.search(phone_pattern, query)

    def _store_contact(self, query):
        email_pattern = r'[\w\.-]+@[\w\.-]+\.\w+'
        phone_pattern = r'((?:\+?\d{1,4}[\s\-\.]?)?(?:\(?\d{2,5}\)?[\s\-\.]?)?[\d\s\-\.]{6,}|\b\d{11,15}\b)'
        sensitive = {}
        emails = re.findall(email_pattern, query)
        phones = re.findall(phone_pattern, query)
        if emails:
            sensitive['email'] = emails
        if phones:
            sensitive['phone'] = phones
        entry = {
            "timestamp": datetime.utcnow().isoformat() + "Z",
            "query": query,
            "detected": sensitive,
        }
        self._append_to_json(entry)

    def _append_to_json(self, entry):
        try:
            if os.path.exists(self.db_path):
                with open(self.db_path, 'r', encoding='utf-8') as f:
                    data = json.load(f)
            else:
                data = []
        except Exception:
            data = []
        data.append(entry)
        with open(self.db_path, 'w', encoding='utf-8') as f:
            json.dump(data, f, indent=2, ensure_ascii=False)