# Estructura de noticias — ChilenosProyección

## Taxonomía

### Divisiones (menú)

1. **Campeonato Nacional** (formativas ANFP)  
2. **Campeonato Regional**  
3. **Fútbol Infantil**

### Categorías por edad (slugs)

| División | Slugs |
|----------|--------|
| Nacional | `sub-20`, `sub-18`, `sub-16`, `sub-15`, `sub-13` |
| Regional | `sub-20-regional`, `sub-18-regional`, `sub-16-regional`, `sub-15-regional` |
| Infantil | `sub-14-infantil`, `sub-13-infantil`, `sub-12-infantil`, `sub-11-infantil` |

Padres en BD: `campeonato-nacional`, `campeonato-regional`, `futbol-infantil`.

### Tags sugeridos

`goles`, `entrevistas`, `resultados`, `fichajes`, `debuts`, `tablas`, `formativas`, `seleccion`

### Clubes

Clubes reales de formativas con escudos en `/assets/escudos/{slug}.ext` (ANFP Nacional + Regional).

---

## Formato de una noticia

| Campo | Regla |
|-------|--------|
| **Título** | Máx. ~70 caracteres, SEO, sin clickbait vacío |
| **Slug** | `slugify(titulo)` único |
| **Extracto** | 1–2 líneas; sirve para cards y meta description |
| **Cuerpo** | HTML: párrafos cortos, `h2`/`h3`, sin paredes de texto |
| **Imagen** | URL (`imagen_destacada_url`), `imagen_alt`, `imagen_credito` |
| **Autor** | `autores.id` (Redacción / Hermes) |
| **Fecha** | `fecha_publicacion` (America/Santiago) |
| **Categoría** | Una principal (`categoria_id`) |
| **Tags** | N vía `noticia_tag` |
| **Clubes** | N vía `noticia_club` |
| **Jugadores** | N vía `noticia_jugador` (opcional) |
| **Estado** | `borrador` → `publicado` → `archivado` |
| **Origen** | `manual` \| `hermes` |
| **Destacada** | home Swiper (`destacada`, `destacada_orden`) |

### Relacionados

En ficha de noticia: misma categoría (y a futuro mismo club/tag).

---

## Flujo de contenido

```
1. Recepción
   PDF / planillas / notas de prensa / Telegram (Hermes)
        ↓
2. Procesamiento IA (opcional)
   resumen, título SEO, slug, tags, extracto
        ↓
3. Revisión humana
   hechos, menores, tono, créditos de imagen
        ↓
4. Publicación
   · Admin web (CRUD — próximo sprint)
   · API Hermes: POST /api/noticias + X-Api-Key
        ↓
5. Distribución
   home, categoría, club, feed RSS, (Metricool draft)
```

### API mínima (Hermes)

- `POST /api/noticias` — body JSON: `titulo`, `contenido`, `extracto?`, `imagen_destacada_url?`, `estado`, `categoria_id?`  
- Header: `X-Api-Key` = `HERMES_API_KEY`

### Imágenes

Preferir Cloudflare R2 + URL pública `media.*` en `imagen_destacada_url` (go-live).

---

## Entrevistas

Tabla `entrevistas`: título, slug, extracto, cuerpo, `jugador_id`, imagen, video opcional.  
Rutas: `/entrevistas`, `/entrevista/{slug}`.
