#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "shlomo.h"
#include <sys/stat.h>

// ========================================================================
// ARGINFO
// ========================================================================

ZEND_BEGIN_ARG_INFO(arginfo_shlomo_hello, 0)
ZEND_END_ARG_INFO()

// ========================================================================
// FUNCTION 1: WELCOME FUNCTION
// ========================================================================

// Welcome function
PHP_FUNCTION(shlomo_hello) {
    RETURN_STRING("Welcome to Epaphrodites in C!\n");
}

// ========================================================================
// CONFIGURATION
// ========================================================================

// PHP Functions Mapping
static const zend_function_entry shlomo_functions[] = {
    PHP_FE(shlomo_hello, arginfo_shlomo_hello)
    PHP_FE_END
};

// Module entry
zend_module_entry shlomo_module_entry = {
    STANDARD_MODULE_HEADER,
    "shlomo", // extension name
    shlomo_functions, // functions
    NULL, // MINIT
    NULL, // MSHUTDOWN
    NULL, // RINIT
    NULL, // RSHUTDOWN
    NULL, // MINFO
    PHP_SHLOMO_VERSION,
    STANDARD_MODULE_PROPERTIES
};

ZEND_GET_MODULE(shlomo)