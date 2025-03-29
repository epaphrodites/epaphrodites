#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "../m4_sh/php_heredia.h"

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_heredia_hello, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

PHP_FUNCTION(heredia_hello) {
    php_printf("Hello depuis l'extension Heredia!\n");
}

const zend_function_entry heredia_functions[] = {
    PHP_FE(heredia_hello, arginfo_heredia_hello)
    PHP_FE_END
};

// Fonction d'initialisation du module
PHP_MINIT_FUNCTION(heredia) {
    return SUCCESS;
}

// Fonction pour afficher les informations du module
PHP_MINFO_FUNCTION(heredia) {
    php_info_print_table_start();
    php_info_print_table_header(2, "heredia support", "enabled");
    php_info_print_table_end();
}

// Définition du module
zend_module_entry heredia_module_entry = {
    STANDARD_MODULE_HEADER,
    "heredia",            // Nom de l'extension
    heredia_functions,    // Liste des fonctions
    PHP_MINIT(heredia),   // Initialisation
    NULL,                 // Shutdown
    NULL,                 // Request start
    NULL,                 // Request shutdown
    PHP_MINFO(heredia),   // Informations PHP
    NO_VERSION_YET,
    STANDARD_MODULE_PROPERTIES
};

// Enregistrement du module
ZEND_GET_MODULE(heredia)
