<?php
declare(strict_types=1);

final class ClubModel
{
    public static function porSlug(string $slug): ?array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            $demo = self::demoMap();
            return $demo[$slug] ?? null;
        }
        try {
            $st = $pdo->prepare('SELECT * FROM clubes WHERE slug = ? LIMIT 1');
            $st->execute([$slug]);
            $row = $st->fetch();
            return $row ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public static function listar(?string $division = null, int $limit = 50): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            $all = array_values(self::demoMap());
            if ($division) {
                $all = array_values(array_filter($all, static fn ($c) => ($c['division'] ?? '') === $division));
            }
            return array_slice($all, 0, $limit);
        }
        try {
            if ($division) {
                $st = $pdo->prepare('SELECT * FROM clubes WHERE division = ? ORDER BY nombre ASC LIMIT ' . (int) $limit);
                $st->execute([$division]);
            } else {
                $st = $pdo->query('SELECT * FROM clubes ORDER BY nombre ASC LIMIT ' . (int) $limit);
            }
            return $st->fetchAll() ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function noticias(int $clubId, int $limit = 8): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            return array_slice(demo_noticias_destacadas(), 0, $limit);
        }
        try {
            $st = $pdo->prepare(
                "SELECT n.*, c.slug AS categoria_slug, c.nombre AS categoria_nombre
                 FROM noticias n
                 INNER JOIN noticia_club nc ON nc.noticia_id = n.id
                 LEFT JOIN categorias c ON c.id = n.categoria_id
                 WHERE nc.club_id = ? AND n.estado = 'publicado'
                 ORDER BY n.fecha_publicacion DESC
                 LIMIT " . (int) $limit
            );
            $st->execute([$clubId]);
            return $st->fetchAll() ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function plantel(int $clubId, int $limit = 30): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            return [
                ['nombre' => 'Diego Sabando', 'slug' => 'diego-sabando', 'posicion' => 'Delantero', 'goles' => 8, 'partidos' => 12],
            ];
        }
        try {
            $st = $pdo->prepare(
                'SELECT * FROM jugadores WHERE club_id = ? ORDER BY goles DESC, nombre ASC LIMIT ' . (int) $limit
            );
            $st->execute([$clubId]);
            return $st->fetchAll() ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function proximoPartido(int $clubId): ?array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            return [
                'fecha' => date('Y-m-d', strtotime('+3 days')),
                'hora' => '15:00:00',
                'local' => 'Universidad de Concepción',
                'visita' => 'Palestino',
                'recinto' => 'Ester Roa',
            ];
        }
        try {
            $st = $pdo->prepare(
                "SELECT * FROM programacion
                 WHERE (club_local_id = ? OR club_visita_id = ?)
                   AND fecha >= CURDATE()
                 ORDER BY fecha ASC, hora ASC
                 LIMIT 1"
            );
            $st->execute([$clubId, $clubId]);
            $row = $st->fetch();
            return $row ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public static function escudoUrl(?array $club): string
    {
        if ($club && !empty($club['escudo_url'])) {
            return (string) $club['escudo_url'];
        }
        if ($club && !empty($club['slug'])) {
            foreach (['png', 'webp', 'jpg'] as $ext) {
                $rel = '/assets/escudos/' . $club['slug'] . '.' . $ext;
                $abs = PUBLIC_PATH . $rel;
                if (is_file($abs)) {
                    return $rel;
                }
            }
        }
        return '/assets/brand/logo-cp.png';
    }

    private static function demoMap(): array
    {
        return [
            'universidad-de-concepcion' => [
                'id' => 1,
                'nombre' => 'Universidad de Concepción',
                'slug' => 'universidad-de-concepcion',
                'region' => 'Biobío',
                'division' => 'nacional',
                'escudo_url' => '/assets/escudos/universidad-de-concepcion.png',
            ],
            'palestino' => [
                'id' => 2,
                'nombre' => 'Palestino',
                'slug' => 'palestino',
                'region' => 'RM',
                'division' => 'nacional',
                'escudo_url' => '/assets/escudos/palestino.png',
            ],
            'colo-colo' => [
                'id' => 3,
                'nombre' => 'Colo-Colo',
                'slug' => 'colo-colo',
                'region' => 'RM',
                'division' => 'nacional',
                'escudo_url' => '/assets/escudos/colo-colo.png',
            ],
            'nublense' => [
                'id' => 4,
                'nombre' => 'Ñublense',
                'slug' => 'nublense',
                'region' => 'Ñuble',
                'division' => 'regional',
                'escudo_url' => '/assets/escudos/nublense.png',
            ],
        ];
    }
}
