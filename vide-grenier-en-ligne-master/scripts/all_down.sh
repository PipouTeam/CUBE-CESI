#!/bin/bash

echo "Stopping all environments..."

docker compose -f docker/docker-compose.dev.yml --project-name dev down
docker stack rm vide-grenier
docker stack rm vide-grenier-stage

echo "All environments stopped successfully"