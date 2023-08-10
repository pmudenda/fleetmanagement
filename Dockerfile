FROM php:8.1-cli

RUN apt-get update && \
    apt-get install -y libaio1 unzip && \
    mkdir /opt/oracle && \
    cd /opt/oracle && \
    curl -o instantclient-basic-linux.x64-19.8.0.0.0dbru.zip https://download.oracle.com/otn_software/linux/instantclient/19800/instantclient-basic-linux.x64-19.8.0.0.0dbru.zip && \
    curl -o instantclient-sdk-linux.x64-19.8.0.0.0dbru.zip https://download.oracle.com/otn_software/linux/instantclient/19800/instantclient-sdk-linux.x64-19.8.0.0.0dbru.zip && \
    unzip instantclient-basic-linux.x64-19.8.0.0.0dbru.zip && \
    unzip instantclient-sdk-linux.x64-19.8.0.0.0dbru.zip && \
    rm -f instantclient-basic-linux.x64-19.8.0.0.0dbru.zip instantclient-sdk-linux.x64-19.8.0.0.0dbru.zip && \
    echo /opt/oracle/instantclient_19_8 > /etc/ld.so.conf.d/oracle-instantclient.conf && \
    ldconfig

RUN pecl install oci8-2.2.0 && \
    docker-php-ext-enable oci8

WORKDIR /var/www/html
