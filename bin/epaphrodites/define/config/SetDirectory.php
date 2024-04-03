<?php

/**************************************************************
 ****************| DEFINE ALL DIRECTORIES |********************** 
 ***************************************************************/

// Config language
define('_LANG_', 'eng');

// Python version in use
define('__PYTHON__', 'python3');

// Config front files
define('_FRONT_', '.html');

// Config API token Key name
define('_KEYGEN_', 'CRSF');

// Main directory folder
define('_DIR_MAIN_', 'bin');

// Set database accpeted 'mysql/postgre/sqlserver/sqlite/mongo/redis'
define('_FIRST_DRIVER_', 'sqlite');

// vendor directory
define('_DIR_VENDOR_', 'vendor');

// Front files extension in end
define('_MAIN_EXTENSION_', '_ep');

// Users session name
define('_SESSION_', 'session');

// Users Token CRSF name
define('_CRSF_TOKEN_', 'crsf_token');

// Views directories
define('_DIR_VIEWS_', 'bin/views');

// Media files
define('_DIR_MEDIA_', 'static/docs/');

// Main directory
define('_ROOT_', dirname(__DIR__));

// Images directory
define('_DIR_IMG_', 'static/img/');

// Documentation directory
define('_DIR_PDF_', 'static/docs/');

// Main directory for all users
define('_DIR_MAIN_TEMP_', '/main/');

// Main directory for admin pages
define('_DIR_ADMIN_TEMP_', '/admin/');

// Database directory
define('_DIR_database_', 'bin/database');

// Migration directory
define('_DIR_MIGRATION_', 'bin/database/gearShift');

// Epaphrodites main directory
define('_EPAPHRODITE_', 'bin/epaphrodites');

// Main static datas (static storage)
define('_DIR_CONFIG_', 'bin/database/datas/arrays/');

// Main email param file
define('_DIR_MAIL_', 'bin/epaphrodites/define/config/ini/');

// Main Json datas directory
define('_DIR_JSON_DATAS_', 'bin/database/datas/json');

// Main toml datas directory
define('_DIR_TOML_DATAS_', 'bin/database/datas/toml/');

// Main sqLite datas directory
define('_DIR_SQLITE_DATAS_', 'bin/database/datas/SqlLite/');

// Main config ini directory
define('_DIR_CONFIG_INI_', 'bin/database/config/ini/');

// Set Application domaine when you are in local "epaphrodite-framework/"
define('_DOMAINE_', "");

// Fake folders link
define('_FAKE_', 'views/');

// Main home page
define('_HOME_', 'views/index/');

// Logout
define('_LOGOUT_', 'logout/');

// python directory
define('_PYTHON_', 'bin/epaphrodites/python/');

// Dashboard home page
define('_DASHBOARD_', 'dashboard/');

// Login home page
define('_LOGIN_', 'views/login/');

// Session auth login
define('_AUTH_LOGIN_', 'login');

// Session auth contact
define('_AUTH_CONTACT_', 'contact');

// Session auth id
define('_AUTH_ID_', 'id');

// Session auth type
define('_AUTH_TYPE_', 'type');

// Session auth nom et prenoms
define('_AUTH_NAME_', 'usersname');

// Session auth email
define('_AUTH_EMAIL_', 'email');

// Token field name
define('CSRF_FIELD_NAME', 'token_csrf');
