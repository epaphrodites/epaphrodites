#ifndef PHP_SHLOMO_H
#define PHP_SHLOMO_H

extern zend_module_entry shlomo_module_entry;
#define phpext_shlomo_ptr &shlomo_module_entry

#define PHP_SHLOMO_VERSION "0.1"

#ifdef ZTS
#include "TSRM.h"
#endif

// Si tu ajoutes des globals plus tard
ZEND_BEGIN_MODULE_GLOBALS(shlomo)
    // long example_global;
ZEND_END_MODULE_GLOBALS(shlomo)

// Accès aux globals
#ifdef ZTS
#define SHLOMO_G(v) TSRMG(shlomo_globals_id, zend_shlomo_globals *, v)
#else
#define SHLOMO_G(v) (shlomo_globals.v)
#endif

// Déclarations des fonctions exposées à PHP
PHP_FUNCTION(shlomo_hello);

#endif /* PHP_SHLOMO_H */
