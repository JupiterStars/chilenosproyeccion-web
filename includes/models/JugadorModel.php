<?php
declare(strict_types=1);

final class JugadorModel
{
    public static function porSlug(string $slug): ?array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            if ($slug === 'diego-sabando') {
                return [
                    'id' => 1,
                    'nombre' => 'Diego Sabando',
                    'slug' => 'diego-sabando',
                    'club_nombre' => 'Universidad de Concepción',
                    'club_slug' => 'universidad-de-concepcion',
                    'categoria_nombre' => 'Sub-20',
                    'posicion' => 'Delantero',
                    'goles' => 8,
                    'partidos' => 12,
                    'asistencias' => 3,
                    'fecha_nacimiento' => '2006-05-12',
                    'foto_url' => null,
                ];
            }
            return null;
        }
        try {
            $st = $pdo->prepare(
                "SELECT j.*, cl.nombre AS club_nombre, cl.slug AS club_slug, cl.escudo_url,
                        cat.nombre AS categoria_nombre, cat.slug AS categoria_slug
                 FROM jugadores j
                 LEFT JOIN clubes cl ON cl.id = j.club_id
                 LEFT JOIN categorias cat ON cat.id = j.categoria_id
                 WHERE j.slug = ? LIMIT 1"
            );
            $st->execute([$slug]);
            $row = $st->fetch();
            return $row ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public static function noticias(int $jugadorId, int $limit = 6): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            return array_slice(demo_noticias_destacadas(), 0, $limit);
        }
        try {
            $st = $pdo->prepare(
                "SELECT n.*, c.slug AS categoria_slug, c.nombre AS categoria_nombre
                 FROM noticias n
                 INNER JOIN noticia_jugador nj ON nj.noticia_id = n.id
                 LEFT JOIN categorias c ON c.id = n.categoria_id
                 WHERE nj.jugador_id = ? AND n.estado = 'publicado'
                 ORDER BY n.fecha_publicacion DESC
                 LIMIT " . (int) $limit
            );
            $st->execute([$jugadorId]);
            $rows = $st->fetchAll();
            if ($rows) {
                return $rows;
            }
            // Fallback: búsqueda por nombre en título
            $st2 = $pdo->prepare(
                "SELECT n.*, c.slug AS categoria_slug, c.nombre AS categoria_nombre
                 FROM noticias n
                 LEFT JOIN categorias c ON c.id = n.categoria_id
                 INNER JOIN jugadores j ON j.id = ?
                 WHERE n.estado = 'publicado' AND n.titulo LIKE CONCAT('%', j.nombre, '%')
                 ORDER BY n.fecha_publicacion DESC
                 LIMIT " . (int) $limit
            );
            $st2->execute([$jugadorId]);
            return $st2->fetchAll() ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function edad(?string $fechaNacimiento): ?int
    {
        if (!$fechaNacimiento) {
            return null;
        }
        try {
            $born = new DateTimeImmutable($fechaNacimiento);
            $now = new DateTimeImmutable('today');
            return (int) $born->diff($now)->y;
        } catch (Throwable $e) {
            return null;
        }
    }
}
