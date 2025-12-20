import mysql.connector
from mysql.connector import Error
import re

class MySQLExecutor:
    def __init__(self, host, user, password, database):
        """
        Initialize the MySQL connection.
        """
        self.host = host
        self.user = user
        self.password = password
        self.database = database
        self.conn = None

    def connect(self):
        """
        Establish the connection.
        """
        try:
            self.conn = mysql.connector.connect(
                host=self.host,
                user=self.user,
                password=self.password,
                database=self.database
            )
        except Error as e:
            raise ConnectionError(f"Error connecting to MySQL: {e}")

    def close(self):
        """
        Close the connection.
        """
        if self.conn and self.conn.is_connected():
            self.conn.close()

    def execute_select(self, query, params=None):
        """
        Execute a SELECT query safely.
        """
        query = self.clean_sql(query)
        # Ensure only SELECT queries are allowed
        if not query.strip().lower().startswith("select"):
            raise ValueError("Only SELECT queries are allowed.")

        try:
            if self.conn is None or not self.conn.is_connected():
                self.connect()
            cursor = self.conn.cursor(dictionary=True)
            cursor.execute(query, params or ())
            result = cursor.fetchall()
            cursor.close()
            return result
        except Error as e:
            raise RuntimeError(f"Error executing query: {e}")

    def clean_sql(self, query: str) -> str:
        """
        Remove any Markdown-like code fences, including ```sql or ```sql 1.
        """
        # Remove ```sql or ```sql 1 at start and ``` at end
        query = re.sub(r"^```[a-zA-Z0-9]*\n", "", query)
        query = re.sub(r"\n```$", "", query)
        return query.strip()
# Example usage:
if __name__ == "__main__":
    sql_query = """
    SELECT
    p.name AS product_name,
    MIN(CASE WHEN YEAR(pr.date) = 2021 THEN pr.retail_price ELSE NULL END) AS price_2021,
    MAX(pr.retail_price) AS current_price,
    (
        MAX(pr.retail_price) - MIN(CASE WHEN YEAR(pr.date) = 2021 THEN pr.retail_price ELSE NULL END)
    ) / MIN(CASE WHEN YEAR(pr.date) = 2021 THEN pr.retail_price ELSE NULL END) * 100 AS percentage_change
    FROM products AS p
    JOIN prices AS pr
    ON p.id = pr.pro_id
    WHERE
    YEAR(pr.date) >= 2021
    GROUP BY
    p.name
    HAVING
    price_2021 IS NOT NULL;
    """

    db = MySQLExecutor(host="localhost", user="root", password="Ai_4845426@", database="fuel_pump_filled")

    try:
        result = db.execute_select(sql_query)
        for row in result:
            print(row)
    finally:
        db.close()
