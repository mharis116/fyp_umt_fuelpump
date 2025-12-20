from flask import Flask, request, jsonify
from chatbot.chatbot import HTSAXONChatbot
from chatbot.vector_store import VectorStore
from flask_cors import CORS

# └─$ sudo systemctl restart htsaxon_python
# Muhammad Haris F2022065116

app = Flask(__name__)
# CORS(app, origins=["http://localhost:8000", "https://yourfrontenddomain.com"])
CORS(app)
# Initialize vector store and chatbot
vector_store = VectorStore('data/db/faiss.index')
chatbot = HTSAXONChatbot(vector_store)

@app.route('/chat', methods=['POST'])
def chat():
    query = request.json.get('query')
    if not query:
        return jsonify({'error': 'No query provided'}), 400
    response = chatbot.answer(query)
    return jsonify({'response': response})

@app.route('/train', methods=['POST'])
def train():
    # Optionally accept new knowledge base
    kb = request.json.get('knowledge_base_path', 'data/knowledge_base.json')
    vector_store.build_index(kb)
    return jsonify({'status': 'Training completed'})

if __name__ == '__main__':
    app.run(debug=True)
