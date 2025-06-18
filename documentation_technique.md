# Documentation Technique - Projet Vide Grenier en Ligne

## 1. Introduction

Le projet "Vide Grenier en Ligne" est une application web permettant aux utilisateurs de publier et consulter des annonces de vente d'objets d'occasion, à l'image d'un vide-grenier traditionnel mais en version numérique. L'application est développée en PHP suivant une architecture MVC (Modèle-Vue-Contrôleur).

## 2. Architecture du Projet

### 2.1 Structure des Répertoires

```
CUBE-CESI/
├── .github/workflows/          # Configuration CI/CD
├── Ressource/                  # Ressources diverses
└── vide-grenier-en-ligne-master/
    ├── App/                    # Code application
    │   ├── Config.php          # Configuration de l'application
    │   ├── Controllers/        # Contrôleurs
    │   ├── Models/             # Modèles
    │   ├── Utility/            # Classes utilitaires
    │   └── Views/              # Templates Twig
    ├── Core/                   # Noyau de l'application
    │   ├── Controller.php      # Contrôleur de base
    │   ├── Error.php           # Gestion des erreurs
    │   ├── Model.php           # Modèle de base
    │   ├── Router.php          # Routeur
    │   └── View.php            # Gestionnaire de vues
    ├── docker/                 # Configuration Docker
    │   ├── DockerFile          # Configuration PHP
    │   ├── docker-compose.dev.yml
    │   ├── docker-compose.prod.yml
    │   └── docker-compose.stage.yml
    ├── logs/                   # Logs de l'application
    ├── public/                 # Point d'entrée public
    ├── scripts/                # Scripts utilitaires
    ├── sql/                    # Scripts SQL
    ├── style/                  # Fichiers CSS/SCSS
    ├── tests/                  # Tests unitaires
    └── vendor/                 # Dépendances Composer
```

### 2.2 Architecture MVC

Le projet suit une architecture Modèle-Vue-Contrôleur (MVC) :

- **Modèles** : Gèrent l'accès aux données et la logique métier
- **Vues** : Gèrent l'affichage et l'interface utilisateur (via Twig)
- **Contrôleurs** : Orchestrent les interactions entre les modèles et les vues

## 3. Composants Principaux

### 3.1 Core

#### 3.1.1 Router (Core/Router.php)
- Gère le routage des requêtes HTTP
- Analyse l'URL et détermine le contrôleur et l'action à exécuter
- Supporte les paramètres d'URL et les expressions régulières
- Exemple de route : `$router->add('product/{id:\d+}', ['controller' => 'Product', 'action' => 'show']);`

#### 3.1.2 Controller (Core/Controller.php)
- Classe de base pour tous les contrôleurs
- Gère les paramètres de la requête

#### 3.1.3 Model (Core/Model.php)
- Classe abstraite pour tous les modèles
- Fournit une connexion à la base de données via PDO
- Utilise la configuration de App/Config.php

#### 3.1.4 View (Core/View.php)
- Gère le rendu des vues via Twig
- Permet de passer des variables aux templates

### 3.2 App

#### 3.2.1 Config (App/Config.php)
- Contient la configuration de l'application
- Paramètres de connexion à la base de données
- Options d'affichage des erreurs

#### 3.2.2 Controllers
- **Home** : Gère la page d'accueil
- **Product** : Gère les annonces de produits
- **User** : Gère l'authentification et les profils utilisateurs
- **Api** : Fournit des endpoints API pour les interactions AJAX

#### 3.2.3 Models
- **Articles** : Gestion des annonces
- **Cities** : Gestion des villes et localisation
- **User** : Gestion des utilisateurs

#### 3.2.4 Views
- Templates Twig organisés par contrôleur
- Template de base (base.html) définissant la structure commune

## 4. Base de Données

- Système : MySQL 8.0
- Principales tables :
  - `articles` : Annonces de produits
  - `users` : Utilisateurs du système
  - `cities` : Villes pour la géolocalisation

## 5. Environnement Docker

### 5.1 Services

Le projet utilise Docker pour l'environnement de développement et de production :

- **PHP** : PHP 8.2 avec Apache (port 8001)
- **MySQL** : Base de données (port 3301)
- **phpMyAdmin** : Interface d'administration MySQL (port 8801)

### 5.2 Configuration Docker

#### 5.2.1 DockerFile
```dockerfile
FROM php:8.2-apache
RUN apt-get update && apt-get upgrade -y 
RUN docker-php-ext-install mysqli pdo_mysql && docker-php-ext-enable mysqli pdo_mysql
RUN a2enmod rewrite
EXPOSE 80
```

#### 5.2.2 docker-compose.dev.yml
Configuration pour l'environnement de développement avec :
- Serveur PHP/Apache
- Base de données MySQL
- Interface phpMyAdmin

## 6. Intégration Continue

Le projet utilise GitHub Actions pour l'intégration continue :

- Workflow : `.github/workflows/dev-ci-containers.yml`
- Vérifie le bon fonctionnement des conteneurs Docker
- S'assure que les services MySQL, PHP et phpMyAdmin démarrent correctement

## 7. Fonctionnalités Principales

### 7.1 Gestion des Annonces
- Création, affichage et recherche d'annonces
- Téléchargement d'images pour les annonces
- Filtrage par popularité et date

