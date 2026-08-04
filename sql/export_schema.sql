-- ChilenosProyección — export_schema.sql
-- Solo CREATE TABLE + índices. Sin DROP. Sin CREATE DATABASE.
-- Charset utf8mb4 · InnoDB · 2026-08-03

SET NAMES utf8mb4;
SET time_zone = '-04:00';

CREATE TABLE IF NOT EXISTS autores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  bio TEXT NULL,
  avatar_url VARCHAR(500) NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------- categorias ----------
CREATE TABLE IF NOT EXISTS categorias (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL,
  slug VARCHAR(80) NOT NULL,
  descripcion TEXT NULL,
  division ENUM('nacional','regional','infantil','general') NOT NULL DEFAULT 'general',
  parent_id INT UNSIGNED NULL,
  orden INT NOT NULL DEFAULT 0,
  UNIQUE KEY uq_categorias_slug (slug),
  KEY idx_categorias_division (division),
  KEY idx_categorias_parent (parent_id),
  CONSTRAINT fk_categorias_parent FOREIGN KEY (parent_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------- clubes ----------
CREATE TABLE IF NOT EXISTS clubes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  slug VARCHAR(120) NOT NULL,
  escudo_url VARCHAR(500) NULL,
  region VARCHAR(80) NULL,
  division ENUM('nacional','regional','otro') NOT NULL DEFAULT 'nacional',
  UNIQUE KEY uq_clubes_slug (slug),
  KEY idx_clubes_division (division)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------- jugadores ----------
CREATE TABLE IF NOT EXISTS jugadores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  slug VARCHAR(120) NOT NULL,
  club_id INT UNSIGNED NULL,
  categoria_id INT UNSIGNED NULL,
  posicion VARCHAR(40) NULL,
  goles INT UNSIGNED NOT NULL DEFAULT 0,
  partidos INT UNSIGNED NOT NULL DEFAULT 0,
  asistencias INT UNSIGNED NOT NULL DEFAULT 0,
  fecha_nacimiento DATE NULL,
  foto_url VARCHAR(500) NULL,
  UNIQUE KEY uq_jugadores_slug (slug),
  KEY idx_jugadores_club (club_id),
  KEY idx_jugadores_categoria (categoria_id),
  CONSTRAINT fk_jugadores_club FOREIGN KEY (club_id) REFERENCES clubes(id) ON DELETE SET NULL,
  CONSTRAINT fk_jugadores_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------- tags ----------
CREATE TABLE IF NOT EXISTS tags (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL,
  slug VARCHAR(80) NOT NULL,
  UNIQUE KEY uq_tags_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------- noticias ----------
CREATE TABLE IF NOT EXISTS noticias (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  extracto TEXT NULL,
  contenido LONGTEXT NULL,
  categoria_id INT UNSIGNED NULL,
  autor_id INT UNSIGNED NULL,
  estado ENUM('borrador','publicado','archivado') NOT NULL DEFAULT 'borrador',
  destacada TINYINT(1) NOT NULL DEFAULT 0,
  destacada_orden INT NOT NULL DEFAULT 0,
  imagen_destacada_url VARCHAR(500) NULL,
  imagen_alt VARCHAR(255) NULL,
  imagen_credito VARCHAR(255) NULL,
  meta_titulo VARCHAR(255) NULL,
  meta_descripcion VARCHAR(320) NULL,
  fecha_publicacion DATETIME NULL,
  fecha_actualizacion DATETIME NULL,
  vistas INT UNSIGNED NOT NULL DEFAULT 0,
  origen ENUM('manual','hermes') NOT NULL DEFAULT 'manual',
  UNIQUE KEY uq_noticias_slug (slug),
  KEY idx_noticias_estado_fecha (estado, fecha_publicacion),
  KEY idx_noticias_categoria (categoria_id),
  KEY idx_noticias_destacada (destacada, destacada_orden),
  FULLTEXT KEY ft_noticias_busqueda (titulo, extracto, contenido),
  CONSTRAINT fk_noticias_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
  CONSTRAINT fk_noticias_autor FOREIGN KEY (autor_id) REFERENCES autores(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------- puentes ----------
CREATE TABLE IF NOT EXISTS noticia_tag (
  noticia_id INT UNSIGNED NOT NULL,
  tag_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (noticia_id, tag_id),
  CONSTRAINT fk_nt_noticia FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE,
  CONSTRAINT fk_nt_tag FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS noticia_club (
  noticia_id INT UNSIGNED NOT NULL,
  club_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (noticia_id, club_id),
  CONSTRAINT fk_nc_noticia FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE,
  CONSTRAINT fk_nc_club FOREIGN KEY (club_id) REFERENCES clubes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS noticia_jugador (
  noticia_id INT UNSIGNED NOT NULL,
  jugador_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (noticia_id, jugador_id),
  CONSTRAINT fk_nj_noticia FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE,
  CONSTRAINT fk_nj_jugador FOREIGN KEY (jugador_id) REFERENCES jugadores(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------- data tables ----------
CREATE TABLE IF NOT EXISTS goleadores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT UNSIGNED NULL,
  torneo VARCHAR(160) NOT NULL,
  jugador VARCHAR(120) NOT NULL,
  jugador_id INT UNSIGNED NULL,
  club VARCHAR(120) NULL,
  club_id INT UNSIGNED NULL,
  goles INT UNSIGNED NOT NULL DEFAULT 0,
  partidos INT UNSIGNED NOT NULL DEFAULT 0,
  fecha_corte DATE NULL,
  KEY idx_goleadores_cat (categoria_id),
  KEY idx_goleadores_jugador (jugador_id),
  CONSTRAINT fk_goleadores_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
  CONSTRAINT fk_goleadores_jugador FOREIGN KEY (jugador_id) REFERENCES jugadores(id) ON DELETE SET NULL,
  CONSTRAINT fk_goleadores_club FOREIGN KEY (club_id) REFERENCES clubes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS posiciones (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT UNSIGNED NULL,
  torneo VARCHAR(160) NOT NULL,
  club VARCHAR(120) NOT NULL,
  club_id INT UNSIGNED NULL,
  pts INT NOT NULL DEFAULT 0,
  pj INT NOT NULL DEFAULT 0,
  pg INT NOT NULL DEFAULT 0,
  pe INT NOT NULL DEFAULT 0,
  pp INT NOT NULL DEFAULT 0,
  gf INT NOT NULL DEFAULT 0,
  gc INT NOT NULL DEFAULT 0,
  dg INT NOT NULL DEFAULT 0,
  fecha_corte DATE NULL,
  KEY idx_posiciones_cat (categoria_id),
  KEY idx_posiciones_club (club_id),
  CONSTRAINT fk_posiciones_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
  CONSTRAINT fk_posiciones_club FOREIGN KEY (club_id) REFERENCES clubes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS programacion (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT UNSIGNED NULL,
  fecha DATE NOT NULL,
  hora TIME NULL,
  local VARCHAR(120) NOT NULL,
  visita VARCHAR(120) NOT NULL,
  recinto VARCHAR(160) NULL,
  club_local_id INT UNSIGNED NULL,
  club_visita_id INT UNSIGNED NULL,
  KEY idx_programacion_cat_fecha (categoria_id, fecha),
  CONSTRAINT fk_programacion_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
  CONSTRAINT fk_programacion_local FOREIGN KEY (club_local_id) REFERENCES clubes(id) ON DELETE SET NULL,
  CONSTRAINT fk_programacion_visita FOREIGN KEY (club_visita_id) REFERENCES clubes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS entrevistas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  extracto TEXT NULL,
  cuerpo LONGTEXT NULL,
  jugador_id INT UNSIGNED NULL,
  fecha_publicacion DATETIME NULL,
  imagen_url VARCHAR(500) NULL,
  video_url VARCHAR(500) NULL,
  estado ENUM('borrador','publicado','archivado') NOT NULL DEFAULT 'borrador',
  UNIQUE KEY uq_entrevistas_slug (slug),
  KEY idx_entrevistas_jugador (jugador_id),
  KEY idx_entrevistas_fecha (fecha_publicacion),
  CONSTRAINT fk_entrevistas_jugador FOREIGN KEY (jugador_id) REFERENCES jugadores(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS suscriptores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL,
  fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('activo','baja') NOT NULL DEFAULT 'activo',
  UNIQUE KEY uq_suscriptores_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS usuarios_admin (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('admin','editor','redactor') NOT NULL DEFAULT 'editor',
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_admin_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
