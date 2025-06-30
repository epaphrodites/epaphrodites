import os
import sys
import redis
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../../../..')))
from bin.database.config.ini.getConfig import GetConfig

class NoSqldbConnexion:

    @staticmethod
    def mongodb(config: dict):
       
        try:
            client = GetConfig.mongodb_connection(config).__enter__()
            return client[config.get('DATABASE', 'admin')]
        except Exception as e:
            raise Exception(f"Erreur de connexion MongoDB : {str(e)}")

    @staticmethod
    def redis(config: dict):

        try:
            conn = GetConfig.redis_connection(config).__enter__()
            return conn
        except redis.RedisError as e:
            raise Exception(f"Erreur de connexion Redis : {str(e)}")