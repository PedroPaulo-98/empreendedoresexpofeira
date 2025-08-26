FROM nginx:alpine

RUN mkdir -p /etc/nginx/snippets
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/laravel.conf.template /etc/nginx/conf.d/laravel.conf.template
COPY docker/nginx/snippets/fastcgi-php.conf /etc/nginx/snippets/
COPY docker/nginx/entrypoint.sh /entrypoint.sh
COPY /certs /certs/

RUN chmod +x /entrypoint.sh

EXPOSE 80 443

ENTRYPOINT ["/entrypoint.sh"]