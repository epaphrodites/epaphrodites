#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "shlomo.h"
#include <sys/stat.h>

// ========================================================================
// SECTION 1: FONCTIONS MATHÉMATIQUES
// ========================================================================

// Fonction interne pour le calcul de factorielle
static zend_long calculate_factorial(zend_long num) {
    zend_long result = 1;
    
    if (num < 0) {
        return -1; // Signal d'erreur
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
// SECTION 2: FONCTIONS DE MANIPULATION DE CHAÎNES
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
// SECTION 3: FONCTIONS DE MANIPULATION DE FICHIERS
// ========================================================================

// Fonction interne pour obtenir les stats d'un fichier
static zend_bool get_file_stats(char *filepath, struct stat *filestat) {
    if (stat(filepath, filestat) != 0) {
        return 0; // échec
    }
    return 1; // succès
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
// SECTION 4: FONCTION PRINCIPALE DE BIENVENUE
// ========================================================================

// Fonction de bienvenue
PHP_FUNCTION(shlomo_hello) {
    php_printf("Bienvenu sur epaphrodites C!\n");
}

// ========================================================================
// SECTION 5: CONFIGURATION DU MODULE
// ========================================================================

// Mapping des fonctions PHP
static const zend_function_entry shlomo_functions[] = {
    PHP_FE(shlomo_hello, NULL)
    PHP_FE(shlomo_factorial, NULL)
    PHP_FE(shlomo_reverse_string, NULL)
    PHP_FE(shlomo_file_stats, NULL)
    PHP_FE_END
};

// Module entry
zend_module_entry shlomo_module_entry = {
    STANDARD_MODULE_HEADER,
    "shlomo", // nom de l'extension
    shlomo_functions, // fonctions exposées
    NULL, // MINIT
    NULL, // MSHUTDOWN
    NULL, // RINIT
    NULL, // RSHUTDOWN
    NULL, // MINFO
    PHP_SHLOMO_VERSION,
    STANDARD_MODULE_PROPERTIES
};

ZEND_GET_MODULE(shlomo)