## Contexte

On a une application web qui fonctionne pour le front et le back en php, on a besoin qu'elle soit sur un serveur, avec une connexion a une base de donnée en sql.

L'objectif est d'avoir 3 environnements docker (dev,stage,prod)(System de containeurisatation)

Dans l'environnement de dev il a y a des suites de test a réaliser.

On a donc choisi un serveur apache, une db mysql, et utiliser phpmyadmin.

## Architecture

    utilisateur -> /Front php/ - /Back php/ - /db Mysql/

Env : 

    Dev -> [Front + Back] + [Mysql] + [PhpMy admin]
    Prod -> [Front + Back] + [Mysql]

## Docker

En premier lieu on a pull les images nécessaire : 

    php:8.2-apache
    mysql:8.0
    phpmyadmin

Pour ce faire on a fait : 

    docker pull php:8.2-apache

## Docker compose

Docker compose est un outil installer avec Docker, pour gérer de manière centralisée les déploiements de nombreux conteneurs Docker différents. 

Le docker-compose.yml est un fichier qui comprend les descriptions des images. Celui ci nous permet de creer nos conteneurs.

On a en suite créer le docker compose de la branche dev, pour y configurer les variable d'environnement pour la db et phpmyadmin, les port des differents containers, et y integrer les fichier necessaire:

    mysql -> import.sql
    php -> l'ensemble du projet dans le var/www/html

## Docker file

On a réaliser le dockerfile qui va executer des commandes dans le container php:8.2-apache 
pour faire les mise a jour :

    RUN apt-get update && apt-get upgrade -y 

pour ajouter des extension php pour la connection a la db :

    RUN docker-php-ext-install mysqli pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

pour activer le module apache 

    RUN a2enmod rewrite

Ensuite pour lancer le projet on a qu'a faire :

    docker compose -f docker-compose.dev.yml -build
    
(Le build sert a build le Dockerfile)

On a par la suite creer les differents environnement pour notre phase de stage et de prod en rajoutant dans le dockerfile la recuperation du projet depuis git au niveau de la branch main.

test