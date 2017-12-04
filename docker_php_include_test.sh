#!/bin/bash
MEMTEST_ARG=''
while getopts ":mo" opt; do
  case ${opt} in
    m )
      MEMTEST_ARG='-m'
    ;;
    o )
      ENABLE_PHP_OPCACHE=1
    ;;
    \? ) echo "Usage: docker_php_include_test.sh [-m]"
      ;;
  esac
done

GREEN='\033[0;32m'
CYAN='\033[0;36m'
NC='\033[0m'


echo -e "\n${GREEN}***** Building PHP Docker Image *****${NC}"
docker build -q -t phpincludetest_image .

echo -e "${GREEN}***** Running test suite from host *****${NC}"
if [ "$ENABLE_PHP_OPCACHE" = "1" ]; then
  PHP_OPCACHE="-d opcache.enable_cli=on"
  DOCKER_PHP_OPCACHE_INSTALL='echo "opcache.enable_cli = on" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini && '
fi

php $PHP_OPCACHE php_include_test.php $MEMTEST_ARG host

echo -e "\n${GREEN}***** Running test suite via Docker Volume *****${NC}"
docker run -it --rm --name phpincludetest -v "$PWD":/usr/src/phpincludetest -w /usr/src/phpincludetest phpincludetest_image php $PHP_OPCACHE php_include_test.php $MEMTEST_ARG volume

echo -e "\n${GREEN}***** Running test suite via Docker Image w/o Volume *****${NC}"
docker run --rm --name phpincludetest_image phpincludetest_image php $PHP_OPCACHE php_include_test.php $MEMTEST_ARG image
