#!/bin/bash
MEMTEST_ARG=''
while getopts ":m" opt; do
  case ${opt} in
    m ) 
      MEMTEST_ARG='-m'
    ;;
    \? ) echo "Usage: docker_php_include_test.sh [-m]"
      ;;
  esac
done

GREEN='\033[0;32m'
CYAN='\033[0;36m'
NC='\033[0m'

echo -e "${GREEN}***** Running test suite from host *****${NC}"
php php_include_test.php $MEMTEST_ARG host

echo -e "\n${GREEN}***** Running test suite via Docker Volume *****${NC}"
docker run -it --rm --name phpincludetest -v "$PWD":/usr/src/phpincludetest -w /usr/src/phpincludetest php:7.1-cli php php_include_test.php $MEMTEST_ARG volume

echo -e "\n${GREEN}***** Running test suite via Docker Image w/o Volume *****${NC}"
docker build -q -t phpincludetest_image --build-arg MEMTEST_ARG=$MEMTEST_ARG .
docker run --rm --name phpincludetest_image phpincludetest_image
