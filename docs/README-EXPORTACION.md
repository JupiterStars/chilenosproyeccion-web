# Guía de exportación y producción — ChilenosProyección

**Fecha:** 2026-08-03  
**Código:** `/home/cosmic/Proyectos-Codigo/chilenosproyeccion-web`  
**Stack:** PHP 8 · MariaDB/MySQL · CSS plano · Swiper CDN

---

## 1. Importar BD en local

```bash
cd sql
DB_USER=root ./import_local.sh seed.sql          # demo
# DB_USER=root ./import_local.sh seed_realista.sql
```

Configurar `.env` (`DB_*`, `APP_URL=http://localhost:8010`).

Arranque:

```bash
cd public
php -S 0.0.0.0:8010 router.php
```

Abrir http://localhost:8010/

Sin BD el sitio funciona en **modo demo**.

---

## 2. Desplegar en HostGator

1. Addon domain / docroot → carpeta del sitio, web root = `public/`.  
2. Crear MariaDB + usuario en cPanel.  
3. Subir código (rsync/SCP). **No** subir `.env` de local.  
4. Importar `export_schema.sql` + `export_seed.sql` (phpMyAdmin o `import_prod.sh`).  
5. `.env` en servidor: `APP_URL`, `DB_*`, `HERMES_API_KEY`.  
6. Probar: `/`, una noticia, `/posiciones/sub-20`, `/goleadores/sub-20`, `/sitemap.xml`, `/legales/politica-privacidad`.

Playbook: `/home/cosmic/vault/ops/hostgator-agent-playbook.md`

---

## 3. Archivos tocados en esta entrega (principales)

### SQL
- `sql/schema.sql`, `export_schema.sql`
- `sql/seed.sql`, `export_seed.sql`, `seed_realista.sql`
- `sql/import_local.sh`, `import_prod.sh`, `README.md`
- `scripts/generate_seeds.py`

### Front / includes
- `includes/header.php`, `footer.php`, `helpers.php`, `bootstrap.php`
- `includes/models/*` (Club, Jugador, Posicion, Goleador, Noticia, Entrevista)
- `includes/partials/news-card.php`
- `public/css/styles.css`, `public/js/main.js`
- `public/router.php`
- Páginas: `club.php`, `jugador.php`, `posiciones.php`, `goleadores.php`, `buscador.php`, `entrevistas.php`, `entrevista.php`, `legales.php`
- `public/legales/*.php` (6 docs Chile)

### Assets
- `public/assets/brand/logo-cp.png` (monograma naranja `#EB7C2B`)
- `public/assets/logo-placeholder.svg`
- `public/assets/escudos/*` (41 clubes ANFP)
- `public/assets/README.md`, `escudos/README.md`

### Docs
- `docs/estructura-noticias.md`
- `docs/README-EXPORTACION.md`

---

## 4. Próximos pasos

| Prioridad | Item |
|-----------|------|
| P0 | Dominio + Cloudflare + deploy HG |
| P0 | Admin CRUD noticias (hoy scaffold) |
| P1 | GA4 / GTM / Pixel tras consent cookies |
| P1 | R2 + Hermes E2E |
| P1 | Textos legales con RUT/domicilio real del responsable |
| P2 | JSON-LD, AdSense live, Lighthouse |

---

## 5. Criterios de verificación

- [ ] Home con hero Swiper y logo naranja  
- [ ] Menú: Nacional / Regional / Infantil (dropdown)  
- [ ] Posiciones con PJ/PG/PE/PP/GF/GC/DG/Pts  
- [ ] Goleadores con link a jugador  
- [ ] Club con escudo + plantel  
- [ ] Legales 6 páginas  
- [ ] Banner cookies  
- [ ] Escudos ANFP visibles en tablas  
- [ ] Import SQL sin error  

---

## Rollback

- Git: `git checkout -- .` o revert del commit atómico.  
- BD: dropear tablas o restaurar dump previo.  
- Assets: re-copiar desde `~/Downloads/WhatSie/`.
