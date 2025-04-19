/*
===============================================================================
                                 MOTIVATION
===============================================================================

The "shlomo" module is a PHP extension written in C, designed for the
Epaphrodites framework. Its goal is to bring native power to PHP: raw
performance, real multitasking, and low-level capabilities, all while keeping
a clean and simple interface for PHP developers.

This extension is motivated by three core principles:
  - Speed up critical operations using native C functions
  - Enable real asynchronous execution (via pthreads) from PHP
  - Provide a modular, extensible base for future framework needs

"Shlomo" means "peace" — symbolizing the harmony between high-level PHP
and low-level C.

===============================================================================
*/

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "../config/noella/noella.h"

// ========================================================================
// ARGINFO
// ========================================================================
ZEND_BEGIN_ARG_INFO_EX(arginfo_noella_hello, 0, 0, 0)
ZEND_END_ARG_INFO()

// ========================================================================
// FUNCTION: HELLO
// ========================================================================
PHP_FUNCTION(hello_noella)
{
    php_printf("Hello from Noella!\n");
}

// ========================================================================
// FUNCTION MAPPING
// ========================================================================
static const zend_function_entry noella_functions[] = {
    PHP_FE(hello_noella, arginfo_noella_hello)
    PHP_FE_END
};

// ========================================================================
// MODULE ENTRY
// ========================================================================
zend_module_entry noella_module_entry = {
    STANDARD_MODULE_HEADER,
    "noella",                    // extension name
    noella_functions,            // Functions
    NULL,                        // MINIT
    NULL,                        // MSHUTDOWN
    NULL,                        // RINIT
    NULL,                        // RSHUTDOWN
    NULL,                        // MINFO
    NOELLA_VERSION,              // Version
    STANDARD_MODULE_PROPERTIES
};

ZEND_GET_MODULE(noella)
