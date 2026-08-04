<?php
declare(strict_types=1);

final class TagModel
{
    public static function porSlug(string $slug): ?array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            $demo = ['debuts' => 'Debuts', 'fichajes' => 'Fichajes', 'goleadores' => 'Goleadores'];
            return isset($demo[$slug]) ? ['nombre' => $demo[$slug], 'slug' => $slug] : null;
        }
        try {
            $st = $pdo->prepare('SELECT * FROM tags WHERE slug = ? LIMIT 1');
            $st->execute([$slug]);
            return $st->fetch() ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public static function noticiasPorTag(string $slug, int $limit = 20): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            return $slug === 'goleadores' ? demo_noticias_destacadas() : [];
        }
        try {
            $st = $pdo->prepare(
                "SELECT n.*, c.slug AS categoria_slug, c.nombre AS categoria_nombre
                 FROM noticias n
                 JOIN noticia_tag nt ON nt.noticia_id = n.id
                 JOIN tags t ON t.id = nt.tag_id
                 LEFT JOIN categorias c ON c.id = n.categoria_id
                 WHERE t.slug = ? AND n.estado = 'publicado'
                 ORDER BY n.fecha_publicacion DESC
                 LIMIT {$limit}"
            );
            $st->execute([$slug]);
            return $st->fetchAll();
        } catch (Throwable $e) {
            return [];
        }
    }
}
