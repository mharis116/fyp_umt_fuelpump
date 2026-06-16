import os
from flask import Flask, request, jsonify
from werkzeug.utils import secure_filename

# --- LangChain Imports ---
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.output_parsers import StrOutputParser
from langchain_community.document_loaders import BSHTMLLoader
from langchain_core.runnables import RunnablePassthrough
from langchain_core.documents import Document

# --- Your Custom Classes ---
from chatbot.sql import MySQLExecutor
# Import your GeminiLLM class directly
from chatbot.gemini_llm import GeminiLLM

# --- Configuration ---
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "Ai_4845426@", # Ensure this is secure, ideally from env vars
    "database": "fuel_pump_filled"
}

class HTSAXONChatbot:
    def __init__(self, vector_store):
        self.db = MySQLExecutor(**DB_CONFIG)
        
        # Initialize your GeminiLLM instance
        # The API key is embedded in your GeminiLLM class, which is okay for
        # this direct usage but for production, consider environment variables.
        self.llm = GeminiLLM() 
        
        self._setup_langchain_chain()

    def _setup_langchain_chain(self):
        """Sets up the LangChain chain to answer a specific query based on report content."""
        
        # 1. Update Prompt: Now it takes BOTH {query} and {report_content}
        qa_prompt = ChatPromptTemplate.from_messages([
            ("system", "You are an expert data analyst. Answer the user's question accurately using ONLY the provided Report Content. If the answer is not in the report, say 'I cannot find the answer in the provided report.'"),
            ("human", "Question: {query}\n\nReport Content:\n{report_content}")
        ])

        # 2. Wrapper for your GeminiLLM: 
        # LangChain outputs a PromptValue object. We need to convert it to a string 
        # so your custom `self.llm.generate()` can read it.
        def llm_invoker(prompt_value):
            # Convert LangChain's prompt structure into a flat string
            prompt_string = prompt_value.to_string() 
            
            # Send the combined question + data string to your LLM
            return self.llm.generate(prompt_string, llm_config={
                "model": "gemini-2.5-flash-lite",
                "temperature": 0.1, # Low temperature so it doesn't hallucinate data
                "max_output_tokens": 1000
            })

        # 3. Create the Chain (No need for StrOutputParser since your generate() already returns a string)
        self.report_chain = qa_prompt | llm_invoker

    def process_html_content(self, query: str, html_content: str) -> str:
        """Extracts text from HTML and feeds BOTH the query and text to the LLM."""
        try:
            from bs4 import BeautifulSoup
            # Extract text cleanly
            soup = BeautifulSoup(html_content, 'html.parser')
            extracted_text = soup.get_text(separator="\n", strip=True)

            if not extracted_text:
                return "Error: Could not extract readable text from the provided HTML."

            # --- FIX HERE ---
            # Pass a dictionary to the chain's invoke method, matching the prompt variables.
            input_data = {
                "query": query,
                "report_content": extracted_text
            }
            
            # Invoke the chain with the dictionary
            response = self.report_chain.invoke(input_data)
            # --- END FIX ---
            
            return response

        except Exception as e:
            print(f"An error occurred during HTML content processing: {e}")
            import traceback
            traceback.print_exc()
            return "An error occurred while processing the document."

    def answer(self, query: str, html_content: str = None) -> str:
        """Routes the query based on whether HTML data was provided."""
        
        # Check if html_content actually has data (not None and not empty space)
        if html_content and html_content.strip():
            print(f"--- Processing question on document: '{query}' ---")
            
            # Pass BOTH the question and the document to the processing function
            resp = self.process_html_content(query, html_content)
            return resp
            
        else:
            # Fallback: No document provided, just chat normally
            print(f"--- No document provided. Running generic query: '{query}' ---")
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
    