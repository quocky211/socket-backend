# Use the official PHP image as the base image
FROM php:8.1 as web

RUN apt-get update -y
RUN apt-get install -y unzip libpq-dev libcurl4-gnutls-dev

RUN docker-php-ext-install pdo pdo_mysql exif bcmath

WORKDIR /var/www
COPY . .

COPY --from=composer:2.5.4 /usr/bin/composer /usr/bin/composer

ENV PORT=8000
ENTRYPOINT [ "docker/entrypoint.sh" ]


