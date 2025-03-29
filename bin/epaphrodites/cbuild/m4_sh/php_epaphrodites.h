#ifndef PHP_EPAPHRODITES_H
#define PHP_EPAPHRODITES_H

extern zend_module_entry epaphrodites_module_entry;
#define phpext_epaphrodites_ptr &epaphrodites_module_entry

#define PHP_EPAPHRODITES_VERSION "0.1"

#ifdef ZTS
#include "TSRM.h"
#endif

PHP_FUNCTION(epaphrodites_hello);

#endif /* PHP_EPAPHRODITES_H */
