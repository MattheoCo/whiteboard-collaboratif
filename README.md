Migration vers Symfony — guide rapide

Objectif

Migrer le site actuel (pages statiques `index.html` et `board.html`) vers un squelette Symfony minimal, en conservant les assets et le JS existant.

Prérequis

- PHP 8.1+ installé
- Composer installé
- (Optionnel) Symfony CLI pour servir localement

Étapes recommandées

1) Créer un nouveau projet Symfony (si vous préférez laisser le dépôt tel quel, exécutez ces commandes depuis le dossier parent):

   - Avec Composer :

     composer create-project symfony/skeleton my_project

   - Ou avec la CLI Symfony :

     symfony new my_project --webapp

2) Copier les dossiers `templates/` et `src/` générés ici dans `my_project/` (ou déplacer ce workspace dans le dossier du projet Symfony).

3) Vérifier que `src/Controller/BoardController.php` existe et que les routes `/` et `/board` renvoient aux templates `index.html.twig` et `board.html.twig`.

4) Installer les dépendances utiles (Twig) si nécessaire:

   composer require twig

5) Placer les assets (CSS, JS, images) dans `public/` ou gérer via Webpack Encore:

   - Pour un début simple, laisser les liens CDN tels quels et copier les fichiers locaux (s'il y en a) dans `public/`.
 
   Actions automatisées dans ce workspace

   - J'ai initialisé les dépendances Symfony via Composer (framework, Twig, maker, runtime, ORM, yaml).
   - J'ai ajouté des entités de base: `Image`, `StickyNote`, `MapMarker` dans `src/Entity/` et leurs repositories.

   Commandes utiles après installation

   1) Créer la base de données (configurez d'abord `DATABASE_URL` dans votre `.env`):

   ```powershell
   php bin/console doctrine:database:create
   php bin/console make:migration
   php bin/console doctrine:migrations:migrate
   ```

   2) Lancer le serveur de développement:

   ```powershell
   symfony serve
   # ou
   php -S 127.0.0.1:8000 -t public
   ```

   3) Générer des contrôleurs ou entités supplémentaires avec Maker:

   ```powershell
   php bin/console make:controller MyController
   php bin/console make:entity MyEntity
   ```

6) Lancer le serveur de développement:

   symfony serve

   ou

   php -S 127.0.0.1:8000 -t public

7) Ouvrir `http://127.0.0.1:8000/` et `http://127.0.0.1:8000/board`.

Notes et points à vérifier

- Les clés Firebase sont incluses dans les templates actuelles. Considérez l'utilisation de variables d'environnement et l'injection via le contrôleur (sécurité).
- Le template `index.html.twig` contient `{{ firebase_config|raw }}` comme placeholder — il faudra fournir la variable depuis le contrôleur ou config.
- Pour une intégration plus propre des assets, pensez à ajouter Webpack Encore et déplacer les scripts CSS/JS dans `assets/`.

Prochaines actions possibles

- Initialiser un projet Symfony complet dans ce workspace (je peux lancer Composer ici si vous voulez).
- Déplacer les JavaScript/CSS locaux dans `public/` et corriger les chemins.
- Rendre la page d'index utilisable avec une variable twig pour la config Firebase (sécurisé via .env).

Dites-moi quelle option vous préférez: je peux initier le projet Symfony ici (exécuter Composer) ou vous fournir les instructions pas-à-pas pour le faire localement.

---

Déploiement (checklist)

- Configurer les variables d'environnement en production (`.env.prod` ou variables d'environnement du service): `APP_ENV=prod`, `APP_SECRET`, `DATABASE_URL`, `MAILER_DSN`.
- Générer/commiter les migrations Doctrine et les exécuter sur l'environnement de production:

```
php bin/console make:migration
php bin/console doctrine:migrations:migrate --no-interaction --env=prod
```

- Build assets (si Webpack Encore utilisé) et déployer `public/` + `var/` fichiers nécessaires.
- Fixer permissions sur `var/` et `vendor/` si nécessaire.
- Utiliser un process manager (supervisord/systemd) ou Docker + orchestrateur pour exécuter PHP-FPM + nginx.

Exemple minimal Dockerfile (PHP-FPM + composer):

```
FROM php:8.3-fpm
WORKDIR /srv/app
RUN apt-get update && apt-get install -y libzip-dev unzip git
RUN docker-php-ext-install pdo pdo_sqlite
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . /srv/app
RUN composer install --no-dev --optimize-autoloader
RUN php bin/console doctrine:migrations:migrate --no-interaction --env=prod || true
CMD ["php-fpm"]
```

Exemple `docker-compose.yml` minimal (PHP-FPM + nginx + optional MySQL):

```
version: '3.8'
services:
   php:
      build: .
      volumes:
         - .:/srv/app
      environment:
         - APP_ENV=prod
      ports:
         - "9000:9000"
   web:
      image: nginx:alpine
      volumes:
         - .:/srv/app:ro
         - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf:ro
      ports:
         - "8080:80"
```

Checklist rapide avant production

- Remplacer `APP_SECRET` par une valeur aléatoire sécurisée.
- Activer HTTPS (nginx reverse proxy or managed platform).
- Activer CSRF protection sur les formulaires (security.yaml) et vérifier les politiques SameSite/secure des cookies.
- Mettre en place des sauvegardes pour la base de données.

