# Image de base : PHP 8.3.6 avec Apache
FROM php:8.3.6-apache

# Installation des dépendances et des extensions PHP
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y libzip-dev zip nano && \
    docker-php-ext-install pdo pdo_mysql && \
    pecl install xdebug && docker-php-ext-enable xdebug && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd && \
    docker-php-ext-install zip

# Configuration de Xdebug
RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.in

# Installation de Composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

# Définition de la variable d'environnement pour autoriser Composer en tant que superutilisateur
ENV COMPOSER_ALLOW_SUPERUSER 1

# Copie du code source dans le conteneur
COPY . /var/www/html/

# Définition du répertoire de travail
WORKDIR /var/www/html/

# Installation de PHPMailer via Composer
RUN composer require phpmailer/phpmailer

# Configuration du répertoire racine d'Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Activation du module de réécriture d'URL d'Apache
RUN a2enmod rewrite

# Exécution de composer install
RUN composer install

# Copie d'un fichier de configuration PHP personnalisé
COPY custom-php.ini /usr/local/etc/php/conf.d/custom-php.ini
