<?php
declare(strict_types=1);

final class CategoriaModel
{
    public static function todas(): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            return categorias_catalogo();
        }
        try {
            $rows = $pdo->query('SELECT * FROM categorias ORDER BY orden, nombre')->fetchAll();
            return $rows ?: categorias_catalogo();
        } catch (Throwable $e) {
            return categorias_catalogo();
        }
    }

    public static function porSlug(string $slug): ?array
    {
        $slug = strtolower(trim($slug));
        if ($slug === '') {
            return null;
        }

        $pdo = Database::pdo();
        if ($pdo) {
            try {
                $st = $pdo->prepare('SELECT * FROM categorias WHERE slug = ? LIMIT 1');
                $st->execute([$slug]);
                $row = $st->fetch();
                if ($row) {
                    return $row;
                }
            } catch (Throwable $e) {
                // fallback catálogo
            }
        }

        foreach (categorias_catalogo() as $c) {
            if ($c['slug'] === $slug) {
                return $c;
            }
        }

        return null;
    }

    public static function esValida(string $slug): bool
    {
        return in_array(strtolower(trim($slug)), categorias_slugs_validos(), true);
    }
}
