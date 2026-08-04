<?php
declare(strict_types=1);

final class ProgramacionModel
{
    public static function porCategoria(?string $slug): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            return self::demo($slug);
        }
        try {
            if ($slug) {
                $st = $pdo->prepare(
                    "SELECT p.* FROM programacion p
                     LEFT JOIN categorias c ON c.id = p.categoria_id
                     WHERE c.slug = ? AND p.fecha >= CURDATE()
                     ORDER BY p.fecha, p.hora"
                );
                $st->execute([$slug]);
            } else {
                $st = $pdo->query(
                    "SELECT * FROM programacion WHERE fecha >= CURDATE() ORDER BY fecha, hora LIMIT 30"
                );
            }
            $rows = $st->fetchAll() ?: [];
            return $rows ?: self::demo($slug);
        } catch (Throwable $e) {
            return self::demo($slug);
        }
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
