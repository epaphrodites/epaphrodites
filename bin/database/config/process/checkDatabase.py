import sys
import os
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../../..')))
from bin.database.config.process.envLoader import EnvLoader
from bin.database.config.getConnexion.etablishConnexion.sqldbConnexion import SqldbConnexion
from bin.database.config.getConnexion.etablishConnexion.noSqldbConnexion import NoSqldbConnexion

class CheckDatabase:
    
    @staticmethod
    def dbConnect( db: int):
        
        config = CheckDatabase.env(db)
        main_driver = config.get('DRIVER', '').lower()
       
        if main_driver == "pgsql":
           connexion = SqldbConnexion.postgreSQL(config)
           
        if main_driver == "mysql":
           connexion = SqldbConnexion.mysql(config)

        return connexion
    
    @staticmethod
    def env(db: int):
        
        envVariables = EnvLoader.get_all_db_vars(db)
        return envVariables