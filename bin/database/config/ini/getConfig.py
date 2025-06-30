import sqlite3
import mysql.connector
import psycopg2
import oracledb
import pyodbc
from pymongo import MongoClient
import redis
from contextlib import contextmanager

class GetConfig:
    
    @staticmethod
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
    def sqlite_connection(config: dict):

        conn = None
        try:
            conn = sqlite3.connect(config['DATABASE'])
            yield conn
        finally:
            if conn:
                conn.close()
                
    @staticmethod
    def postgres_connection(config: dict):
        return config
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
    def mongodb_connection(config: dict):
       
        client = None
        try:
            client = MongoClient(
                host=config['HOST'],
                port=config.get('PORT', 27017),
                username=config.get('USER'),
                password=config.get('PASSWORD'),
                authSource=config.get('DATABASE', 'admin')
            )
            yield client
        finally:
            if client:
                client.close()
                
    @staticmethod
    def redis_connection(config: dict):
       
        conn = None
        try:
            conn = redis.Redis(
                host=config['HOST'],
                port=config.get('PORT', 6379),
                password=config.get('PASSWORD'),
                db=config.get('DB', 0),
                decode_responses=True
            )
            yield conn
        finally:
            if conn:
                conn.close()