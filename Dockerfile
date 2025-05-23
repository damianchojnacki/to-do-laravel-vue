#syntax=docker/dockerfile:1

# Adapted from https://github.com/dunglas/symfony-docker

# Versions
FROM dunglas/frankenphp:1-php8.3 AS frankenphp_upstream

# The different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target

# Base FrankenPHP + bind9 image
FROM frankenphp_upstream AS frankenphp_base

WORKDIR /app

RUN apt-get update; \
    apt-get install --no-install-recommends -y \
	acl \
	file \
	gettext \
	git \
    apt-get remove --purge --auto-remove -y; \
    rm -rf /var/lib/apt/lists/*;

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN set -eux; \
	install-php-extensions \
		@composer \
		apcu \
		intl \
		opcache \
		zip \
    	gd \
    	exif \
        pdo_sqlite \
	;

COPY --link frankenphp/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY --link --chmod=755 frankenphp/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY --link frankenphp/Caddyfile /etc/caddy/Caddyfile

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1

CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]

# Build frontend assets
FROM node:22-alpine AS builder

# Check https://github.com/nodejs/docker-node/tree/b4117f9333da4138b03a546ec926ef50a31506c3#nodealpine to understand why libc6-compat might be needed.
# hadolint ignore=DL3018
RUN apk add --no-cache libc6-compat

WORKDIR /srv/app

COPY package.json package.json
COPY package-lock.json package-lock.json

RUN npm ci

COPY --link . .

RUN	npm run build

# Prod FrankenPHP image
FROM frankenphp_base AS frankenphp_prod

ENV APP_ENV prod

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# prevent the reinstallation of vendors at every changes in the source code
COPY --link composer.* ./
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

# copy sources
COPY --link . ./
COPY --from=builder --link /srv/app/public/build ./public/build
RUN rm -Rf frankenphp/

RUN set -eux; \
	composer dump-autoload --classmap-authoritative --no-dev
