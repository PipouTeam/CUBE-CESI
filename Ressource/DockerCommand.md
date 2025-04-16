
# Commande Docker

## Commande Basique

Liste les contenaire:

    sudo docker ps

Liste les image:

    sudo docker image ls 

Pull une image (exemple avec apache):

    sudo docker pull httpd

Supprimer une image:

    sudo docker image rm 83d938198316(id image)

Executer une image (telecharger dans le hub si image non telecharger)

    sudo docker run httpd 
    sudo docker run -p :8080:80 httpd (envoie sur le port 8080 a la place du 80)
    sudo docker run --name apache1 -d -p :8080:80 httpd 

Arreter une image

    sudo docker stop "name"/"id"

Effacer des images/container dans le caches

    sudo docker rm "name"/"id" 



## Docker Compose

Il faut creer un fichier docker-compose.yml comme clui ci par exemple : 

    version: '3.8'

    services:
    php:
        image: php:8.2-apache
        container_name: php82
        ports:
        - 8000:80
        volumes:
        - ./php:/var/www/html

Dans l'endroit ou il y a le fichier docker-compose, lance le fichier docker-compose install et lance les images :

    sudo docker-compose up 

Rajouter build permet de build le DockerFile

    sudo docker-compose up build 

Arrete le docker-compose :

    sudo docker-compose down


Rajouter build permet de build via le docker-compose.dev

    sudo docker compose -f docker-compose.dev.yml up --build

Arrete le docker-compose  via le docker-compose.dev:

    udo docker compose -f docker-compose.dev.yml down

Execute un bash sur un contenaire en route  :

    sudo docker exec -it contenaire-id bash