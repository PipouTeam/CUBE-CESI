# Présentation du Projet

Pour ce projet, nous vous livrons une application. Votre travail sera de :

- Mettre en place l’environnement de développement
- Corriger l’application
- Mettre en place l’environnement de pré-production
- Mettre en place l’environnement de production

---

## Mail reçu par votre client

**Expéditeur**: DERRY John <john.derry@videgrenierenligne.fr>  
**Date**: 20 septembre 2020 à 17:42:03 UTC+2  
**Destinataire**: Agence Tekos <dev@tekos.com>  
**Objet**: Reprise du site internet Vide Grenier en ligne

---

Bonjour l'équipe,

Voici ci-joint les éléments que m’a transmis mon ancien prestataire (en cessation d’activité).

Comme je vous l’ai dit précédemment, nous permettons avec Vide Grenier En Ligne à chaque résident en France métropolitaine de donner sans aucune contrepartie des objets entreposés dans leur grenier ou garage. Jeu de carte, livre, puzzle etc. Le tout gratuitement !

Le site est actuellement en ligne mais nos utilisateurs nous remontent beaucoup d’erreurs…

En voici quelques-unes mais je pense que vous trouverez le reste tout seul…

- Un message d’erreur s’affiche quand on ne poste pas une photo dans une annonce (les champs devaient être tous requis)  
- Quand un utilisateur s’enregistre il n’est pas connecté tout de suite après  
- J’ai l’impression que le bouton “se souvenir de moi” ne fonctionne pas, pouvez-vous vérifier ?  
- Il était prévu que nous ayons un formulaire de contact sur la page du produit mais aujourd’hui c’est la boite mail qui s’ouvre

Également, il était prévu que j’aie sur mon compte VideGrenierEnLigne un espace où je peux voir les statistiques du site, mais aujourd’hui je n’ai rien quand je me connecte avec mon adresse mail.

Je passe également au mode Progressive Web App qui était initialement prévu… je n’ai même pas de favicon.

Merci de revenir vers moi avec une estimation du temps que va prendre le debug de ces fonctionnalités. Je présente le site devant des investisseurs en fin de semaine, j’espère que vous pourrez régler tout ça !

Cordialement,  
**John DERRY**  
VideGrenierEnLigne

---

## Tâches à réaliser

Pour ce projet, vous allez devoir :

- Créer un repository pour y mettre le code de l’application.
- Mettre en place (utiliser) un système de gestion des issues et répartir ces tâches entre les membres de votre équipe.
- Travailler en mode « GitFlow ».
- Concevoir un environnement de développement basé sur Docker (serveur Web + Base de données).
- Apporter les corrections au site Internet.
- Créer les tests unitaires de l’application.
- Utiliser le merge request pour pousser le code de la branche « stage » (ou recette) vers la branche « master » (ou main).
- Concevoir un environnement de pré-production basé sur Docker en respectant l’architecture suivante :
  - Un Container pour la base de données en persistance.
  - Un Container pour le service Web avec récupération du dépôt GIT branche « Stage » (ou recette).
- Concevoir un environnement de production basé sur Docker en respectant l’architecture suivante :
  - Un Container pour la base de données en persistance.
  - Un Container pour le service Web avec le code de la branche « master » (ou main) déjà présent dans l’image Docker.
- Utiliser un système de génération documentaire pour le code API.

### [GIT] Important : 
Dès la remise du présent document, vous devrez créer votre organisation dans laquelle se trouveront les repositories nécessaires à ce projet. Ces repositories doivent être privés et vous inviterez votre pilote. Ces repositories doivent être vivants (il sera interdit de faire un first commit qui comprend tout le code d’un coup sans traçabilité).

---

## Travail demandé / Livrable final

### Oral

La présentation de votre projet durera 20 minutes et devra comprendre :

- La démonstration des tests unitaires.
- La démonstration de la correction des bugs du site Internet.
- Le développement en live (prévoyez le code à l’avance) d’une modification de code en respectant le GitFlow :
  - Prouver que votre environnement de développement est à jour, mais que l’environnement de préproduction et de production est encore sur l’ancienne version.
  - Mettre à jour l’environnement de préproduction en regard de l’environnement de développement. Démontrer que l’environnement de production est toujours sur l’ancienne version.
  - Mettre à jour l’environnement de production en regard de l’environnement de préproduction.

---

## Déroulement et Livrables intermédiaires

Pour ce projet, vous serez en équipe de 3 développeurs.  
À titre d’information, voici une idée de répartition de vos journées :

| Jour  | Étapes | Livrables attendus |
|-------|--------|--------------------|
| **J1** | Matin : appropriation du CUBES, échange entre apprenants sur les idées et hypothèses, répartition en groupe <br> Après-midi : Commencer la correction de l’application | Équipes composées <br> Environnement de développement Docker <br> Création des issues et affectation des tâches aux membres de l’équipe |
| **J2** | Développer les corrections de l’application <br> Développer les tests unitaires | État d’avancement des issues |
| **J3** | Terminer le développement des corrections de l’application <br> Terminer le développement des tests unitaires | État d’avancement des issues |
| **J4 & J5** | Concevoir les environnements de préproduction et de production <br> Développer une fonctionnalité quelconque et la déployer sur les différents environnements | |
| **J6** | Préparation de la présentation orale | Présentation/Soutenance Orale |

---

## Conseils

- Nous vous conseillons d’utiliser **GitLab** qui intègre un système pratique de gestion des issues avec affectation des tâches aux membres de l’équipe. Par ailleurs, dans la suite de votre formation, vous pourrez utiliser le système CI/CD fourni par GitLab.
- Pour la création de tests unitaires, regardez du côté de **PHP-Unit**.
- Pour vos environnements Docker, nous vous suggérons d’utiliser **Docker-Compose**.
  - Pensez à nommer vos containers ainsi il sera possible à vos containers de dialoguer entre eux avec le nom « machine » (utile pour la chaîne de connexion à la base de données etc.).
  - Vous aurez à faire un script `.sh` qui monte votre environnement (récupération du code sur git, instanciation des containers, etc.). Profitez-en pour réaliser un script pour chaque environnement (développement, préproduction et production).
- Pour la génération de documentation API, regardez du côté de **Swagger**.
