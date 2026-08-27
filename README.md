Touche pas au klaxon

Application web interne de covoiturage inter-sites développée en PHP avec une architecture MVC et une base de données MySQL/MariaDB.

L'application permet aux employés de consulter les trajets disponibles et de proposer leurs propres trajets.

Fonctionnalités

Visiteur

Consulter les trajets futurs disposant de places disponibles.

Consulter les trajets par date de départ croissante.

Accéder à la page de connexion.

Utilisateur connecté

Consulter les détails d'un trajet.

Voir les coordonnées de la personne proposant le trajet.

Créer un trajet.

Modifier ses propres trajets.

Supprimer ses propres trajets.

Se déconnecter.

Administrateur

Accéder au tableau de bord.

Lister les utilisateurs.

Gérer les agences : création, modification et suppression.

Lister les trajets.

Supprimer un trajet.

Les employés ne sont pas gérés en CRUD dans l'application : leurs données sont fournies par le système RH de l'entreprise.

Technologies

PHP 8.2+

MySQL / MariaDB

Bootstrap 5.3

Sass

Composer

PHPUnit

PHPStan

Apache / XAMPP

Prérequis

Installer :

PHP 8.2 ou supérieur

Apache

MySQL ou MariaDB

Composer

Node.js et npm

Installation

1. Placer le projet

Avec XAMPP, placer le projet dans :

C:\xampp\htdocs\application_MVC_PHP

2. Installer les dépendances

Dans le dossier du projet :

composer install
npm install

3. Créer la base de données

Exécuter :

database/schema.sql
database/seed.sql

Le premier script crée les tables et le second insère le jeu d'essai.

4. Configurer la connexion à la base

Renseigner le fichier .env :

DB_HOST=127.0.0.1
DB_NAME=covoiturage_db
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4

Adapter les valeurs si nécessaire selon la configuration locale.

5. Compiler le Sass

npx sass assets/scss/custom.scss public/css/style.css

Lancement

Démarrer Apache et MySQL depuis XAMPP, puis ouvrir :

http://localhost/application_MVC_PHP/

Comptes de test

Les comptes sont créés par database/seed.sql.

6. Tests

PHPUnit

vendor/bin/phpunit

Les tests couvrent notamment les opérations d'écriture en base concernant les agences et les trajets.

PHPStan

vendor/bin/phpstan analyse

La configuration se trouve dans phpstan.neon.

7. Base de données

Le projet utilise trois tables principales :

USER
AGENCY
RIDE

La table ride référence :

l'agence de départ ;

l'agence d'arrivée ;

l'utilisateur ayant proposé le trajet.

Des contrôles de cohérence sont également présents pour les agences, les dates et le nombre de places.

Le MCD est fourni dans MCD.png et le MLD dans MLD.pdf.

8. Contrôle des accès

L'application distingue :

les visiteurs ;

les utilisateurs connectés ;

les administrateurs.

Les accès protégés sont contrôlés par les middlewares d'authentification et d'administration.

Un utilisateur ne peut modifier ou supprimer que ses propres trajets.

9. Dépôt GitHub

À compléter avec l'adresse du dépôt GitHub :

https://github.com/Tvairet/application_MVC_PHP