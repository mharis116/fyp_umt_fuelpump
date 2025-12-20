import requests
import os
# import google.generativeai as genai
from google import genai

from google.genai import types

class GeminiLLM:
    def __init__(self, api_key="", endpoint=None):
        # You must provide your actual Gemini API KEY and endpoint.
        # os.environ["GOOGLE_API_KEY"] = "AIzaSyCyUVL3eODqJNjsOxcLUlppuI86vOtg70c"

        self.client = genai.Client(
            api_key='AIzaSyCyUVL3eODqJNjsOxcLUlppuI86vOtg70c',
            http_options=types.HttpOptions(api_version='v1alpha')
        )

    def generate(self, prompt: str, llm_config: dict = None) -> str:
        """
        Generate text using Google Gemini API.

        :param prompt: The prompt string.
        :param llm_config: Optional dictionary of parameters:
            - model
            - temperature
            - top_p
            - candidate_count
            - max_output_tokens
            - stop
        """
        llm_config = llm_config or {}

        # Use model from config or default
        model_name = llm_config.get("model", "gemini-2.0-flash-001")

        # Only include parameters explicitly passed
        config_kwargs = {k: v for k, v in llm_config.items() if k in [
            "temperature", "top_p", "candidate_count", "max_output_tokens", "stop"
        ]}

        config = types.GenerateContentConfig(**config_kwargs)

        try:
            response = self.client.models.generate_content(
                model=model_name,
                contents=prompt,
                config=config
            )
            if response and response.candidates:
                return response.candidates[0].content.parts[0].text
            else:
                return "Sorry, I couldn't process your request at the moment."
        except Exception as e:
            # Optionally log e here
            return f"Error communicating with LLM: {str(e)}"

    # def generate(self, prompt):
    #     # This is the new method using the Google Gemini API.
    #     try:
    #         # response = self.model.generate_content(prompt)
    #         response = self.client.models.generate_content(
    #             model='gemini-2.0-flash-001', 
    #             contents=prompt,
    #             config=types.GenerateContentConfig(
    #                 temperature=0.1,        # keep deterministic for SQL
    #                 top_p=0.8,
    #                 candidate_count=1,
    #                 max_output_tokens=500
    #             )
    #         )
    #         if response and response.candidates:
    #             return response.candidates[0].content.parts[0].text
    #         else:
    #             return "Sorry, I couldn't process your request at the moment."
    #     except Exception as e:
    #         raise e
    #         return "Sorry, I couldn't process your request at the moment. Please try again later."
            
    #         # return f"Error communicating with LLM: {str(e)}"
   