#!/bin/bash

echo "Stopping all environments..."

docker compose -f docker/docker-compose.dev.yml --project-name dev down
docker stack rm vide-grenier
docker stack rm vide-grenier-stage


echo "Start all environments..."

docker stack deploy -c docker/docker-compose.prod.yml vide-grenier
docker stack deploy -c docker/docker-compose.stage.yml vide-grenier-stage
docker compose -f docker/docker-compose.dev.yml --project-name dev up --build -d 


echo "All environments run successfully"