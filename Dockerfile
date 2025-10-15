# Usando a imagem oficial do PHP 8.3 com Apache
FROM php:8.3-apache

# Habilitando o mod rewrite do Apache
RUN a2enmod rewrite

# Instalando extensões do PHP necessárias (PDO, PDO_MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Copiando os arquivos da aplicação para o diretório raiz do Apache
COPY public_html /var/www/html/

# Definindo as permissões corretas para o Apache
RUN chown -R www-data:www-data /var/www/html

# Expondo a porta padrão do Apache (80)
EXPOSE 80

# Iniciando o Apache
CMD ["apache2-foreground"]
