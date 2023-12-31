#!/bin/bash
set -e

POSTGRES="psql --username ${POSTGRES_USER}"

echo "Creating database role: ${TEST_POSTGRES_USER}"

$POSTGRES <<-EOSQL
CREATE USER ${TEST_POSTGRES_USER} WITH SUPERUSER PASSWORD '${TEST_POSTGRES_PASSWORD}';
EOSQL
