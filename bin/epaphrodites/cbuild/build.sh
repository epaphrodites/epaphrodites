#!/bin/bash

# Chemins importants
EXT_NAME="epaphrodites"                  # Nom de l'extension
PHP_INCLUDES=$(php-config --includes)    # Inclus les headers PHP
EXTENSION_DIR=$(php-config --extension-dir) # Répertoire des extensions PHP
SRC_DIR="./src"                          # Répertoire des fichiers sources
OUTPUT_FILE="${EXT_NAME}.so"             # Nom du fichier compilé

# Chemin d'inclusion de PhpCpp
PHPCPP_INCLUDE="/usr/local/include"
PHPCPP_LIB="/usr/local/lib"

# Commande de compilation
g++ -shared -o ${OUTPUT_FILE} -fPIC \
    -I${PHP_INCLUDES} \
    -I${PHPCPP_INCLUDE} \
    -L${PHPCPP_LIB} -lphpcpp \
    ${SRC_DIR}/functions.cpp \
    ${SRC_DIR}/extension.cpp

# Déplacer le fichier compilé dans le répertoire des extensions PHP
sudo mv ${OUTPUT_FILE} ${EXTENSION_DIR}

# Afficher un message de succès
echo "Extension ${EXT_NAME} compilée et installée dans ${EXTENSION_DIR}."
