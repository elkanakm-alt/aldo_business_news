FROM php:8.2-apache

# 1. Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# 2. Activer le module de réécriture d'Apache (indispensable pour les routes Laravel)
RUN a2enmod rewrite

# 3. Configurer Apache pour pointer vers le dossier /public de Laravel
# C'est ce qui corrige l'erreur 403 Forbidden
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Copier les fichiers du projet dans le conteneur
COPY . /var/www/html

# 5. Installer Composer et les dépendances PHP
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

# 6. Donner les bons droits aux dossiers de cache et de stockage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Gestion du port pour Render
ENV PORT=80
EXPOSE 80

# 8. Commande de démarrage : ajuste les ports Apache et lance le serveur
CMD sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf && \
    apache2-foreground