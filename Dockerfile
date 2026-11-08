FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql \
    && apt-get update \
    && apt-get install -y --no-install-recommends msmtp-mta \
    && rm -rf /var/lib/apt/lists/*

COPY msmtprc /etc/msmtprc
RUN chmod 644 /etc/msmtprc \
    && printf 'sendmail_path = "/usr/sbin/sendmail -t -i"\n' > /usr/local/etc/php/conf.d/mail.ini

WORKDIR /var/www/html
COPY . /var/www/html/
EXPOSE 80