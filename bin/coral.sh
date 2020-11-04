#!/usr/bin/env bash

set -e

case $1 in

  install)
    # Create directories to persist the data in MongoDB and Redis.
    if [[ ! -d "./coral/data" ]]; then
      echo "Creating Coral directories."
      mkdir -p coral/data/{mongo,redis}
    fi
    ;;
  start)
    # Start Coral Docker containers.
    echo "Starting Docker containers."
    docker-compose -f coral/docker-compose.yml up -d
    ;;
  stop)
    # Stop Coral Docker containers.
    echo "Stopping Docker containers."
    docker-compose -f coral/docker-compose.yml down
    ;;
  *)
    echo "Invalid command. Use either 'install', 'start', or 'stop'."
    ;;
esac
