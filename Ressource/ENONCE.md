1. Objectif général

Tu dois :

    Créer un script Web (par exemple un simple index.php) qui requête une base de données (c'est-à-dire, afficher des données stockées dans une table).

    Héberger ce script dans un environnement conteneurisé avec Docker.

    Gérer deux environnements (développement et production) qui doivent fonctionner simultanément sur ta machine.

2. Les deux environnements Docker

Tu dois créer deux environnements distincts en utilisant Docker Compose pour gérer plusieurs conteneurs.
A. Environnement de développement

Cet environnement est conçu pour le travail en cours de développement. Il contient :

    Un conteneur pour le serveur web (Apache avec PHP par exemple).

    Un conteneur pour la base de données (MySQL, MariaDB ou PostgreSQL).

    Les fichiers web et de la base de données doivent être accessibles et modifiables directement depuis ta machine.

👉 Ici, le code source du projet est monté dans le conteneur depuis ton système local pour que tu puisses travailler facilement dessus.
B. Environnement de production

Cet environnement est destiné à simuler un déploiement final en ligne. Il contient :

    Un conteneur pour la base de données (comme dans l'environnement de développement).

    Un conteneur pour le serveur web avec le code intégré dans l'image Docker (contrairement à l'environnement de dev où il est monté depuis ton PC).

    Seuls les fichiers de la base de données restent accessibles, pas le code source du site.

👉 Cela signifie que pour mettre à jour le code en production, tu dois reconstruire l’image Docker et la redéployer.
3. Workflow Git (GitFlow)

Le projet doit être géré avec GitFlow, ce qui implique :

    Créer un repository Git privé.

    Utiliser des branches :

        dev : pour le développement en cours.

        master ou main : pour la version stable (celle qui est utilisée en production).

    Utiliser les Merge Requests (ou Pull Requests) pour fusionner les modifications de dev vers master.

4. Automatisation avec des scripts .sh

Tu dois écrire des scripts shell (.sh) pour automatiser le lancement des environnements :

    Un script pour démarrer l’environnement de développement.

    Un script pour démarrer l’environnement de production.

    Ces scripts doivent récupérer le code depuis Git, lancer les conteneurs Docker, etc.

5. Déroulement du projet

Le projet doit être réalisé en deux jours :

    Jour 1 : Création des environnements Docker et du script web qui interagit avec la base de données.

    Jour 2 : Test de mise à jour d'une nouvelle fonctionnalité, démonstration du passage de la nouvelle version de l’environnement de développement à la production.

6. Présentation finale (Soutenance)

    Montrer comment les environnements sont mis en route avec tes scripts .sh.

    Faire une démonstration en direct d'une modification du code :

        Montrer que l’environnement de développement est à jour.

        Montrer que la production est encore sur l’ancienne version.

        Mettre à jour l’environnement de production.

7. Conseils pratiques

✅ Utiliser Docker Compose pour simplifier la gestion des conteneurs.
✅ Nommer tes conteneurs pour qu’ils puissent se reconnaître et communiquer entre eux (important pour la connexion à la base de données).
✅ Éviter de commit tout d’un coup ! Il faut montrer l’historique des changements via Git.
✅ Tester avant la soutenance ! Vérifie bien que ton script .sh fonctionne et que les mises à jour se propagent bien de dev vers prod.
Conclusion

Ce projet est un exercice pratique sur Docker et Git. Il te permet de comprendre comment séparer un environnement de développement et un environnement de production tout en automatisant leur gestion avec des scripts et GitFlow. 🚀

Si tu veux, je peux t'aider à décomposer les étapes en tâches précises ou à écrire les fichiers Docker et les scripts .sh. 😊