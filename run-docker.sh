#!/bin/sh
#
# used to run on a docker image - see yafraorg docker.com 
#
export BASENODE=/work/repos
export PHPNODE=$BASENODE/yafra-php
export SYSADM=$BASENODE/yafra/org.yafra.sysadm
export WORKNODE=/work/yafra-runtime

cd $PHPNODE
npm install
bower install
cp -r backend /var/www/html
cp -r rest /var/www/html
cp -r bower_components /var/www/html
cp composer.json /var/www/html
cp index.php /var/www/html
cp main.css /var/www/html

cd /var/www/html
composer.phar install --no-dev

cd
apache2ctl start