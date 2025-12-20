from chatbot.sql import MySQLExecutor
from chatbot.gemini_llm import GeminiLLM
from chatbot.schema import db_schema, db_constraints

class HTSAXONChatbot:
    def __init__(self, vector_store=None):

        db_config = {"host":"localhost", "user":"root", "password":"Ai_4845426@", "database":"fuel_pump_filled"}
        self.db = MySQLExecutor(**db_config)
        self.llm = GeminiLLM()

    # ---------------- Generic LLM runner ----------------
    def _run_llm(self, prompt: str, llm_config: dict) -> str:
        """
        Generic LLM call for any deep-think step.
        llm_config can include:
            - temperature
            - max_tokens
            - model
            - stop sequences, etc.
        """
        return self.llm.generate(prompt, llm_config)

    # ---------------- Step 1: SQL generation ----------------
    def _generate_sql(self, query: str) -> str:
        prompt = f"""
            You are a MySQL SQL generator. 
            Generate exactly ONE valid MySQL SELECT query.

            Database schema:
            {db_schema}

            Constraints and relationships:
            {db_constraints}

            **Use table names and column names exactly as shown in the schema, also apply joins or sub queries to get complete information of question later you have to explain the results.**

            Business rules you MUST follow:
            - Gross revenue = SUM of sales.retail_amount.
            - Gross profit = SUM of (sales.retail_amount - sales.cost_amount).
            - Net profit = Gross profit - SUM of expenses.amount.
            - Expenses are stored in the 'expenses' table (use expense_date for filtering).
            - Always join or aggregate across multiple tables if required by these rules.
            - Always use COALESCE() to prevent NULLs in results.
            - Always exclude deleted rows (isdeleted = 0) if column exists.

            Guidelines:
            - Never return NULL values; wrap aggregates with COALESCE().
            - Always respect MySQL's ONLY_FULL_GROUP_BY:
                • If non-aggregated columns appear in SELECT, they must also be in GROUP BY.
                • Prefer aggregating or removing non-grouped columns instead of unsafe SELECTs.
            - Use explicit JOINs instead of implicit joins.
            - Only use table/column names from the schema.
            - Always include proper date filters (e.g., YEAR(date) = YEAR(CURDATE()) - 1 for last year).
            - Return only SQL. No comments, no explanations, no markdown.
            - Never generate INSERT, UPDATE, DELETE, or DROP — only SELECT.
            - Return only SQL as plain text.
            - Do NOT use Markdown formatting, code blocks, or backticks.
            - Do not include any comments, explanations, or other text.
            


            User question: "{query}"
            SQL:
        """
        sql_llm_config = {"temperature":0.1, "top_p":0.8,  "candidate_count":1, "max_output_tokens":500 }
        return self._run_llm(prompt, sql_llm_config)

    # ---------------- Step 2: Explain results ----------------
    def _explain_results(self, user_query: str, sql_query: str, results: list) -> str:
        prompt = f"""
            You are a business assistant that explains database results to non-technical users.

            User question: "{user_query}"
            SQL query results: {results}

            Instructions:
            - Explain the information clearly in plain English.
            - Do NOT mention SQL, column names, or technical details.
            - Group related information together.
            - Highlight key insights, trends, or potential actions.
            - Use simple sentences, bullet points only if needed.
            - Keep it short, readable, and user-friendly.
            - Currency code id Rs.
            - locality is Pakistan Asia\Karachi

            Provide your explanation:
        """

        explain_llm_config = {
            "temperature": 0.7,
            "top_p": 0.9,
            "candidate_count": 1,
            "max_output_tokens": 800
        }
        # explain_llm_config = {"temperature":0.7, "top_p":0.8,  "candidate_count":1, "max_output_tokens":800 }
        # explain_llm_config = {"temperature": 0.7, "max_tokens": 800}
        return self._run_llm(prompt, explain_llm_config)

    # ---------------- Main entry ----------------
    def answer(self, user_query: str):
        sql_query = self._generate_sql(user_query)
        print(sql_query)
        try:
            result_rows = self.db.execute_select(sql_query)
            for row in result_rows:
                print(row)
        except Exception as e:
            return f"Error executing SQL query: {e}"
        return self._explain_results(user_query, sql_query, result_rows)
