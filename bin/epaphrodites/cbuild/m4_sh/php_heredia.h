#ifndef PHP_HEREDIA_H
#define PHP_HEREDIA_H

extern zend_module_entry heredia_module_entry;
#define phpext_heredia_ptr &heredia_module_entry

#define PHP_HEREDIA_VERSION "0.1"

#ifdef ZTS
#include "TSRM.h"
#endif

PHP_FUNCTION(heredia_hello);

#endif /* PHP_HEREDIA_H */
