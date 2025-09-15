#!/bin/sh
set -e

echo "DATABASE_URL is: ${DATABASE_URL}"

echo "Waiting for PostgreSQL to be ready..."
while ! pg_isready -h db -p 5432 -U postgres; do
  echo "PostgreSQL is still starting up..."
  sleep 1
done
echo "PostgreSQL is fully ready."

echo "Creating database if not exists..."
php bin/console doctrine:database:create --if-not-exists

echo "Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

echo "Loading fixtures..."
php bin/console doctrine:fixtures:load --env=dev --no-interaction

