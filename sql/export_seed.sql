-- export_seed — datos demo exportables
-- ChilenosProyección · generado 2026-08-03
-- Charset utf8mb4 · zona America/Santiago

SET NAMES utf8mb4;
SET time_zone = '-04:00';
SET FOREIGN_KEY_CHECKS = 0;

USE chilenosproyeccion;
INSERT INTO autores (id, nombre, bio) VALUES
  (1, 'Redacción ChilenosProyección', 'Equipo editorial del medio.'),
  (2, 'Hermes Agent', 'Asistencia IA — siempre con revisión humana.')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

INSERT INTO categorias (id, nombre, slug, division, orden, parent_id, descripcion) VALUES
  (1, 'Campeonato Nacional', 'campeonato-nacional', 'nacional', 10, NULL, 'Campeonato Nacional'),
  (2, 'Campeonato Regional', 'campeonato-regional', 'regional', 20, NULL, 'Campeonato Regional'),
  (3, 'Fútbol Infantil', 'futbol-infantil', 'infantil', 30, NULL, 'Fútbol Infantil'),
  (10, 'Sub-20', 'sub-20', 'nacional', 11, 1, 'Sub-20'),
  (11, 'Sub-18', 'sub-18', 'nacional', 12, 1, 'Sub-18'),
  (12, 'Sub-16', 'sub-16', 'nacional', 13, 1, 'Sub-16'),
  (13, 'Sub-15', 'sub-15', 'nacional', 14, 1, 'Sub-15'),
  (14, 'Sub-13', 'sub-13', 'nacional', 15, 1, 'Sub-13'),
  (20, 'Sub-20 Regional', 'sub-20-regional', 'regional', 21, 2, 'Sub-20 Regional'),
  (21, 'Sub-18 Regional', 'sub-18-regional', 'regional', 22, 2, 'Sub-18 Regional'),
  (22, 'Sub-16 Regional', 'sub-16-regional', 'regional', 23, 2, 'Sub-16 Regional'),
  (23, 'Sub-15 Regional', 'sub-15-regional', 'regional', 24, 2, 'Sub-15 Regional'),
  (30, 'Sub-13 Infantil', 'sub-13-infantil', 'infantil', 31, 3, 'Sub-13 Infantil'),
  (31, 'Sub-12 Infantil', 'sub-12-infantil', 'infantil', 32, 3, 'Sub-12 Infantil'),
  (32, 'Sub-11 Infantil', 'sub-11-infantil', 'infantil', 33, 3, 'Sub-11 Infantil'),
  (33, 'Sub-14 Infantil', 'sub-14-infantil', 'infantil', 34, 3, 'Sub-14 Infantil')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre), division = VALUES(division), orden = VALUES(orden), parent_id = VALUES(parent_id);

INSERT INTO tags (id, nombre, slug) VALUES
  (1, 'Goles', 'goles'),
  (2, 'Entrevistas', 'entrevistas'),
  (3, 'Resultados', 'resultados'),
  (4, 'Fichajes', 'fichajes'),
  (5, 'Debuts', 'debuts'),
  (6, 'Tablas', 'tablas'),
  (7, 'Formativas', 'formativas'),
  (8, 'Selección', 'seleccion')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

