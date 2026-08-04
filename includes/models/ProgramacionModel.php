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
                     WHERE c.slug = ? AND p.fecha >= CURDATE()
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
                     WHERE p.fecha >= CURDATE()
                     ORDER BY p.fecha, p.hora
                     LIMIT 30"
                );
            }
            $rows = $st->fetchAll() ?: [];
            return self::withEscudos($rows ?: self::demo($slug));
        } catch (Throwable $e) {
            return self::withEscudos(self::demo($slug));
        }
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
        return [
            [
                'fecha' => date('Y-m-d', strtotime('+3 days')),
                'hora' => '15:00:00',
                'local' => 'Universidad de Concepción',
                'visita' => 'Palestino',
                'recinto' => 'Ester Roa',
                'club_local_slug' => 'universidad-de-concepcion',
                'club_visita_slug' => 'palestino',
            ],
            [
                'fecha' => date('Y-m-d', strtotime('+3 days')),
                'hora' => '17:30:00',
                'local' => 'Colo-Colo',
                'visita' => 'Everton',
                'recinto' => 'Monumental',
                'club_local_slug' => 'colo-colo',
                'club_visita_slug' => 'everton',
            ],
            [
                'fecha' => date('Y-m-d', strtotime('+4 days')),
                'hora' => '11:00:00',
                'local' => 'Universidad de Chile',
                'visita' => 'Cobreloa',
                'recinto' => 'Cancha formativas',
                'club_local_slug' => 'universidad-de-chile',
                'club_visita_slug' => 'cobreloa',
            ],
            [
                'fecha' => date('Y-m-d', strtotime('+5 days')),
                'hora' => '16:00:00',
                'local' => 'Huachipato',
                'visita' => 'Universidad Católica',
                'recinto' => 'CAP',
                'club_local_slug' => 'huachipato',
                'club_visita_slug' => 'universidad-catolica',
            ],
        ];
    }
}
