#!/bin/bash

# if the /data/mariadb directory doesn't contain anything, then initialise it
directory="/data/mariadb/data"

chmod -R 777 /data/mariadb
setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx /data/mariadb
setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx /data/mariadb
if [ ! "$(ls -A $directory)" ]; then
    /usr/bin/mysql_install_db --datadir=/data/mariadb/data --user=mysql
    /usr/bin/mysqld_safe --init-file=/opt/bin/mariadb-setup.sql
else
    /usr/bin/mysqld_safe
fi

