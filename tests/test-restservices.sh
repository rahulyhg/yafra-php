#!/bin/sh
#
#
#export RANDOMID=openssl rand -base64 20
export YSERVER=http://192.168.1.1/yafraphp/rest
export YKEY="yafra-DEBUG-dummytoken000demo.demo@gmail.com"


printf "\n\ntest BEGIN\n"
printf "test var YSERVER: $YSERVER \n"
printf "test var YKEY: $YKEY \n"

printf "test program load\n"
curl $YSERVER/users

printf "\n\ntest END\n"
