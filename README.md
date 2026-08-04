# ChilenosProyección — web

Sitio de noticias **chilenosproyeccion.cl**

**Stack (confirmado):** PHP 8 plano + MariaDB + CSS plano + Swiper CDN.  
**No aplica:** Next.js, Supabase, Tailwind build, Node para el frontend.

## Arranque local

```bash
cd /home/cosmic/Proyectos-Codigo/chilenosproyeccion-web/public
php -S 0.0.0.0:8010 router.php
```

Abrir: **http://localhost:8010/**

Sin BD funciona en **modo demo** (datos embebidos). Con BD:

```bash
cd sql && DB_USER=root ./import_local.sh seed.sql
# o: seed_realista.sql
# ajustar .env (DB_*)
```

Export / HostGator: ver `sql/README.md` y `docs/README-EXPORTACION.md`.

## Rutas

| URL | Página |
|-----|--------|
| `/` | Home + Swiper destacadas |
| `/futbol-joven` | Portada sección |
| `/futbol-joven/sub-20` … `sub-15` | Categoría |
| `/futbol-joven/sub-20/pagina/2` | Paginación |
| `/noticia/{slug}` | Artículo |
| `/goleadores/{categoria}` | Tabla goleadores |
| `/posiciones/{categoria}` | Posiciones |
| `/programacion/{categoria}` | Calendario |
| `/club/{slug}` | Ficha club |
| `/jugador/{slug}` | Ficha jugador |
| `/tema/{slug}` | Tag |
| `/buscador?q=` | FULLTEXT |
| `/newsletter` | Suscripción |
| `/contacto` | Contacto |
| `/quienes-somos` | Institucional |
| `/legales/*` | Legales + editorial IA |
| `/feed.xml` | RSS |
| `/sitemap.xml` | Sitemap |
| `/robots.txt` | Robots |
| `/admin` | Panel (scaffold) |
| `/api/noticias` | Hermes API (header `X-Api-Key`) |

## Estructura

```
chilenosproyeccion-web/
├── public/          # docroot
├── admin/           # panel
├── api/             # Hermes
├── includes/        # db, models, header/footer
├── sql/schema.sql
├── sql/seed.sql
├── .env             # fuera de public
├── docs/stack-decision.md
└── README.md
```

## Hermes API

```bash
curl -H "X-Api-Key: $HERMES_API_KEY" http://localhost:8010/api/noticias
curl -X POST -H "X-Api-Key: $HERMES_API_KEY" -H "Content-Type: application/json" \
  -d '{"titulo":"Test","contenido":"<p>Hola</p>","estado":"borrador"}' \
  http://localhost:8010/api/noticias
```

## HostGator

1. Subir proyecto; **document root** → `public/`
2. `includes/`, `.env`, `sql/` **fuera** del docroot o bloqueados
3. Importar `sql/schema.sql` + `seed.sql` en cPanel MariaDB
4. Copiar `.env.example` → `.env` con credenciales cPanel
5. `.htaccess` de `public/` ya tiene rewrites

## Colores

- Negro `#000000`
- Naranjo `#EB7C2B`
- Blanco `#FFFFFF`
