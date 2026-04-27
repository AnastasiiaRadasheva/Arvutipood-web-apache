FROM php:8.2-apache

RUN a2enmod rewrite

COPY src/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

RUN touch /var/log/apache2/api.log && chmod 666 /var/log/apache2/api.log
