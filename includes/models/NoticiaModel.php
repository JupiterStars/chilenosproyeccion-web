<?php
declare(strict_types=1);

final class NoticiaModel
{
    public static function destacadas(int $limit = 5): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            $all = demo_noticias_destacadas();
            $dest = array_values(array_filter($all, static fn ($n) => !empty($n['destacada'])));
            usort($dest, static fn ($a, $b) => ((int) ($a['destacada_orden'] ?? 99)) <=> ((int) ($b['destacada_orden'] ?? 99)));
            return array_slice($dest ?: $all, 0, $limit);
        }
        try {
            $sql = "SELECT n.*, c.slug AS categoria_slug, c.nombre AS categoria_nombre
                    FROM noticias n
                    LEFT JOIN categorias c ON c.id = n.categoria_id
                    WHERE n.estado = 'publicado' AND n.destacada = 1
                    ORDER BY n.destacada_orden ASC, n.fecha_publicacion DESC
                    LIMIT :lim";
            $st = $pdo->prepare($sql);
            $st->bindValue(':lim', $limit, PDO::PARAM_INT);
            $st->execute();
            $rows = $st->fetchAll();
            return $rows ?: array_slice(demo_noticias_destacadas(), 0, $limit);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return array_slice(demo_noticias_destacadas(), 0, $limit);
        }
    }

    public static function recientes(int $limit = 12, int $offset = 0, ?string $categoriaSlug = null): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            $all = demo_noticias_destacadas();
            return array_slice($all, $offset, $limit);
        }
        try {
            $params = [];
            $where = "n.estado = 'publicado'";
            if ($categoriaSlug) {
                $where .= ' AND c.slug = :cat';
                $params[':cat'] = $categoriaSlug;
            }
            $sql = "SELECT n.*, c.slug AS categoria_slug, c.nombre AS categoria_nombre
                    FROM noticias n
                    LEFT JOIN categorias c ON c.id = n.categoria_id
                    WHERE {$where}
                    ORDER BY n.fecha_publicacion DESC
                    LIMIT :lim OFFSET :off";
            $st = $pdo->prepare($sql);
            foreach ($params as $k => $v) {
                $st->bindValue($k, $v);
            }
            $st->bindValue(':lim', $limit, PDO::PARAM_INT);
            $st->bindValue(':off', $offset, PDO::PARAM_INT);
            $st->execute();
            $rows = $st->fetchAll();
            return $rows ?: ($categoriaSlug ? [] : array_slice(demo_noticias_destacadas(), 0, $limit));
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return array_slice(demo_noticias_destacadas(), 0, $limit);
        }
    }

    public static function contarPublicadas(?string $categoriaSlug = null): int
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            return count(demo_noticias_destacadas());
        }
        try {
            if ($categoriaSlug) {
                $st = $pdo->prepare(
                    "SELECT COUNT(*) FROM noticias n
                     JOIN categorias c ON c.id = n.categoria_id
                     WHERE n.estado = 'publicado' AND c.slug = ?"
                );
                $st->execute([$categoriaSlug]);
            } else {
                $st = $pdo->query("SELECT COUNT(*) FROM noticias WHERE estado = 'publicado'");
            }
            return (int) $st->fetchColumn();
        } catch (Throwable $e) {
            return 0;
        }
    }

    public static function porSlug(string $slug): ?array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            foreach (demo_noticias_destacadas() as $n) {
                if ($n['slug'] === $slug) {
                    $n['autor_nombre'] = $n['autor_nombre'] ?? 'Redacción ChilenosProyección';
                    return $n;
                }
            }
            return null;
        }
        try {
            $st = $pdo->prepare(
                "SELECT n.*, c.slug AS categoria_slug, c.nombre AS categoria_nombre,
                        a.nombre AS autor_nombre
                 FROM noticias n
                 LEFT JOIN categorias c ON c.id = n.categoria_id
                 LEFT JOIN autores a ON a.id = n.autor_id
                 WHERE n.slug = ? AND n.estado = 'publicado'
                 LIMIT 1"
            );
            $st->execute([$slug]);
            $row = $st->fetch();
            return $row ?: null;
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * @param array{q?:string,categoria?:string,tag?:string,club?:string,page?:int,per_page?:int} $filters
     * @return array{items: array, total: int, page: int, per_page: int}
     */
    public static function buscarAvanzado(array $filters): array
    {
        $q = trim((string) ($filters['q'] ?? ''));
        $cat = trim((string) ($filters['categoria'] ?? ''));
        $tag = trim((string) ($filters['tag'] ?? ''));
        $club = trim((string) ($filters['club'] ?? ''));
        $page = max(1, (int) ($filters['page'] ?? 1));
        $per = min(30, max(6, (int) ($filters['per_page'] ?? 12)));
        $offset = ($page - 1) * $per;

        $pdo = Database::pdo();
        if (!$pdo) {
            $out = [];
            foreach (demo_noticias_destacadas() as $n) {
                $hay = ($q === '' || stripos($n['titulo'] . ' ' . $n['extracto'], $q) !== false);
                if ($hay) {
                    $out[] = $n;
                }
            }
            $total = count($out);
            return [
                'items' => array_slice($out, $offset, $per),
                'total' => $total,
                'page' => $page,
                'per_page' => $per,
            ];
        }

        try {
            $where = ["n.estado = 'publicado'"];
            $params = [];
            $join = ' LEFT JOIN categorias c ON c.id = n.categoria_id ';

            if ($q !== '') {
                $where[] = '(MATCH(n.titulo, n.extracto, n.contenido) AGAINST (:q IN NATURAL LANGUAGE MODE)
                    OR n.titulo LIKE :qlike OR n.extracto LIKE :qlike2)';
                $params[':q'] = $q;
                $params[':qlike'] = '%' . $q . '%';
                $params[':qlike2'] = '%' . $q . '%';
            }
            if ($cat !== '') {
                $where[] = 'c.slug = :cat';
                $params[':cat'] = $cat;
            }
            if ($tag !== '') {
                $join .= ' INNER JOIN noticia_tag nt ON nt.noticia_id = n.id INNER JOIN tags t ON t.id = nt.tag_id ';
                $where[] = 't.slug = :tag';
                $params[':tag'] = $tag;
            }
            if ($club !== '') {
                $join .= ' INNER JOIN noticia_club nc ON nc.noticia_id = n.id INNER JOIN clubes cl ON cl.id = nc.club_id ';
                $where[] = 'cl.slug = :club';
                $params[':club'] = $club;
            }

            $w = implode(' AND ', $where);
            $stc = $pdo->prepare("SELECT COUNT(DISTINCT n.id) FROM noticias n {$join} WHERE {$w}");
            foreach ($params as $k => $v) {
                $stc->bindValue($k, $v);
            }
            $stc->execute();
            $total = (int) $stc->fetchColumn();

            $sql = "SELECT DISTINCT n.*, c.slug AS categoria_slug, c.nombre AS categoria_nombre
                    FROM noticias n {$join}
                    WHERE {$w}
                    ORDER BY n.fecha_publicacion DESC
                    LIMIT :lim OFFSET :off";
            $st = $pdo->prepare($sql);
            foreach ($params as $k => $v) {
                $st->bindValue($k, $v);
            }
            $st->bindValue(':lim', $per, PDO::PARAM_INT);
            $st->bindValue(':off', $offset, PDO::PARAM_INT);
            $st->execute();
            return [
                'items' => $st->fetchAll() ?: [],
                'total' => $total,
                'page' => $page,
                'per_page' => $per,
            ];
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return ['items' => self::buscar($q, $per), 'total' => 0, 'page' => $page, 'per_page' => $per];
        }
    }

    public static function buscar(string $q, int $limit = 20): array
    {
        $res = self::buscarAvanzado(['q' => $q, 'per_page' => $limit, 'page' => 1]);
        return $res['items'];
    }

    public static function relacionadas(int $id, ?int $categoriaId, int $limit = 3): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            $out = [];
            foreach (demo_noticias_destacadas() as $n) {
                if ((int) ($n['id'] ?? 0) === $id) {
                    continue;
                }
                if ($categoriaId !== null && isset($n['categoria_id']) && (int) $n['categoria_id'] !== $categoriaId) {
                    // prefer same category first; skip later fill
                }
                $out[] = $n;
            }
            // Priorizar misma categoría
            if ($categoriaId !== null) {
                usort($out, static function ($a, $b) use ($categoriaId) {
                    $aSame = ((int) ($a['categoria_id'] ?? 0) === $categoriaId) ? 0 : 1;
                    $bSame = ((int) ($b['categoria_id'] ?? 0) === $categoriaId) ? 0 : 1;
                    return $aSame <=> $bSame;
                });
            }
            return array_slice($out, 0, $limit);
        }
        try {
            $st = $pdo->prepare(
                "SELECT n.*, c.slug AS categoria_slug, c.nombre AS categoria_nombre
                 FROM noticias n
                 LEFT JOIN categorias c ON c.id = n.categoria_id
                 WHERE n.estado = 'publicado' AND n.id != :id
                   AND (:cat IS NULL OR n.categoria_id = :cat2)
                 ORDER BY n.fecha_publicacion DESC
                 LIMIT :lim"
            );
            $st->bindValue(':id', $id, PDO::PARAM_INT);
            $st->bindValue(':cat', $categoriaId, $categoriaId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $st->bindValue(':cat2', $categoriaId, $categoriaId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $st->bindValue(':lim', $limit, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll();
        } catch (Throwable $e) {
            return [];
        }
    }
}
