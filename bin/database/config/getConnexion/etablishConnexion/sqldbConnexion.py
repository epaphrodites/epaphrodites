import sys
import os
import logging
from typing import Dict, Any, Union, Optional

# Configuration du logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

# Imports conditionnels avec gestion des erreurs
try:
    import psycopg2
    from psycopg2 import OperationalError
    PSYCOPG2_AVAILABLE = True
except ImportError as e:
    logger.warning(f"PostgreSQL driver non disponible: {e}")
    PSYCOPG2_AVAILABLE = False

try:
    import sqlite3
    SQLITE3_AVAILABLE = True
except ImportError as e:
    logger.warning(f"SQLite driver non disponible: {e}")
    SQLITE3_AVAILABLE = False

try:
    import pymysql
    PYMYSQL_AVAILABLE = True
except ImportError as e:
    logger.warning(f"MySQL driver non disponible: {e}")
    PYMYSQL_AVAILABLE = False

try:
    import cx_Oracle
    ORACLE_AVAILABLE = True
except ImportError as e:
    logger.warning(f"Oracle driver non disponible: {e}")
    ORACLE_AVAILABLE = False

try:
    import pyodbc
    PYODBC_AVAILABLE = True
except ImportError as e:
    logger.warning(f"SQL Server driver non disponible: {e}")
    PYODBC_AVAILABLE = False

sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../../../..')))

# Configuration robuste des chemins
SQLITE_PATH = os.getenv("SQLITE_PATH", "bin/database/datas/SqlLite/")

class DatabaseConnectionError(Exception):
    """Exception personnalisée pour les erreurs de connexion"""
    def __init__(self, db_type: str, message: str, original_error: Exception = None):
        self.db_type = db_type
        self.original_error = original_error
        super().__init__(message)

class ConfigurationError(Exception):
    """Exception pour les erreurs de configuration"""
    pass

