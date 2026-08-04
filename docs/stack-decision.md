# Decisión de stack web — chilenosproyeccion.cl

**Fecha:** 2026-08-01 · Documento vivo

Corrige y reemplaza cualquier mención previa a **Next.js, Supabase, Control Centers o build con Node.js** — **no aplican** a este proyecto.

## Stack final

| Capa | Elección |
|------|----------|
| Hosting | HostGator Business (cPanel) |
| Backend | PHP 8.x plano, MVC simple **sin** Laravel/Slim |
| BD | MariaDB (InnoDB, utf8mb4) |
| CSS | CSS plano + variables (`:root`) — sin Tailwind, sin Node |
| JS | Vanilla + **Swiper.js CDN** (solo slider) |
| Animaciones | CSS transitions / keyframes |
| Imágenes | Cloudflare R2 + Image Resizing (fase media) |
| Buscador | FULLTEXT MariaDB |

## Colores

```css
--color-negro: #000000;
--color-naranjo: #EB7C2B;
--color-blanco: #FFFFFF;
--color-gris-oscuro: #111111;
--color-gris-texto: #CCCCCC;
```

## Rutas

Ver README del proyecto.
