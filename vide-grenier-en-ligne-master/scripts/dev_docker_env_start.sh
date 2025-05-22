#!/bin/bash

echo "Stopping all environments..."
docker compose -f docker/docker-compose.dev.yml --project-name dev down
docker compose -f docker/docker-compose.stage.yml --project-name stage down
docker compose -f docker/docker-compose.prod.yml --project-name prod down

echo "Starting development environment..."
docker compose -f docker/docker-compose.dev.yml --project-name dev up --build -d 