class SqldbConnexion:
    
    # Validation des configurations requises
    REQUIRED_FIELDS = {
        'postgresql': ['HOST', 'PORT', 'DATABASE', 'USER', 'PASSWORD'],
        'mysql': ['HOST', 'PORT', 'DATABASE', 'USER', 'PASSWORD'],
        'sqlite': ['DATABASE'],
        'oracle': ['HOST', 'PORT', 'DATABASE', 'USER', 'PASSWORD'],
        'sqlserver': ['HOST', 'PORT', 'DATABASE', 'USER', 'PASSWORD']
    }
    
    # Ports par défaut
    DEFAULT_PORTS = {
        'postgresql': 5432,
        'mysql': 3306,
        'oracle': 1521,
        'sqlserver': 1433
    }
    
    @staticmethod
    def _validate_config(config: Dict[str, Any], db_type: str) -> None:
        """Valide la configuration avant connexion"""
        if not isinstance(config, dict):
            raise ConfigurationError(f"Configuration doit être un dictionnaire pour {db_type}")
        
        required_fields = SqldbConnexion.REQUIRED_FIELDS.get(db_type, [])
        missing_fields = [field for field in required_fields if not config.get(field)]
        
        if missing_fields:
            raise ConfigurationError(f"Champs manquants pour {db_type}: {missing_fields}")
        
        # Validation spécifique des ports
        if 'PORT' in config and config['PORT']:
            try:
                port = int(config['PORT'])
                if not (1 <= port <= 65535):
                    raise ConfigurationError(f"Port invalide pour {db_type}: {port}")
            except (ValueError, TypeError):
                raise ConfigurationError(f"Port doit être un nombre pour {db_type}: {config['PORT']}")
    
    @staticmethod
    def _sanitize_config(config: Dict[str, Any], db_type: str) -> Dict[str, Any]:
        """Nettoie et normalise la configuration"""
        sanitized = config.copy()
        
        # Nettoyer les espaces
        for key, value in sanitized.items():
            if isinstance(value, str):
                sanitized[key] = value.strip()
        
        # Ajouter les ports par défaut si manquants
        if not sanitized.get('PORT') and db_type in SqldbConnexion.DEFAULT_PORTS:
            sanitized['PORT'] = SqldbConnexion.DEFAULT_PORTS[db_type]
            logger.info(f"Port par défaut utilisé pour {db_type}: {sanitized['PORT']}")
        
        return sanitized
    
    @staticmethod
    def _check_driver_availability(db_type: str) -> None:
        """Vérifie la disponibilité du driver"""
        availability = {
            'postgresql': PSYCOPG2_AVAILABLE,
            'mysql': PYMYSQL_AVAILABLE,
            'sqlite': SQLITE3_AVAILABLE,
            'oracle': ORACLE_AVAILABLE,
            'sqlserver': PYODBC_AVAILABLE
        }
        
        if not availability.get(db_type, False):
            raise DatabaseConnectionError(
                db_type, 
                f"Driver non disponible pour {db_type}. Installez la librairie correspondante."
            )
    
    @staticmethod
    def postgreSQL(config: Dict[str, Any]) -> Union[object, str]:
        """Connexion PostgreSQL robuste"""
        db_type = 'postgresql'
        
        try:
            SqldbConnexion._check_driver_availability(db_type)
            SqldbConnexion._validate_config(config, db_type)
            clean_config = SqldbConnexion._sanitize_config(config, db_type)
            
            logger.info(f"Tentative de connexion PostgreSQL à {clean_config['HOST']}:{clean_config['PORT']}")
            
            conn = psycopg2.connect(
                dbname=clean_config["DATABASE"],
                user=clean_config["USER"],
                password=clean_config["PASSWORD"],
                host=clean_config["HOST"],
                port=clean_config["PORT"],
                connect_timeout=30,  # Timeout de connexion
                application_name="SqldbConnexion"
            )
            
            # Test de la connexion
            with conn.cursor() as cursor:
                cursor.execute("SELECT 1")
                cursor.fetchone()
            
            logger.info("Connexion PostgreSQL réussie")
            return conn
            
        except (ConfigurationError, DatabaseConnectionError):
            raise
        except OperationalError as e:
            error_msg = f"Erreur de connexion PostgreSQL : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)
        except Exception as e:
            error_msg = f"Erreur inattendue PostgreSQL : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)

    @staticmethod
    def mysql(config: Dict[str, Any]) -> Union[object, str]:
        """Connexion MySQL robuste"""
        db_type = 'mysql'
        
        try:
            SqldbConnexion._check_driver_availability(db_type)
            SqldbConnexion._validate_config(config, db_type)
            clean_config = SqldbConnexion._sanitize_config(config, db_type)
            
            logger.info(f"Tentative de connexion MySQL à {clean_config['HOST']}:{clean_config['PORT']}")
            
            conn = pymysql.connect(
                host=clean_config["HOST"],
                port=int(clean_config["PORT"]),
                user=clean_config["USER"],
                password=clean_config["PASSWORD"],
                database=clean_config["DATABASE"],
                charset='utf8mb4',
                cursorclass=pymysql.cursors.DictCursor,
                connect_timeout=30,
                read_timeout=30,
                write_timeout=30,
                autocommit=False
            )
            
            # Test de la connexion
            with conn.cursor() as cursor:
                cursor.execute("SELECT 1")
                cursor.fetchone()
            
            logger.info("Connexion MySQL réussie")
            return conn
            
        except (ConfigurationError, DatabaseConnectionError):
            raise
        except pymysql.MySQLError as e:
            error_msg = f"Erreur de connexion MySQL : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)
        except Exception as e:
            error_msg = f"Erreur inattendue MySQL : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)

    @staticmethod
    def sqlite(config: Dict[str, Any]) -> Union[object, str]:
        """Connexion SQLite robuste"""
        db_type = 'sqlite'
        
        try:
            SqldbConnexion._check_driver_availability(db_type)
            SqldbConnexion._validate_config(config, db_type)
            clean_config = SqldbConnexion._sanitize_config(config, db_type)
            
            # Construction du chemin sécurisé
            db_filename = clean_config["DATABASE"]
            if not db_filename.endswith('.db') and not db_filename.endswith('.sqlite'):
                db_filename += '.db'
            
            db_path = os.path.join(SQLITE_PATH, db_filename)
            
            # Sécurité : éviter les path traversal
            if '..' in db_path or not db_path.startswith(SQLITE_PATH):
                raise ConfigurationError(f"Chemin de base de données non sécurisé: {db_path}")
            
            # Créer le répertoire si nécessaire
            os.makedirs(os.path.dirname(db_path), exist_ok=True)
            
            logger.info(f"Tentative de connexion SQLite à {db_path}")
            
            conn = sqlite3.connect(
                db_path,
                timeout=30,
                check_same_thread=False
            )
            
            # Configuration SQLite optimisée
            conn.execute("PRAGMA journal_mode=WAL")
            conn.execute("PRAGMA synchronous=NORMAL")
            conn.execute("PRAGMA temp_store=MEMORY")
            conn.execute("PRAGMA mmap_size=268435456")  # 256MB
            
            # Test de la connexion
            conn.execute("SELECT 1")
            
            logger.info("Connexion SQLite réussie")
            return conn
            
        except (ConfigurationError, DatabaseConnectionError):
            raise
        except sqlite3.Error as e:
            error_msg = f"Erreur de connexion SQLite : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)
        except Exception as e:
            error_msg = f"Erreur inattendue SQLite : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)

    @staticmethod
    def oracle(config: Dict[str, Any]) -> Union[object, str]:
        """Connexion Oracle robuste"""
        db_type = 'oracle'
        
        try:
            SqldbConnexion._check_driver_availability(db_type)
            SqldbConnexion._validate_config(config, db_type)
            clean_config = SqldbConnexion._sanitize_config(config, db_type)
            
            logger.info(f"Tentative de connexion Oracle à {clean_config['HOST']}:{clean_config['PORT']}")
            
            dsn = cx_Oracle.makedsn(
                clean_config["HOST"], 
                clean_config["PORT"], 
                service_name=clean_config["DATABASE"]
            )
            
            conn = cx_Oracle.connect(
                user=clean_config["USER"],
                password=clean_config["PASSWORD"],
                dsn=dsn,
                encoding="UTF-8"
            )
            
            # Test de la connexion
            with conn.cursor() as cursor:
                cursor.execute("SELECT 1 FROM DUAL")
                cursor.fetchone()
            
            logger.info("Connexion Oracle réussie")
            return conn
            
        except (ConfigurationError, DatabaseConnectionError):
            raise
        except cx_Oracle.Error as e:
            error_msg = f"Erreur de connexion Oracle : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)
        except Exception as e:
            error_msg = f"Erreur inattendue Oracle : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)

    @staticmethod
    def sqlserver(config: Dict[str, Any]) -> Union[object, str]:
        """Connexion SQL Server robuste"""
        db_type = 'sqlserver'
        
        try:
            SqldbConnexion._check_driver_availability(db_type)
            SqldbConnexion._validate_config(config, db_type)
            clean_config = SqldbConnexion._sanitize_config(config, db_type)
            
            logger.info(f"Tentative de connexion SQL Server à {clean_config['HOST']}:{clean_config['PORT']}")
            
            conn_str = (
                f"DRIVER={{ODBC Driver 17 for SQL Server}};"
                f"SERVER={clean_config['HOST']},{clean_config['PORT']};"
                f"DATABASE={clean_config['DATABASE']};"
                f"UID={clean_config['USER']};"
                f"PWD={clean_config['PASSWORD']};"
                f"Encrypt=yes;"
                f"TrustServerCertificate=no;"
                f"Connection Timeout=30;"
            )
            
            conn = pyodbc.connect(conn_str)
            
            # Test de la connexion
            with conn.cursor() as cursor:
                cursor.execute("SELECT 1")
                cursor.fetchone()
            
            logger.info("Connexion SQL Server réussie")
            return conn
            
        except (ConfigurationError, DatabaseConnectionError):
            raise
        except pyodbc.Error as e:
            error_msg = f"Erreur de connexion SQL Server : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)
        except Exception as e:
            error_msg = f"Erreur inattendue SQL Server : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)

    @staticmethod
    def get_connection(db_type: str, config: Dict[str, Any]) -> Union[object, str]:
        """
        Méthode utilitaire robuste pour obtenir une connexion selon le type
        Usage: SqldbConnexion.get_connection('postgresql', config)
        """
        if not isinstance(db_type, str):
            raise ValueError("Le type de base de données doit être une chaîne de caractères")
        
        methods = {
            'postgresql': SqldbConnexion.postgreSQL,
            'mysql': SqldbConnexion.mysql,
            'sqlite': SqldbConnexion.sqlite,
            'oracle': SqldbConnexion.oracle,
            'sqlserver': SqldbConnexion.sqlserver
        }
        
        db_type_lower = db_type.lower().strip()
        if db_type_lower not in methods:
            available_types = ', '.join(methods.keys())
            raise ValueError(f"Type de base de données non supporté: {db_type}. Types disponibles: {available_types}")
        
        return methods[db_type_lower](config)

    @staticmethod
    def test_connection(db_type: str, config: Dict[str, Any]) -> bool:
        """
        Test de connexion sans maintenir la connexion ouverte
        Retourne True si succès, False sinon
        """
        try:
            conn = SqldbConnexion.get_connection(db_type, config)
            if hasattr(conn, 'close'):
                conn.close()
            logger.info(f"Test de connexion {db_type} réussi")
            return True
        except Exception as e:
            logger.error(f"Test de connexion {db_type} échoué: {e}")
            return False