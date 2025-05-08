# Dockerfile
FROM php:8.4.5-apache

# Instala libs para pdo_mysql e outras extensões + ferramentas para instalar o Composer
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    default-mysql-client \
    libmariadb-dev-compat \
    libmariadb-dev \
    curl \
    git \
  && rm -rf /var/lib/apt/lists/*

# Habilita extensões PHP necessárias
RUN docker-php-ext-install pdo_mysql mbstring zip

# Instala o Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php \
 && mv composer.phar /usr/local/bin/composer

# Copia o php.ini customizado
COPY php.ini /usr/local/etc/php/conf.d/

# Define um ServerName para suprimir o warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Altera o DocumentRoot para apontar para a pasta public do Laravel
RUN sed -ri 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' \
  /etc/apache2/sites-available/*.conf \
 && sed -ri 's!<Directory /var/www/html>!<Directory /var/www/html/public>!g' \
  /etc/apache2/apache2.conf

# Habilita o mod_rewrite do Apache (necessário para as URLs amigáveis do Laravel)
RUN a2enmod rewrite

WORKDIR /var/www/html

# # Ajusta permissões de storage e cache para o usuário www-data
# RUN chown -R www-data:www-data storage bootstrap/cache \
#  && chmod -R 775 storage bootstrap/cache
