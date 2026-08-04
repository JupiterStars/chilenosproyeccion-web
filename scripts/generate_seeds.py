#!/usr/bin/env python3
"""Genera export_seed.sql y seed_realista.sql desde mapa de clubes ANFP."""
from __future__ import annotations
import re
from pathlib import Path
from datetime import datetime, timedelta
import random

ROOT = Path("/home/cosmic/Proyectos-Codigo/chilenosproyeccion-web")
MAP = Path("/tmp/clubes_map.txt")
SQL = ROOT / "sql"
random.seed(42)

REGIONS = {
    "colo-colo": "RM", "universidad-de-chile": "RM", "universidad-catolica": "RM",
    "palestino": "RM", "union-espanola": "RM", "audax-italiano": "RM",
    "deportes-recoleta": "RM", "magallanes": "RM", "santiago-morning": "RM",
    "santiago-city": "RM", "real-san-joaquin": "RM", "atletico-colina": "RM",
    "everton": "Valparaíso", "santiago-wanderers": "Valparaíso", "san-luis": "Valparaíso",
    "union-san-felipe": "Valparaíso", "union-la-calera": "Valparaíso",
    "o-higgins": "O'Higgins", "colchagua": "O'Higgins", "deportes-rengo": "O'Higgins",
    "curico-unido": "Maule", "rangers": "Maule", "deportes-linares": "Maule",
    "huachipato": "Biobío", "universidad-de-concepcion": "Biobío",
    "deportes-concepcion": "Biobío", "nublense": "Ñuble",
    "deportes-temuco": "La Araucanía", "deportes-puerto-montt": "Los Lagos",
    "provincial-osorno": "Los Lagos", "coquimbo-unido": "Coquimbo",
    "deportes-la-serena": "Coquimbo", "cobreloa": "Antofagasta",
    "deportes-antofagasta": "Antofagasta", "deportes-iquique": "Tarapacá",
    "san-marcos": "Arica y Parinacota", "deportes-copiapo": "Atacama",
    "cobresal": "Atacama", "deportes-limache": "Valparaíso",
    "deportes-santa-cruz": "O'Higgins", "trasandino": "Valparaíso",
}

NOMBRES = [
    "Diego","Matías","Benjamín","Tomás","Joaquín","Martín","Lucas","Agustín","Felipe","Ignacio",
    "Sebastián","Nicolás","Cristóbal","Vicente","Maximiliano","Gabriel","Álvaro","Bruno","Pablo","Andrés",
    "Leonardo","Esteban","Rodrigo","Camila","Sofía","Valentina","Fernanda","Isidora","Amanda","Catalina",
]
APELLIDOS = [
    "Sabando","Toledo","Díaz","Stancampiano","González","Muñoz","Rojas","Silva","Martínez","Pérez",
    "Soto","Contreras","Morales","Fuentes","Vargas","Castro","Reyes","Figueroa","Henríquez","Navarro",
    "Pizarro","Araya","Cortés","Espinoza","Valdés","Tapia","Bravo","Campos","Maldonado","Olivares",
]
POSICIONES = ["Portero","Defensa","Mediocampista","Delantero"]

def esc(s: str) -> str:
    return s.replace("\\", "\\\\").replace("'", "''")

def slugify(text: str) -> str:
    t = text.lower()
    repl = {"á":"a","é":"e","í":"i","ó":"o","ú":"u","ñ":"n","ü":"u","'":""}
    for a,b in repl.items():
        t = t.replace(a,b)
    t = re.sub(r"[^a-z0-9]+", "-", t)
    return t.strip("-") or "item"

def load_clubs():
    clubs = []
    for line in MAP.read_text().splitlines():
        if not line.strip():
            continue
        div, slug, nombre, file = line.strip().split("|")
        clubs.append({
            "slug": slug,
            "nombre": nombre,
            "division": div,
            "region": REGIONS.get(slug, "Chile"),
            "escudo": f"/assets/escudos/{file}",
        })
    clubs.sort(key=lambda c: c["nombre"])
    return clubs

