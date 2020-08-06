FROM php:7.4.1-fpm

COPY install-composer.sh /
RUN apt-get update \
  && apt-get install -y wget git unzip libpq-dev \
     gcc \
     make \
     libpng-dev \
     vim \
     libpng-dev \
     libjpeg-dev \
     libfreetype6-dev \
     libonig-dev \
  && : 'Install Node.js' \
  &&  curl -sL https://deb.nodesource.com/setup_12.x | bash - \
  && apt-get install -y nodejs \
  && : 'Install PHP Extensions' \
  && docker-php-ext-install -j$(nproc) pdo_pgsql \
  && docker-php-ext-install pdo_mysql mysqli \
  && : 'Install PHP GD' \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \
  && : 'Install Composer' \
  && chmod 755 /install-composer.sh \
  && /install-composer.sh \
  && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html/vuesplash
