#!/usr/bin/env bash
# HostGator / prod: la DB ya existe (cPanel). Solo tablas + seed.
# Uso:
#   DB_NAME=xxx DB_USER=xxx DB_PASS=xxx DB_HOST=localhost ./import_prod.sh [seed.sql]
set -euo pipefail
DIR="$(cd "$(dirname "$0")" && pwd)"
MYSQL_BIN="${MYSQL_BIN:-mysql}"
SEED="${1:-export_seed.sql}"
: "${DB_NAME:?Define DB_NAME}"
: "${DB_USER:?Define DB_USER}"
: "${DB_HOST:=localhost}"

echo "==> Schema en $DB_NAME (HostGator)..."
$MYSQL_BIN -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$DIR/export_schema.sql"
echo "==> Seed $SEED ..."
# seed tiene USE chilenosproyeccion — strip USE for prod name
sed '/^USE /d' "$DIR/$SEED" | $MYSQL_BIN -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME"
echo "OK prod import."
