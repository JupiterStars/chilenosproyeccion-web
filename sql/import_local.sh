#!/usr/bin/env bash
# Importa schema + seed en local (crea DB chilenosproyeccion)
set -euo pipefail
DIR="$(cd "$(dirname "$0")" && pwd)"
MYSQL_BIN="${MYSQL_BIN:-mysql}"
SEED="${1:-seed.sql}"   # seed.sql | seed_realista.sql | export_seed.sql

echo "==> Creando DB e importando schema..."
$MYSQL_BIN -u "${DB_USER:-root}" ${DB_PASS:+-p"$DB_PASS"} < "$DIR/schema.sql"
echo "==> Importando $SEED ..."
$MYSQL_BIN -u "${DB_USER:-root}" ${DB_PASS:+-p"$DB_PASS"} chilenosproyeccion < "$DIR/$SEED"
echo "OK. Verifica: $MYSQL_BIN -u ${DB_USER:-root} chilenosproyeccion -e 'SHOW TABLES; SELECT COUNT(*) AS noticias FROM noticias;'"
