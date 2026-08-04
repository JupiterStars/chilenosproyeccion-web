<?php
declare(strict_types=1);

final class GoleadorModel
{
    private static ?array $anfpCache = null;

    public static function porCategoria(?string $slug, int $limit = 20): array
    {
        $slug = $slug ?: 'sub-20';
        // Preferir datos ANFP (PDF) en local y como fallback de demo
        $anfp = self::desdeAnfp($slug);
        if ($anfp) {
            return array_slice($anfp, 0, max(1, $limit));
        }

        $pdo = Database::pdo();
        if (!$pdo) {
            return array_slice(self::demoLegacy($slug), 0, $limit);
        }
        try {
            $st = $pdo->prepare(
                "SELECT g.*, j.slug AS jugador_slug, cl.slug AS club_slug, cl.escudo_url
                 FROM goleadores g
                 LEFT JOIN categorias c ON c.id = g.categoria_id
                 LEFT JOIN jugadores j ON j.id = g.jugador_id
                 LEFT JOIN clubes cl ON cl.id = g.club_id
                 WHERE c.slug = ?
                 ORDER BY g.goles DESC, g.partidos ASC, g.jugador ASC
                 LIMIT " . (int) $limit
            );
            $st->execute([$slug]);
            $rows = $st->fetchAll() ?: [];
            foreach ($rows as &$r) {
                $pj = (int) ($r['partidos'] ?? 0);
                $g = (int) ($r['goles'] ?? 0);
                $r['promedio'] = $pj > 0 ? round($g / $pj, 2) : 0.0;
                if (empty($r['jugador_slug']) && !empty($r['jugador'])) {
                    $r['jugador_slug'] = slugify((string) $r['jugador']);
                }
            }
            unset($r);
            return $rows ?: array_slice(self::demoLegacy($slug), 0, $limit);
        } catch (Throwable $e) {
            return array_slice(self::demoLegacy($slug), 0, $limit);
        }
    }

    public static function metaCategoria(string $slug): array
    {
        $data = self::loadAnfp();
        $key = self::normalizeSlug($slug);
        if ($key && isset($data[$key])) {
            // No exponer fuente/COMET en UI pública
            $torneo = (string) ($data[$key]['torneo'] ?? '');
            $torneo = preg_replace('/\s*[·•]\s*ANFP.*$/iu', '', $torneo) ?? $torneo;
            $torneo = preg_replace('/\bCOMET\b/iu', '', $torneo) ?? $torneo;
            return [
                'torneo' => trim($torneo),
                'fuente' => '',
            ];
        }
        return ['torneo' => 'Goleadores ' . strtoupper($slug), 'fuente' => ''];
    }

    /** @return list<array<string,mixed>> */
    private static function desdeAnfp(string $slug): array
    {
        $data = self::loadAnfp();
        $key = self::normalizeSlug($slug);
        if (!$key || empty($data[$key]['goleadores'])) {
            return [];
        }
        $rows = $data[$key]['goleadores'];
        // ya vienen ordenados por goles en el JSON
        return $rows;
    }

    private static function normalizeSlug(string $slug): ?string
    {
        $slug = strtolower(trim($slug));
        // Mapear slugs del menú a keys del JSON
        $map = [
            'sub-15' => 'sub-15',
            'sub-16' => 'sub-16',
            'sub-18' => 'sub-18',
            'sub-20' => 'sub-20',
            'sub-15-nacional' => 'sub-15',
            'sub-16-nacional' => 'sub-16',
            'sub-18-nacional' => 'sub-18',
            'sub-20-nacional' => 'sub-20',
        ];
        return $map[$slug] ?? (isset(self::loadAnfp()[$slug]) ? $slug : null);
    }

    private static function loadAnfp(): array
    {
        if (self::$anfpCache !== null) {
            return self::$anfpCache;
        }
        $path = ROOT_PATH . '/data/goleadores-anfp-apertura-2026.json';
        if (!is_readable($path)) {
            self::$anfpCache = [];
            return self::$anfpCache;
        }
        try {
            $json = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
            self::$anfpCache = is_array($json) ? $json : [];
        } catch (Throwable $e) {
            self::$anfpCache = [];
        }
        return self::$anfpCache;
    }

    /** @return list<array<string,mixed>> */
    private static function demoLegacy(string $slug): array
    {
        return [
            ['jugador' => '—', 'jugador_slug' => '', 'club' => '—', 'club_slug' => '', 'goles' => 0, 'partidos' => 0, 'torneo' => $slug, 'promedio' => 0],
        ];
    }
}
