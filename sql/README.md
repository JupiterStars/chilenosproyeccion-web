# Base de datos — ChilenosProyección

## Archivos

| Archivo | Uso |
|---------|-----|
| `schema.sql` | Crea DB `chilenosproyeccion` + tablas (local) |
| `export_schema.sql` | Solo `CREATE TABLE` + índices (HostGator / DB ya creada) |
| `seed.sql` / `export_seed.sql` | Demo exportable (~15 noticias, ~40 jugadores, 41 clubes ANFP) |
| `seed_realista.sql` | Dataset amplio (~50 noticias, 200 jugadores, 41 clubes) |
| `import_local.sh` | Import local con root/usuario |
| `import_prod.sh` | Import en HostGator (vars de entorno) |

## Local

```bash
cd /home/cosmic/Proyectos-Codigo/chilenosproyeccion-web/sql

# Opción A — script
DB_USER=root ./import_local.sh seed.sql
# o dataset grande:
DB_USER=root ./import_local.sh seed_realista.sql

# Opción B — CLI
mysql -u root < schema.sql
mysql -u root chilenosproyeccion < seed.sql
```

Ajustá `.env` en la raíz del proyecto:

```env
DB_HOST=127.0.0.1
DB_NAME=chilenosproyeccion
DB_USER=root
DB_PASS=
```

Sin PDO el sitio sigue en **modo demo** (datos embebidos).

## HostGator (phpMyAdmin)

1. cPanel → MySQL® Databases → crear DB + usuario + privilegios.  
2. phpMyAdmin → seleccionar la DB.  
3. Importar **primero** `export_schema.sql`.  
4. Importar **después** `export_seed.sql` (o `seed_realista.sql`).  
5. Si el seed trae `USE chilenosproyeccion;`, borralo o usá `import_prod.sh` que lo elimina.

## HostGator (CLI / SSH)

```bash
export DB_NAME=bdeaeami_XXXX
export DB_USER=bdeaeami_XXXX
export DB_PASS='…'   # no pegar en chats
export DB_HOST=localhost
./import_prod.sh export_seed.sql
```

## Tablas

Core: `autores`, `categorias`, `clubes`, `jugadores`, `tags`, `noticias`  
Puentes: `noticia_tag`, `noticia_club`, `noticia_jugador`  
Datos: `goleadores`, `posiciones`, `programacion`, `entrevistas`, `suscriptores`  
Admin: `usuarios_admin`

## Admin demo

- Email: `admin@chilenosproyeccion.cl`  
- Password seed: regenerar con `password_hash('TuClave', PASSWORD_DEFAULT)` en PHP antes de producción.

## Regenerar seeds

```bash
# Requiere /tmp/clubes_map.txt (generado al copiar escudos)
python3 ../scripts/generate_seeds.py
```
