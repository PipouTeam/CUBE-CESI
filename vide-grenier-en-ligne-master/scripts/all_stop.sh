#!/bin/bash

echo "Stopping all environments..."
docker-compose -f docker/local/docker-compose.dev.yml down
docker-compose -f docker/remote/docker-compose.stage.yml down
docker-compose -f docker/remote/docker-compose.prod.yml down

echo "All environments stopped successfully"