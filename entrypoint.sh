cp -r /var/data/certbot-conf /var/data/certbot-conf-www
chown -R www-data:www-data /var/data/certbot-conf-www
exec docker-php-entrypoint "$@"