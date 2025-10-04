FROM composer:2 as composer
FROM php:8.4-apache

# Install composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Install OS packages and cleanup
RUN apt-get update && apt-get install -y git zip
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP application
COPY . /app/
RUN  composer install -d /app/
RUN ln -s /app/public/api /var/www/api

# Setup SimpleSAMLphp backend
RUN cp /app/src/config/* /app/vendor/simplesamlphp/simplesamlphp/config/ && \
    cp /app/src/metadata/* /app/vendor/simplesamlphp/simplesamlphp/metadata/ && \
    ln -s /app/vendor/simplesamlphp/simplesamlphp/public /var/www/sso && \
    mkdir -p /var/cache/simplesamlphp && \
    chown -R www-data:www-data /var/cache/simplesamlphp && \
    chmod 700 /var/cache/simplesamlphp && \
    mkdir -p /app/logs && \
    chown -R www-data:www-data /app/logs && \
    chmod 700 /app/logs

# Setup Apache backend
RUN cp /app/.docker/apache/ports.conf /etc/apache2/ports.conf && \
    cp /app/.docker/apache/simplesamlphp.conf /etc/apache2/sites-available/simplesamlphp.conf && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    a2enmod ssl && \
    a2dissite 000-default.conf default-ssl.conf && \
    a2ensite simplesamlphp.conf

# Set work dir
WORKDIR /app

# General setup
EXPOSE 8080 8443

# Call entrypoint
ENTRYPOINT ["sh", "/app/.docker/entrypoint.sh"]
