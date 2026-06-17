FROM php:8.2-apache

COPY . /var/www/html/

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

# Page d'accueil = login.php
RUN echo "DirectoryIndex login.php index.php index.html" > /etc/apache2/mods-enabled/dir.conf

RUN echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

EXPOSE 80