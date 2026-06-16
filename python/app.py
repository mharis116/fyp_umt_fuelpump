from flask import Flask, request, jsonify
from chatbot.chatbot2 import HTSAXONChatbot
from chatbot.chatbot3 import HTSAXONChatbot as HTSAXONChatbotJson
from chatbot.vector_store import VectorStore
from anomaly_detection.model import predict_anomaly
from flask_cors import CORS

# └─$ sudo systemctl restart htsaxon_python
# Muhammad Haris F2022065116

app = Flask(__name__)
# CORS(app, origins=["http://localhost:8000", "https://yourfrontenddomain.com"])
CORS(app)
# Initialize vector store and chatbot
vector_store = VectorStore('data/db/faiss.index')
chatbot = HTSAXONChatbot(vector_store)
chatbotJson = HTSAXONChatbotJson(vector_store)

# @app.route('/chat', methods=['POST'])
# def chat():
#     query = request.json.get('query')
#     if not query:
#         return jsonify({'error': 'No query provided'}), 400
#     response = chatbot.answer(query)
#     return jsonify({'response': response})


@app.route('/chat', methods=['POST'])
def chat():
    data = request.get_json()
    query = data.get('query')
    html_content = data.get('html_content') # Get HTML content from request

    if not query:
        return jsonify({'error': 'No query provided'}), 400

    # Debugging: print what was received
    print(f"Received Query: {query}")
    if html_content:
        print(f"Received HTML Content (first 100 chars): {html_content[:100]}...")
    else:
        print("No HTML Content received.")

    try:
        # Pass both query and html_content to the chatbot's answer method
        response = chatbot.answer(query=query, html_content=html_content)
        return jsonify({'response': response})
    except Exception as e:
        print(f"Error in Flask /chat endpoint: {e}")
        # Log the error for debugging on the server side
        import traceback
        traceback.print_exc()
        return jsonify({'error': 'An internal server error occurred'}), 500


@app.route('/chat_json', methods=['POST']) # Use a different route to distinguish
def chat_json():
    data = request.get_json()
    query = data.get('query')
    json_data_str = data.get('json_data') # Expecting a JSON string here

    if not query:
        return jsonify({'error': 'No query provided'}), 400

    # --- Debugging ---
    print("\n--- Flask Request Received (JSON Processor) ---")
    print(f"Raw JSON data received: {data}")
    print(f"Query type: {type(query)}, Value: {query}")
    
    if json_data_str is not None:
        print(f"JSON Data string (first 100 chars): {str(json_data_str)[:100]}...")
    else:
        print("JSON Data string is None.")
    print("--------------------------------------------\n")
    # --- End Debugging ---

    try:
        # Pass query and the JSON data string
        response = chatbotJson.answer(query=query, json_data_str=json_data_str)
        return jsonify({'response': response})
    except Exception as e:
        print(f"Error in Flask /chat_json endpoint: {e}")
        import traceback
        traceback.print_exc()
        return jsonify({'error': 'An internal server error occurred'}), 500


@app.route('/train', methods=['POST'])
def train():
    # Optionally accept new knowledge base
    kb = request.json.get('knowledge_base_path', 'data/knowledge_base.json')
    vector_store.build_index(kb)
    return jsonify({'status': 'Training completed'})

@app.route('/predict/anomaly', methods=['POST'])
def predict():
    # Optionally accept new knowledge base
    data = request.json.get('data', '')
    # vector_store.build_index(kb)
    # return jsonify({'status': 'Training completed'})
    
    res = predict_anomaly(data, rolling_abs_variance_mean=0.05)

    return jsonify(res)

    # match = "✅" if res["predicted_label"] == expected else "❌"
    # if match == "❌": all_pass = False
    # print(f"{expected:20s}  {res['predicted_label']:20s}  "
    #     f"{res['confidence']:.4f}  {match}")

if __name__ == '__main__':
    app.run(debug=True)
