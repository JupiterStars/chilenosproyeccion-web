<?php
declare(strict_types=1);

final class PosicionModel
{
    public static function porCategoria(?string $slug): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            return self::demo();
        }
        try {
            if ($slug) {
                $st = $pdo->prepare(
                    "SELECT p.*, cl.slug AS club_slug, cl.escudo_url
                     FROM posiciones p
                     LEFT JOIN categorias c ON c.id = p.categoria_id
                     LEFT JOIN clubes cl ON cl.id = p.club_id
                     WHERE c.slug = ?
                     ORDER BY p.pts DESC, p.dg DESC, p.gf DESC, p.club ASC"
                );
                $st->execute([$slug]);
            } else {
                $st = $pdo->query(
                    'SELECT p.*, cl.slug AS club_slug, cl.escudo_url
                     FROM posiciones p
                     LEFT JOIN clubes cl ON cl.id = p.club_id
                     ORDER BY p.pts DESC, p.dg DESC'
                );
            }
            $rows = $st->fetchAll() ?: [];
            foreach ($rows as &$r) {
                $r['gf'] = (int) ($r['gf'] ?? 0);
                $r['gc'] = (int) ($r['gc'] ?? 0);
                $r['dg'] = (int) ($r['dg'] ?? ($r['gf'] - $r['gc']));
            }
            unset($r);
            return $rows ?: self::demo();
        } catch (Throwable $e) {
            return self::demo();
        }
    }

    private static function demo(): array
    {
        return [
            ['club' => 'Universidad de Concepción', 'club_slug' => 'universidad-de-concepcion', 'escudo_url' => '/assets/escudos/universidad-de-concepcion.png', 'pts' => 24, 'pj' => 10, 'pg' => 7, 'pe' => 3, 'pp' => 0, 'gf' => 22, 'gc' => 8, 'dg' => 14, 'torneo' => 'Demo Sub-20'],
            ['club' => 'Colo-Colo', 'club_slug' => 'colo-colo', 'escudo_url' => '/assets/escudos/colo-colo.png', 'pts' => 22, 'pj' => 10, 'pg' => 7, 'pe' => 1, 'pp' => 2, 'gf' => 20, 'gc' => 10, 'dg' => 10, 'torneo' => 'Demo Sub-20'],
            ['club' => 'Universidad de Chile', 'club_slug' => 'universidad-de-chile', 'escudo_url' => '/assets/escudos/universidad-de-chile.png', 'pts' => 20, 'pj' => 10, 'pg' => 6, 'pe' => 2, 'pp' => 2, 'gf' => 18, 'gc' => 11, 'dg' => 7, 'torneo' => 'Demo Sub-20'],
            ['club' => 'Palestino', 'club_slug' => 'palestino', 'escudo_url' => '/assets/escudos/palestino.png', 'pts' => 18, 'pj' => 10, 'pg' => 5, 'pe' => 3, 'pp' => 2, 'gf' => 16, 'gc' => 12, 'dg' => 4, 'torneo' => 'Demo Sub-20'],
            ['club' => 'Huachipato', 'club_slug' => 'huachipato', 'escudo_url' => '/assets/escudos/huachipato.png', 'pts' => 15, 'pj' => 10, 'pg' => 4, 'pe' => 3, 'pp' => 3, 'gf' => 14, 'gc' => 13, 'dg' => 1, 'torneo' => 'Demo Sub-20'],
            ['club' => 'Everton', 'club_slug' => 'everton', 'escudo_url' => '/assets/escudos/everton.png', 'pts' => 12, 'pj' => 10, 'pg' => 3, 'pe' => 3, 'pp' => 4, 'gf' => 12, 'gc' => 15, 'dg' => -3, 'torneo' => 'Demo Sub-20'],
        ];
    }
}
