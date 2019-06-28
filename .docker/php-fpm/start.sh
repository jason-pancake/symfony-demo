#!/bin/bash

setfacl -R -m u:"www-data":rwX /app/var
setfacl -dR -m u:"www-data":rwX /app/var

php-fpm -F