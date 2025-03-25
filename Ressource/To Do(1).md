### **🎯 Objectif du projet**

Créer une **petite application web** (un simple `index.php` par exemple) qui **requête une base de données** (c’est-à-dire affiche le contenu d’une table).  
 Cette application doit être **conteneurisée avec Docker**, et fonctionner dans **deux environnements distincts** :

---

### **🧪 1\. Environnement de développement**

Tu dois créer **2 conteneurs Docker** :

* Un pour le **serveur Web** (Apache/Nginx \+ PHP),

* Un pour la **base de données** (MySQL ou MariaDB, par exemple).

🔁 **Les fichiers doivent être accessibles** :

* Le fichier PHP doit rester modifiable **dans ton système de fichiers** (ex. : monté en volume),

* La base de données aussi doit être **persistée sur ton disque** (montée en volume aussi).

  ---

  ### **🚀 2\. Environnement de production**

Encore avec Docker, mais cette fois **avec 3 conteneurs** :

* Serveur Web,

* Base de données,

* (Et potentiellement un autre conteneur pour séparer un service, ou monitoring, selon les besoins... Mais à lire, ça semble rester à 2 conteneurs au final.)

🔐 **Différence principale** avec l’environnement de dev :

* Le **code PHP ne doit pas être visible en dehors du conteneur** : tu dois **créer une image Docker personnalisée** contenant le code PHP directement à l’intérieur du conteneur web.

* La base de données reste persistée, mais dans un **répertoire différent** de celui de l’environnement de développement.

  ---

  ### **✅ Checklist des tâches à faire**

| Tâche | Explication |
| ----- | ----- |
| ✅ Créer un repository | Un dépôt Git pour stocker ton code. |
| ✅ Travailler avec GitFlow | Une méthode de gestion de branches (avec `main`, `dev`, `feature/...`). |
| ✅ Environnement Docker (dev) | Créer un `docker-compose` avec serveur web \+ BDD. Fichiers accessibles. |
| ✅ Merge Request | Lorsque le travail en dev est fini, tu fais une **pull/merge request** de la branche `dev` vers `main`. |
| ✅ Créer une image Docker du serveur web | L’image contiendra directement le code PHP (pas besoin de montage de volume). |
| ✅ Environnement Docker (prod) | Nouveau `docker-compose`, même principe, mais avec image personnalisée \+ autre volume BDD. |
| ✅ Les 2 environnements doivent coexister | Tu dois pouvoir lancer **les deux en même temps** sans conflit. |
| ✅ Scripts de lancement | Pour chaque environnement, un **fichier `.sh`** qui build et lance tout automatiquement. |

  ---

  ### **💡 Exemple concret :**

* `environnement-dev/`

  * `docker-compose.yml` → avec volumes montés pour code et BDD

  * `index.php` → ton fichier Web

  * `init-db.sql` → script pour créer la table test

  * `start.sh` → lance les conteneurs dev

* `environnement-prod/`

  * `docker-compose.yml` → base \+ image personnalisée web

  * `Dockerfile` → pour construire l’image avec le `index.php` embarqué

  * `start.sh` → lance les conteneurs prod

**💡 Les points évalués**

**Git** : il doit y avoir un système de gestion des issues avec répartition des tâches

**Code** : Le code est débuggé

**Test** : Tests unitaires à faire, ils s'exécutent correctement

**Documentation** : Il faut faire une documentation technique

**Docker** : 

- 3 environnements \-\> test, préprod, prod  
- création d’un script par environnement (3 scripts) qui permet de “monter” chacun d’entre eux (il faut pouvoir prouver qu’on peut supprimer un env et le recréer avec le script)  
- créer une nouvelle fonctionnalité et montrer son fonctionnement dans les différents environnements

