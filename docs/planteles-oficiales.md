# Planteles y reglas de clasificación

Fuente: coordinación editorial (WhatsApp). Código: `planteles_oficiales()`, `reglas_clasificacion()`, `fila_clasifica()`.

## Campeonato Nacional (16 equipos) — Sub-20 · Sub-18 · Sub-16 · Sub-15

**Clasifican los 8 primeros** de la tabla a playoff (1° al 8°). Los 8 restantes no se destacan.  
UI: fila verde + badge “Playoff”.

Equipos: Deportes Iquique, Cobreloa, Coquimbo Unido, Everton, Santiago Wanderers, Palestino, Audax Italiano, Unión Española, Colo-Colo, Universidad de Chile, Universidad Católica, Deportes Recoleta, O'Higgins, Huachipato, Universidad de Concepción, Deportes Temuco.

## Campeonato Regional (25) — Sub-20 · Sub-18 · Sub-16 · Sub-15

Se separan en **2 grupos**. Clasifican a playoff los **2 primeros de cada grupo**.  
UI: fila verde + badge “Playoff” en puestos 1° y 2° de cada grupo.

**Grupo Centro Norte:** Unión La Calera, Deportes La Serena, Deportes Antofagasta, San Marcos, Cobresal, San Luis, Atlético Colina, Santiago Morning, Unión San Felipe, Deportes Limache, Deportes Copiapó, Trasandino, Santiago City.

**Grupo Centro Sur:** Rangers, Magallanes, Deportes Puerto Montt, Ñublense, Deportes Concepción, Curicó Unido, Deportes Santa Cruz, Colchagua, Deportes Linares, Deportes Rengo, Real San Joaquín, Provincial Osorno.

## Campeonato Infantil

### Sub-13 y Sub-14 — 4 grupos
Norte · Centro 1 · Centro 2 · Sur (listas en `infantil_sub13_14`).

### Sub-11 y Sub-12 — Grupo 1 y Grupo 2
**Solo clasifica el 1° de cada grupo.** UI: fila verde + badge “Clasifica”.

**Grupo 1 (15):** Universidad de Chile, Universidad Católica, Audax Italiano, Cobresal, Santiago Wanderers, Universidad San Sebastián, Atlético Colina, Unión La Calera, Captadores FC, San Luis, Trasandino, Everton, Deportes Recoleta, Real San Joaquín, Academia Antofagasta.

**Grupo 2 (15):** Colo-Colo, Unión Española, O'Higgins, Palestino, Cobreloa, Colchagua, Santiago Morning, Magallanes, Santiago City, Fluminense Chile, Deportes Rengo, Sport Madrid, Diablos Rojos, Deportes Santa Cruz, Lautaro de Buin.

### Goleadores
Sin categoría Sub-13 infantil en goleadores.  
En la tabla de goleadores el escudo del club va **solo** en la columna Club (no se repite junto al nombre del jugador).  
No mostrar fuente/COMET ni texto de clasificación (eso es solo de posiciones).

### Posiciones
Columna **Prom.** (Pts/PJ) en la tabla; sin pestaña extra “Promedio”.  
Con multi-grupo: botones de salto a cada grupo (CN / CS, etc.).

## Programación
- Navegación por **fechas** (F1, F2… y Anterior/Siguiente), incluidas fechas sin horario aún.
- Apartados por división: **Nacional · Regional · Infantil**, y chips de categoría.

## Escudos
`public/assets/escudos/*.png` optimizados 256×256 (incl. Sub-11/12 nuevos).
