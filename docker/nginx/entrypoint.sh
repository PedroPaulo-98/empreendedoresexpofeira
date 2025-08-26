#!/bin/sh

# Substituir variáveis de ambiente nos arquivos de configuração
envsubst '${SERVER_NAME} ${SSL_CERT} ${SSL_CERT_KEY}' < /etc/nginx/conf.d/laravel.conf.template > /etc/nginx/conf.d/default.conf

echo "Iniciando Nginx com as seguintes configurações:"
echo "Server Name: ${SERVER_NAME}"
echo "SSL Certificate: ${SSL_CERT}"
echo "SSL Certificate Key: ${SSL_CERT_KEY}"

# Iniciar nginx
exec nginx -g 'daemon off;'
