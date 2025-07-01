import sys
import os
import logging
from typing import Dict, Any, Union, Optional

# Configuration du logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

# Imports conditionnels avec gestion des erreurs
try:
    import pymongo
    from pymongo import MongoClient
    from pymongo.errors import ConnectionFailure, ServerSelectionTimeoutError, ConfigurationError as MongoConfigError
    PYMONGO_AVAILABLE = True
except ImportError as e:
    logger.warning(f"MongoDB driver non disponible: {e}")
    PYMONGO_AVAILABLE = False

try:
    import redis
    from redis.exceptions import ConnectionError as RedisConnectionError, TimeoutError as RedisTimeoutError
    REDIS_AVAILABLE = True
except ImportError as e:
    logger.warning(f"Redis driver non disponible: {e}")
    REDIS_AVAILABLE = False

sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../../../..')))

class DatabaseConnectionError(Exception):
    """Exception personnalisée pour les erreurs de connexion"""
    def __init__(self, db_type: str, message: str, original_error: Exception = None):
        self.db_type = db_type
        self.original_error = original_error
        super().__init__(message)

class ConfigurationError(Exception):
    """Exception pour les erreurs de configuration"""
    pass

class NoSqldbConnexion:
    """Gestionnaire de connexions NoSQL (MongoDB, Redis)"""
    
    # Validation des configurations requises
    REQUIRED_FIELDS = {
        'mongodb': ['HOST', 'PORT', 'DATABASE'],
        'redis': ['HOST', 'PORT']
    }
    
    # Ports par défaut
    DEFAULT_PORTS = {
        'mongodb': 27017,
        'redis': 6379
    }
    
    @staticmethod
    def _validate_config(config: Dict[str, Any], db_type: str) -> None:
        """Valide la configuration avant connexion"""
        if not isinstance(config, dict):
            raise ConfigurationError(f"Configuration doit être un dictionnaire pour {db_type}")
        
        required_fields = NoSqldbConnexion.REQUIRED_FIELDS.get(db_type, [])
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
        if not sanitized.get('PORT') and db_type in NoSqldbConnexion.DEFAULT_PORTS:
            sanitized['PORT'] = NoSqldbConnexion.DEFAULT_PORTS[db_type]
            logger.info(f"Port par défaut utilisé pour {db_type}: {sanitized['PORT']}")
        
        return sanitized
    
    @staticmethod
    def _check_driver_availability(db_type: str) -> None:
        """Vérifie la disponibilité du driver"""
        availability = {
            'mongodb': PYMONGO_AVAILABLE,
            'redis': REDIS_AVAILABLE
        }
        
        if not availability.get(db_type, False):
            raise DatabaseConnectionError(
                db_type, 
                f"Driver non disponible pour {db_type}. Installez la librairie correspondante."
            )
    
    @staticmethod
    def mongodb(config: Dict[str, Any]) -> Union[object, str]:
        """Connexion MongoDB robuste"""
        db_type = 'mongodb'
        
        try:
            NoSqldbConnexion._check_driver_availability(db_type)
            NoSqldbConnexion._validate_config(config, db_type)
            clean_config = NoSqldbConnexion._sanitize_config(config, db_type)
            
            logger.info(f"Tentative de connexion MongoDB à {clean_config['HOST']}:{clean_config['PORT']}")
            
            # Construction de l'URI MongoDB
            auth_part = ""
            if clean_config.get('USER') and clean_config.get('PASSWORD'):
                auth_part = f"{clean_config['USER']}:{clean_config['PASSWORD']}@"
            
            uri = f"mongodb://{auth_part}{clean_config['HOST']}:{clean_config['PORT']}/{clean_config['DATABASE']}"
            
            # Options de connexion robustes
            client_options = {
                'serverSelectionTimeoutMS': 30000,  # 30 secondes
                'connectTimeoutMS': 30000,
                'socketTimeoutMS': 30000,
                'maxPoolSize': 50,
                'minPoolSize': 5,
                'maxIdleTimeMS': 300000,  # 5 minutes
                'retryWrites': True,
                'retryReads': True,
                'readPreference': 'primary',
                'w': 1,  # Write concern
                'j': True,  # Journal
                'appName': 'NoSqldbConnexion'
            }
            
            # Ajout des options SSL si spécifiées
            if clean_config.get('SSL', False):
                client_options.update({
                    'ssl': True,
                    'ssl_cert_reqs': 'CERT_NONE' if clean_config.get('SSL_CERT_REQS') == 'none' else 'CERT_REQUIRED'
                })
            
            # Création du client MongoDB
            client = MongoClient(uri, **client_options)
            
            # Test de connexion
            client.admin.command('ping')
            
            # Retourner la base de données spécifique
            database = client[clean_config['DATABASE']]
            
            logger.info("Connexion MongoDB réussie")
            return database
            
        except (ConfigurationError, DatabaseConnectionError):
            raise
        except (ConnectionFailure, ServerSelectionTimeoutError) as e:
            error_msg = f"Erreur de connexion MongoDB : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)
        except MongoConfigError as e:
            error_msg = f"Erreur de configuration MongoDB : {e}"
            logger.error(error_msg)
            raise ConfigurationError(error_msg)
        except Exception as e:
            error_msg = f"Erreur inattendue MongoDB : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)
    
    @staticmethod
    def redis(config: Dict[str, Any]) -> Union[object, str]:
        """Connexion Redis robuste"""
        db_type = 'redis'
        
        try:
            NoSqldbConnexion._check_driver_availability(db_type)
            NoSqldbConnexion._validate_config(config, db_type)
            clean_config = NoSqldbConnexion._sanitize_config(config, db_type)
            
            logger.info(f"Tentative de connexion Redis à {clean_config['HOST']}:{clean_config['PORT']}")
            
            # Options de connexion robustes
            redis_options = {
                'host': clean_config['HOST'],
                'port': int(clean_config['PORT']),
                'socket_timeout': 30,
                'socket_connect_timeout': 30,
                'socket_keepalive': True,
                'socket_keepalive_options': {},
                'retry_on_timeout': True,
                'retry_on_error': [ConnectionError, TimeoutError],
                'health_check_interval': 30,
                'max_connections': 50,
                'decode_responses': True,  # Décoder automatiquement les réponses en UTF-8
                'encoding': 'utf-8'
            }
            
            # Ajout de l'authentification si fournie
            if clean_config.get('PASSWORD'):
                redis_options['password'] = clean_config['PASSWORD']
            
            # Sélection de la base de données (0 par défaut)
            if clean_config.get('DATABASE'):
                try:
                    redis_options['db'] = int(clean_config['DATABASE'])
                except (ValueError, TypeError):
                    logger.warning(f"Base de données Redis invalide: {clean_config['DATABASE']}, utilisation de la DB 0")
                    redis_options['db'] = 0
            else:
                redis_options['db'] = 0
            
            # Options SSL si spécifiées
            if clean_config.get('SSL', False):
                redis_options.update({
                    'ssl': True,
                    'ssl_cert_reqs': 'none' if clean_config.get('SSL_CERT_REQS') == 'none' else 'required'
                })
            
            # Création du pool de connexions Redis
            connection_pool = redis.ConnectionPool(**redis_options)
            client = redis.Redis(connection_pool=connection_pool)
            
            # Test de connexion
            client.ping()
            
            # Test d'écriture/lecture simple
            test_key = "__nosqldb_test__"
            client.set(test_key, "test_value", ex=10)  # Expire en 10 secondes
            test_result = client.get(test_key)
            client.delete(test_key)
            
            if test_result != "test_value":
                raise DatabaseConnectionError(db_type, "Test d'écriture/lecture Redis échoué")
            
            logger.info("Connexion Redis réussie")
            return client
            
        except (ConfigurationError, DatabaseConnectionError):
            raise
        except (RedisConnectionError, RedisTimeoutError) as e:
            error_msg = f"Erreur de connexion Redis : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)
        except Exception as e:
            error_msg = f"Erreur inattendue Redis : {e}"
            logger.error(error_msg)
            raise DatabaseConnectionError(db_type, error_msg, e)
    
    @staticmethod
    def get_connection(db_type: str, config: Dict[str, Any]) -> Union[object, str]:
        """
        Méthode utilitaire robuste pour obtenir une connexion selon le type
        Usage: NoSqldbConnexion.get_connection('mongodb', config)
        """
        if not isinstance(db_type, str):
            raise ValueError("Le type de base de données doit être une chaîne de caractères")
        
        methods = {
            'mongodb': NoSqldbConnexion.mongodb,
            'redis': NoSqldbConnexion.redis
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
            conn = NoSqldbConnexion.get_connection(db_type, config)
            
            # Fermeture spécifique selon le type
            if db_type.lower() == 'mongodb' and hasattr(conn, 'client'):
                conn.client.close()
            elif db_type.lower() == 'redis' and hasattr(conn, 'connection_pool'):
                conn.connection_pool.disconnect()
            
            logger.info(f"Test de connexion {db_type} réussi")
            return True
        except Exception as e:
            logger.error(f"Test de connexion {db_type} échoué: {e}")
            return False
    
    @staticmethod
    def get_mongodb_collections(database) -> list:
        """Retourne la liste des collections MongoDB"""
        try:
            return database.list_collection_names()
        except Exception as e:
            logger.error(f"Erreur lors de la récupération des collections: {e}")
            return []
    
    @staticmethod
    def get_redis_info(client) -> dict:
        """Retourne les informations du serveur Redis"""
        try:
            return client.info()
        except Exception as e:
            logger.error(f"Erreur lors de la récupération des infos Redis: {e}")
            return {}
    
    @staticmethod
    def get_redis_keys(client, pattern: str = "*") -> list:
        """Retourne les clés Redis selon un pattern"""
        try:
            return client.keys(pattern)
        except Exception as e:
            logger.error(f"Erreur lors de la récupération des clés Redis: {e}")
            return []

# Exemple d'utilisation
if __name__ == "__main__":
    # Configuration MongoDB
    config_mongodb = {
        "HOST": "localhost",
        "PORT": "27017",
        "DATABASE": "ma_base_mongo",
        "USER": "mon_user",  # Optionnel
        "PASSWORD": "mon_password"  # Optionnel
    }
    
    # Configuration Redis
    config_redis = {
        "HOST": "localhost",
        "PORT": "6379",
        "PASSWORD": "mon_password_redis",  # Optionnel
        "DATABASE": "0"  # Numéro de la DB Redis (0-15)
    }
    
    try:
        print("=== Test MongoDB ===")
        if NoSqldbConnexion.test_connection('mongodb', config_mongodb):
            print("Test MongoDB réussi")
            
            # Connexion effective
            db = NoSqldbConnexion.mongodb(config_mongodb)
            print(f"Connexion MongoDB établie sur la base: {db.name}")
            
            # Lister les collections
            collections = NoSqldbConnexion.get_mongodb_collections(db)
            print(f"Collections disponibles: {collections}")
            
            # Test d'insertion/lecture
            test_collection = db.test_collection
            test_doc = {"test": "document", "timestamp": "2024"}
            result = test_collection.insert_one(test_doc)
            print(f"Document inséré avec ID: {result.inserted_id}")
            
            # Nettoyage
            test_collection.delete_one({"_id": result.inserted_id})
            db.client.close()
            print("Connexion MongoDB fermée")
        
        print("\n=== Test Redis ===")
        if NoSqldbConnexion.test_connection('redis', config_redis):
            print("Test Redis réussi")
            
            # Connexion effective
            r = NoSqldbConnexion.redis(config_redis)
            print("Connexion Redis établie")
            
            # Informations du serveur
            info = NoSqldbConnexion.get_redis_info(r)
            print(f"Version Redis: {info.get('redis_version', 'N/A')}")
            
            # Test d'écriture/lecture
            r.set("test_key", "test_value", ex=60)
            value = r.get("test_key")
            print(f"Test écriture/lecture: {value}")
            
            # Lister quelques clés
            keys = NoSqldbConnexion.get_redis_keys(r, "test*")
            print(f"Clés commençant par 'test': {keys}")
            
            # Nettoyage
            r.delete("test_key")
            r.connection_pool.disconnect()
            print("Connexion Redis fermée")
            
    except ConfigurationError as e:
        print(f"Erreur de configuration: {e}")
    except DatabaseConnectionError as e:
        print(f"Erreur de connexion: {e}")
        if e.original_error:
            print(f"Détail de l'erreur: {e.original_error}")
    except Exception as e:
        print(f"Erreur inattendue: {e}")
        logger.exception("Erreur détaillée:")