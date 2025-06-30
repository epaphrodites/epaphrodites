import os
import sys
import sqlite3
import mysql.connector
import psycopg2
import oracledb
import pyodbc
from contextlib import contextmanager
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../../../..')))
from bin.database.config.ini.getConfig import GetConfig

class SqldbConnexion:
    @staticmethod
    @contextmanager
    def oracle_connection(config: dict):
       
        conn = None
        try:
            conn = oracledb.connect(
                user=config['USER'],
                password=config['PASSWORD'],
                dsn=f"{config['HOST']}:{config['PORT']}/{config['DATABASE']}"
            )
            yield conn
        finally:
            if conn:
                conn.close()

    @staticmethod
    def oracle(config: dict):
        
        try:
            conn = SqldbConnexion.oracle_connection(config).__enter__()
            cursor = conn.cursor()
            return cursor
        except oracledb.Error as e:
            raise Exception(f"Connection error to Oracle : {str(e)}")

    @staticmethod
    @contextmanager
    def mysql_connection(config: dict):
        conn = None
        try:
            
            conn = mysql.connector.connect(
                host=config['HOST'],
                user=config['USER'],
                password=config['PASSWORD'],
                database=config['DATABASE'],
                port=config.get('PORT', 3306)
            )
            yield conn
        finally:
            if conn and conn.is_connected():
                conn.close()

    @staticmethod
    def mysql(config: dict):
        
        try:
            conn = SqldbConnexion.mysql_connection(config).__enter__()
            cursor = conn.cursor()
            return cursor
        except mysql.connector.Error as e:
            raise Exception(f"Connection error to MySQL : {str(e)}")

    @staticmethod
    @contextmanager
    def postgres_connection(config: dict):

        conn = None
        try:
            conn = psycopg2.connect(
                host=config['HOST'],
                database=config['DATABASE'],
                user=config['USER'],
                password=config['PASSWORD'],
                port=config.get('PORT', 5432)
            )
            yield conn
        finally:
            if conn:
                conn.close()

    @staticmethod
    def postgreSQL(config: dict):

        try:
            conn = SqldbConnexion.postgres_connection(config).__enter__()
            cursor = conn.cursor()
            return cursor
        except psycopg2.Error as e:
            raise Exception(f"Connection error to PostgreSQL : {str(e)}")

    @staticmethod
    @contextmanager
    def sqlite_connection(config: dict):

        conn = None
        try:
            conn = sqlite3.connect(config['DATABASE'])
            yield conn
        finally:
            if conn:
                conn.close()

    @staticmethod
    def sqLite(config: dict):

        try:
            conn = SqldbConnexion.sqlite_connection(config).__enter__()
            cursor = conn.cursor()
            return cursor
        except sqlite3.Error as e:
            raise Exception(f"Connection error to SQLite : {str(e)}")

    @staticmethod
    @contextmanager
    def sqlserver_connection(config: dict):

        conn = None
        try:
            conn = pyodbc.connect(
                f"DRIVER={{ODBC Driver 17 for SQL Server}};"
                f"SERVER={config['HOST']};"
                f"DATABASE={config['DATABASE']};"
                f"UID={config['USER']};"
                f"PWD={config['PASSWORD']};"
                f"PORT={config.get('PORT', 1433)}"
            )
            yield conn
        finally:
            if conn:
                conn.close()

    @staticmethod
    def sqlServer(config: dict):

        try:
            conn = SqldbConnexion.sqlserver_connection(config).__enter__()
            cursor = conn.cursor()
            return cursor
        except pyodbc.Error as e:
            raise Exception(f"Connection error to SQL Server : {str(e)}")