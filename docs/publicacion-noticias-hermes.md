# Publicación de noticias — Hermes vs front (riesgo y velocidad)

**Proyecto:** ChilenosProyección / futbolistaschilenos.cl  
**Actualizado:** 2026-08-04  
**Relacionado:** [[estructura-noticias.md]], [[README-EXPORTACION.md]], vault board (Hermes E2E)

---

## 1. Pregunta corta

| Pregunta | Respuesta |
|----------|-----------|
| ¿Hermes va a “manejar toda la página”? | **No.** Solo **contenido** (filas en BD + opcional media en R2). |
| ¿Riesgo de cagar el sitio en caliente? | **Bajo** si solo usa la API. **Alto** si edita PHP/CSS/JS en prod. |
| ¿Solo edita lo mínimo? | En el diseño correcto: **cero ediciones de archivos**. Solo `POST` a la API. |
| ¿Hay un segundo LLM que verifica? | **Hoy no está cableado de forma obligatoria.** Se recomienda (y se documenta abajo) un **paso revisor** antes de `estado=publicado`. |

---

## 2. Cómo se publican las noticias (diseño canónico)

```
Fuente (PDF / planillas / Telegram / APIs)
        ↓
Hermes Agent (redacción + SEO + tags + categoría)
        ↓
[Opcional] Imagen → Cloudflare R2 → URL pública
        ↓
POST https://futbolistaschilenos.cl/api/noticias
     Header: X-Api-Key: $HERMES_API_KEY
     Body JSON (ver §4)
        ↓
MariaDB (HostGator) — tabla `noticias` (+ tags/clubes si se extiende)
        ↓
El sitio PHP YA lee la BD → home / categoría / ficha
        ↓
(No hace falta redeploy del front)
```

### Qué NO es el flujo normal

- ❌ Editar `public/index.php` o partials para “meter” una nota  
- ❌ Subir HTML a mano al servidor por cada titular  
- ❌ Redeploy de CSS/JS solo por contenido nuevo  

Eso es lo que hizo un agente humano/Grok **cuando hubo features nuevas** (badge dorado, sin imagen, layout). **Hermes no debería hacer eso en el día a día.**

---

## 3. Hermes: alcance y riesgo en caliente

### 3.1 Qué puede tocar Hermes (permitido)

| Recurso | Acción | Riesgo al sitio |
|---------|--------|-----------------|
| `POST /api/noticias` | Crear noticia | Bajo (contenido) |
| R2 bucket media | Subir imagen | Bajo (no toca PHP) |
| Lectura `GET /api/noticias` | Listar | Nulo |

La API ya tiene:

- Auth por `X-Api-Key`  
- Rate limit por IP  
- Sanitización HTML del cuerpo (`sanitize_html`)  
- Payload size cap  

### 3.2 Qué NO debe tocar Hermes en prod (prohibido por política)

| Recurso | Por qué |
|---------|---------|
| `public/**/*.php`, `includes/**`, `css/`, `js/` | Puede romper layout, menús, SEO, auth |
| `.env` / secrets | Seguridad |
| `DROP` / migraciones destructivas | Pérdida de datos |
| `rsync --delete` del docroot | Borrado accidental |

**Conclusión:** si Hermes solo publica por API, **trabajar “en caliente” no recarga ni reescribe la página**: el front sigue igual; solo cambia la data.

### 3.3 Analogía

- **Front (PHP/CSS/JS)** = motor y chasis del auto.  
- **Noticias en BD** = pasajeros y equipaje.  
- Hermes carga pasajeros. **No desarma el motor en la carretera.**

---

## 4. Payload mínimo (rápido)

### Header

```http
POST /api/noticias
Content-Type: application/json
X-Api-Key: <HERMES_API_KEY del .env de prod>
```

### Body (ejemplo)

```json
{
  "titulo": "Colo-Colo goleó 10-0 a Deportes Temuco",
  "contenido": "<p>Texto corto en HTML sanitizado…</p>",
  "extracto": "Blanquinegros arrollaron 10-0…",
  "imagen_destacada_url": null,
  "estado": "publicado",
  "categoria_id": 10
}
```

| Campo | Obligatorio | Notas |
|-------|-------------|--------|
| `titulo` | Sí | ≤ 250 chars |
| `contenido` | Sí | HTML; se sanitiza en servidor |
| `extracto` | No | Si falta, se corta del cuerpo |
| `imagen_destacada_url` | No | `null` = sin imagen (soportado en front) |
| `estado` | No | `publicado` \| `borrador` (default borrador en API si no se manda bien) |
| `categoria_id` | No | Preferible siempre (Sub-20 = id según BD) |

**Respuesta OK:** `{ "ok": true, "id": N, "slug": "..." }`  
La home ordena por `fecha_publicacion DESC` → la nueva sale arriba sin tocar código.

### Tiempo esperado

