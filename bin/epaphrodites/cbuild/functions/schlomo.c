#include "php.h"

/* Argument info pour schlomo_hello (aucun argument) */
ZEND_BEGIN_ARG_INFO(arginfo_schlomo_hello, 0)
ZEND_END_ARG_INFO()

/* Argument info pour schlomo_add (2 long) */
ZEND_BEGIN_ARG_INFO(arginfo_schlomo_add, 0)
    ZEND_ARG_INFO(0, a)
    ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

PHP_FUNCTION(schlomo_hello)
{
    php_printf("Salut depuis l'extension native schlomo!\n");
}

PHP_FUNCTION(schlomo_add)
{
    zend_long a, b;

    if (zend_parse_parameters(ZEND_NUM_ARGS(), "ll", &a, &b) == FAILURE) {
        RETURN_FALSE;
    }

    RETURN_LONG(a + b);
}

/* Table des fonctions */
static const zend_function_entry schlomo_functions[] = {
    PHP_FE(schlomo_hello, arginfo_schlomo_hello)
    PHP_FE(schlomo_add, arginfo_schlomo_add)
    PHP_FE_END
};

/* Déclaration du module */
zend_module_entry schlomo_module_entry = {
    STANDARD_MODULE_HEADER,
    "schlomo",  /* Nom du module */
    schlomo_functions,  /* Fonctionnalités du module */
    NULL, NULL, NULL, NULL, NULL,  /* Callback's */
    "0.1",  /* Version */
    STANDARD_MODULE_PROPERTIES
};

ZEND_GET_MODULE(schlomo)  /* Point d'entrée nécessaire ! */
