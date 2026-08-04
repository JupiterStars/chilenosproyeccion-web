<?php
declare(strict_types=1);

final class EntrevistaModel
{
    public static function listar(int $limit = 20, int $offset = 0): array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            return array_slice(self::demo(), $offset, $limit);
        }
        try {
            $st = $pdo->prepare(
                "SELECT e.*, j.nombre AS jugador_nombre, j.slug AS jugador_slug, cl.nombre AS club_nombre
                 FROM entrevistas e
                 LEFT JOIN jugadores j ON j.id = e.jugador_id
                 LEFT JOIN clubes cl ON cl.id = j.club_id
                 WHERE e.estado = 'publicado'
                 ORDER BY e.fecha_publicacion DESC
                 LIMIT :lim OFFSET :off"
            );
            $st->bindValue(':lim', $limit, PDO::PARAM_INT);
            $st->bindValue(':off', $offset, PDO::PARAM_INT);
            $st->execute();
            $rows = $st->fetchAll();
            return $rows ?: array_slice(self::demo(), $offset, $limit);
        } catch (Throwable $e) {
            return array_slice(self::demo(), $offset, $limit);
        }
    }

    public static function porSlug(string $slug): ?array
    {
        $pdo = Database::pdo();
        if (!$pdo) {
            foreach (self::demo() as $e) {
                if ($e['slug'] === $slug) {
                    return $e;
                }
            }
            return null;
        }
        try {
            $st = $pdo->prepare(
                "SELECT e.*, j.nombre AS jugador_nombre, j.slug AS jugador_slug,
                        cl.nombre AS club_nombre, cl.slug AS club_slug
                 FROM entrevistas e
                 LEFT JOIN jugadores j ON j.id = e.jugador_id
                 LEFT JOIN clubes cl ON cl.id = j.club_id
                 WHERE e.slug = ? AND e.estado = 'publicado'
                 LIMIT 1"
            );
            $st->execute([$slug]);
            $row = $st->fetch();
            return $row ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    private static function demo(): array
    {
        return [
            [
                'id' => 1,
                'titulo' => 'Entrevista a Diego Sabando: el día a día en formativas',
                'slug' => 'entrevista-a-diego-sabando-el-dia-a-dia-en-formativas',
                'extracto' => 'El delantero de U. de Concepción habla de competencia, presión y proyección.',
                'cuerpo' => '<p>Conversamos con <strong>Diego Sabando</strong> sobre su rol en el plantel Sub-20.</p><p>“Trabajo, humildad y ganas de aprender”, resume.</p>',
                'jugador_nombre' => 'Diego Sabando',
                'jugador_slug' => 'diego-sabando',
                'club_nombre' => 'Universidad de Concepción',
                'fecha_publicacion' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'imagen_url' => '/assets/brand/portada-preview.jpg',
                'video_url' => null,
            ],
            [
                'id' => 2,
                'titulo' => 'Entrevista a Pablo Toledo: goleador regional',
                'slug' => 'entrevista-a-pablo-toledo-goleador-regional',
                'extracto' => 'Toledo cierra el Regional con números de punta y mira el Nacional.',
                'cuerpo' => '<p><strong>Pablo Toledo</strong> repasa el torneo regional y la meta de seguir creciendo.</p>',
                'jugador_nombre' => 'Pablo Toledo',
                'jugador_slug' => 'pablo-toledo',
                'club_nombre' => 'Ñublense',
                'fecha_publicacion' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'imagen_url' => '/assets/brand/goleadores-regional.jpg',
                'video_url' => null,
            ],
            [
                'id' => 3,
                'titulo' => 'André Stancampiano y la generación infantil',
                'slug' => 'andre-stancampiano-y-la-generacion-infantil',
                'extracto' => 'La cantera infantil asoma con nombres que ya se siguen en la redacción.',
                'cuerpo' => '<p>El juvenil detalla su rutina y el apoyo familiar detrás de cada fecha.</p>',
                'jugador_nombre' => 'André Stancampiano',
                'jugador_slug' => 'andre-stancampiano',
                'club_nombre' => 'Colo-Colo',
                'fecha_publicacion' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'imagen_url' => '/assets/brand/goleadores-sub14.jpg',
                'video_url' => null,
            ],
        ];
    }
}
