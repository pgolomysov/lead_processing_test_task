#версия с zend thread safety для работы parallels
FROM mohsenmottaghi/php-fpm-zts:latest
RUN pecl install parallel
WORKDIR /var/www/html

COPY ./app /var/www/html
COPY ../.docker/php-ext-parallel.ini /usr/local/etc/php/conf.d/php-ext-parallel.ini
#todo composer
#todo проброс портов