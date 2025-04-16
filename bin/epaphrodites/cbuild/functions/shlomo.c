#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"

// Déclaration des fonctions
PHP_FUNCTION(shlomo_hello);

// Mapping des fonctions PHP
static const zend_function_entry shlomo_functions[] = {
    PHP_FE(shlomo_hello, NULL)
    PHP_FE_END
};

// Module entry
zend_module_entry shlomo_module_entry = {
    STANDARD_MODULE_HEADER,
    "shlomo",                // nom de l'extension
    shlomo_functions,        // fonctions exposées
    NULL,                    // MINIT
    NULL,                    // MSHUTDOWN
    NULL,                    // RINIT
    NULL,                    // RSHUTDOWN
    NULL,                    // MINFO
    NO_VERSION_YET,
    STANDARD_MODULE_PROPERTIES
};

ZEND_GET_MODULE(shlomo)

// Implémentation de la fonction
PHP_FUNCTION(shlomo_hello)
{
    php_printf("Bienvenu sur epaphrodites C!\n");
}