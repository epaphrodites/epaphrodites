#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "shlomo.h"
#include <sys/stat.h>

// ========================================================================
// SECTION 0: ARGINFO
// ========================================================================

ZEND_BEGIN_ARG_INFO(arginfo_shlomo_hello, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_shlomo_factorial, 0, 1, IS_LONG, 0)
    ZEND_ARG_TYPE_INFO(0, num, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_shlomo_reverse_string, 0, 1, IS_STRING, 0)
    ZEND_ARG_TYPE_INFO(0, str, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_MASK_EX(arginfo_shlomo_file_stats, 0, 1, MAY_BE_ARRAY|MAY_BE_FALSE)
    ZEND_ARG_TYPE_INFO(0, filepath, IS_STRING, 0)
ZEND_END_ARG_INFO()

// ========================================================================
// SECTION 1: MATHEMATIQUES FUNCTION
// ========================================================================

// Fonction interne pour le calcul de factorielle
static zend_long calculate_factorial(zend_long num) {
    zend_long result = 1;
    
    if (num < 0) {
        return -1; // error
    }
    
    for (zend_long i = 1; i <= num; i++) {
        result *= i;
    }
    
    return result;
}

// Fonction exposée à PHP pour la factorielle
PHP_FUNCTION(shlomo_factorial) {
    zend_long num;
    zend_long result;
    
    ZEND_PARSE_PARAMETERS_START(1, 1)
        Z_PARAM_LONG(num)
    ZEND_PARSE_PARAMETERS_END();
    
    result = calculate_factorial(num);
    
    if (result == -1) {
        php_error_docref(NULL, E_WARNING, "Le nombre doit être positif");
        RETURN_FALSE;
    }
    
    RETURN_LONG(result);
}

// ========================================================================
// SECTION 2: MANAGEMENT CHARCHAR FUNCTION
// ========================================================================

// Fonction interne pour inverser une chaîne
static char* reverse_string(char *str, size_t str_len) {
    char *result = (char *) emalloc(str_len + 1);
    
    for (size_t i = 0; i < str_len; i++) {
        result[i] = str[str_len - i - 1];
    }
    result[str_len] = '\0';
    
    return result;
}

// Fonction exposée à PHP pour inverser une chaîne
PHP_FUNCTION(shlomo_reverse_string) {
    char *str;
    size_t str_len;
    char *result;
    
    ZEND_PARSE_PARAMETERS_START(1, 1)
        Z_PARAM_STRING(str, str_len)
    ZEND_PARSE_PARAMETERS_END();
    
    result = reverse_string(str, str_len);
    
    RETVAL_STRINGL(result, str_len);
    efree(result);
}

// ========================================================================
// SECTION 3: MANAGEMENT FILES FUNCTION
// ========================================================================

// Fonction interne pour obtenir les stats d'un fichier
static zend_bool get_file_stats(char *filepath, struct stat *filestat) {
    if (stat(filepath, filestat) != 0) {
        return 0; // fail
    }
    return 1; // success
}

// Fonction exposée à PHP pour obtenir les stats d'un fichier
PHP_FUNCTION(shlomo_file_stats) {
    char *filepath;
    size_t filepath_len;
    struct stat filestat;
    
    ZEND_PARSE_PARAMETERS_START(1, 1)
        Z_PARAM_STRING(filepath, filepath_len)
    ZEND_PARSE_PARAMETERS_END();
    
    if (!get_file_stats(filepath, &filestat)) {
        php_error_docref(NULL, E_WARNING, "Impossible d'accéder au fichier: %s", filepath);
        RETURN_FALSE;
    }
    
    array_init(return_value);
    
    add_assoc_long(return_value, "size", filestat.st_size);
    add_assoc_long(return_value, "mode", filestat.st_mode);
    add_assoc_long(return_value, "mtime", filestat.st_mtime);
    add_assoc_long(return_value, "atime", filestat.st_atime);
    add_assoc_long(return_value, "ctime", filestat.st_ctime);
}

// ========================================================================
// SECTION 4: WELCOME FUNCTION
// ========================================================================

// Welcome function
PHP_FUNCTION(shlomo_hello) {
    RETURN_STRING("Welcome to Epaphrodites est la!\n");
}

// ========================================================================
// SECTION 5: CONFIGURATION
// ========================================================================

// PHP Functions Mapping
static const zend_function_entry shlomo_functions[] = {
    PHP_FE(shlomo_hello, arginfo_shlomo_hello)
    PHP_FE(shlomo_factorial, arginfo_shlomo_factorial)
    PHP_FE(shlomo_reverse_string, arginfo_shlomo_reverse_string)
    PHP_FE(shlomo_file_stats, arginfo_shlomo_file_stats)
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