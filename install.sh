#!/bin/bash

yes |  cp -rf $(pwd)/vagrant/bin/clean.sh /usr/local/bin/clean &&  chmod a+x /usr/local/bin/clean
yes |  cp -rf $(pwd)/vagrant/bin/mysql.sh /usr/local/bin/mysql &&  chmod a+x /usr/local/bin/mysql
yes |  cp -rf $(pwd)/vagrant/bin/mysqldump.sh /usr/local/bin/mysqldump &&  chmod a+x /usr/local/bin/mysqldump
yes |  cp -rf $(pwd)/vagrant/bin/login.sh /usr/local/bin/login &&   chmod a+x /usr/local/bin/login
yes |  cp -rf $(pwd)/vagrant/bin/composer.sh /usr/local/bin/composer &&   chmod a+x /usr/local/bin/composer
yes |  cp -rf $(pwd)/vagrant/bin/console.sh /usr/local/bin/console &&   chmod a+x /usr/local/bin/console
yes |  cp -rf $(pwd)/vagrant/bin/npm.sh /usr/local/bin/npm &&   chmod a+x /usr/local/bin/npm
yes |   cp -rf $(pwd)/vagrant/bin/bower.sh /usr/local/bin/bower &&   chmod a+x /usr/local/bin/bower
yes |   cp -rf $(pwd)/vagrant/bin/gulp.sh /usr/local/bin/gulp &&   chmod a+x /usr/local/bin/gulp
yes |   cp -rf $(pwd)/vagrant/bin/cache.sh /usr/local/bin/cache &&  chmod a+x /usr/local/bin/cache
yes |   cp -rf $(pwd)/vagrant/bin/queue.sh /usr/local/bin/queue &&  chmod a+x /usr/local/bin/queue

mkdir -p $(pwd)/application/bower_components
mkdir -p $(pwd)/application/node_modules
mkdir -p $(pwd)/vagrant/data

chmod -R 777 $(pwd)/vagrant/data
chmod -R 777 $(pwd)/application/var/sessions
chmod -R 777 $(pwd)/application/var/cache
chmod -R 777 $(pwd)/application/web/uploads
chmod -R 777 $(pwd)/application/bower_components
chmod -R 777 $(pwd)/application/node_modules

docker-compose up -d --build

/usr/local/bin/queue $1

/usr/local/bin/composer $1 install --no-interaction

/usr/local/bin/console $1 doctrine:schema:drop --force

/usr/local/bin/console $1 doctrine:schema:update --force

/usr/local/bin/console $1 fos:elastica:reset

/usr/local/bin/console $1 dummy:data

/usr/local/bin/npm $1 install --no-optional

/usr/local/bin/bower $1 install

/usr/local/bin/gulp $1

/usr/local/bin/cache $1

chmod -R 777 $(pwd)/application/var/sessions
chmod -R 777 $(pwd)/application/var/cache
chmod -R 777 $(pwd)/application/web/uploads
chmod -R 777 $(pwd)/application/bower_components
chmod -R 777 $(pwd)/application/node_modules
chmod -R 777 $(pwd)/vagrant/data