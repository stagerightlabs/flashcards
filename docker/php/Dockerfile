FROM php:7.3-fpm-alpine

LABEL maintainer="Ryan Durham <ryan@stagerightlabs.com>"

# replace www-data(82:82) with custom user (1000:1000) or override
ARG NAME=pbr
ENV NAME ${NAME}
RUN deluser www-data && \
    adduser -s /bin/sh -D -u 1000 -g '' ${NAME} ${NAME}

# install packages
RUN apk add --no-cache postgresql-dev

# install php extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql bcmath

# make sure that ~/.composer belongs to the user
RUN mkdir /home/${NAME}/.composer && chown -R ${NAME}:${NAME} /home/${NAME}

# install composer
COPY install_composer.php /home/${NAME}/
RUN php /home/${NAME}/install_composer.php --install-dir=/usr/local/bin --filename=composer && \
    rm /home/${NAME}/install_composer.php

# command
USER ${NAME}
WORKDIR /var/www
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
