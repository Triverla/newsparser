FROM php:7.4-fpm

RUN apt-get update -y
RUN apt-get -y install gcc make autoconf libc-dev pkg-config libzip-dev

RUN apt-get install -y --no-install-recommends \
	git \
	libmemcached-dev \
	libz-dev \
	libpq-dev \
	libssl-dev libssl-doc libsasl2-dev \
	libmcrypt-dev \
	libxml2-dev \
	zlib1g-dev libicu-dev g++ \
	libldap2-dev libbz2-dev \
	curl libcurl4-openssl-dev \
	re2c libpng++-dev \
	libwebp-dev libjpeg-dev libjpeg62-turbo-dev libpng-dev libxpm-dev libvpx-dev libfreetype6-dev \
	libmagick++-dev \
	libmagickwand-dev \
	zlib1g-dev libgd-dev \
	libtidy-dev libxslt1-dev libmagic-dev libexif-dev file \
	sqlite3 libsqlite3-dev libxslt-dev \
	libmhash2 libmhash-dev libc-client-dev libkrb5-dev libssh2-1-dev \
	unzip libpcre3 libpcre3-dev \
	poppler-utils ghostscript libmagickwand-6.q16-dev libsnmp-dev libedit-dev libreadline6-dev libsodium-dev \
	freetds-bin freetds-dev freetds-common libct4 libsybdb5 tdsodbc libreadline-dev librecode-dev libpspell-dev libonig-dev

# fix for docker-php-ext-install pdo_dblib
# https://stackoverflow.com/questions/43617752/docker-php-and-freetds-cannot-find-freetds-in-know-installation-directories
RUN ln -s /usr/lib/x86_64-linux-gnu/libsybdb.so /usr/lib/

# RUN docker-php-ext-configure hash --with-mhash && \
# 	docker-php-ext-install hash
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
	docker-php-ext-install imap iconv

RUN docker-php-ext-install bcmath bz2 calendar ctype curl dba dom
RUN docker-php-ext-install intl json ldap mbstring mysqli
RUN docker-php-ext-install opcache pcntl pspell
RUN docker-php-ext-install pdo pdo_dblib pdo_mysql pdo_pgsql pdo_sqlite pgsql phar posix
RUN docker-php-ext-install session shmop simplexml soap sockets sodium
RUN docker-php-ext-install sysvmsg sysvsem sysvshm
# RUN docker-php-ext-install snmp

# fix for docker-php-ext-install xmlreader
# https://github.com/docker-library/php/issues/373
RUN export CFLAGS="-I/usr/src/php" && docker-php-ext-install xmlreader xmlwriter xml xmlrpc xsl

RUN docker-php-ext-install tidy tokenizer zend_test zip

# already build in... what they say...
# RUN docker-php-ext-install filter reflection spl standard
# RUN docker-php-ext-install pdo_firebird pdo_oci

# https://serverpilot.io/docs/how-to-install-the-php-ssh2-extension
# 	pecl install ssh2-1.1.2 && \
# docker-php-ext-enable ssh2

# install APCu
RUN pecl install apcu && \
	docker-php-ext-enable apcu --ini-name docker-php-ext-10-apcu.ini

# Install libssl
RUN apt-get -y install librabbitmq-dev

# install amqp
RUN pecl install amqp && \
    docker-php-ext-enable amqp

# Install git
RUN apt-get update \
    && apt-get -y install git

RUN apt-get update -y && apt-get install -y apt-transport-https locales gnupg

# set locale to utf-8
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && locale-gen
ENV LANG='en_US.UTF-8' LANGUAGE='en_US:en' LC_ALL='en_US.UTF-8'

#--------------------------------------------------------------------------
# Final Touches
#--------------------------------------------------------------------------

# install required libs for health check
RUN apt-get -y install libfcgi0ldbl nano htop iotop lsof cron mariadb-client redis-tools wget

# install composer
RUN EXPECTED_CHECKSUM="$(wget -q -O - https://composer.github.io/installer.sig)" && \
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
	ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")" && \
	if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then >&2 echo 'ERROR: Invalid installer checksum' && exit 1; fi

RUN php composer-setup.php --quiet && rm composer-setup.php && \
	mv composer.phar /usr/local/sbin/composer && \
	chmod +x /usr/local/sbin/composer

# Health check
RUN echo '#!/bin/bash' > /healthcheck && \
	echo 'env -i SCRIPT_NAME=/health SCRIPT_FILENAME=/health REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1' >> /healthcheck && \
	chmod +x /healthcheck

# Clean up
RUN apt-get remove -y git && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR "/application"