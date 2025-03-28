PHP_ARG_ENABLE(epaphrodites, whether to enable the Epaphrodites extension,
[  --enable-epaphrodites   Enable Epaphrodites extension support])

if test "$PHP_EPAPHRODITES" != "no"; then
    AC_DEFINE(HAVE_EPAPHRODITES, 1, [Have epaphrodites extension])
    PHP_NEW_EXTENSION(epaphrodites, ../functions/epaphrodites.c, $ext_shared)

fi