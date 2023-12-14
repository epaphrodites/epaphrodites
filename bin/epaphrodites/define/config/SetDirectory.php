<?php

/**************************************************************
 ****************| DEFINE ALL DIRECTORIES |********************** 
 ***************************************************************/

// Config language
define('_LANG_', 'eng');

// Config front files
define('_FRONT_', '.html');

// Config API token Key name for POST or GET
define('_KEYGEN_', 'CRSF');

// Main directory folder
define('_DIR_MAIN_', 'bin');

// Set database accpeted 'sql' or 'nosql'
define('_DATABASE_', 'sql');

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

// Epaphrodite main directory
define('_EPAPHRODITE_', 'bin/epaphrodite');

// Main static datas (static storage)
define('_DIR_CONFIG_', 'bin/database/datas/arrays/');

// Main email param file
define('_DIR_MAIL_', 'bin/epaphrodite/define/config/ini/');

// Main Json datas directory
define('_DIR_JSON_DATAS_', 'bin/database/datas/json');

// Main toml datas directory
define('_DIR_TOML_DATAS_', 'bin/database/datas/toml/');

// Main config ini directory
define('_DIR_CONFIG_INI_', 'bin/database/config/ini/');

// Set Application domaine when you are in local "epaphrodite-framework/"
define('_DOMAINE_', "");

// Share directory
define('_DIR_PRINTER_', 'bin/share/FilesImportLibrary/fpdf');

// Main home page
define('_HOME_', 'views/index/');

// Logout
define('_LOGOUT_', 'logout/');

define('_PYTHON_', 'bin/epaphrodite/python/');

// Dashboard home page
define('_DASHBOARD_', 'dashboard/');

// Login home page
define('_LOGIN_', 'views/login/');

// Session auth login
define('_AUTH_LOGIN_', 'login');

// Session auth login
define('_AUTH_OTHER_LOGIN_', 'login_other');

// Session auth contact
define('_AUTH_CONTACT_', 'contact');

// Session auth id
define('_AUTH_ID_', 'id');

// Session auth type
define('_AUTH_TYPE_', 'type');

// Session auth nom et prenoms
define('_AUTH_NAME_', 'nom_prenoms');

// Session auth nom et prenoms
define('_AUTH_EMAIL_', 'email');

// Token field name
define('CSRF_FIELD_NAME', 'token_csrf');
