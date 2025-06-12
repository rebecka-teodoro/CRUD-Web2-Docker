# Dockerfile

# Usa a imagem base do PHP com Apache
FROM php:8.2-apache

# Define o diretório de trabalho dentro do container
WORKDIR /var/www/html

# Instala as extensões PHP necessárias (pdo_mysql para conexão com MySQL)
# e outras dependências comuns para desenvolvimento web
RUN apt-get update && \
    apt-get install -y \
    libpq-dev \
    libzip-dev \
    git \
    unzip \
    && \
    docker-php-ext-install pdo_mysql zip

# Habilita o módulo rewrite do Apache (útil para URLs amigáveis)
RUN a2enmod rewrite

# Copia o conteúdo do diretório 'app' local para o diretório de trabalho do container
COPY app/ .

# Expõe a porta 80, que é a porta padrão do Apache
EXPOSE 80

# O comando padrão para iniciar o Apache já está na imagem base