### 7.2 Géolocalisation
- Recherche d'annonces par proximité géographique
- Utilisation de Leaflet pour l'affichage des cartes
- Clustering de marqueurs pour améliorer la visualisation

### 7.3 Gestion des Utilisateurs
- Inscription et connexion
- Profils utilisateurs
- Gestion des annonces personnelles

## 8. Frontend

- Framework CSS : Bootstrap
- Bibliothèques JavaScript :
  - jQuery
  - Leaflet (cartes interactives)
  - Bootstrap Autocomplete
- Préprocesseur CSS : SASS/SCSS

## 9. Installation et Déploiement

### 9.1 Prérequis
- Docker et Docker Compose
- Composer (pour les dépendances PHP)
- Node.js et npm (pour la compilation SCSS)

### 9.2 Installation en Développement
1. Cloner le dépôt
2. Exécuter `./scripts/dev_docker_env_start.sh` pour démarrer les conteneurs
3. Exécuter `composer install` pour installer les dépendances PHP
4. Exécuter `npm install` pour installer les dépendances Node.js
5. Exécuter `npm run watch` pour compiler les fichiers SCSS

### 9.3 Déploiement en Production
1. Configurer le fichier `docker-compose.prod.yml`
2. Exécuter les scripts de déploiement appropriés

## 10. Tests

Le projet inclut des tests unitaires dans le répertoire `tests/`. Ces tests sont organisés par domaine fonctionnel et utilisent PHPUnit comme framework de test.

### 10.1 Structure des Tests

Les tests sont organisés selon la structure suivante :

```
tests/
├── Article/                    # Tests pour le modèle Article
│   ├── ArticleCreationTest.php # Tests de création d'articles
│   ├── ArticleRetrievalTest.php # Tests de récupération d'articles
│   ├── ArticleStatisticsTest.php # Tests des statistiques d'articles
│   └── README.md               # Documentation des tests d'articles
├── User/                       # Tests pour le modèle User
│   ├── UserCreationTest.php    # Tests de création d'utilisateurs
│   ├── UserAuthenticationTest.php # Tests d'authentification
│   └── README.md               # Documentation des tests utilisateurs
├── Utility/                    # Tests pour les classes utilitaires
│   └── README.md               # Documentation des tests utilitaires
├── Probleme-Test-Vendor.md     # Documentation des problèmes connus
└── tuto_TESTS.md               # Tutoriel d'utilisation des tests
```

### 10.2 Méthodologie de Test

Les tests unitaires suivent une approche d'isolation en utilisant des mocks pour simuler les dépendances externes comme la base de données. Cette approche permet :

- Des tests rapides et fiables
- L'indépendance vis-à-vis d'une base de données réelle
- La possibilité de tester des cas limites et des conditions d'erreur

Exemple de configuration d'un test avec mock de PDO :

```php
protected function setUp(): void {
    $this->mockPDO = $this->createMock(\PDO::class);
    $this->mockStatement = $this->createMock(\PDOStatement::class);
    $this->mockPDO->method('prepare')->willReturn($this->mockStatement);
    Articles::setDBForTests($this->mockPDO);
}
```

### 10.3 Tests des Modèles

#### 10.3.1 Tests du Modèle Article

Les tests du modèle Article sont divisés en trois catégories principales :

1. **ArticleCreationTest** : Tests pour la création et la mise à jour d'articles
   - Création d'articles avec données valides
   - Attachement d'images aux articles
   - Gestion des erreurs (champs manquants, caractères spéciaux)

2. **ArticleRetrievalTest** : Tests pour la récupération d'articles
   - Récupération d'articles par utilisateur
   - Récupération d'articles par ID
   - Filtrage des articles

3. **ArticleStatisticsTest** : Tests pour les statistiques d'articles
   - Incrémentation du compteur de vues

#### 10.3.2 Tests du Modèle User

Les tests du modèle User couvrent :

1. **UserCreationTest** : Tests pour la création d'utilisateurs
   - Création d'utilisateurs avec données valides
   - Validation des données utilisateur (email, nom d'utilisateur)
   - Gestion des erreurs (doublons, données manquantes)

2. **UserAuthenticationTest** : Tests pour l'authentification
   - Connexion utilisateur
   - Vérification des mots de passe
   - Gestion des sessions

### 10.4 Exécution des Tests

Pour exécuter tous les tests du projet :

```bash
./vendor/bin/phpunit tests/
```

Pour exécuter une catégorie spécifique de tests :

```bash
./vendor/bin/phpunit tests/Article/
```

Pour exécuter un fichier de test spécifique :

```bash
./vendor/bin/phpunit tests/Article/ArticleCreationTest.php
```

### 10.5 Couverture de Code

Les tests actuels couvrent les fonctionnalités de base des modèles principaux. Des améliorations sont prévues pour augmenter la couverture de code, notamment :

- Tests des contrôleurs
- Tests d'intégration
- Tests de bout en bout (end-to-end)

## 11. Sécurité

- Utilisation de PDO avec des requêtes préparées pour éviter les injections SQL
- Validation des entrées utilisateur
- Gestion des sessions pour l'authentification

## 12. Conclusion

Le projet "Vide Grenier en Ligne" est une application web PHP moderne suivant l'architecture MVC. Il utilise Docker pour faciliter le déploiement et le développement, et intègre des fonctionnalités de géolocalisation pour améliorer l'expérience utilisateur.
