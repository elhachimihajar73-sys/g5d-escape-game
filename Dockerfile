FROM php:8.2-apache

# Copier tout le projet
COPY . /var/www/html/

# Activer les extensions PHP
RUN docker-php-ext-install pdo pdo_mysql

# Définir le fichier principal
RUN echo "DirectoryIndex electricity_router.php" >> /etc/apache2/conf-enabled/docker-php.conf

# Activer mod_rewrite
RUN a2enmod rewrite

EXPOSE 80