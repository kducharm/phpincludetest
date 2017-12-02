FROM php:7.1-cli
ARG MEMTEST_ARG
ENV MEMTEST_ARG ${MEMTEST_ARG}
COPY . /usr/src/phpincludetest
WORKDIR /usr/src/phpincludetest
CMD [ "sh", "-c", "php ./php_include_test.php ${MEMTEST_ARG} image" ] 