| Paso | Tiempo orientativo |
|------|-------------------|
| Redacción IA + validación | 10–60 s |
| Upload R2 (si hay foto) | 2–15 s |
| `POST /api/noticias` | &lt; 1 s |
| Visible en web | inmediato (cache browser aparte) |

**Redeploy del sitio: 0.**

---

## 5. Segundo LLM / verificación de decisión

### Estado actual (honestidad)

- **No hay un “segundo LLM obligatorio” cableado en la API** que apruebe cada `POST`.  
- La API confía en la key + validaciones técnicas (JSON, largo, HTML, rate limit).  
- En `estructura-noticias.md` el flujo editorial ya dice: **revisión humana** de hechos/menores/tono.

### Diseño recomendado (para no “cagarla” editorialmente)

```
Hermes Redactor (LLM A)
   → genera título, cuerpo, tags, categoría, flags (destacada?, goleada?)
        ↓
Hermes Revisor / “segundo LLM” (LLM B)  [recomendado]
   → checklist: hechos, tono, menores, SEO, categoría correcta, sin inventar
   → output: APROBAR | CORREGIR | RECHAZAR
        ↓
Solo si APROBAR → POST estado=publicado
Si no → estado=borrador o reintento
```

Opcional endurecido:

- Publicar siempre en **`borrador`** y un humano o un job confirma.  
- Destacadas / etiqueta dorada (`goleada`) solo con flag explícito + revisor.  
- Council / war room **solo** para decisiones de producto (no por cada nota).

### Qué verifica el “segundo LLM” (checklist)

1. ¿El resultado/hecho es coherente y no inventado?  
2. ¿Categoría de edad correcta (Sub-20, Regional, etc.)?  
3. ¿Título sin clickbait vacío y &lt; ~70–80 chars preferible?  
4. ¿HTML mínimo y limpio?  
5. ¿Imagen con crédito/alt si aplica?  
6. ¿Menores / datos sensibles?  
7. ¿Tags razonables (`resultados`, `goleada`, …)?

---

## 6. Etiqueta dorada y sin imagen (estado del front)

Ya soportado en el sitio:

| Feature | Cómo se activa |
|---------|----------------|
| Badge dorado “Goleada” | Tag `goleada` en `noticia_tag` (el front lee `tag_slugs`) |
| Sin imagen | `imagen_destacada_url = NULL` (no se fuerza placeholder) |
| Primero en home | `fecha_publicacion` reciente y/o `destacada=1` + `destacada_orden` bajo |

**Nota API:** el `POST` actual crea la fila base; **tags/destacada** pueden requerir extensión de la API o un paso SQL/admin hasta que Hermes envíe esos campos. Prioridad E2E: ampliar `POST` para `tags[]`, `destacada`, `destacada_orden`.

---

## 7. Matriz de responsabilidades

| Actor | Publica contenido | Cambia layout/CSS | Deploy HostGator | Verifica decisión |
|-------|-------------------|-------------------|------------------|-------------------|
| **Hermes (prod)** | Sí (API) | No | No | Idealmente LLM B |
| **Grok/Claude (dev)** | A veces (emergencia) | Sí (features) | Sí (cuando se pide) | Humano / review |
| **Humano editorial** | Sí | No | No | Sí (última palabra) |

---

## 8. Riesgos residuales (aunque solo use API)

| Riesgo | Mitigación |
|--------|------------|
| Noticia falsa / mal resultado | Segundo LLM + humano en casos sensibles |
| Spam de posts | Rate limit API (ya existe) |
| HTML malicioso | `sanitize_html` en API |
| Key filtrada | Rotar `HERMES_API_KEY`; no loguear en claro |
| Categoría mal puesta | Revisor + mapa slug→id en Hermes |
| Destacada en mal orden | Default: no destacar; solo flag explícito |

**No hay riesgo de “recargar la página entera” ni de reescribir el menú** si Hermes no tiene acceso a editar el filesystem de prod.

---

## 9. Checklist operativo (mínima demora)

1. [ ] Hermes tiene `HERMES_API_KEY` de prod (secreto, no en git).  
2. [ ] Si hay foto → R2 → URL pública.  
3. [ ] LLM A redacta JSON.  
4. [ ] LLM B (o humano) aprueba.  
5. [ ] `POST /api/noticias` con `estado=publicado`.  
6. [ ] Smoke: home + `/noticia/{slug}` 200.  
7. [ ] **No** deploy de código.

---

## 10. Decisiones abiertas (para el board)

- [ ] Extender API: `tags[]`, `destacada`, `destacada_orden`, `clubes[]`.  
- [ ] Forzar pipeline Redactor → Revisor en Hermes cloud.  
- [ ] Webhook post-publicación (Metricool / GA4 event).  
- [ ] Admin CRUD humano (próximo sprint en `estructura-noticias.md`).

---

## 11. Resumen en una frase

**Hermes no edita la web en caliente: empuja filas a la base por API; el front solo lee. Un segundo LLM revisor es recomendable y aún no es un hard-gate automático en el servidor.**
