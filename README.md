# yafra.org

## PHP
This repository holds a RESTful server based on PHP and Slimframework. It provides a simple database access (native sql drivers - no ORM - currently MySQL). PHP is supported very well on many different hosting providers and therefore provides a simple approach to implement a server providing RESTful API, mainly for mobile or fat clients.

[![Build Status](https://api.shippable.com/projects/54ceb4b15ab6cc13528a789e/badge?branchName=master)](https://app.shippable.com/projects/54ceb4b15ab6cc13528a789e/builds/latest)

## Development Environment
 * https://github.com/yafraorg/yafra/wiki/PHP using Jetbrains PhpStorm IDE

## Automatic build and run environment
 * Shippable: https://app.shippable.com/projects/54ceb4b15ab6cc13528a789e
 * Docker: https://github.com/yafraorg/docker-yafraphp

## Further information
read more about yafra on:
 * http://www.yafra.org
 * https://github.com/yafraorg/yafra/wiki/PHP
 * raise a ticket related to yafra.org framework: https://github.com/yafraorg/yafra/issues?state=open
 * raise a ticket related to this nodejs code: https://github.com/yafraorg/yafra-php/issues?state=open

## Starting with PHP Slimframework

 * PHP 5.x enabled web server with common modules installed
 * MySQL database with a "yafra" database model
 * update the include/Config.php as needed
 * use curl or Chrom REST Console plugin to test

## Standards used

### Date Time
YYYY-mm-ddThh:mm converted to dd.mm.YYYY hh:mm

ISO 8601