INSERT INTO clubes (id, nombre, slug, escudo_url, region, division) VALUES
  (1, 'Atlético Colina', 'atletico-colina', '/assets/escudos/atletico-colina.png', 'RM', 'regional'),
  (2, 'Audax Italiano', 'audax-italiano', '/assets/escudos/audax-italiano.png', 'RM', 'nacional'),
  (3, 'Cobreloa', 'cobreloa', '/assets/escudos/cobreloa.png', 'Antofagasta', 'nacional'),
  (4, 'Cobresal', 'cobresal', '/assets/escudos/cobresal.png', 'Atacama', 'regional'),
  (5, 'Colchagua', 'colchagua', '/assets/escudos/colchagua.png', 'O''Higgins', 'regional'),
  (6, 'Colo-Colo', 'colo-colo', '/assets/escudos/colo-colo.png', 'RM', 'nacional'),
  (7, 'Coquimbo Unido', 'coquimbo-unido', '/assets/escudos/coquimbo-unido.png', 'Coquimbo', 'nacional'),
  (8, 'Curicó Unido', 'curico-unido', '/assets/escudos/curico-unido.png', 'Maule', 'regional'),
  (9, 'Deportes Antofagasta', 'deportes-antofagasta', '/assets/escudos/deportes-antofagasta.png', 'Antofagasta', 'regional'),
  (10, 'Deportes Concepción', 'deportes-concepcion', '/assets/escudos/deportes-concepcion.png', 'Biobío', 'regional'),
  (11, 'Deportes Copiapó', 'deportes-copiapo', '/assets/escudos/deportes-copiapo.png', 'Atacama', 'regional'),
  (12, 'Deportes Iquique', 'deportes-iquique', '/assets/escudos/deportes-iquique.png', 'Tarapacá', 'nacional'),
  (13, 'Deportes La Serena', 'deportes-la-serena', '/assets/escudos/deportes-la-serena.png', 'Coquimbo', 'regional'),
  (14, 'Deportes Limache', 'deportes-limache', '/assets/escudos/deportes-limache.png', 'Valparaíso', 'regional'),
  (15, 'Deportes Linares', 'deportes-linares', '/assets/escudos/deportes-linares.png', 'Maule', 'regional'),
  (16, 'Deportes Puerto Montt', 'deportes-puerto-montt', '/assets/escudos/deportes-puerto-montt.png', 'Los Lagos', 'regional'),
  (17, 'Deportes Recoleta', 'deportes-recoleta', '/assets/escudos/deportes-recoleta.png', 'RM', 'nacional'),
  (18, 'Deportes Rengo', 'deportes-rengo', '/assets/escudos/deportes-rengo.png', 'O''Higgins', 'regional'),
  (19, 'Deportes Santa Cruz', 'deportes-santa-cruz', '/assets/escudos/deportes-santa-cruz.png', 'O''Higgins', 'regional'),
  (20, 'Deportes Temuco', 'deportes-temuco', '/assets/escudos/deportes-temuco.png', 'La Araucanía', 'nacional'),
  (21, 'Everton', 'everton', '/assets/escudos/everton.png', 'Valparaíso', 'nacional'),
  (22, 'Huachipato', 'huachipato', '/assets/escudos/huachipato.png', 'Biobío', 'nacional'),
  (23, 'Magallanes', 'magallanes', '/assets/escudos/magallanes.png', 'RM', 'regional'),
  (24, 'O''Higgins', 'o-higgins', '/assets/escudos/o-higgins.png', 'O''Higgins', 'nacional'),
  (25, 'Palestino', 'palestino', '/assets/escudos/palestino.png', 'RM', 'nacional'),
  (26, 'Provincial Osorno', 'provincial-osorno', '/assets/escudos/provincial-osorno.png', 'Los Lagos', 'regional'),
  (27, 'Rangers', 'rangers', '/assets/escudos/rangers.png', 'Maule', 'regional'),
  (28, 'Real San Joaquín', 'real-san-joaquin', '/assets/escudos/real-san-joaquin.png', 'RM', 'regional'),
  (29, 'San Luis', 'san-luis', '/assets/escudos/san-luis.png', 'Valparaíso', 'regional'),
  (30, 'San Marcos', 'san-marcos', '/assets/escudos/san-marcos.png', 'Arica y Parinacota', 'regional'),
  (31, 'Santiago City', 'santiago-city', '/assets/escudos/santiago-city.png', 'RM', 'regional'),
  (32, 'Santiago Morning', 'santiago-morning', '/assets/escudos/santiago-morning.png', 'RM', 'regional'),
  (33, 'Santiago Wanderers', 'santiago-wanderers', '/assets/escudos/santiago-wanderers.png', 'Valparaíso', 'nacional'),
  (34, 'Trasandino', 'trasandino', '/assets/escudos/trasandino.png', 'Valparaíso', 'regional'),
  (35, 'Universidad Católica', 'universidad-catolica', '/assets/escudos/universidad-catolica.png', 'RM', 'nacional'),
  (36, 'Universidad de Chile', 'universidad-de-chile', '/assets/escudos/universidad-de-chile.png', 'RM', 'nacional'),
  (37, 'Universidad de Concepción', 'universidad-de-concepcion', '/assets/escudos/universidad-de-concepcion.png', 'Biobío', 'nacional'),
  (38, 'Unión Española', 'union-espanola', '/assets/escudos/union-espanola.png', 'RM', 'nacional'),
  (39, 'Unión La Calera', 'union-la-calera', '/assets/escudos/union-la-calera.png', 'Valparaíso', 'regional'),
  (40, 'Unión San Felipe', 'union-san-felipe', '/assets/escudos/union-san-felipe.png', 'Valparaíso', 'regional'),
  (41, 'Ñublense', 'nublense', '/assets/escudos/nublense.png', 'Ñuble', 'regional')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre), escudo_url = VALUES(escudo_url), region = VALUES(region), division = VALUES(division);