def write_schema_export():
    # full schema is written separately; placeholder ok
    pass

def sql_header(comment: str) -> str:
    return f"""-- {comment}
-- ChilenosProyección · generado {datetime.now().strftime('%Y-%m-%d')}
-- Charset utf8mb4 · zona America/Santiago

SET NAMES utf8mb4;
SET time_zone = '-04:00';

"""

def build_seed(clubs, n_jugadores, n_noticias, label: str, use_db=True) -> str:
    lines = [sql_header(label)]
    if use_db:
        lines.append("USE chilenosproyeccion;\n")

    # autores
    lines.append("""INSERT INTO autores (id, nombre, bio) VALUES
  (1, 'Redacción ChilenosProyección', 'Equipo editorial del medio.'),
  (2, 'Hermes Agent', 'Asistencia IA — siempre con revisión humana.')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

""")

    # categorias con division
    cats = [
        (1, "Campeonato Nacional", "campeonato-nacional", "nacional", 10, None),
        (2, "Campeonato Regional", "campeonato-regional", "regional", 20, None),
        (3, "Fútbol Infantil", "futbol-infantil", "infantil", 30, None),
        (10, "Sub-20", "sub-20", "nacional", 11, 1),
        (11, "Sub-18", "sub-18", "nacional", 12, 1),
        (12, "Sub-16", "sub-16", "nacional", 13, 1),
        (13, "Sub-15", "sub-15", "nacional", 14, 1),
        (14, "Sub-13", "sub-13", "nacional", 15, 1),
        (20, "Sub-20 Regional", "sub-20-regional", "regional", 21, 2),
        (21, "Sub-18 Regional", "sub-18-regional", "regional", 22, 2),
        (22, "Sub-16 Regional", "sub-16-regional", "regional", 23, 2),
        (23, "Sub-15 Regional", "sub-15-regional", "regional", 24, 2),
        (30, "Sub-13 Infantil", "sub-13-infantil", "infantil", 31, 3),
        (31, "Sub-12 Infantil", "sub-12-infantil", "infantil", 32, 3),
        (32, "Sub-11 Infantil", "sub-11-infantil", "infantil", 33, 3),
        (33, "Sub-14 Infantil", "sub-14-infantil", "infantil", 34, 3),
    ]
    lines.append("INSERT INTO categorias (id, nombre, slug, division, orden, parent_id, descripcion) VALUES\n")
    vals = []
    for c in cats:
        vals.append(f"  ({c[0]}, '{esc(c[1])}', '{c[2]}', '{c[3]}', {c[4]}, {c[5] if c[5] else 'NULL'}, '{esc(c[1])}')")
    lines.append(",\n".join(vals) + "\nON DUPLICATE KEY UPDATE nombre = VALUES(nombre), division = VALUES(division), orden = VALUES(orden), parent_id = VALUES(parent_id);\n\n")

    tags = [
        (1,"Goles","goles"),(2,"Entrevistas","entrevistas"),(3,"Resultados","resultados"),
        (4,"Fichajes","fichajes"),(5,"Debuts","debuts"),(6,"Tablas","tablas"),
        (7,"Formativas","formativas"),(8,"Selección","seleccion"),
    ]
    lines.append("INSERT INTO tags (id, nombre, slug) VALUES\n")
    lines.append(",\n".join(f"  ({t[0]}, '{t[1]}', '{t[2]}')" for t in tags))
    lines.append("\nON DUPLICATE KEY UPDATE nombre = VALUES(nombre);\n\n")

    # clubes
    lines.append("INSERT INTO clubes (id, nombre, slug, escudo_url, region, division) VALUES\n")
    cvals = []
    for i, c in enumerate(clubs, 1):
        cvals.append(f"  ({i}, '{esc(c['nombre'])}', '{c['slug']}', '{c['escudo']}', '{esc(c['region'])}', '{c['division']}')")
    lines.append(",\n".join(cvals))
    lines.append("\nON DUPLICATE KEY UPDATE nombre = VALUES(nombre), escudo_url = VALUES(escudo_url), region = VALUES(region), division = VALUES(division);\n\n")

    # jugadores
    cat_ids_player = [10, 11, 12, 13, 14, 20, 21, 30, 33]
    jug_rows = []
    used_slugs = set()
    for i in range(1, n_jugadores + 1):
        club = clubs[(i - 1) % len(clubs)]
        nom = f"{random.choice(NOMBRES)} {random.choice(APELLIDOS)}"
        if i <= 3:
            # fixed stars for demos
            stars = [(1,"Diego Sabando"),(2,"Pablo Toledo"),(3,"André Stancampiano")]
            if i <= 3:
                nom = stars[i-1][1]
        base = slugify(nom)
        slug = base
        n = 2
        while slug in used_slugs:
            slug = f"{base}-{n}"
            n += 1
        used_slugs.add(slug)
        pos = random.choice(POSICIONES)
        goles = random.randint(0, 14) if pos != "Portero" else 0
        pj = random.randint(3, 18)
        ast = random.randint(0, 8) if pos != "Portero" else 0
        cat = random.choice(cat_ids_player)
        club_id = clubs.index(club) + 1
        year = random.randint(2006, 2014)
        fn = f"{year}-{random.randint(1,12):02d}-{random.randint(1,28):02d}"
        jug_rows.append((i, nom, slug, club_id, cat, pos, goles, pj, ast, fn, club["slug"]))
    lines.append("INSERT INTO jugadores (id, nombre, slug, club_id, categoria_id, posicion, goles, partidos, asistencias, fecha_nacimiento, foto_url) VALUES\n")
    jv = []
    for j in jug_rows:
        jv.append(f"  ({j[0]}, '{esc(j[1])}', '{j[2]}', {j[3]}, {j[4]}, '{j[5]}', {j[6]}, {j[7]}, {j[8]}, '{j[9]}', NULL)")
    lines.append(",\n".join(jv))
    lines.append("\nON DUPLICATE KEY UPDATE nombre = VALUES(nombre), club_id = VALUES(club_id), goles = VALUES(goles), partidos = VALUES(partidos);\n\n")

    # posiciones for sub-20 nacional and regional — full table stats
    nat_clubs = [c for c in clubs if c["division"] == "nacional"][:12]
    reg_clubs = [c for c in clubs if c["division"] == "regional"][:12]
    lines.append("INSERT INTO posiciones (categoria_id, torneo, club, club_id, pts, pj, pg, pe, pp, gf, gc, dg, fecha_corte) VALUES\n")
    pvals = []
    for rank, c in enumerate(nat_clubs):
        pj = 10
        pg = max(0, 9 - rank)
        pe = random.randint(0, 2)
        pp = max(0, pj - pg - pe)
        gf = 20 - rank + random.randint(0, 3)
        gc = 5 + rank + random.randint(0, 2)
        pts = pg * 3 + pe
        dg = gf - gc
        cid = clubs.index(c) + 1
        pvals.append(f"  (10, 'Clausura Formativo Sub-20', '{esc(c['nombre'])}', {cid}, {pts}, {pj}, {pg}, {pe}, {pp}, {gf}, {gc}, {dg}, CURDATE())")
    for rank, c in enumerate(reg_clubs):
        pj = 10
        pg = max(0, 8 - rank)
        pe = 1
        pp = max(0, pj - pg - pe)
        gf = 18 - rank
        gc = 6 + rank
        pts = pg * 3 + pe
        dg = gf - gc
        cid = clubs.index(c) + 1
        pvals.append(f"  (20, 'Campeonato Regional Sub-20', '{esc(c['nombre'])}', {cid}, {pts}, {pj}, {pg}, {pe}, {pp}, {gf}, {gc}, {dg}, CURDATE())")
    lines.append(",\n".join(pvals) + ";\n\n")

    # goleadores
    lines.append("INSERT INTO goleadores (categoria_id, torneo, jugador, jugador_id, club, club_id, goles, partidos, fecha_corte) VALUES\n")
    gvals = []
    top = sorted(jug_rows, key=lambda x: -x[6])[:25]
    for j in top:
        club = clubs[j[3]-1]
        avg_cat = 10 if j[4] < 20 else (20 if j[4] < 30 else 33)
        gvals.append(f"  ({avg_cat}, 'Tabla goleadores', '{esc(j[1])}', {j[0]}, '{esc(club['nombre'])}', {j[3]}, {j[6]}, {j[7]}, CURDATE())")
    lines.append(",\n".join(gvals) + ";\n\n")

    # programacion
    lines.append("INSERT INTO programacion (categoria_id, fecha, hora, local, visita, recinto, club_local_id, club_visita_id) VALUES\n")
    pr = []
    for i in range(8):
        a = nat_clubs[i % len(nat_clubs)]
        b = nat_clubs[(i+3) % len(nat_clubs)]
        if a == b:
            b = nat_clubs[(i+1) % len(nat_clubs)]
        day = (datetime.now() + timedelta(days=2+i*3)).strftime("%Y-%m-%d")
        hour = "15:00:00" if i % 2 == 0 else "17:30:00"
        pr.append(f"  (10, '{day}', '{hour}', '{esc(a['nombre'])}', '{esc(b['nombre'])}', 'Estadio local', {clubs.index(a)+1}, {clubs.index(b)+1})")
    lines.append(",\n".join(pr) + ";\n\n")

    # noticias
    titles_tpl = [
        ("{j} lidera el goleo en {torneo}", "goles"),
        ("{c} suma puntos clave en {torneo}", "resultados"),
        ("Debut prometedor: {j} en {c}", "debuts"),
        ("Tabla apretada: {c} pelea la punta del {torneo}", "tablas"),
        ("Entrevista: la mirada de {j} en formativas", "entrevistas"),
        ("{c} y el trabajo diario de cantera", "formativas"),
        ("Fichaje formativo: {j} refuerza a {c}", "fichajes"),
        ("Crónica: {c} vs rival en {torneo}", "resultados"),
        ("Proyección: {j} en la mira de la selección juvenil", "seleccion"),
        ("Goleada de {c}: el Sub brilla otra vez", "goles"),
    ]
    imgs = [
        "/assets/brand/goleadores-sub20.jpg",
        "/assets/brand/goleadores-regional.jpg",
        "/assets/brand/goleadores-sub14.jpg",
        "/assets/brand/goleadores-proyeccion.jpg",
        "/assets/brand/goleadores-regional.jpg",
        "/assets/brand/portada-preview.jpg",
    ]
    lines.append("INSERT INTO noticias (titulo, slug, extracto, contenido, categoria_id, autor_id, estado, destacada, destacada_orden, imagen_destacada_url, imagen_alt, meta_titulo, meta_descripcion, fecha_publicacion, origen) VALUES\n")
    nvals = []
    used_nslugs = set()
    for i in range(1, n_noticias + 1):
        j = jug_rows[(i * 3) % len(jug_rows)]
        c = clubs[(i * 2) % len(clubs)]
        tpl, tag = titles_tpl[i % len(titles_tpl)]
        torneo = "Sub-20 Nacional" if i % 3 else "Regional Sub-20"
        titulo = tpl.format(j=j[1], c=c["nombre"], torneo=torneo)
        if len(titulo) > 70:
            titulo = titulo[:67] + "…"
        base = slugify(titulo)
        slug = base
        n = 2
        while slug in used_nslugs:
            slug = f"{base}-{n}"
            n += 1
        used_nslugs.add(slug)
        extracto = f"{titulo}. Cobertura ChilenosProyección con foco en fútbol joven chileno."
        contenido = (
            f"<p>{esc(extracto)}</p>"
            f"<h2>Lo que pasó en la cancha</h2>"
            f"<p>El trabajo de cantera de <strong>{esc(c['nombre'])}</strong> sigue dando frutos. "
            f"<strong>{esc(j[1])}</strong> fue figura con números que llaman la atención en formativas.</p>"
            f"<h2>Proyección</h2>"
            f"<p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, "
            f"con tablas, goleadores y crónicas de fin de semana.</p>"
        )
        cat_id = [10, 11, 12, 13, 20, 33, 14][i % 7]
        dest = 1 if i <= 5 else 0
        dord = i if i <= 5 else 0
        img = imgs[i % len(imgs)]
        hours = i * 5
        autor = 1 if i % 4 else 2
        origen = "hermes" if autor == 2 else "manual"
        nvals.append(
            f"  ('{esc(titulo)}', '{slug}', '{esc(extracto)}', '{contenido}', {cat_id}, {autor}, 'publicado', "
            f"{dest}, {dord}, '{img}', '{esc(titulo)}', '{esc(titulo)} | ChilenosProyección', "
            f"'{esc(extracto[:150])}', NOW() - INTERVAL {hours} HOUR, '{origen}')"
        )
    lines.append(",\n".join(nvals) + ";\n\n")

    # noticia_tag sample
    lines.append("""INSERT INTO noticia_tag (noticia_id, tag_id)
SELECT n.id, ((n.id % 8) + 1) FROM noticias n
ON DUPLICATE KEY UPDATE noticia_id = noticia_id;

INSERT INTO noticia_club (noticia_id, club_id)
SELECT n.id, ((n.id % {nc}) + 1) FROM noticias n
ON DUPLICATE KEY UPDATE noticia_id = noticia_id;

""".format(nc=len(clubs)))

    # entrevistas 5
    lines.append("INSERT INTO entrevistas (titulo, slug, extracto, cuerpo, jugador_id, fecha_publicacion, imagen_url, video_url, estado) VALUES\n")
    evals = []
    for i in range(1, 6):
        j = jug_rows[i]
        titulo = f"Entrevista a {j[1]}: el día a día en formativas"
        slug = slugify(titulo)
        cuerpo = f"<p>Conversamos con <strong>{esc(j[1])}</strong> sobre su rol en el plantel, la competencia y su proyección.</p><p>\"Trabajo, humildad y ganas de aprender\", resume el juvenil.</p>"
        extracto = f"{j[1]} abre la puerta de la cantera y habla de competencia y proyección."
        evals.append(f"  ('{esc(titulo)}', '{slug}', '{esc(extracto)}', '{cuerpo}', {j[0]}, NOW() - INTERVAL {i} DAY, '/assets/brand/portada-preview.jpg', NULL, 'publicado')")
    lines.append(",\n".join(evals) + ";\n\n")

    # admin - password_hash for Admin123! generated with bcrypt alternative - use known PHP password_hash for Admin123!
    # $2y$10$... for password "password" is the laravel default - we'll note to regenerate
    # Generate with python bcrypt if available
    try:
        import bcrypt
        ph = bcrypt.hashpw(b"Admin123!", bcrypt.gensalt(rounds=10)).decode()
    except Exception:
        # fallback: PHP compatible for 'password' — document change
        ph = "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"
    lines.append(f"""INSERT INTO usuarios_admin (nombre, email, password_hash, rol) VALUES
  ('Admin', 'admin@chilenosproyeccion.cl', '{ph}', 'admin')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

""")
    return "".join(lines)

def main():
    clubs = load_clubs()
    # export seed: all clubs, ~40 players, 15 news
    seed = build_seed(clubs, n_jugadores=40, n_noticias=15, label="export_seed — datos demo exportables")
    (SQL / "export_seed.sql").write_text(seed, encoding="utf-8")
    # also update seed.sql to match export for local
    (SQL / "seed.sql").write_text(seed, encoding="utf-8")
    real = build_seed(clubs, n_jugadores=200, n_noticias=50, label="seed_realista — dataset amplio")
    (SQL / "seed_realista.sql").write_text(real, encoding="utf-8")
    print("clubs", len(clubs), "wrote export_seed, seed, seed_realista")

if __name__ == "__main__":
    main()
