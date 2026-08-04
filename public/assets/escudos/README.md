# Escudos de clubes (ANFP)

Archivos en esta carpeta: `{slug}.{png|webp}`

Origen local de copia:  
- `~/Downloads/WhatSie/ESCUDOS PNG CAMPEONATO NACIONAL`  
- `~/Downloads/WhatSie/ESCUDOS PNG CAMPEONATO REGIONAL`

## Convención

| Campo | Valor |
|-------|--------|
| URL en BD | `/assets/escudos/{slug}.png` (o `.webp`) |
| Fallback UI | logo marca naranja si no hay archivo |

## Clubes esperados

### Campeonato Nacional (formativas)

| Club | Archivo |
|------|---------|
| Audax Italiano | `audax-italiano.png` |
| Cobreloa | `cobreloa.webp` |
| Colo-Colo | `colo-colo.png` |
| Coquimbo Unido | `coquimbo-unido.png` |
| Deportes Iquique | `deportes-iquique.png` |
| Deportes Recoleta | `deportes-recoleta.png` |
| Deportes Temuco | `deportes-temuco.png` |
| Everton | `everton.png` |
| Huachipato | `huachipato.png` |
| O'Higgins | `o-higgins.png` |
| Palestino | `palestino.png` |
| Santiago Wanderers | `santiago-wanderers.png` |
| Unión Española | `union-espanola.png` |
| Universidad Católica | `universidad-catolica.png` |
| Universidad de Chile | `universidad-de-chile.png` |
| Universidad de Concepción | `universidad-de-concepcion.png` |

### Campeonato Regional

| Club | Archivo |
|------|---------|
| Atlético Colina | `atletico-colina.png` |
| Cobresal | `cobresal.png` |
| Colchagua | `colchagua.webp` |
| Curicó Unido | `curico-unido.png` |
| Deportes Antofagasta | `deportes-antofagasta.png` |
| Deportes Concepción | `deportes-concepcion.webp` |
| Deportes Copiapó | `deportes-copiapo.png` |
| Deportes La Serena | `deportes-la-serena.png` |
| Deportes Limache | `deportes-limache.png` |
| Deportes Linares | `deportes-linares.png` |
| Deportes Puerto Montt | `deportes-puerto-montt.png` |
| Deportes Rengo | `deportes-rengo.webp` |
| Deportes Santa Cruz | `deportes-santa-cruz.png` |
| Magallanes | `magallanes.png` |
| Ñublense | `nublense.png` |
| Provincial Osorno | `provincial-osorno.png` |
| Rangers | `rangers.png` |
| Real San Joaquín | `real-san-joaquin.png` |
| San Luis | `san-luis.png` |
| San Marcos | `san-marcos.png` |
| Santiago City | `santiago-city.png` |
| Santiago Morning | `santiago-morning.png` |
| Trasandino | `trasandino.png` |
| Unión La Calera | `union-la-calera.png` |
| Unión San Felipe | `union-san-felipe.png` |

## Reemplazar un escudo

1. Exportá PNG/WebP transparente (ideal 256–512 px).  
2. Guardá como `{slug}.ext` en esta carpeta.  
3. Actualizá `escudo_url` en tabla `clubes` si cambia la extensión.