INSERT INTO jugadores (id, nombre, slug, club_id, categoria_id, posicion, goles, partidos, asistencias, fecha_nacimiento, foto_url) VALUES
  (1, 'Diego Sabando', 'diego-sabando', 1, 13, 'Portero', 0, 11, 0, '2009-03-24', NULL),
  (2, 'Pablo Toledo', 'pablo-toledo', 2, 10, 'Portero', 0, 16, 0, '2006-02-07', NULL),
  (3, 'André Stancampiano', 'andre-stancampiano', 3, 33, 'Portero', 0, 9, 0, '2012-04-15', NULL),
  (4, 'Pablo Martínez', 'pablo-martinez', 4, 21, 'Portero', 0, 8, 0, '2011-05-05', NULL),
  (5, 'Lucas Valdés', 'lucas-valdes', 5, 11, 'Mediocampista', 1, 5, 6, '2011-06-20', NULL),
  (6, 'Felipe Tapia', 'felipe-tapia', 6, 33, 'Portero', 0, 17, 0, '2007-07-03', NULL),
  (7, 'Bruno Pérez', 'bruno-perez', 7, 10, 'Mediocampista', 9, 9, 1, '2009-05-03', NULL),
  (8, 'Isidora Silva', 'isidora-silva', 8, 14, 'Portero', 0, 15, 0, '2013-11-27', NULL),
  (9, 'Nicolás Muñoz', 'nicolas-munoz', 9, 11, 'Mediocampista', 5, 9, 4, '2008-09-24', NULL),
  (10, 'Agustín Muñoz', 'agustin-munoz', 10, 13, 'Delantero', 6, 11, 8, '2011-01-08', NULL),
  (11, 'Fernanda Toledo', 'fernanda-toledo', 11, 13, 'Mediocampista', 6, 11, 1, '2011-04-21', NULL),
  (12, 'Gabriel Morales', 'gabriel-morales', 12, 13, 'Delantero', 2, 11, 2, '2014-09-09', NULL),
  (13, 'Camila Henríquez', 'camila-henriquez', 13, 13, 'Delantero', 14, 15, 5, '2008-09-16', NULL),
  (14, 'Benjamín Valdés', 'benjamin-valdes', 14, 12, 'Portero', 0, 6, 0, '2008-11-14', NULL),
  (15, 'Andrés Díaz', 'andres-diaz', 15, 14, 'Delantero', 6, 17, 8, '2014-01-22', NULL),
  (16, 'Camila Stancampiano', 'camila-stancampiano', 16, 14, 'Mediocampista', 12, 13, 1, '2012-03-15', NULL),
  (17, 'Diego Espinoza', 'diego-espinoza', 17, 11, 'Mediocampista', 8, 8, 8, '2010-11-17', NULL),
  (18, 'Andrés Rojas', 'andres-rojas', 18, 33, 'Defensa', 5, 8, 8, '2006-10-11', NULL),
  (19, 'Gabriel Sabando', 'gabriel-sabando', 19, 14, 'Portero', 0, 14, 0, '2009-01-08', NULL),
  (20, 'Amanda Henríquez', 'amanda-henriquez', 20, 30, 'Portero', 0, 5, 0, '2007-09-25', NULL),
  (21, 'Joaquín González', 'joaquin-gonzalez', 21, 33, 'Delantero', 8, 8, 4, '2012-04-18', NULL),
  (22, 'Sofía Espinoza', 'sofia-espinoza', 22, 20, 'Defensa', 11, 12, 6, '2013-09-15', NULL),
  (23, 'Tomás Silva', 'tomas-silva', 23, 33, 'Defensa', 1, 13, 0, '2009-10-08', NULL),
  (24, 'Diego Díaz', 'diego-diaz', 24, 11, 'Portero', 0, 10, 0, '2006-06-03', NULL),
  (25, 'Álvaro Silva', 'alvaro-silva', 25, 33, 'Mediocampista', 10, 18, 3, '2008-12-19', NULL),
  (26, 'Pablo Castro', 'pablo-castro', 26, 13, 'Defensa', 12, 18, 6, '2007-02-22', NULL),
  (27, 'Vicente Contreras', 'vicente-contreras', 27, 11, 'Delantero', 6, 17, 0, '2006-07-24', NULL),
  (28, 'Sebastián Tapia', 'sebastian-tapia', 28, 13, 'Portero', 0, 10, 0, '2009-09-15', NULL),
  (29, 'Joaquín Fuentes', 'joaquin-fuentes', 29, 11, 'Defensa', 4, 17, 3, '2013-09-04', NULL),
  (30, 'Matías Pizarro', 'matias-pizarro', 30, 13, 'Portero', 0, 5, 0, '2008-07-16', NULL),
  (31, 'Gabriel Rojas', 'gabriel-rojas', 31, 21, 'Delantero', 14, 4, 2, '2006-07-09', NULL),
  (32, 'Catalina Tapia', 'catalina-tapia', 32, 30, 'Delantero', 4, 16, 8, '2008-04-10', NULL),
  (33, 'Lucas Toledo', 'lucas-toledo', 33, 10, 'Portero', 0, 13, 0, '2006-10-16', NULL),
  (34, 'Álvaro Olivares', 'alvaro-olivares', 34, 11, 'Defensa', 0, 5, 2, '2007-11-28', NULL),
  (35, 'Agustín Morales', 'agustin-morales', 35, 10, 'Portero', 0, 10, 0, '2007-07-22', NULL),
  (36, 'Pablo Henríquez', 'pablo-henriquez', 36, 20, 'Mediocampista', 14, 11, 3, '2009-05-13', NULL),
  (37, 'Joaquín Araya', 'joaquin-araya', 37, 10, 'Mediocampista', 7, 13, 1, '2013-10-19', NULL),
  (38, 'Tomás Díaz', 'tomas-diaz', 38, 20, 'Defensa', 8, 11, 2, '2007-04-12', NULL),
  (39, 'Ignacio Muñoz', 'ignacio-munoz', 39, 10, 'Delantero', 13, 12, 8, '2014-05-22', NULL),
  (40, 'Tomás Maldonado', 'tomas-maldonado', 40, 33, 'Defensa', 4, 6, 1, '2008-05-10', NULL)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre), club_id = VALUES(club_id), goles = VALUES(goles), partidos = VALUES(partidos);

