#!/bin/bash

echo "Stopping all environments..."
docker-compose -f docker-compose.dev.yml down
docker-compose -f docker-compose.stage.yml down
docker-compose -f docker-compose.prod.yml down

echo "Starting staging environment..."
docker-compose -f docker-compose.stage.yml up --build
