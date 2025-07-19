FROM php:8.4-cli

WORKDIR /app

ENV HOST_UID=1000
ENV HOST_GID=1000

ENV APP_DIRECTORY=/app
ENV APP_UID=1000
ENV APP_GID=1000
ENV APP_USER=app
ENV APP_GROUP=app

RUN apt-get update && apt-get install -y \
    bash \
    bash-completion \
    fish \
    git \
    gosu \
    libzip-dev \
    man \
    pandoc \
    unzip \
    zlib1g-dev \
    zsh \
    && groupadd -g "$APP_GID" "$APP_GROUP" \
    && useradd -m -u "$APP_UID" -g "$APP_GROUP" "$APP_USER" \
    && chmod 777 "$APP_DIRECTORY" \
    && chown -R "$APP_USER:$APP_GROUP" "$APP_DIRECTORY" \
    && ln -s /app/extra/bash_completion.d/textile /etc/bash_completion.d/textile \
    && ln -s /app/extra/fish_completion.d/textile.fish /etc/fish/completions/textile.fish \
    && ln -s /app/extra/zsh_completion.d/_textile /usr/local/share/zsh/site-functions/_textile \
    && echo "autoload -U compinit; compinit" >> /home/app/.zshrc \
    && chown "$APP_USER:$APP_GROUP" /home/app/.zshrc

RUN docker-php-ext-install zip

RUN pecl install xdebug-3.4.0beta1

RUN docker-php-ext-enable xdebug

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_ROOT_VERSION=dev-master
ENV PATH=/app/bin:$PATH
