#версия с zend thread safety для работы parallels
FROM mohsenmottaghi/php-fpm-zts:latest
RUN apt-get -y update
RUN apt-get -y install git
RUN pecl install parallel
RUN curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer
WORKDIR /var/www/html

ADD ../app /var/www/html
ADD ../.docker/php-ext-parallel.ini /usr/local/etc/php/conf.d/php-ext-parallel.ini

WORKDIR /var/www/html
RUN composer install
RUN chmod 777 /var/www/html/logs