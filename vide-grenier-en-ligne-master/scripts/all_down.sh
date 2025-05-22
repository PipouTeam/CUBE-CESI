#!/bin/bash

echo "Stopping all environments..."
docker compose -f docker/docker-compose.dev.yml down
docker compose -f docker/docker-compose.stage.yml down
docker compose -f docker/docker-compose.prod.yml down

echo "All environments stopped successfully"