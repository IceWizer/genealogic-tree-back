FROM php:8.5.12-fpm-alpine3.24
LABEL authors="Florian Charlot"

COPY --from=composer:2.11.2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache \
	bash \
	curl \
	git \
	libzip-dev \
	unzip \
	zip \
	&& docker-php-ext-install \
	pdo_mysql \
	zip \
	&& rm -rf /var/cache/apk/* \
	# Add xdebug
	&& apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
	&& apk add --no-cache --update linux-headers \
	&& pecl install xdebug-3.5.3 \
	&& apk del -f .build-deps

COPY ./ /var/www/html/

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN	mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/html

RUN	composer install --no-interaction --no-scripts --no-dev

EXPOSE 80
EXPOSE 9003

# Entrypoint script
COPY --chmod=755 ./docker/php/entrypoint.sh /docker/php/entrypoint.sh

ADD docker/php/conf.d/php.ini /usr/local/etc/php/
ADD docker/php/conf.d/www.conf /usr/local/etc/php-fpm.d/

ENTRYPOINT ["/docker/php/entrypoint.sh"]
