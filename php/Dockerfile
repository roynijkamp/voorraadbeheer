FROM php:8.3-apache

# MySQL extensie installeren
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Apache rewrite module activeren
RUN a2enmod rewrite