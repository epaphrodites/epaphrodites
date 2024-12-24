<?php

/**************************************************************
 ****************| DEFINE EPAPHRODITES CONSTANT |********************** 
 ***************************************************************/

# Config language
define('_LANG_', 'eng');

# Python version in use
define('_PYTHON_', 'python');

# Config front files
define('_FRONT_', '.html');

# Config API token Key name
define('_KEYGEN_', 'CRSF');

# Main directory folder
define('_DIR_MAIN_', 'bin');

# Set database accpeted 'mysql/oracle/pgsql/sqlserver/sqlite/mongodb/redis'
define('_FIRST_DRIVER_', 'sqlite');

# vendor directory
define('_DIR_VENDOR_', 'vendor');

# Front files extension in end
define('_MAIN_EXTENSION_', '_ep');

# Users session name
define('_SESSION_', 'session');

# Users Token CRSF name
define('_CRSF_TOKEN_', 'crsfToken');

# Views directories
define('_DIR_VIEWS_', 'public');

# Media files
define('_DIR_MEDIA_', 'static/docs/');

# Main directory
define('_ROOT_', dirname(__DIR__));

# Images directory
define('_DIR_IMG_', 'static/img/');

# Documentation directory
define('_DIR_PDF_', 'static/docs/');

# Main directory for all users
define('_DIR_MAIN_TEMP_', '/views/main/');

# Main directory for admin pages
define('_DIR_ADMIN_TEMP_', '/views/admin/');

# Database directory
define('_DIR_database_', 'bin/database');

# Migration directory
define('_DIR_MIGRATION_', 'bin/database/gearShift');

# Epaphrodites main directory
define('_EPAPHRODITE_', 'bin/epaphrodites');

# Console main directory
define('_CONSOLE_', 'bin/epaphrodites/Console/Models');

# Main static datas (static storage)
define('_DIR_CONFIG_', 'bin/database/datas/arrays/');

# Main Json datas directory
define('_DIR_JSON_DATAS_', 'bin/database/datas/json');

# Main toml datas directory
define('_DIR_TOML_DATAS_', 'bin/database/datas/toml/');

# Main sqLite datas directory
define('_DIR_SQLITE_DATAS_', 'bin/database/datas/SqlLite/');

# Main config ini directory
define('_DIR_CONFIG_INI_', 'bin/config/');

# Set Application domaine when you are in local "epaphrodite-framework/"
define('_DOMAINE_', "");

# Fake folders link
define('_FAKE_', 'view/');

# Main home page
define('_HOME_', _FAKE_ . 'index/');

# Login home page
define('_LOGIN_', _FAKE_ . 'login/');

# Logout
define('_LOGOUT_', 'logout/');

# python directory
define('_PYTHON_FILE_FOLDERS_', 'bin/epaphrodites/python/');

# Dashboard home page
define('_DASHBOARD_', 'dashboard/');

# Dashboard home folders
define('_DASHBOARD_FOLDERS_', 'dashboardFolder/');

# Session auth id
define('_AUTH_ID_', 'id');

# Session auth type
define('_AUTH_TYPE_', 'type');

# Session auth nom et prenoms
define('_AUTH_NAME_', 'usersname');

# Session auth login
define('_AUTH_LOGIN_', 'login');

# Session auth contact
define('_AUTH_CONTACT_', 'contact');

# Session auth email
define('_AUTH_EMAIL_', 'email');

# Token field name
define('CSRF_FIELD_NAME', 'token_csrf');

# Main developpement file
define('_SERVER_LOG_', 'server.log');

# DeepLp API key
define('_YOUR_DEEPL_API_KEY', 'INSERT_YOUR_API_KEY');

# Users dashboard color config
define('_DIR_COLORS_PATH_', 'bin/config/Config.json');