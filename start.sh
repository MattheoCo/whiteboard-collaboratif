#!/bin/bash

# Créer le cache de production
php bin/console cache:clear --env=prod --no-debug

# Créer la base de données si elle n'existe pas
php bin/console doctrine:database:create --if-not-exists --env=prod

# Exécuter les migrations
php bin/console doctrine:migrations:migrate --no-interaction --env=prod

# Démarrer le serveur
php -S 0.0.0.0:$PORT -t public
