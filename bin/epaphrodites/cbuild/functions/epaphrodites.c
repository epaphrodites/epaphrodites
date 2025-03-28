#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "php_epaphrodites.h"

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_epaphrodites_hello, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

PHP_FUNCTION(epaphrodites_hello_c) {
    php_printf("Hello depuis l'extension Epaphrodites!\n");
}

const zend_function_entry epaphrodites_functions[] = {
    PHP_FE(epaphrodites_hello, arginfo_epaphrodites_hello)
    PHP_FE_END
};

// Fonction d'initialisation du module
PHP_MINIT_FUNCTION(epaphrodites) {
    return SUCCESS;
}

// Fonction pour afficher les informations du module
PHP_MINFO_FUNCTION(epaphrodites) {
    php_info_print_table_start();
    php_info_print_table_header(2, "epaphrodites support", "enabled");
    php_info_print_table_end();
}

// Définition du module
zend_module_entry epaphrodites_module_entry = {
    STANDARD_MODULE_HEADER,
    "epaphrodites",            // Nom de l'extension
    epaphrodites_functions,    // Liste des fonctions
    PHP_MINIT(epaphrodites),   // Initialisation
    NULL,                      // Shutdown
    NULL,                      // Request start
    NULL,                      // Request shutdown
    PHP_MINFO(epaphrodites),   // Informations PHP
    NO_VERSION_YET,
    STANDARD_MODULE_PROPERTIES
};

// Enregistrement du module
ZEND_GET_MODULE(epaphrodites)
