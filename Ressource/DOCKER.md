Pourquoi utiliser Docker ?

Docker permet de : ✔️ Standardiser les environnements (plus de "ça marche sur ma machine mais pas sur la tienne").
✔️ Isoler les applications et leurs dépendances.
✔️ Simplifier le déploiement en encapsulant tout dans un conteneur unique.
✔️ Gagner en légèreté et en rapidité par rapport aux machines virtuelles.
🔹 Différence entre Docker et une Machine Virtuelle
🔹 Docker (Conteneurs)	🔹 Machines Virtuelles (VMs)
Partage le même noyau du système d’exploitation	Chaque VM a son propre OS
Léger et rapide (quelques Mo)	Plus lourd (plusieurs Go)
Démarrage instantané	Démarrage plus lent
Isolé mais partage certaines ressources	Complètement indépendant

En résumé, Docker est plus rapide et plus efficace car il n’a pas besoin d’un OS entier pour chaque application.
🔹 Les concepts clés de Docker
1️⃣ Conteneur

Un conteneur est une instance en cours d'exécution d’une application avec tout ce dont elle a besoin (code, dépendances, configurations).

👉 Exemple : Un conteneur Apache avec PHP et MySQL.
2️⃣ Image

Une image Docker est un modèle qui contient tout le nécessaire pour exécuter une application.
Un conteneur est une instance d'une image.

👉 Exemple : Une image PHP avec Apache peut servir à créer plusieurs conteneurs.
3️⃣ Dockerfile

Un Dockerfile est un script qui définit comment construire une image.

Exemple simple d'un Dockerfile pour une application PHP :

FROM php:8.0-apache
COPY . /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]

🛠️ Ce fichier :

    Prend PHP et Apache comme base.

    Copie le code source dans le conteneur.

    Ouvre le port 80 pour y accéder.

4️⃣ Docker Compose

C’est un outil qui permet de gérer plusieurs conteneurs avec un seul fichier de configuration (docker-compose.yml).

Exemple de docker-compose.yml pour un site PHP avec MySQL :

    version: '3'

    services:
    web:
        image: php:8.0-apache
        ports:
        - "8080:80"
        volumes:
        - ./www:/var/www/html
        depends_on:
        - db

    db:
        image: mysql:5.7
        environment:
        MYSQL_ROOT_PASSWORD: root
        MYSQL_DATABASE: mydb

👉 Ici, on a :

    Un conteneur web qui exécute PHP et Apache.

    Un conteneur db avec MySQL.

    Un volume pour synchroniser le code entre ta machine et le conteneur.

🔹 Commandes Docker essentielles
Commande	Description
docker build -t mon_image .	Crée une image Docker
docker run -d -p 8080:80 mon_image	Lance un conteneur basé sur une image
docker ps	Liste les conteneurs en cours d’exécution
docker stop <id_contener>	Arrête un conteneur
docker-compose up -d	Démarre plusieurs conteneurs (avec docker-compose.yml)
docker-compose down	Stoppe et supprime les conteneurs
🔹 Conclusion

🚀 Docker est une technologie clé pour déployer facilement des applications sans se soucier des différences entre machines.
💡 Dans ton projet, tu vas l’utiliser pour héberger ton site web et ta base de données dans des conteneurs distincts et gérer les environnements dev/prod avec Docker Compose.

Si tu veux, je peux t’aider à écrire un Dockerfile ou un docker-compose.yml pour ton projet ! 😊