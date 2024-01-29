FROM ubuntu/apache2

# Install dependencies
RUN apt-get update -y && apt-get upgrade -y

# Install tzdata and set timezone
RUN apt-get install -y tzdata
ENV TZ="America/Sao_Paulo"
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install required packages (combine for efficiency)
RUN apt-get install -y \
    git zlib1g wget


RUN apt-get install -y \
    php8.2 libapache2-mod-php php-dev build-essential unzip \
    php8.2-curl php8.2-gd php8.2-gmp php8.2-mysql php8.2-mbstring php8.2-zip tidy \
    vim nano gnupg supervisor

# Install Node.js and NPM
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -
RUN apt-get install -y nodejs

RUN apt-get install -y unzip


# # Crie um diretório para os arquivos Oracle Instant Client
# RUN mkdir /opt/oracle
# WORKDIR /opt/oracle

# # Download Oracle Instant Client files
# RUN wget --no-check-certificate --no-cookies --header "Cookie: oraclelicense=accept-securebackup-cookie" \
#     https://download.oracle.com/otn_software/linux/instantclient/2113000/instantclient-basic-linux.x64-21.13.0.0.0dbru.zip \
#     https://download.oracle.com/otn_software/linux/instantclient/2113000/instantclient-sdk-linux.x64-21.13.0.0.0dbru.zip

# # Extract Oracle Instant Client files
# RUN unzip instantclient-basic-linux.x64-21.13.0.0.0dbru.zip \
#     && unzip instantclient-sdk-linux.x64-21.13.0.0.0dbru.zip \
#     && rm instantclient-basic-linux.x64-21.13.0.0.0dbru.zip instantclient-sdk-linux.x64-21.13.0.0.0dbru.zip


# ENV ORACLE_HOME /opt/oracle/instantclient_21_13
# # Install PHP OCI8 extension
# RUN pecl channel-update pecl.php.net \
#     && echo 'instantclient,/opt/oracle/instantclient_21_13/' | pecl install oci8 \
#     && echo "extension=oci8.so" >> /etc/php/8.2/apache2/php.ini

# # Configure environment variables for Oracle Instant Client
# ENV LD_LIBRARY_PATH /opt/oracle/instantclient_21_13:$LD_LIBRARY_PATH


# Install Composer globally
RUN cd ~
RUN wget -O composer-setup.php https://getcomposer.org/installer
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

RUN apt upgrade -y && apt update -y && apt install -y npm
# Install Vue.js CLI globally
RUN npm install -g @vue/cli
RUN npm install -g create-vite
# RUN ./var/www/html/npm install --save-dev vite

#Term aanmaken
RUN echo "export TERM=xterm" >> /root/.bashrc

# Install app
RUN rm -rf /var/www/html/*

ADD src/portal_prime/ /var/www/html/

# Configure apache
RUN rm -f /etc/apache2/sites-available/000-default.conf

ADD ./settings/000-default.conf /etc/apache2/sites-available/

# Instalar dependências do projeto Laravel
WORKDIR /var/www/html
RUN npm install

RUN a2enmod rewrite

RUN a2enmod headers

RUN a2enmod proxy

RUN a2enmod ssl

RUN a2enmod proxy_http

# RUN apt-get install -y nodejs

# RUN apt-get install -y npm

RUN chown -R www-data:www-data /var/www/html/

ENV APACHE_RUN_USER www-data

ENV APACHE_RUN_GROUP www-data

ENV APACHE_LOG_DIR /var/log/apache2

ENV APACHE_LOCK_DIR /var/lock/apache2

ENV APACHE_PID_FILE /var/run/apache2.pid

ARG DEBIAN_FRONTEND=noninteractive

EXPOSE 443 80

COPY run.sh /run.sh

# copy ssl/server.* /etc/apache2/ssl/example/

RUN chmod a+rx /run.sh

CMD ["/bin/bash", "/run.sh"]