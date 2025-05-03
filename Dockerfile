FROM php:8.2-apache

# 必要な拡張をインストール
RUN docker-php-ext-install pdo pdo_mysql

