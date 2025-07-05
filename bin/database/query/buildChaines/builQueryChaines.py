import os
import sys
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../..')))
from bin.database.query.buildQuery.buildQuery import BuildQuery

class QueryBuilder:
    def __init__(self, connection):
        self.connection = connection
        self.query = ""

    def select(self, query: str):
        self.query = query
        return self

    def insert(self, query: str):
        self.query = query
        return self

    def update(self, query: str):
        self.query = query
        return self

    def delete(self, query: str):
        self.query = query
        return self

    def get(self):
        try:
            cursor = self.connection.cursor()
            cursor.execute(self.query)
            rows = cursor.fetchall()
            cursor.close()
            return rows
        except Exception as e:
            print(f"Error during query execution : {e}")
            return None

    def execute(self):
        try:
            cursor = self.connection.cursor()
            cursor.execute(self.query)
            self.connection.commit()
            cursor.close()
            return True
        except Exception as e:
            print(f"Error during query execution : {e}")
            return False


def db(n: int):
    conn = BuildQuery.sdb(n)
    return QueryBuilder(conn)

