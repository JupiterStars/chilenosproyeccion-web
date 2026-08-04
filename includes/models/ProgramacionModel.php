<?php
declare(strict_types=1);

final class ProgramacionModel
{
    public static function porCategoria(?string $slug): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            return self::withEscudos(self::demo($slug));
        }
        try {
            if ($slug) {
                $st = $pdo->prepare(
                    "SELECT p.*,
                            cl.slug AS club_local_slug, cl.escudo_url AS escudo_local_db,
                            cv.slug AS club_visita_slug, cv.escudo_url AS escudo_visita_db
                     FROM programacion p
                     LEFT JOIN categorias c ON c.id = p.categoria_id
                     LEFT JOIN clubes cl ON cl.id = p.club_local_id
                     LEFT JOIN clubes cv ON cv.id = p.club_visita_id
                     WHERE c.slug = ?
                     ORDER BY p.fecha, p.hora"
                );
                $st->execute([$slug]);
            } else {
                $st = $pdo->query(
                    "SELECT p.*,
                            cl.slug AS club_local_slug, cl.escudo_url AS escudo_local_db,
                            cv.slug AS club_visita_slug, cv.escudo_url AS escudo_visita_db
                     FROM programacion p
                     LEFT JOIN clubes cl ON cl.id = p.club_local_id
                     LEFT JOIN clubes cv ON cv.id = p.club_visita_id
                     ORDER BY p.fecha, p.hora
                     LIMIT 60"
                );
            }
            $rows = $st->fetchAll() ?: [];
            return self::withEscudos($rows ?: self::demo($slug));
        } catch (Throwable $e) {
            return self::withEscudos(self::demo($slug));
        }
    }

    /**
     * Partidos de una categoría en una fecha concreta (incluye fechas sin partidos → []).
     * @return list<array<string,mixed>>
     */
    public static function porCategoriaYFecha(?string $slug, string $fecha): array
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            return [];
        }

        $pdo = Database::pdo();
        if (!$pdo) {
            $all = self::withEscudos(self::demo($slug));
            return array_values(array_filter(
                $all,
                static fn (array $r): bool => (string) ($r['fecha'] ?? '') === $fecha
            ));
        }
        try {
            if ($slug) {
                $st = $pdo->prepare(
                    "SELECT p.*,
                            cl.slug AS club_local_slug, cl.escudo_url AS escudo_local_db,
                            cv.slug AS club_visita_slug, cv.escudo_url AS escudo_visita_db
                     FROM programacion p
                     LEFT JOIN categorias c ON c.id = p.categoria_id
                     LEFT JOIN clubes cl ON cl.id = p.club_local_id
                     LEFT JOIN clubes cv ON cv.id = p.club_visita_id
                     WHERE c.slug = ? AND p.fecha = ?
                     ORDER BY p.hora, p.local"
                );
                $st->execute([$slug, $fecha]);
            } else {
                $st = $pdo->prepare(
                    "SELECT p.*,
                            cl.slug AS club_local_slug, cl.escudo_url AS escudo_local_db,
                            cv.slug AS club_visita_slug, cv.escudo_url AS escudo_visita_db
                     FROM programacion p
                     LEFT JOIN clubes cl ON cl.id = p.club_local_id
                     LEFT JOIN clubes cv ON cv.id = p.club_visita_id
                     WHERE p.fecha = ?
                     ORDER BY p.hora, p.local
                     LIMIT 40"
                );
                $st->execute([$fecha]);
            }
            $rows = $st->fetchAll() ?: [];
            if ($rows) {
                return self::withEscudos($rows);
            }
            // Fallback demo solo si hay partidos demo en esa fecha
            $demo = self::withEscudos(self::demo($slug));
            return array_values(array_filter(
                $demo,
                static fn (array $r): bool => (string) ($r['fecha'] ?? '') === $fecha
            ));
        } catch (Throwable $e) {
            $demo = self::withEscudos(self::demo($slug));
            return array_values(array_filter(
                $demo,
                static fn (array $r): bool => (string) ($r['fecha'] ?? '') === $fecha
            ));
        }
    }

    /**
     * Fechas distintas con partidos para una categoría (orden ascendente).
     * @return list<string> Y-m-d
     */
    public static function fechasDisponibles(?string $slug): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            $fechas = [];
            foreach (self::demo($slug) as $r) {
                $f = (string) ($r['fecha'] ?? '');
                if ($f !== '') {
                    $fechas[$f] = true;
                }
            }
            $out = array_keys($fechas);
            sort($out);
            return $out;
        }
        try {
            if ($slug) {
                $st = $pdo->prepare(
                    "SELECT DISTINCT p.fecha
                     FROM programacion p
                     LEFT JOIN categorias c ON c.id = p.categoria_id
                     WHERE c.slug = ?
                     ORDER BY p.fecha"
                );
                $st->execute([$slug]);
            } else {
                $st = $pdo->query(
                    'SELECT DISTINCT fecha FROM programacion ORDER BY fecha'
                );
            }
            $rows = $st->fetchAll() ?: [];
            $out = [];
            foreach ($rows as $r) {
                $f = (string) ($r['fecha'] ?? '');
                if ($f !== '') {
                    $out[] = $f;
                }
            }
            if ($out) {
                return $out;
            }
            // demo
            $fechas = [];
            foreach (self::demo($slug) as $r) {
                $f = (string) ($r['fecha'] ?? '');
                if ($f !== '') {
                    $fechas[$f] = true;
                }
            }
            $out = array_keys($fechas);
            sort($out);
            return $out;
        } catch (Throwable $e) {
            $fechas = [];
            foreach (self::demo($slug) as $r) {
                $f = (string) ($r['fecha'] ?? '');
                if ($f !== '') {
                    $fechas[$f] = true;
                }
            }
            $out = array_keys($fechas);
            sort($out);
            return $out;
        }
    }

    /**
     * Extiende el listado de fechas con jornadas futuras (cada 3–4 días)
     * para poder navegar fechas sin programación aún.
     * @param list<string> $fechas
     * @return list<string>
     */
    public static function enriquecerFechasCalendario(array $fechas, int $minSlots = 10): array
    {
        $set = [];
        foreach ($fechas as $f) {
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $f)) {
                $set[$f] = true;
            }
        }
        if (count($set) >= $minSlots) {
            $out = array_keys($set);
            sort($out);
            return $out;
        }

        // Base: última fecha con datos o hoy
        $base = date('Y-m-d');
        if ($set) {
            $keys = array_keys($set);
            sort($keys);
            $base = end($keys) ?: $base;
        }

        try {
            $dt = new DateTimeImmutable($base);
        } catch (Throwable $e) {
            $dt = new DateTimeImmutable('today');
        }

        // Añadir slots semanales (cada 3–4 días) hacia adelante hasta minSlots
        $i = 0;
        while (count($set) < $minSlots && $i < 40) {
            $i++;
            $add = $i % 2 === 0 ? 4 : 3;
            $dt = $dt->modify('+' . $add . ' days');
            $set[$dt->format('Y-m-d')] = true;
        }

        // Si no había fechas pasadas, incluir algunas previas desde hoy
        if (count($fechas) === 0) {
            try {
                $prev = new DateTimeImmutable('today');
                for ($j = 1; $j <= 3; $j++) {
                    $prev = $prev->modify('-4 days');
                    $set[$prev->format('Y-m-d')] = true;
                }
            } catch (Throwable $e) {
            }
        }

        $out = array_keys($set);
        sort($out);
        return $out;
    }

    public static function tienePartidos(?string $slug, string $fecha): bool
    {
        return count(self::porCategoriaYFecha($slug, $fecha)) > 0;
    }

    /**
     * Normaliza escudos/slugs para la UI (siempre hay path de escudo si el nombre se reconoce).
     * @param list<array<string,mixed>> $rows
     * @return list<array<string,mixed>>
     */
    private static function withEscudos(array $rows): array
    {
        $out = [];
        foreach ($rows as $r) {
            $local = (string) ($r['local'] ?? '');
            $visita = (string) ($r['visita'] ?? '');
            $lSlug = (string) ($r['club_local_slug'] ?? '');
            $vSlug = (string) ($r['club_visita_slug'] ?? '');
            if ($lSlug === '' && $local !== '') {
                $lSlug = slugify($local);
            }
            if ($vSlug === '' && $visita !== '') {
                $vSlug = slugify($visita);
            }
            $escL = (string) ($r['escudo_local'] ?? $r['escudo_local_db'] ?? '');
            $escV = (string) ($r['escudo_visita'] ?? $r['escudo_visita_db'] ?? '');
            if ($escL === '' && $lSlug !== '') {
                $escL = club_escudo_url($lSlug);
            }
            if ($escV === '' && $vSlug !== '') {
                $escV = club_escudo_url($vSlug);
            }
            $r['club_local_slug'] = $lSlug;
            $r['club_visita_slug'] = $vSlug;
            $r['escudo_local'] = $escL;
            $r['escudo_visita'] = $escV;
            $out[] = $r;
        }
        return $out;
    }

    /** @return list<array<string,mixed>> */
    private static function demo(?string $slug): array
    {
        // Varias fechas para poder navegar (algunas con partidos, otras no se listan aquí)
        $d1 = date('Y-m-d', strtotime('+2 days'));
        $d2 = date('Y-m-d', strtotime('+5 days'));
        $d3 = date('Y-m-d', strtotime('+8 days'));
        $d4 = date('Y-m-d', strtotime('+12 days'));

        return [
            [
                'fecha' => $d1,
                'hora' => '15:00:00',
                'local' => 'Universidad de Concepción',
                'visita' => 'Palestino',
                'recinto' => 'Ester Roa',
                'club_local_slug' => 'universidad-de-concepcion',
                'club_visita_slug' => 'palestino',
            ],
            [
                'fecha' => $d1,
                'hora' => '17:30:00',
                'local' => 'Colo-Colo',
                'visita' => 'Everton',
                'recinto' => 'Monumental',
                'club_local_slug' => 'colo-colo',
                'club_visita_slug' => 'everton',
            ],
            [
                'fecha' => $d2,
                'hora' => '11:00:00',
                'local' => 'Universidad de Chile',
                'visita' => 'Cobreloa',
                'recinto' => 'Cancha formativas',
                'club_local_slug' => 'universidad-de-chile',
                'club_visita_slug' => 'cobreloa',
            ],
            [
                'fecha' => $d2,
                'hora' => '16:00:00',
                'local' => 'Huachipato',
                'visita' => 'Universidad Católica',
                'recinto' => 'CAP',
                'club_local_slug' => 'huachipato',
                'club_visita_slug' => 'universidad-catolica',
            ],
            [
                'fecha' => $d3,
                'hora' => '15:00:00',
                'local' => 'Unión Española',
                'visita' => 'Audax Italiano',
                'recinto' => 'Estadio local',
                'club_local_slug' => 'union-espanola',
                'club_visita_slug' => 'audax-italiano',
            ],
            [
                'fecha' => $d3,
                'hora' => '17:30:00',
                'local' => 'Deportes Iquique',
                'visita' => "O'Higgins",
                'recinto' => 'Estadio local',
                'club_local_slug' => 'deportes-iquique',
                'club_visita_slug' => 'o-higgins',
            ],
            [
                'fecha' => $d4,
                'hora' => '15:00:00',
                'local' => 'Santiago Wanderers',
                'visita' => 'Deportes Temuco',
                'recinto' => 'Estadio local',
                'club_local_slug' => 'santiago-wanderers',
                'club_visita_slug' => 'deportes-temuco',
            ],
            [
                'fecha' => $d4,
                'hora' => '17:00:00',
                'local' => 'Coquimbo Unido',
                'visita' => 'Deportes Recoleta',
                'recinto' => 'Estadio local',
                'club_local_slug' => 'coquimbo-unido',
                'club_visita_slug' => 'deportes-recoleta',
            ],
        ];
    }
}
