<?php
declare(strict_types=1);

/**
 * API mínima para Hermes Agent (VPS).
 * Auth: header X-Api-Key
 * GET  /api/noticias — lista
 * POST /api/noticias — crear (JSON)
 */
require_once dirname(__DIR__) . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
// No indexar API
header('X-Robots-Tag: noindex, nofollow');

$ip = client_ip();

// Rate limit por IP (auth fallida y uso total)
if (!rate_limit_allow('api_noticias_ip_' . $ip, 60, 60)) {
    http_response_code(429);
    echo json_encode(['error' => 'Demasiadas solicitudes']);
    exit;
}

$apiKey = (string) ($_SERVER['HTTP_X_API_KEY'] ?? '');
$expected = (string) (env('HERMES_API_KEY', '') ?? '');
if ($expected === '' || !hash_equals($expected, $apiKey)) {
    // Limitar fuerza bruta de keys
    if (!rate_limit_allow('api_noticias_authfail_' . $ip, 15, 60)) {
        http_response_code(429);
        echo json_encode(['error' => 'Demasiadas solicitudes']);
        exit;
    }
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET') {
    $limit = min(50, max(1, (int) ($_GET['limit'] ?? 10)));
    $rows = NoticiaModel::recientes($limit);
    echo json_encode(['ok' => true, 'data' => $rows], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'POST') {
    // Publicaciones: más estricto
    if (!rate_limit_allow('api_noticias_post_' . $ip, 20, 60)) {
        http_response_code(429);
        echo json_encode(['error' => 'Demasiadas publicaciones']);
        exit;
    }

    $raw = file_get_contents('php://input') ?: '';
    if (strlen($raw) > 1_500_000) {
        http_response_code(413);
        echo json_encode(['error' => 'Payload demasiado grande']);
        exit;
    }
    $body = json_decode($raw, true);
    if (!is_array($body)) {
        http_response_code(400);
        echo json_encode(['error' => 'JSON inválido']);
        exit;
    }

    $titulo = trim((string) ($body['titulo'] ?? ''));
    // Sanitizar HTML entrante de Hermes (mismo filtro que el front)
    $contenido = sanitize_html((string) ($body['contenido'] ?? ''));
    if ($titulo === '' || $contenido === '') {
        http_response_code(422);
        echo json_encode(['error' => 'titulo y contenido son obligatorios']);
        exit;
    }
    if (mb_strlen($titulo) > 250) {
        http_response_code(422);
        echo json_encode(['error' => 'titulo demasiado largo']);
        exit;
    }

    $pdo = Database::pdo();
    if (!$pdo) {
        http_response_code(503);
        echo json_encode(['error' => 'Base de datos no disponible']);
        exit;
    }

    $slug = slugify($titulo);
    $extracto = trim((string) ($body['extracto'] ?? mb_substr(strip_tags($contenido), 0, 200)));
    $estado = ($body['estado'] ?? 'borrador') === 'publicado' ? 'publicado' : 'borrador';
    $img = $body['imagen_destacada_url'] ?? null;
    $catId = isset($body['categoria_id']) ? (int) $body['categoria_id'] : null;

    try {
        $st = $pdo->prepare(
            "INSERT INTO noticias
             (titulo, slug, extracto, contenido, categoria_id, autor_id, estado,
              imagen_destacada_url, fecha_publicacion, origen)
             VALUES (?, ?, ?, ?, ?, 2, ?, ?, IF(?='publicado', NOW(), NULL), 'hermes')"
        );
        $st->execute([$titulo, $slug, $extracto, $contenido, $catId, $estado, $img, $estado]);
        $id = (int) $pdo->lastInsertId();
        echo json_encode(['ok' => true, 'id' => $id, 'slug' => $slug], JSON_UNESCAPED_UNICODE);
    } catch (Throwable $e) {
        error_log($e->getMessage());
        // slug conflict
        $slug .= '-' . substr(bin2hex(random_bytes(3)), 0, 6);
        try {
            $st = $pdo->prepare(
                "INSERT INTO noticias
                 (titulo, slug, extracto, contenido, categoria_id, autor_id, estado,
                  imagen_destacada_url, fecha_publicacion, origen)
                 VALUES (?, ?, ?, ?, ?, 2, ?, ?, IF(?='publicado', NOW(), NULL), 'hermes')"
            );
            $st->execute([$titulo, $slug, $extracto, $contenido, $catId, $estado, $img, $estado]);
            echo json_encode(['ok' => true, 'id' => (int) $pdo->lastInsertId(), 'slug' => $slug], JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e2) {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo crear la noticia']);
        }
    }
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Método no permitido']);
