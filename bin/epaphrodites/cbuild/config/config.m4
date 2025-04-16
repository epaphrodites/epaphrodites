PHP_ARG_ENABLE(schlomo, whether to enable schlomo extension,
[  --enable-schlomo      Enable schlomo support])

if test "$PHP_SCHLOMO" != "no"; then
  PHP_NEW_EXTENSION(schlomo, ../functions/schlomo.c, $ext_shared)
fi
