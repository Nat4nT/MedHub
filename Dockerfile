FROM php:8.3-apache

RUN a2enmod rewrite

RUN docker-php-ext-install pdo pdo_mysql

COPY public_html /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD bash -c "vendor/bin/phinx migrate -e development && vendor/bin/phinx seed:run -e development && apache2-foreground"
