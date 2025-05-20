---
author: RaspY
date: 2025-05-20
description: Ce fichier sert à expliciter le fonctionnement de Docker dans le projet CUBE.
---


# Projet CUBE -- Vide grenier
## Environnements
* Local
	* DEV : Permet de tester / visualiser / lancer la fonctionnalité en cours de développement dans un environnement fonctionnel.
* Remote (git)
	* STAGE : Récupération de la branche STAGE pour la tester dans un environnement fonctionnel.
	* PROD : Récupération de la branche PROD pour la tester dans un environnement fonctionnel.

## Docker compose
La structure est la même pour les 3 environnements. Le projet est en cours de développement, la différence entre le local et le remote n'est pas encore bien implémenté.

### Services
* php  
  * description : Fait le backend et une partie du frontend dans le projet
  * image : php:8.2-apache
  * port (sur la machine) : 8000
  * port (dans le docker) : 80
* db 
  * description : La base de données du projet
  * image : mysql:8.0
  * port (sur la machine) : 3307
  * port (dans le docker) : 3306
* phpma 
  * description : Nous permet de vérifier les données et leur manipulation lors du développement. Il serait interessant de retirer ce service pour les environnements de STAGE et PROD
  * image : phpmyadmin/phpmyadmin
  * port (sur la machine) : 8899
  * port (dans le docker) : 80

### Tag
* environment
	* Mise en place des variables d'environnement qui servent au sein du service
* volumes
	* Schéma --> /path/in/the/project:/path/in/the/docker
		* La première partie (avant ':') cible un fichier ou un dossier pour le copier sur le chemin indiqué dans le Docker
		* La seconde partie cible un chemin au sein du Docker

## Note
1. L'objectif des 2 environnements en remote est de pouvoir les automatiser dans une CI/CD. Pour la STAGE, serait dans un premier temps de vérifier le bon build de l'environnement. Pour la PROD, vérifier également le bon build, mais également l'envoie de l'image (fonctionnelle finalement).
2. Des scripts permettent le lancement ainsi que l'arrêt des différents environnements Docker.
	*	all_stop
	*	dev_docker_env_start
	*	stage_docker_env_start
	*	prod_docker_env_start
