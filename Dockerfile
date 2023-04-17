# Imagen base de PHP
FROM php:8.2-fpm

# Actualizar el sistema
RUN apt-get update && apt-get upgrade

RUN apt-get install build-essential



RUN apt-get install -y \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

RUN apt-get install -y libxml2-utils

RUN apt-get update \
    && apt-get install -y libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-enable zip \
    && apt-get install -y libxml2-dev \
    && docker-php-ext-install xml \
    && docker-php-ext-enable xml \
    && apt-get install -y libpng-dev

RUN apt-get update && \
    apt-get install -y libpng-dev && \
    docker-php-ext-install gd

RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev

RUN apt-get update && apt-get install -y unixodbc-dev

RUN pecl install sqlsrv pdo_sqlsrv xdebug

RUN docker-php-ext-enable sqlsrv pdo_sqlsrv xdebug

RUN apt-get update && apt-get install -y sudo

RUN useradd -u 1000 jorge

# Exit root user
RUN usermod -aG sudo jorge

RUN echo "myuser ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/jorge

# Cambiar al usuario root para descargar la clave pública de Microsoft
USER root

# Configurar el servidor DNS de Docker
# RUN echo "nameserver 8.8.8.8" > /etc/resolv.conf

# Actualizar el sistema e instalar dependencias necesarias
RUN apt-get update \
    && apt-get install -y gnupg2 curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Descargar la clave pública de Microsoft y agregarla al keyring de apt
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -

# Agregar el repositorio de Microsoft al archivo sources.list de apt
RUN curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list

# Actualizar el sistema e instalar el controlador ODBC de Microsoft para SQL Server
RUN apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

RUN apt-get install -y libmagickwand-dev --no-install-recommends
RUN pecl install imagick && docker-php-ext-enable imagick
