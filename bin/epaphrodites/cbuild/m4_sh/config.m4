PHP_ARG_ENABLE(heredia, whether to enable the Heredia extension,
[  --enable-heredia   Enable Heredia extension support])

PHP_ADD_INCLUDE([../m4_sh])

CFLAGS="$CFLAGS -I$(pwd)/cbuild/m4_sh"
CPPFLAGS="$CPPFLAGS -I$(pwd)/cbuild/m4_sh"

if test "$PHP_HEREDIA" != "no"; then
    AC_DEFINE(HAVE_HEREDIA, 1, [Have heredia extension])
    PHP_NEW_EXTENSION(heredia, ../functions/heredia.c, $ext_shared)
fi
