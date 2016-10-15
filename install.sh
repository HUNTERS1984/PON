#!/bin/bash

chmod -R 777 $(pwd)/vagrant/data

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

docker-compose up -d --build

/usr/local/bin/queue