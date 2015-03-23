#!/bin/sh
#
#
#export RANDOMID=openssl rand -base64 20
export YSERVER=http://www.yafra.org/


printf "\n\ntest BEGIN\n"
printf "test var YSERVER: $YSERVER \n"
printf "test var YKEY: $YKEY \n"

printf "test program load\n"
curl $YSERVER

printf "\n\ntest END\n"
