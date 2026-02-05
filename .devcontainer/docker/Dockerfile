FROM ubuntu:22.04

ARG NODE_VERSION=18
ARG POSTGRES_VERSION=15

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive TZ=UTC

RUN set -eux && \
    ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone && \
    apt-get update && \
    apt-get install -y \
        gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 \
        libcap2-bin libpng-dev python2 dnsutils && \
    curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor \
        | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null && \
    echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] \
        https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" \
        > /etc/apt/sources.list.d/ppa_ondrej_php.list && \
    curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor \
        | tee /etc/apt/keyrings/pgdg.gpg >/dev/null && \
    echo "deb [signed-by=/etc/apt/keyrings/pgdg.gpg] \
        http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" \
        > /etc/apt/sources.list.d/pgdg.list && \
    curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor \
        | tee /etc/apt/keyrings/yarn.gpg >/dev/null && \
    echo "deb [signed-by=/etc/apt/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" \
        > /etc/apt/sources.list.d/yarn.list && \
    apt-get update && \
    apt-get install -y \
        php8.4-cli php8.4-dev php8.4-pgsql php8.4-sqlite3 php8.4-gd php8.4-imagick \
        php8.4-curl php8.4-imap php8.4-mysql php8.4-mbstring php8.4-xml \
        php8.4-zip php8.4-bcmath php8.4-soap php8.4-intl php8.4-readline \
        php8.4-ldap php8.4-mongodb php8.4-msgpack php8.4-igbinary php8.4-redis \
        php8.4-swoole php8.4-memcached php8.4-pcov php8.4-xdebug && \
    curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer && \
    apt-get install -y nodejs && \
    npm install -g npm && \
    apt-get install -y postgresql-client-$POSTGRES_VERSION && \
    npx playwright install --with-deps && \
    apt-get -y autoremove && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    setcap "cap_net_bind_service=+ep" /usr/bin/php8.4 && \
    groupadd --force -g 1000 laravel && \
    useradd -ms /bin/bash --no-user-group -g 1000 -u 1337 laravel

COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php/8.4/cli/conf.d/99-laravel.ini

RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000

ENTRYPOINT ["start-container"]
