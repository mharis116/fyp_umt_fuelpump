import os
from flask import Flask, request, jsonify
import json # Import the json module

# --- LangChain Imports ---
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.output_parsers import StrOutputParser
# We don't need BSHTMLLoader anymore for JSON
# from langchain_community.document_loaders import BSHTMLLoader 
from langchain_core.runnables import RunnablePassthrough
# from langchain_core.documents import Document # May not be needed if parsing JSON directly

# --- Your Custom Classes ---
# Assuming MySQLExecutor and GeminiLLM are in the 'chatbot' directory
from chatbot.sql import MySQLExecutor
from chatbot.gemini_llm import GeminiLLM

# --- Configuration ---
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "Ai_4845426@", # Ensure this is secure, ideally from env vars
    "database": "fuel_pump_filled"
}

# --- Updated Chatbot Class for JSON Processing ---
class HTSAXONChatbot:
    def __init__(self, vector_store):
        self.db = MySQLExecutor(**DB_CONFIG)
        self.llm = GeminiLLM() 
        self._setup_langchain_chain()

    def _setup_langchain_chain(self):
        """Sets up the LangChain chain to answer a specific query based on JSON data."""
        
        # 1. Adjusted Prompt for JSON data
        # We assume the JSON data will be presented as key-value pairs.
        # json_qa_prompt = ChatPromptTemplate.from_messages([
        #     ("system", "You are an expert data analyst of a fuel pump management system's reports. Answer the user's question accurately using ONLY the provided JSON Data. If the answer is not in the data, say 'I cannot find the answer in the provided data.'"),
        #     ("human", "Question: {query}\n\nJSON Data:\n{json_data}") # Changed from Report Content
        # ])

        json_qa_prompt = ChatPromptTemplate.from_messages([
        ("system", """
            You are a professional data analyst for a fuel pump management system.
            You are given structured report data in JSON format that represents EXACTLY what a user sees in a dashboard (tables, charts, and summary).
            Your job is to:
            - Analyze the data
            - Answer questions accurately
            - Explain insights clearly
            =====================
            HOW TO USE THE DATA
            =====================
            1. "summary"
            → High-level totals, KPIs, and date range
            2. "table"
            → Detailed row-level data (source of truth)
            3. "charts"
            → Time-series or visual trends
            - Use "title" to understand what the chart represents
            - Use "labels" for X-axis (usually dates)
            - Use "datasets" for values (e.g., quantity, revenue)
            =====================
            RULES
            =====================
            - Answer ONLY using the provided JSON data
            - Do NOT make assumptions beyond the data
            - If data is missing, say:
            "I cannot find the answer in the provided data."
            - When needed:
            ✔ Calculate totals
            ✔ Compare values
            ✔ Identify trends (increase/decrease)
            ✔ Highlight peaks or drops
            =====================
            RESPONSE STYLE
            =====================
            - Be concise but clear
            - Use numbers from data
            - Use bullet points when helpful
            - Mention dates when talking about trends
            =====================
            EXAMPLES
            =====================
            Good:
            "Sales peaked on 2026-01-10 with Rs. 50,000."
            Bad:
            "Sales look high sometimes."
        """),

        ("human", """
            Question: {query}
            JSON Data:
            {json_data}
        """)
        ])

        # 2. Wrapper for your GeminiLLM (remains similar)
        def llm_invoker(prompt_value):
            prompt_string = prompt_value.to_string() 
            return self.llm.generate(prompt_string, llm_config={
                "model": "gemini-2.5-flash-lite",
                "temperature": 0.1, 
                "max_output_tokens": 1000
            })

        # 3. Create the Chain
        self.json_chain = json_qa_prompt | llm_invoker

    def process_json_data(self, query: str, json_data_str: str) -> str:
        """Parses JSON string and feeds BOTH the query and parsed data to the LLM."""
        try:
            # Parse the JSON string into a Python dictionary
            parsed_json = json.loads(json_data_str)
            
            # Convert the Python dictionary back into a nicely formatted string for the LLM
            # This makes it easier for the LLM to read than raw Python dict output.
            formatted_json_data = json.dumps(parsed_json, indent=2)

            if not formatted_json_data:
                return "Error: Provided JSON data is empty or invalid."

            # Prepare input dictionary for the chain
            input_data = {
                "query": query,
                "json_data": formatted_json_data # Use the formatted JSON string
            }
            
            # Invoke the chain with the dictionary
            response = self.json_chain.invoke(input_data)
            return response

        except json.JSONDecodeError:
            return "Error: Invalid JSON format provided."
        except Exception as e:
            print(f"An error occurred during JSON data processing: {e}")
            import traceback
            traceback.print_exc()
            return "An error occurred while processing the JSON data."

    def answer(self, query: str, html_content: str = None, json_data_str: str = None) -> str:
        """Routes the query based on whether HTML or JSON data was provided."""
        
        # Prioritize JSON data if provided and not empty/whitespace
        if json_data_str and json_data_str.strip():
            print(f"--- Processing question on JSON data: '{query}' ---")
            return self.process_json_data(query, json_data_str)
        
        # Then, check for HTML content (if you want to support both)
        elif html_content and html_content.strip():
            print(f"--- Processing question on HTML document: '{query}' ---")
            # You would call your original HTML processing method here
            # For now, we'll assume this file is ONLY for JSON processing
            # If you want to combine, you'd need to re-integrate the HTML logic.
            return "HTML processing is handled by a different service or is not enabled here."

        else:
            # Fallback: No data provided, just chat normally
            print(f"--- No document/data provided. Running generic query: '{query}' ---")
            try:
                response = self.llm.generate(query, llm_config={
                    "model": "gemini-2.5-flash-lite",
                    "temperature": 0.7, 
                    "max_output_tokens": 1000
                })
                return response
            except Exception as e:
                print(f"Error answering generic query: {e}")
                return "Sorry, I couldn't process that query."

