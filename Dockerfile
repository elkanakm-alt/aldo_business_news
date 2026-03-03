FROM php:8.2-apache

# 1. Installer les dépendances système et Node.js
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    unzip \
    curl \
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# 2. Activer le module de réécriture d'Apache
RUN a2enmod rewrite

# 3. Configurer Apache pour pointer vers /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Copier les fichiers du projet
COPY . /var/www/html

# 5. Installer Composer et les dépendances PHP
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

# 6. Compiler les assets (Tailwind/Vite)
RUN cd /var/www/html && npm install && npm run build

# 7. CRÉER LE LIEN SYMBOLIQUE POUR LES IMAGES (Correction ici)
# On force la création du lien pendant le build pour éviter l'erreur de permission 500
RUN cd /var/www/html && php artisan storage:link --force

# 8. Donner les bons droits aux dossiers Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/storage
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Gestion du port pour Render
ENV PORT=80
EXPOSE 80

# 10. Commande de démarrage
CMD sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf && \
    apache2-foreground