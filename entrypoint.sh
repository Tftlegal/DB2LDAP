#!/bin/bash
cp -r /var/data/certbot-conf /var/data/certbot-conf-www
chown -R www-data:www-data /var/data/certbot-conf-www
/usr/local/bin/docker-php-entrypoint apache2-foreground