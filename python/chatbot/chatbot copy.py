from chatbot.retriever import Retriever
from chatbot.gemini_llm import GeminiLLM
# from chatbot.llms.llama3_llm import Llama3LLM
from chatbot.schema import db_schema, db_constraints

class HTSAXONChatbot:
    def __init__(self, vector_store = None):
        # self.retriever = Retriever(vector_store)
        self.llm = GeminiLLM()
        # self.llm = Llama3LLM()
        
    def answer(self, query):
        # relevant_docs = self.retriever.retrieve(query)
        prompt = self._build_sql_prompt(query)
        return self.llm.generate(prompt)

    def _build_sql_prompt(self, query):
        prompt_sql = f"""
            You are a MySQL SQL generator. 
            Generate exactly ONE valid MySQL SELECT query.

            Database schema:
            {db_schema}

            Constraints and relationships:
            {db_constraints}

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

            **Use table names and column names exactly as shown in the schema, also apply joins or sub queries to get complete information of question later you have to explain the results.**



            User question: "{query}"
            SQL:
        """
        return prompt_sql