INSERT INTO posiciones (categoria_id, torneo, club, club_id, pts, pj, pg, pe, pp, gf, gc, dg, fecha_corte) VALUES
  (10, 'Clausura Formativo Sub-20', 'Audax Italiano', 2, 29, 10, 9, 2, 0, 21, 7, 14, CURDATE()),
  (10, 'Clausura Formativo Sub-20', 'Cobreloa', 3, 25, 10, 8, 1, 1, 20, 8, 12, CURDATE()),
  (10, 'Clausura Formativo Sub-20', 'Colo-Colo', 6, 23, 10, 7, 2, 1, 20, 9, 11, CURDATE()),
  (10, 'Clausura Formativo Sub-20', 'Coquimbo Unido', 7, 19, 10, 6, 1, 3, 19, 8, 11, CURDATE()),
  (10, 'Clausura Formativo Sub-20', 'Deportes Iquique', 12, 15, 10, 5, 0, 5, 19, 10, 9, CURDATE()),
  (10, 'Clausura Formativo Sub-20', 'Deportes Recoleta', 17, 12, 10, 4, 0, 6, 15, 11, 4, CURDATE()),
  (10, 'Clausura Formativo Sub-20', 'Deportes Temuco', 20, 9, 10, 3, 0, 7, 16, 11, 5, CURDATE()),
  (10, 'Clausura Formativo Sub-20', 'Everton', 21, 8, 10, 2, 2, 6, 16, 14, 2, CURDATE()),
  (10, 'Clausura Formativo Sub-20', 'Huachipato', 22, 5, 10, 1, 2, 7, 15, 15, 0, CURDATE()),
  (10, 'Clausura Formativo Sub-20', 'O''Higgins', 24, 0, 10, 0, 0, 10, 11, 14, -3, CURDATE()),
  (10, 'Clausura Formativo Sub-20', 'Palestino', 25, 2, 10, 0, 2, 8, 11, 17, -6, CURDATE()),
  (10, 'Clausura Formativo Sub-20', 'Santiago Wanderers', 33, 0, 10, 0, 0, 10, 11, 18, -7, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Atlético Colina', 1, 25, 10, 8, 1, 1, 18, 6, 12, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Cobresal', 4, 22, 10, 7, 1, 2, 17, 7, 10, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Colchagua', 5, 19, 10, 6, 1, 3, 16, 8, 8, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Curicó Unido', 8, 16, 10, 5, 1, 4, 15, 9, 6, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Deportes Antofagasta', 9, 13, 10, 4, 1, 5, 14, 10, 4, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Deportes Concepción', 10, 10, 10, 3, 1, 6, 13, 11, 2, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Deportes Copiapó', 11, 7, 10, 2, 1, 7, 12, 12, 0, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Deportes La Serena', 13, 4, 10, 1, 1, 8, 11, 13, -2, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Deportes Limache', 14, 1, 10, 0, 1, 9, 10, 14, -4, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Deportes Linares', 15, 1, 10, 0, 1, 9, 9, 15, -6, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Deportes Puerto Montt', 16, 1, 10, 0, 1, 9, 8, 16, -8, CURDATE()),
  (20, 'Campeonato Regional Sub-20', 'Deportes Rengo', 18, 1, 10, 0, 1, 9, 7, 17, -10, CURDATE());

INSERT INTO goleadores (categoria_id, torneo, jugador, jugador_id, club, club_id, goles, partidos, fecha_corte) VALUES
  (10, 'Tabla goleadores', 'Camila Henríquez', 13, 'Deportes La Serena', 13, 14, 15, CURDATE()),
  (20, 'Tabla goleadores', 'Gabriel Rojas', 31, 'Santiago City', 31, 14, 4, CURDATE()),
  (20, 'Tabla goleadores', 'Pablo Henríquez', 36, 'Universidad de Chile', 36, 14, 11, CURDATE()),
  (10, 'Tabla goleadores', 'Ignacio Muñoz', 39, 'Unión La Calera', 39, 13, 12, CURDATE()),
  (10, 'Tabla goleadores', 'Camila Stancampiano', 16, 'Deportes Puerto Montt', 16, 12, 13, CURDATE()),
  (10, 'Tabla goleadores', 'Pablo Castro', 26, 'Provincial Osorno', 26, 12, 18, CURDATE()),
  (20, 'Tabla goleadores', 'Sofía Espinoza', 22, 'Huachipato', 22, 11, 12, CURDATE()),
  (33, 'Tabla goleadores', 'Álvaro Silva', 25, 'Palestino', 25, 10, 18, CURDATE()),
  (10, 'Tabla goleadores', 'Bruno Pérez', 7, 'Coquimbo Unido', 7, 9, 9, CURDATE()),
  (10, 'Tabla goleadores', 'Diego Espinoza', 17, 'Deportes Recoleta', 17, 8, 8, CURDATE()),
  (33, 'Tabla goleadores', 'Joaquín González', 21, 'Everton', 21, 8, 8, CURDATE()),
  (20, 'Tabla goleadores', 'Tomás Díaz', 38, 'Unión Española', 38, 8, 11, CURDATE()),
  (10, 'Tabla goleadores', 'Joaquín Araya', 37, 'Universidad de Concepción', 37, 7, 13, CURDATE()),
  (10, 'Tabla goleadores', 'Agustín Muñoz', 10, 'Deportes Concepción', 10, 6, 11, CURDATE()),
  (10, 'Tabla goleadores', 'Fernanda Toledo', 11, 'Deportes Copiapó', 11, 6, 11, CURDATE()),
  (10, 'Tabla goleadores', 'Andrés Díaz', 15, 'Deportes Linares', 15, 6, 17, CURDATE()),
  (10, 'Tabla goleadores', 'Vicente Contreras', 27, 'Rangers', 27, 6, 17, CURDATE()),
  (10, 'Tabla goleadores', 'Nicolás Muñoz', 9, 'Deportes Antofagasta', 9, 5, 9, CURDATE()),
  (33, 'Tabla goleadores', 'Andrés Rojas', 18, 'Deportes Rengo', 18, 5, 8, CURDATE()),
  (10, 'Tabla goleadores', 'Joaquín Fuentes', 29, 'San Luis', 29, 4, 17, CURDATE()),
  (33, 'Tabla goleadores', 'Catalina Tapia', 32, 'Santiago Morning', 32, 4, 16, CURDATE()),
  (33, 'Tabla goleadores', 'Tomás Maldonado', 40, 'Unión San Felipe', 40, 4, 6, CURDATE()),
  (10, 'Tabla goleadores', 'Gabriel Morales', 12, 'Deportes Iquique', 12, 2, 11, CURDATE()),
  (10, 'Tabla goleadores', 'Lucas Valdés', 5, 'Colchagua', 5, 1, 5, CURDATE()),
  (33, 'Tabla goleadores', 'Tomás Silva', 23, 'Magallanes', 23, 1, 13, CURDATE());

INSERT INTO programacion (categoria_id, fecha, hora, local, visita, recinto, club_local_id, club_visita_id) VALUES
  (10, '2026-08-05', '15:00:00', 'Audax Italiano', 'Coquimbo Unido', 'Estadio local', 2, 7),
  (10, '2026-08-08', '17:30:00', 'Cobreloa', 'Deportes Iquique', 'Estadio local', 3, 12),
  (10, '2026-08-11', '15:00:00', 'Colo-Colo', 'Deportes Recoleta', 'Estadio local', 6, 17),
  (10, '2026-08-14', '17:30:00', 'Coquimbo Unido', 'Deportes Temuco', 'Estadio local', 7, 20),
  (10, '2026-08-17', '15:00:00', 'Deportes Iquique', 'Everton', 'Estadio local', 12, 21),
  (10, '2026-08-20', '17:30:00', 'Deportes Recoleta', 'Huachipato', 'Estadio local', 17, 22),
  (10, '2026-08-23', '15:00:00', 'Deportes Temuco', 'O''Higgins', 'Estadio local', 20, 24),
  (10, '2026-08-26', '17:30:00', 'Everton', 'Palestino', 'Estadio local', 21, 25);

INSERT INTO noticias (titulo, slug, extracto, contenido, categoria_id, autor_id, estado, destacada, destacada_orden, imagen_destacada_url, imagen_alt, meta_titulo, meta_descripcion, fecha_publicacion, origen) VALUES
  ('Cobreloa suma puntos clave en Sub-20 Nacional', 'cobreloa-suma-puntos-clave-en-sub-20-nacional', 'Cobreloa suma puntos clave en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Cobreloa suma puntos clave en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Cobreloa</strong> sigue dando frutos. <strong>Pablo Martínez</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 11, 1, 'publicado', 1, 1, '/assets/brand/goleadores-regional.jpg', 'Cobreloa suma puntos clave en Sub-20 Nacional', 'Cobreloa suma puntos clave en Sub-20 Nacional | ChilenosProyección', 'Cobreloa suma puntos clave en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 5 HOUR, 'manual'),
  ('Debut prometedor: Bruno Pérez en Colchagua', 'debut-prometedor-bruno-perez-en-colchagua', 'Debut prometedor: Bruno Pérez en Colchagua. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Debut prometedor: Bruno Pérez en Colchagua. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Colchagua</strong> sigue dando frutos. <strong>Bruno Pérez</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 12, 1, 'publicado', 1, 2, '/assets/brand/goleadores-sub14.jpg', 'Debut prometedor: Bruno Pérez en Colchagua', 'Debut prometedor: Bruno Pérez en Colchagua | ChilenosProyección', 'Debut prometedor: Bruno Pérez en Colchagua. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 10 HOUR, 'manual'),
  ('Tabla apretada: Coquimbo Unido pelea la punta del Regional Sub-20', 'tabla-apretada-coquimbo-unido-pelea-la-punta-del-regional-sub-20', 'Tabla apretada: Coquimbo Unido pelea la punta del Regional Sub-20. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Tabla apretada: Coquimbo Unido pelea la punta del Regional Sub-20. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Coquimbo Unido</strong> sigue dando frutos. <strong>Agustín Muñoz</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 13, 1, 'publicado', 1, 3, '/assets/brand/goleadores-proyeccion.jpg', 'Tabla apretada: Coquimbo Unido pelea la punta del Regional Sub-20', 'Tabla apretada: Coquimbo Unido pelea la punta del Regional Sub-20 | ChilenosProyección', 'Tabla apretada: Coquimbo Unido pelea la punta del Regional Sub-20. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 15 HOUR, 'manual'),
  ('Entrevista: la mirada de Camila Henríquez en formativas', 'entrevista-la-mirada-de-camila-henriquez-en-formativas', 'Entrevista: la mirada de Camila Henríquez en formativas. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Entrevista: la mirada de Camila Henríquez en formativas. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Deportes Antofagasta</strong> sigue dando frutos. <strong>Camila Henríquez</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 20, 2, 'publicado', 1, 4, '/assets/brand/goleadores-regional.jpg', 'Entrevista: la mirada de Camila Henríquez en formativas', 'Entrevista: la mirada de Camila Henríquez en formativas | ChilenosProyección', 'Entrevista: la mirada de Camila Henríquez en formativas. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 20 HOUR, 'hermes'),
  ('Deportes Copiapó y el trabajo diario de cantera', 'deportes-copiapo-y-el-trabajo-diario-de-cantera', 'Deportes Copiapó y el trabajo diario de cantera. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Deportes Copiapó y el trabajo diario de cantera. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Deportes Copiapó</strong> sigue dando frutos. <strong>Camila Stancampiano</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 33, 1, 'publicado', 1, 5, '/assets/brand/portada-preview.jpg', 'Deportes Copiapó y el trabajo diario de cantera', 'Deportes Copiapó y el trabajo diario de cantera | ChilenosProyección', 'Deportes Copiapó y el trabajo diario de cantera. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 25 HOUR, 'manual'),
  ('Fichaje formativo: Gabriel Sabando refuerza a Deportes La Serena', 'fichaje-formativo-gabriel-sabando-refuerza-a-deportes-la-serena', 'Fichaje formativo: Gabriel Sabando refuerza a Deportes La Serena. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Fichaje formativo: Gabriel Sabando refuerza a Deportes La Serena. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Deportes La Serena</strong> sigue dando frutos. <strong>Gabriel Sabando</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 14, 1, 'publicado', 0, 0, '/assets/brand/goleadores-sub20.jpg', 'Fichaje formativo: Gabriel Sabando refuerza a Deportes La Serena', 'Fichaje formativo: Gabriel Sabando refuerza a Deportes La Serena | ChilenosProyección', 'Fichaje formativo: Gabriel Sabando refuerza a Deportes La Serena. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 30 HOUR, 'manual'),
  ('Crónica: Deportes Linares vs rival en Sub-20 Nacional', 'cronica-deportes-linares-vs-rival-en-sub-20-nacional', 'Crónica: Deportes Linares vs rival en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Crónica: Deportes Linares vs rival en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Deportes Linares</strong> sigue dando frutos. <strong>Sofía Espinoza</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 10, 1, 'publicado', 0, 0, '/assets/brand/goleadores-regional.jpg', 'Crónica: Deportes Linares vs rival en Sub-20 Nacional', 'Crónica: Deportes Linares vs rival en Sub-20 Nacional | ChilenosProyección', 'Crónica: Deportes Linares vs rival en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 35 HOUR, 'manual'),
  ('Proyección: Álvaro Silva en la mira de la selección juvenil', 'proyeccion-alvaro-silva-en-la-mira-de-la-seleccion-juvenil', 'Proyección: Álvaro Silva en la mira de la selección juvenil. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Proyección: Álvaro Silva en la mira de la selección juvenil. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Deportes Recoleta</strong> sigue dando frutos. <strong>Álvaro Silva</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 11, 2, 'publicado', 0, 0, '/assets/brand/goleadores-sub14.jpg', 'Proyección: Álvaro Silva en la mira de la selección juvenil', 'Proyección: Álvaro Silva en la mira de la selección juvenil | ChilenosProyección', 'Proyección: Álvaro Silva en la mira de la selección juvenil. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 40 HOUR, 'hermes'),
  ('Goleada de Deportes Santa Cruz: el Sub brilla otra vez', 'goleada-de-deportes-santa-cruz-el-sub-brilla-otra-vez', 'Goleada de Deportes Santa Cruz: el Sub brilla otra vez. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Goleada de Deportes Santa Cruz: el Sub brilla otra vez. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Deportes Santa Cruz</strong> sigue dando frutos. <strong>Sebastián Tapia</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 12, 1, 'publicado', 0, 0, '/assets/brand/goleadores-proyeccion.jpg', 'Goleada de Deportes Santa Cruz: el Sub brilla otra vez', 'Goleada de Deportes Santa Cruz: el Sub brilla otra vez | ChilenosProyección', 'Goleada de Deportes Santa Cruz: el Sub brilla otra vez. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 45 HOUR, 'manual'),
  ('Gabriel Rojas lidera el goleo en Sub-20 Nacional', 'gabriel-rojas-lidera-el-goleo-en-sub-20-nacional', 'Gabriel Rojas lidera el goleo en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Gabriel Rojas lidera el goleo en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Everton</strong> sigue dando frutos. <strong>Gabriel Rojas</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 13, 1, 'publicado', 0, 0, '/assets/brand/goleadores-regional.jpg', 'Gabriel Rojas lidera el goleo en Sub-20 Nacional', 'Gabriel Rojas lidera el goleo en Sub-20 Nacional | ChilenosProyección', 'Gabriel Rojas lidera el goleo en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 50 HOUR, 'manual'),
  ('Magallanes suma puntos clave en Sub-20 Nacional', 'magallanes-suma-puntos-clave-en-sub-20-nacional', 'Magallanes suma puntos clave en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Magallanes suma puntos clave en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Magallanes</strong> sigue dando frutos. <strong>Álvaro Olivares</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 20, 1, 'publicado', 0, 0, '/assets/brand/portada-preview.jpg', 'Magallanes suma puntos clave en Sub-20 Nacional', 'Magallanes suma puntos clave en Sub-20 Nacional | ChilenosProyección', 'Magallanes suma puntos clave en Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 55 HOUR, 'manual'),
  ('Debut prometedor: Joaquín Araya en Palestino', 'debut-prometedor-joaquin-araya-en-palestino', 'Debut prometedor: Joaquín Araya en Palestino. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Debut prometedor: Joaquín Araya en Palestino. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Palestino</strong> sigue dando frutos. <strong>Joaquín Araya</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 33, 2, 'publicado', 0, 0, '/assets/brand/goleadores-sub20.jpg', 'Debut prometedor: Joaquín Araya en Palestino', 'Debut prometedor: Joaquín Araya en Palestino | ChilenosProyección', 'Debut prometedor: Joaquín Araya en Palestino. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 60 HOUR, 'hermes'),
  ('Tabla apretada: Rangers pelea la punta del Sub-20 Nacional', 'tabla-apretada-rangers-pelea-la-punta-del-sub-20-nacional', 'Tabla apretada: Rangers pelea la punta del Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Tabla apretada: Rangers pelea la punta del Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Rangers</strong> sigue dando frutos. <strong>Tomás Maldonado</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 14, 1, 'publicado', 0, 0, '/assets/brand/goleadores-regional.jpg', 'Tabla apretada: Rangers pelea la punta del Sub-20 Nacional', 'Tabla apretada: Rangers pelea la punta del Sub-20 Nacional | ChilenosProyección', 'Tabla apretada: Rangers pelea la punta del Sub-20 Nacional. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 65 HOUR, 'manual'),
  ('Entrevista: la mirada de André Stancampiano en formativas', 'entrevista-la-mirada-de-andre-stancampiano-en-formativas', 'Entrevista: la mirada de André Stancampiano en formativas. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Entrevista: la mirada de André Stancampiano en formativas. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>San Luis</strong> sigue dando frutos. <strong>André Stancampiano</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 10, 1, 'publicado', 0, 0, '/assets/brand/goleadores-sub14.jpg', 'Entrevista: la mirada de André Stancampiano en formativas', 'Entrevista: la mirada de André Stancampiano en formativas | ChilenosProyección', 'Entrevista: la mirada de André Stancampiano en formativas. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 70 HOUR, 'manual'),
  ('Santiago City y el trabajo diario de cantera', 'santiago-city-y-el-trabajo-diario-de-cantera', 'Santiago City y el trabajo diario de cantera. Cobertura ChilenosProyección con foco en fútbol joven chileno.', '<p>Santiago City y el trabajo diario de cantera. Cobertura ChilenosProyección con foco en fútbol joven chileno.</p><h2>Lo que pasó en la cancha</h2><p>El trabajo de cantera de <strong>Santiago City</strong> sigue dando frutos. <strong>Felipe Tapia</strong> fue figura con números que llaman la atención en formativas.</p><h2>Proyección</h2><p>Desde la redacción seguimos de cerca el desarrollo de las categorías Sub-20 a Infantil, con tablas, goleadores y crónicas de fin de semana.</p>', 11, 1, 'publicado', 0, 0, '/assets/brand/goleadores-proyeccion.jpg', 'Santiago City y el trabajo diario de cantera', 'Santiago City y el trabajo diario de cantera | ChilenosProyección', 'Santiago City y el trabajo diario de cantera. Cobertura ChilenosProyección con foco en fútbol joven chileno.', NOW() - INTERVAL 75 HOUR, 'manual');

INSERT INTO noticia_tag (noticia_id, tag_id)
SELECT n.id, ((n.id % 8) + 1) FROM noticias n
ON DUPLICATE KEY UPDATE noticia_id = noticia_id;

INSERT INTO noticia_club (noticia_id, club_id)
SELECT n.id, ((n.id % 41) + 1) FROM noticias n
ON DUPLICATE KEY UPDATE noticia_id = noticia_id;

INSERT INTO entrevistas (titulo, slug, extracto, cuerpo, jugador_id, fecha_publicacion, imagen_url, video_url, estado) VALUES
  ('Entrevista a Pablo Toledo: el día a día en formativas', 'entrevista-a-pablo-toledo-el-dia-a-dia-en-formativas', 'Pablo Toledo abre la puerta de la cantera y habla de competencia y proyección.', '<p>Conversamos con <strong>Pablo Toledo</strong> sobre su rol en el plantel, la competencia y su proyección.</p><p>"Trabajo, humildad y ganas de aprender", resume el juvenil.</p>', 2, NOW() - INTERVAL 1 DAY, '/assets/brand/portada-preview.jpg', NULL, 'publicado'),
  ('Entrevista a André Stancampiano: el día a día en formativas', 'entrevista-a-andre-stancampiano-el-dia-a-dia-en-formativas', 'André Stancampiano abre la puerta de la cantera y habla de competencia y proyección.', '<p>Conversamos con <strong>André Stancampiano</strong> sobre su rol en el plantel, la competencia y su proyección.</p><p>"Trabajo, humildad y ganas de aprender", resume el juvenil.</p>', 3, NOW() - INTERVAL 2 DAY, '/assets/brand/portada-preview.jpg', NULL, 'publicado'),
  ('Entrevista a Pablo Martínez: el día a día en formativas', 'entrevista-a-pablo-martinez-el-dia-a-dia-en-formativas', 'Pablo Martínez abre la puerta de la cantera y habla de competencia y proyección.', '<p>Conversamos con <strong>Pablo Martínez</strong> sobre su rol en el plantel, la competencia y su proyección.</p><p>"Trabajo, humildad y ganas de aprender", resume el juvenil.</p>', 4, NOW() - INTERVAL 3 DAY, '/assets/brand/portada-preview.jpg', NULL, 'publicado'),
  ('Entrevista a Lucas Valdés: el día a día en formativas', 'entrevista-a-lucas-valdes-el-dia-a-dia-en-formativas', 'Lucas Valdés abre la puerta de la cantera y habla de competencia y proyección.', '<p>Conversamos con <strong>Lucas Valdés</strong> sobre su rol en el plantel, la competencia y su proyección.</p><p>"Trabajo, humildad y ganas de aprender", resume el juvenil.</p>', 5, NOW() - INTERVAL 4 DAY, '/assets/brand/portada-preview.jpg', NULL, 'publicado'),
  ('Entrevista a Felipe Tapia: el día a día en formativas', 'entrevista-a-felipe-tapia-el-dia-a-dia-en-formativas', 'Felipe Tapia abre la puerta de la cantera y habla de competencia y proyección.', '<p>Conversamos con <strong>Felipe Tapia</strong> sobre su rol en el plantel, la competencia y su proyección.</p><p>"Trabajo, humildad y ganas de aprender", resume el juvenil.</p>', 6, NOW() - INTERVAL 5 DAY, '/assets/brand/portada-preview.jpg', NULL, 'publicado');

INSERT INTO usuarios_admin (nombre, email, password_hash, rol) VALUES
  ('Admin', 'admin@chilenosproyeccion.cl', '$2b$10$qbYkUevJKWX84azqNVzoy.mio1J94XghIsm5alelevXduXV7r8rEK', 'admin')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

SET FOREIGN_KEY_CHECKS = 1;
