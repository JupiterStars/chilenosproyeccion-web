<?php
declare(strict_types=1);

function env(string $key, ?string $default = null): ?string
{
    return $_ENV[$key] ?? $default;
}

function app_url(string $path = ''): string
{
    $base = rtrim(env('APP_URL', 'http://localhost:8010') ?? '', '/');
    if ($path === '' || $path === '/') {
        return $base . '/';
    }
    return $base . '/' . ltrim($path, '/');
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Cabeceras HTTP de endurecimiento (compatibles con GTM, AdSense, Clarity, Swiper, fonts).
 */
function send_security_headers(): void
{
    if (headers_sent()) {
        return;
    }

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    header('Cross-Origin-Opener-Policy: same-origin-allow-popups');

    // CSP: allowlist de terceros ya usados en header.php / GTM
    $csp = implode('; ', [
        "default-src 'self'",
        "base-uri 'self'",
        "form-action 'self'",
        "object-src 'none'",
        "frame-ancestors 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com https://pagead2.googlesyndication.com https://www.clarity.ms https://scripts.clarity.ms https://cdn.jsdelivr.net https://tracker.metricool.com",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
        "font-src 'self' https://fonts.gstatic.com data:",
        "img-src 'self' data: blob: https: http:",
        "connect-src 'self' https://www.google-analytics.com https://region1.google-analytics.com https://analytics.google.com https://www.clarity.ms https://*.clarity.ms https://pagead2.googlesyndication.com https://tracker.metricool.com https://*.metricool.com",
        "frame-src https://www.googletagmanager.com https://googleads.g.doubleclick.net https://tpc.googlesyndication.com https://www.google.com",
    ]);
    header('Content-Security-Policy: ' . $csp);

    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443)
        || (strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https');
    if ($https) {
        header('Strict-Transport-Security: max-age=15552000; includeSubDomains');
    }
}

/**
 * IP cliente (respeta un proxy confiable tipo Cloudflare/HostGator).
 */
function client_ip(): string
{
    $candidates = [
        $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null,
        $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
        $_SERVER['REMOTE_ADDR'] ?? null,
    ];
    foreach ($candidates as $raw) {
        if (!is_string($raw) || $raw === '') {
            continue;
        }
        // X-Forwarded-For puede ser lista
        $ip = trim(explode(',', $raw)[0]);
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }
    return '0.0.0.0';
}

/**
 * Rate limit simple en disco (sin Redis). true = permitido.
 */
function rate_limit_allow(string $bucket, int $max, int $windowSeconds): bool
{
    $dir = ROOT_PATH . '/storage/rate_limit';
    if (!is_dir($dir)) {
        @mkdir($dir, 0700, true);
    }
    if (!is_dir($dir) || !is_writable($dir)) {
        // Si no hay storage, no tumbar el sitio
        return true;
    }

    $file = $dir . '/' . hash('sha256', $bucket) . '.json';
    $now = time();
    $data = ['start' => $now, 'count' => 0];

    $fp = @fopen($file, 'c+');
    if ($fp === false) {
        return true;
    }

    try {
        flock($fp, LOCK_EX);
        $raw = stream_get_contents($fp);
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && isset($decoded['start'], $decoded['count'])) {
                $data = $decoded;
            }
        }
        if (($now - (int) $data['start']) >= $windowSeconds) {
            $data = ['start' => $now, 'count' => 0];
        }
        $data['count'] = (int) $data['count'] + 1;
        $allowed = $data['count'] <= $max;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        return $allowed;
    } catch (Throwable $e) {
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }
}

/**
 * Sanitiza HTML editorial (noticias/entrevistas): quita script/eventos, allowlist de tags/attrs.
 * No rompe markup básico de artículos.
 */
function sanitize_html(?string $html): string
{
    if ($html === null || $html === '') {
        return '';
    }

    $allowedTags = '<p><br><strong><b><em><i><u><a><ul><ol><li><h2><h3><h4><blockquote><figure><figcaption><img><span><div><hr><table><thead><tbody><tr><th><td>';
    $html = strip_tags($html, $allowedTags);

    // Sin DOM: strip handlers y javascript:
    if (!class_exists('DOMDocument')) {
        $html = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? $html;
        $html = preg_replace('/\s*(href|src)\s*=\s*([\'"]?)\s*javascript:[^\'"\s>]*/i', ' $1=$2#', $html) ?? $html;
        return $html;
    }

    $prev = libxml_use_internal_errors(true);
    $dom = new DOMDocument('1.0', 'UTF-8');
    $wrapped = '<div id="cp-sanitize-root">' . $html . '</div>';
    // force UTF-8
    $dom->loadHTML('<?xml encoding="UTF-8">' . $wrapped, LIBXML_HTML_NODEFDTD);

    $allowed = [
        'p' => [],
        'br' => [],
        'hr' => [],
        'strong' => [],
        'b' => [],
        'em' => [],
        'i' => [],
        'u' => [],
        'ul' => [],
        'ol' => [],
        'li' => [],
        'h2' => [],
        'h3' => [],
        'h4' => [],
        'blockquote' => [],
        'figure' => [],
        'figcaption' => [],
        'span' => ['class'],
        'div' => ['class'],
        'table' => [],
        'thead' => [],
        'tbody' => [],
        'tr' => [],
        'th' => [],
        'td' => [],
        'a' => ['href', 'title', 'rel', 'target'],
        'img' => ['src', 'alt', 'width', 'height', 'loading', 'class'],
    ];

    $xpath = new DOMXPath($dom);
    $nodes = $xpath->query('//*');
    if ($nodes) {
        /** @var DOMElement $el */
        foreach (iterator_to_array($nodes) as $el) {
            if (!$el instanceof DOMElement) {
                continue;
            }
            $tag = strtolower($el->tagName);
            if ($tag === 'html' || $tag === 'body' || $el->getAttribute('id') === 'cp-sanitize-root') {
                continue;
            }
            if (!isset($allowed[$tag])) {
                // unwrap: keep children text
                $parent = $el->parentNode;
                if ($parent) {
                    while ($el->firstChild) {
                        $parent->insertBefore($el->firstChild, $el);
                    }
                    $parent->removeChild($el);
                }
                continue;
            }
            $allowAttrs = $allowed[$tag];
            if ($el->hasAttributes()) {
                $toRemove = [];
                foreach ($el->attributes as $attr) {
                    $name = strtolower($attr->name);
                    if (str_starts_with($name, 'on') || !in_array($name, $allowAttrs, true)) {
                        $toRemove[] = $attr->name;
                        continue;
                    }
                    $val = trim($attr->value);
                    if ($name === 'href' || $name === 'src') {
                        $lower = strtolower($val);
                        if (str_starts_with($lower, 'javascript:') || str_starts_with($lower, 'data:text/html')) {
                            $toRemove[] = $attr->name;
                            continue;
                        }
                        if ($name === 'href' && !preg_match('#^(https?:)?//|^/|^mailto:|^#|^tel:#i', $val)) {
                            $toRemove[] = $attr->name;
                            continue;
                        }
                        if ($name === 'src' && !preg_match('#^(https?:)?//|^/#i', $val)) {
                            $toRemove[] = $attr->name;
                        }
                    }
                    if ($name === 'target' && $val !== '_blank' && $val !== '_self') {
                        $toRemove[] = $attr->name;
                    }
                }
                foreach ($toRemove as $n) {
                    $el->removeAttribute($n);
                }
            }
            if ($tag === 'a' && $el->getAttribute('target') === '_blank') {
                $rel = $el->getAttribute('rel');
                if ($rel === '') {
                    $el->setAttribute('rel', 'noopener noreferrer');
                } elseif (!str_contains($rel, 'noopener')) {
                    $el->setAttribute('rel', trim($rel . ' noopener noreferrer'));
                }
            }
        }
    }

    $root = $dom->getElementById('cp-sanitize-root');
    $out = '';
    if ($root) {
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }
    }
    libxml_clear_errors();
    libxml_use_internal_errors($prev);

    return $out;
}

function redirect(string $path): never
{
    header('Location: ' . app_url($path));
    exit;
}

function abort_404(string $mensaje = 'No encontramos esa página.'): never
{
    http_response_code(404);
    $pageTitle = 'Página no encontrada | ChilenosProyección';
    $metaDescription = 'La página que buscás no está en ChilenosProyección.';
    require INCLUDES_PATH . '/header.php';
    require PUBLIC_PATH . '/404.php';
    require INCLUDES_PATH . '/footer.php';
    exit;
}

function slugify(string $text): string
{
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-') ?: 'item';
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['_csrf'])
        && hash_equals($_SESSION['_csrf'], $token);
}

/** Categorías fútbol joven (chips portada — nacional) */
function categorias_futbol_joven(): array
{
    return [
        ['slug' => 'sub-20', 'nombre' => 'Sub-20', 'division' => 'nacional'],
        ['slug' => 'sub-18', 'nombre' => 'Sub-18', 'division' => 'nacional'],
        ['slug' => 'sub-16', 'nombre' => 'Sub-16', 'division' => 'nacional'],
        ['slug' => 'sub-15', 'nombre' => 'Sub-15', 'division' => 'nacional'],
    ];
}

/** Menú por división (Nacional / Regional / Infantil) */
function nav_divisiones(): array
{
    return [
        'nacional' => [
            'label' => 'Campeonato Nacional',
            'href' => '/futbol-joven',
            'items' => [
                ['slug' => 'sub-20', 'nombre' => 'Sub-20'],
                ['slug' => 'sub-18', 'nombre' => 'Sub-18'],
                ['slug' => 'sub-16', 'nombre' => 'Sub-16'],
                ['slug' => 'sub-15', 'nombre' => 'Sub-15'],
            ],
        ],
        'regional' => [
            'label' => 'Campeonato Regional',
            'href' => '/futbol-joven',
            'items' => [
                ['slug' => 'sub-20-regional', 'nombre' => 'Sub-20'],
                ['slug' => 'sub-18-regional', 'nombre' => 'Sub-18'],
                ['slug' => 'sub-16-regional', 'nombre' => 'Sub-16'],
                ['slug' => 'sub-15-regional', 'nombre' => 'Sub-15'],
            ],
        ],
        'infantil' => [
            'label' => 'Campeonato Infantil',
            'href' => '/futbol-joven',
            'items' => [
                ['slug' => 'sub-14-infantil', 'nombre' => 'Sub-14'],
                ['slug' => 'sub-13-infantil', 'nombre' => 'Sub-13'],
                ['slug' => 'sub-12-infantil', 'nombre' => 'Sub-12'],
                ['slug' => 'sub-11-infantil', 'nombre' => 'Sub-11'],
            ],
        ],
    ];
}

/**
 * Planteles y grupos oficiales (coordinación editorial).
 * @return array<string, mixed>
 */
function planteles_oficiales(): array
{
    return [
        'nacional' => [
            'Deportes Iquique', 'Cobreloa', 'Coquimbo Unido', 'Everton', 'Santiago Wanderers',
            'Palestino', 'Audax Italiano', 'Unión Española', 'Colo-Colo', 'Universidad de Chile',
            'Universidad Católica', 'Deportes Recoleta', "O'Higgins", 'Huachipato',
            'Universidad de Concepción', 'Deportes Temuco',
        ],
        'regional' => [
            'Deportes Antofagasta', 'Cobresal', 'San Marcos', 'Deportes Copiapó', 'Deportes La Serena',
            'Trasandino', 'Unión San Felipe', 'San Luis', 'Unión La Calera', 'Magallanes',
            'Colchagua', 'Deportes Santa Cruz', 'Santiago Morning', 'Santiago City', 'Deportes Rengo',
            'Real San Joaquín', 'Curicó Unido', 'Rangers', 'Provincial Osorno', 'Deportes Concepción',
            'Ñublense', 'Atlético Colina', 'Deportes Limache', 'Deportes Puerto Montt', 'Deportes Linares',
        ],
        'regional_zonas' => [
            'centro_norte' => [
                'Unión La Calera', 'Deportes La Serena', 'Deportes Antofagasta', 'San Marcos', 'Cobresal',
                'San Luis', 'Atlético Colina', 'Santiago Morning', 'Unión San Felipe', 'Deportes Limache',
                'Deportes Copiapó', 'Trasandino', 'Santiago City',
            ],
            'centro_sur' => [
                'Rangers', 'Magallanes', 'Deportes Puerto Montt', 'Ñublense', 'Deportes Concepción',
                'Curicó Unido', 'Deportes Santa Cruz', 'Colchagua', 'Deportes Linares', 'Deportes Rengo',
                'Real San Joaquín', 'Provincial Osorno',
            ],
        ],
        // Sub-13 y Sub-14
        'infantil_sub13_14' => [
            'norte' => [
                'Coquimbo Unido', 'Deportes Iquique', 'Deportes La Serena', 'Deportes Antofagasta',
                'San Marcos', 'Deportes Copiapó',
            ],
            'centro_1' => [
                'Colo-Colo', 'Everton', 'Santiago Wanderers', 'Palestino', "O'Higgins",
                'Universidad de Chile', 'Universidad Católica', 'Magallanes', 'Unión Española',
                'Cobreloa', 'Cobresal', 'Audax Italiano',
            ],
            'centro_2' => [
                'Deportes Recoleta', 'Atlético Colina', 'San Luis', 'Deportes Limache', 'Santiago Morning',
                'Unión San Felipe', 'Real San Joaquín', 'Colchagua', 'Unión La Calera', 'Deportes Rengo',
                'Santiago City', 'Trasandino',
            ],
            'sur' => [
                'Deportes Concepción', 'Rangers', 'Deportes Santa Cruz', 'Universidad de Concepción',
                'Huachipato', 'Deportes Puerto Montt', 'Curicó Unido', 'Deportes Temuco', 'Ñublense',
            ],
        ],
        // Sub-11 y Sub-12 — solo clasifica el 1° de cada grupo
        'infantil_sub11_12' => [
            'grupo_1' => [
                'Universidad de Chile', 'Universidad Católica', 'Audax Italiano', 'Cobresal',
                'Santiago Wanderers', 'Universidad San Sebastián', 'Atlético Colina', 'Unión La Calera',
                'Captadores FC', 'San Luis', 'Trasandino', 'Everton', 'Deportes Recoleta',
                'Real San Joaquín', 'Academia Antofagasta',
            ],
            'grupo_2' => [
                'Colo-Colo', 'Unión Española', "O'Higgins", 'Palestino', 'Cobreloa', 'Colchagua',
                'Santiago Morning', 'Magallanes', 'Santiago City', 'Fluminense Chile', 'Deportes Rengo',
                'Sport Madrid', 'Diablos Rojos', 'Deportes Santa Cruz', 'Lautaro de Buin',
            ],
        ],
    ];
}

/** @deprecated usar infantil_sub13_14 */
function planteles_infantil_sub13_14(): array
{
    return planteles_oficiales()['infantil_sub13_14'];
}

/**
 * Reglas de clasificación por tipo de torneo.
 * @return array{tipo:string,cupos:int,descripcion:string,estilo:string}
 */
function reglas_clasificacion(string $categoriaSlug): array
{
    // Nacional (4 categorías): top 8
    if (in_array($categoriaSlug, ['sub-20', 'sub-18', 'sub-16', 'sub-15'], true)) {
        return [
            'tipo' => 'nacional',
            'cupos' => 8,
            'descripcion' => 'Clasifican los 8 primeros de la tabla (puestos 1° al 8°).',
            'estilo' => 'clasifica', // naranja
        ];
    }
    // Regional
    if (str_contains($categoriaSlug, 'regional')) {
        return [
            'tipo' => 'regional',
            'cupos' => 0,
            'descripcion' => 'Campeonato Regional — Zona Centro Norte y Zona Centro Sur.',
            'estilo' => 'clasifica',
        ];
    }
    // Sub-11 / Sub-12: 1° de cada grupo
    if (in_array($categoriaSlug, ['sub-11-infantil', 'sub-12-infantil'], true)) {
        return [
            'tipo' => 'infantil_11_12',
            'cupos' => 1,
            'descripcion' => 'Solo clasifica el 1° lugar de cada grupo (Grupo 1 y Grupo 2).',
            'estilo' => 'lider-grupo', // rojo / distintivo
        ];
    }
    // Sub-13 / Sub-14: grupos Norte / Centro 1 / Centro 2 / Sur
    if (in_array($categoriaSlug, ['sub-13-infantil', 'sub-14-infantil'], true)) {
        return [
            'tipo' => 'infantil_13_14',
            'cupos' => 0,
            'descripcion' => 'Grupos: Norte, Centro 1, Centro 2 y Sur.',
            'estilo' => 'clasifica',
        ];
    }
    return [
        'tipo' => 'otro',
        'cupos' => 0,
        'descripcion' => '',
        'estilo' => 'clasifica',
    ];
}

/**
 * ¿La fila de tabla clasifica? (1-based position within its group/table).
 */
function fila_clasifica(string $categoriaSlug, int $posicion, ?string $grupo = null): bool
{
    $reglas = reglas_clasificacion($categoriaSlug);
    if ($reglas['tipo'] === 'nacional') {
        return $posicion >= 1 && $posicion <= 8;
    }
    if ($reglas['tipo'] === 'infantil_11_12') {
        return $posicion === 1; // solo el líder del grupo
    }
    return false;
}

/** Categorías con tabla de goleadores (sin Sub-13 infantil). */
function goleadores_categorias_slugs(): array
{
    return [
        'sub-20', 'sub-18', 'sub-16', 'sub-15',
        'sub-20-regional', 'sub-18-regional', 'sub-16-regional', 'sub-15-regional',
        'sub-14-infantil', 'sub-12-infantil', 'sub-11-infantil',
        // sin sub-13-infantil
    ];
}

/**
 * Apellido / etiqueta legible de una categoría (para títulos y pills).
 * @return array{slug:string,nombre:string,apellido:string,titulo:string,corto:string,iniciales:string,division:string}
 */
function categoria_etiqueta(string $slug): array
{
    $slug = strtolower(trim($slug));
    $map = [
        'sub-20' => ['nombre' => 'Sub-20', 'apellido' => 'Nacional', 'iniciales' => 'S20-N', 'division' => 'nacional'],
        'sub-18' => ['nombre' => 'Sub-18', 'apellido' => 'Nacional', 'iniciales' => 'S18-N', 'division' => 'nacional'],
        'sub-16' => ['nombre' => 'Sub-16', 'apellido' => 'Nacional', 'iniciales' => 'S16-N', 'division' => 'nacional'],
        'sub-15' => ['nombre' => 'Sub-15', 'apellido' => 'Nacional', 'iniciales' => 'S15-N', 'division' => 'nacional'],
        'sub-20-regional' => ['nombre' => 'Sub-20', 'apellido' => 'Regional', 'iniciales' => 'S20-R', 'division' => 'regional'],
        'sub-18-regional' => ['nombre' => 'Sub-18', 'apellido' => 'Regional', 'iniciales' => 'S18-R', 'division' => 'regional'],
        'sub-16-regional' => ['nombre' => 'Sub-16', 'apellido' => 'Regional', 'iniciales' => 'S16-R', 'division' => 'regional'],
        'sub-15-regional' => ['nombre' => 'Sub-15', 'apellido' => 'Regional', 'iniciales' => 'S15-R', 'division' => 'regional'],
        'sub-14-infantil' => ['nombre' => 'Sub-14', 'apellido' => 'Infantil', 'iniciales' => 'S14-I', 'division' => 'infantil'],
        'sub-13-infantil' => ['nombre' => 'Sub-13', 'apellido' => 'Infantil', 'iniciales' => 'S13-I', 'division' => 'infantil'],
        'sub-12-infantil' => ['nombre' => 'Sub-12', 'apellido' => 'Infantil', 'iniciales' => 'S12-I', 'division' => 'infantil'],
        'sub-11-infantil' => ['nombre' => 'Sub-11', 'apellido' => 'Infantil', 'iniciales' => 'S11-I', 'division' => 'infantil'],
    ];
    $m = $map[$slug] ?? ['nombre' => strtoupper($slug), 'apellido' => '', 'iniciales' => strtoupper(substr($slug, 0, 4)), 'division' => 'otro'];
    $titulo = trim($m['nombre'] . ($m['apellido'] !== '' ? ' ' . $m['apellido'] : ''));
    $corto = $m['apellido'] !== '' ? ($m['nombre'] . ' ' . mb_substr($m['apellido'], 0, 3) . '.') : $m['nombre'];
    return [
        'slug' => $slug,
        'nombre' => $m['nombre'],
        'apellido' => $m['apellido'],
        'titulo' => $titulo,
        'corto' => $corto,
        'iniciales' => $m['iniciales'],
        'division' => $m['division'],
    ];
}

/**
 * Secciones / grupos de una categoría (Regional por zona, Infantil por grupos).
 * @return list<array{key:string,label:string,corto:string,iniciales:string,equipos:list<string>}>
 */
function categoria_secciones(string $slug): array
{
    $slug = strtolower(trim($slug));
    $p = planteles_oficiales();

    // Regional: dos zonas (Centro Norte / Centro Sur)
    if (str_contains($slug, 'regional')) {
        $zn = $p['regional_zonas']['centro_norte'] ?? [];
        $zs = $p['regional_zonas']['centro_sur'] ?? [];
        return [
            [
                'key' => 'centro_norte',
                'label' => 'Zona Centro Norte',
                'corto' => 'Centro Norte',
                'iniciales' => 'CN',
                'equipos' => $zn,
            ],
            [
                'key' => 'centro_sur',
                'label' => 'Zona Centro Sur',
                'corto' => 'Centro Sur',
                'iniciales' => 'CS',
                'equipos' => $zs,
            ],
        ];
    }

    // Sub-11 / Sub-12: Grupo 1 y 2
    if (in_array($slug, ['sub-11-infantil', 'sub-12-infantil'], true)) {
        $g = $p['infantil_sub11_12'] ?? [];
        return [
            [
                'key' => 'grupo_1',
                'label' => 'Grupo 1',
                'corto' => 'Grupo 1',
                'iniciales' => 'G1',
                'equipos' => $g['grupo_1'] ?? [],
            ],
            [
                'key' => 'grupo_2',
                'label' => 'Grupo 2',
                'corto' => 'Grupo 2',
                'iniciales' => 'G2',
                'equipos' => $g['grupo_2'] ?? [],
            ],
        ];
    }

    // Sub-13 / Sub-14: Norte, Centro 1, Centro 2, Sur
    if (in_array($slug, ['sub-13-infantil', 'sub-14-infantil'], true)) {
        $g = $p['infantil_sub13_14'] ?? [];
        $out = [];
        $labels = [
            'norte' => ['Grupo Norte', 'Norte', 'N'],
            'centro_1' => ['Grupo Centro 1', 'Centro 1', 'C1'],
            'centro_2' => ['Grupo Centro 2', 'Centro 2', 'C2'],
            'sur' => ['Grupo Sur', 'Sur', 'S'],
        ];
        foreach ($labels as $k => $labs) {
            $out[] = [
                'key' => $k,
                'label' => $labs[0],
                'corto' => $labs[1],
                'iniciales' => $labs[2],
                'equipos' => $g[$k] ?? [],
            ];
        }
        return $out;
    }

    // Nacional: una sola tabla (todos los 16)
    if (in_array($slug, ['sub-20', 'sub-18', 'sub-16', 'sub-15'], true)) {
        return [[
            'key' => 'unica',
            'label' => 'Tabla general',
            'corto' => 'General',
            'iniciales' => 'GEN',
            'equipos' => $p['nacional'] ?? [],
        ]];
    }

    return [];
}

/**
 * Construye filas demo de posiciones a partir de una lista de nombres de club.
 * @param list<string> $equipos
 * @return list<array<string,mixed>>
 */
function posiciones_demo_desde_equipos(array $equipos, string $torneoLabel): array
{
    $rows = [];
    $pts = 30;
    $i = 0;
    foreach ($equipos as $nombre) {
        $slug = slugify($nombre);
        $pj = 10;
        $pg = max(0, 8 - $i);
        $pe = min(3, $i % 3);
        $pp = max(0, $pj - $pg - $pe);
        $gf = max(5, 22 - $i * 2);
        $gc = max(3, 6 + $i);
        $rows[] = [
            'club' => $nombre,
            'club_slug' => $slug,
            'escudo_url' => club_escudo_url($slug),
            'pts' => max(0, $pts - $i * 2),
            'pj' => $pj,
            'pg' => $pg,
            'pe' => $pe,
            'pp' => $pp,
            'gf' => $gf,
            'gc' => $gc,
            'dg' => $gf - $gc,
            'torneo' => $torneoLabel,
        ];
        $i++;
    }
    return $rows;
}

/**
 * Catálogo plano de todas las categorías del menú (para rutas y ficha).
 * @return list<array{slug:string,nombre:string,division:string,descripcion:string}>
 */
function categorias_catalogo(): array
{
    $out = [];
    foreach (nav_divisiones() as $key => $div) {
        $label = $div['label'] ?? $key;
        foreach ($div['items'] as $item) {
            $slug = $item['slug'];
            $nombreCorto = $item['nombre'];
            $nombre = $nombreCorto;
            if ($key === 'nacional') {
                $nombre = $nombreCorto . ' Nacional';
                $desc = 'Campeonato Nacional — ' . $nombreCorto . '. Clasifican los 8 primeros. Noticias, goleadores y tablas.';
            } elseif ($key === 'regional') {
                $nombre = $nombreCorto . ' Regional';
                $desc = 'Campeonato Regional — ' . $nombreCorto . ' (Zona Centro Norte y Zona Centro Sur).';
            } else {
                $nombre = $nombreCorto . ' Infantil';
                $desc = 'Campeonato Infantil — ' . $nombreCorto . '. Grupos según categoría (Norte/C1/C2/Sur o G1/G2).';
            }
            $out[] = [
                'slug' => $slug,
                'nombre' => $nombre,
                'nombre_corto' => $nombreCorto,
                'division' => $key,
                'division_label' => $label,
                'descripcion' => $desc,
            ];
        }
    }
    return $out;
}

/** @return list<string> */
function categorias_slugs_validos(): array
{
    return array_map(static fn ($c) => $c['slug'], categorias_catalogo());
}

function asset_url(string $path): string
{
    return app_url($path);
}

/** Escudo por slug de club (prioriza PNG; evita .webp roto en algunos paths) */
function club_escudo_url(?string $slug): string
{
    $slug = trim((string) $slug);
    if ($slug === '') {
        return '';
    }
    $base = defined('PUBLIC_PATH') ? PUBLIC_PATH : '';
    foreach (['png', 'webp', 'jpg', 'jpeg'] as $ext) {
        $rel = '/assets/escudos/' . $slug . '.' . $ext;
        if ($base !== '' && is_file($base . $rel)) {
            return $rel;
        }
    }
    // Prefer PNG even if not yet verified on disk (deploy may lag)
    return '/assets/escudos/' . $slug . '.png';
}

/**
 * Ticker: goleadores (naranja) + resultados (verde) + próximos (azul).
 * Clubes referidos por escudo.
 * @return list<array<string,mixed>>
 */
function ticker_bandas(): array
{
    return [
        [
            'kind' => 'goleadores',
            'label' => 'Goleadores',
            'aria' => 'Goleadores — top 5 por categoría',
            'items' => ticker_goleadores(),
        ],
        [
            'kind' => 'resultados',
            'label' => 'Resultados',
            'aria' => 'Últimos resultados',
            'items' => ticker_resultados_partidos(),
        ],
        [
            'kind' => 'proximos',
            'label' => 'Próximos',
            'aria' => 'Próximos partidos',
            'items' => ticker_proximos_partidos(),
        ],
    ];
}

/**
 * Top 5 goleadores por división (Sub-20/18/16/15).
 * @return list<array<string,mixed>>
 */
function ticker_goleadores(): array
{
    $items = [];
    $labels = [
        'sub-20' => 'Sub-20',
        'sub-18' => 'Sub-18',
        'sub-16' => 'Sub-16',
        'sub-15' => 'Sub-15',
    ];
    try {
        foreach ($labels as $cat => $label) {
            foreach (GoleadorModel::porCategoria($cat, 5) as $g) {
                $nombre = trim((string) ($g['jugador'] ?? ''));
                $goles = (int) ($g['goles'] ?? 0);
                if ($nombre === '' || $goles < 1) {
                    continue;
                }
                $club = (string) ($g['club'] ?? '');
                $slug = (string) ($g['club_slug'] ?? '');
                $esc = (string) ($g['escudo_url'] ?? '');
                if ($esc === '' && $slug !== '') {
                    $esc = club_escudo_url($slug);
                }
                $items[] = [
                    'kind' => 'goleador',
                    'jugador' => $nombre,
                    'goles' => $goles,
                    'categoria' => $label,
                    'escudo_url' => $esc,
                    'escudo_local' => $esc,
                    'escudo_visita' => '',
                    'club' => $club,
                    'club_local' => $club,
                    'club_visita' => '',
                    'score' => '',
                    'text' => $nombre . ' · ' . $goles . ' · ' . $label,
                ];
            }
        }
    } catch (Throwable $e) {
        // silencioso
    }
    return $items;
}

/**
 * Últimos resultados (demo / programados como finalizados).
 * @return list<array<string,mixed>>
 */
function ticker_resultados_partidos(): array
{
    $rows = [
        ['local' => 'Palestino', 'local_slug' => 'palestino', 'visita' => 'Everton', 'visita_slug' => 'everton', 'score' => '2-1', 'cat' => 'Sub-20'],
        ['local' => 'Cobreloa', 'local_slug' => 'cobreloa', 'visita' => 'Colo-Colo', 'visita_slug' => 'colo-colo', 'score' => '1-1', 'cat' => 'Sub-20'],
        ['local' => 'U. de Chile', 'local_slug' => 'universidad-de-chile', 'visita' => 'Huachipato', 'visita_slug' => 'huachipato', 'score' => '3-0', 'cat' => 'Sub-18'],
        ['local' => 'U. Católica', 'local_slug' => 'universidad-catolica', 'visita' => 'Audax Italiano', 'visita_slug' => 'audax-italiano', 'score' => '2-2', 'cat' => 'Sub-16'],
        ['local' => 'Colo-Colo', 'local_slug' => 'colo-colo', 'visita' => 'Unión Española', 'visita_slug' => 'union-espanola', 'score' => '4-1', 'cat' => 'Sub-15'],
        ['local' => 'O\'Higgins', 'local_slug' => 'o-higgins', 'visita' => 'Coquimbo Unido', 'visita_slug' => 'coquimbo-unido', 'score' => '0-1', 'cat' => 'Sub-20'],
        ['local' => 'S. Wanderers', 'local_slug' => 'santiago-wanderers', 'visita' => 'Deportes Iquique', 'visita_slug' => 'deportes-iquique', 'score' => '1-0', 'cat' => 'Sub-18'],
        ['local' => 'Ñublense', 'local_slug' => 'nublense', 'visita' => 'Rangers', 'visita_slug' => 'rangers', 'score' => '2-1', 'cat' => 'Sub-16'],
    ];
    $items = [];
    foreach ($rows as $r) {
        $items[] = [
            'kind' => 'resultado',
            'club_local' => $r['local'],
            'club_visita' => $r['visita'],
            'escudo_local' => club_escudo_url($r['local_slug']),
            'escudo_visita' => club_escudo_url($r['visita_slug']),
            'score' => $r['score'],
            'categoria' => $r['cat'],
            'text' => $r['local'] . ' ' . $r['score'] . ' ' . $r['visita'] . ' · ' . $r['cat'],
        ];
    }
    return $items;
}

/**
 * Próximos partidos (con escudos).
 * @return list<array<string,mixed>>
 */
function ticker_proximos_partidos(): array
{
    $items = [];
    $demo = [
        ['local' => 'U. de Concepción', 'local_slug' => 'universidad-de-concepcion', 'visita' => 'Palestino', 'visita_slug' => 'palestino', 'cuando' => 'Sáb 15:00', 'cat' => 'Sub-20'],
        ['local' => 'Colo-Colo', 'local_slug' => 'colo-colo', 'visita' => 'Everton', 'visita_slug' => 'everton', 'cuando' => 'Sáb 17:30', 'cat' => 'Sub-18'],
        ['local' => 'U. de Chile', 'local_slug' => 'universidad-de-chile', 'visita' => 'Cobreloa', 'visita_slug' => 'cobreloa', 'cuando' => 'Dom 11:00', 'cat' => 'Sub-20'],
        ['local' => 'Huachipato', 'local_slug' => 'huachipato', 'visita' => 'U. Católica', 'visita_slug' => 'universidad-catolica', 'cuando' => 'Dom 15:00', 'cat' => 'Sub-16'],
        ['local' => 'Audax Italiano', 'local_slug' => 'audax-italiano', 'visita' => 'Unión Española', 'visita_slug' => 'union-espanola', 'cuando' => 'Dom 17:00', 'cat' => 'Sub-15'],
        ['local' => 'Coquimbo Unido', 'local_slug' => 'coquimbo-unido', 'visita' => 'Deportes La Serena', 'visita_slug' => 'deportes-la-serena', 'cuando' => 'Lun 16:00', 'cat' => 'Sub-18'],
    ];
    try {
        $prog = ProgramacionModel::porCategoria('sub-20');
        foreach (array_slice($prog, 0, 4) as $p) {
            $local = (string) ($p['local'] ?? '');
            $visita = (string) ($p['visita'] ?? '');
            if ($local === '' || $visita === '') {
                continue;
            }
            $fecha = !empty($p['fecha']) ? date('d/m', strtotime((string) $p['fecha'])) : '';
            $hora = !empty($p['hora']) ? substr((string) $p['hora'], 0, 5) : '';
            $cuando = trim($fecha . ' ' . $hora);
            $lSlug = (string) ($p['club_local_slug'] ?? slugify($local));
            $vSlug = (string) ($p['club_visita_slug'] ?? slugify($visita));
            $items[] = [
                'kind' => 'proximo',
                'club_local' => $local,
                'club_visita' => $visita,
                'escudo_local' => club_escudo_url($lSlug),
                'escudo_visita' => club_escudo_url($vSlug),
                'score' => 'vs',
                'categoria' => 'Sub-20',
                'cuando' => $cuando,
                'text' => $local . ' vs ' . $visita . ($cuando ? ' · ' . $cuando : ''),
            ];
        }
    } catch (Throwable $e) {
        // silencioso
    }
    if ($items === []) {
        foreach ($demo as $r) {
            $items[] = [
                'kind' => 'proximo',
                'club_local' => $r['local'],
                'club_visita' => $r['visita'],
                'escudo_local' => club_escudo_url($r['local_slug']),
                'escudo_visita' => club_escudo_url($r['visita_slug']),
                'score' => 'vs',
                'categoria' => $r['cat'],
                'cuando' => $r['cuando'],
                'text' => $r['local'] . ' vs ' . $r['visita'] . ' · ' . $r['cuando'],
            ];
        }
    }
    return $items;
}

/** @deprecated */
function ticker_resultados(): array
{
    return ticker_goleadores();
}

function social_facebook_url(): string
{
    $default = 'https://www.facebook.com/profile.php?id=61584767852507';
    return env('SOCIAL_FACEBOOK', $default) ?? $default;
}

function social_instagram_url(): string
{
    $default = 'https://www.instagram.com/futbolistas.chilenos/';
    return env('SOCIAL_INSTAGRAM', $default) ?? $default;
}

/** Microsoft Clarity project ID (tracking). Export JWT lives offline — never in HTML. */
function clarity_project_id(): string
{
    return trim((string) (env('CLARITY_PROJECT_ID', 'AVgYQzF1Q6aDdBCEmRNkUA') ?? 'AVgYQzF1Q6aDdBCEmRNkUA'));
}

/** Google AdSense publisher ID */
function adsense_client_id(): string
{
    return trim((string) (env('ADSENSE_CLIENT_ID', 'ca-pub-9876535709659512') ?? 'ca-pub-9876535709659512'));
}

/**
 * Escudo + nombre de club (o jugador con escudo de club).
 * Uso: render_entity_with_crest($nombre, $slug, $escudo, ['href' => ..., 'size' => 28])
 *
 * @param array{href?:string,size?:int,class?:string,show_name?:bool,sub?:string} $opts
 */
function render_entity_with_crest(string $name, ?string $slug = null, ?string $escudo = null, array $opts = []): string
{
    $name = trim($name);
    if ($name === '' && ($escudo === null || $escudo === '')) {
        return '—';
    }
    $size = (int) ($opts['size'] ?? 28);
    $class = trim('club-cell ' . (string) ($opts['class'] ?? ''));
    $showName = $opts['show_name'] ?? true;
    $sub = trim((string) ($opts['sub'] ?? ''));
    $href = $opts['href'] ?? null;
    if ($href === null && $slug) {
        $href = app_url('/club/' . $slug);
    }

    if (($escudo === null || $escudo === '') && $slug) {
        $escudo = club_escudo_url($slug);
    }
    if (($escudo === null || $escudo === '') && $name !== '') {
        $escudo = club_escudo_url(slugify($name));
    }

    $html = '<div class="' . e(trim($class)) . '">';
    if ($escudo) {
        $html .= '<img class="club-mini" src="' . e(app_url($escudo)) . '" alt="' . e($name) . '"'
            . ' width="' . $size . '" height="' . $size . '" loading="lazy"'
            . ' onerror="this.style.display=\'none\'" />';
    }
    if ($showName) {
        $inner = e($name !== '' ? $name : '—');
        if ($href) {
            $html .= '<a class="club-cell-name" href="' . e($href) . '">' . $inner . '</a>';
        } else {
            $html .= '<span class="club-cell-name">' . $inner . '</span>';
        }
        if ($sub !== '') {
            $html .= '<span class="club-cell-sub">' . e($sub) . '</span>';
        }
    }
    $html .= '</div>';
    return $html;
}

/** Bloque CTA dentro de una nota (HTML seguro) */
function noticia_cta(string $titulo, string $slug, string $texto = 'Seguir leyendo'): string
{
    $url = e(app_url('/noticia/' . $slug));
    $t = e($titulo);
    $tx = e($texto);
    return '<aside class="article-cta">'
        . '<p class="article-cta-label">También te puede interesar</p>'
        . '<a class="article-cta-link" href="' . $url . '"><strong>' . $t . '</strong><span>' . $tx . ' →</span></a>'
        . '</aside>';
}

/**
 * Noticias editoriales a partir de goleadores ANFP (Apertura 2026).
 * Fuente: listados COMET / ANFP — 13.07.2026.
 * @return list<array<string,mixed>>
 */
function demo_noticias_destacadas(): array
{
    $g20 = app_url('/goleadores/sub-20');
    $g18 = app_url('/goleadores/sub-18');
    $g16 = app_url('/goleadores/sub-16');
    $g15 = app_url('/goleadores/sub-15');

    $base = [
        [
            'id' => 1,
            'titulo' => 'Riquelme manda en el Sub-20 Nacional: 12 goles con Palestino',
            'slug' => 'riquelme-lider-goleadores-sub-20-nacional-apertura-2026',
            'extracto' => 'Benjamín Riquelme Gómez encabeza la tabla de goleadores del Sub 20 Nacional Apertura 2026. Detrás, Gallardo (Cobreloa) y un Palestino que también suma a Díaz con 8.',
            'imagen_destacada_url' => '/assets/brand/goleadores-sub20.jpg',
            'imagen_alt' => 'Goleadores Sub-20 Nacional Apertura 2026',
            'categoria_slug' => 'sub-20',
            'categoria_nombre' => 'Sub-20',
            'categoria_id' => 10,
            'autor_nombre' => 'Redacción ChilenosProyección',
            'fecha_publicacion' => date('Y-m-d H:i:s', strtotime('-3 hours')),
            'destacada' => 1,
            'destacada_orden' => 1,
            'cta_slug' => 'palestino-marca-la-pauta-en-el-golo-formativo',
            'cta_titulo' => 'Palestino también pelea el gol en Sub-18',
        ],
        [
            'id' => 2,
            'titulo' => 'Aillapán es un monstruo en Sub-15: 26 goles con Colo-Colo',
            'slug' => 'aillapan-26-goles-sub-15-nacional-colo-colo',
            'extracto' => 'Justin Aillapán Cumian dispara la tabla del Sub 15 Nacional Apertura 2026. Simón Rosas (U. Católica) lo persigue con 20; Amaro Delgado suma 16 también en albo.',
            'imagen_destacada_url' => '/assets/brand/goleadores-sub14.jpg',
            'imagen_alt' => 'Goleadores Sub-15 Nacional',
            'categoria_slug' => 'sub-15',
            'categoria_nombre' => 'Sub-15',
            'categoria_id' => 13,
            'autor_nombre' => 'Redacción ChilenosProyección',
            'fecha_publicacion' => date('Y-m-d H:i:s', strtotime('-5 hours')),
            'destacada' => 1,
            'destacada_orden' => 2,
            'cta_slug' => 'colo-colo-domina-el-golo-en-sub-15-y-sub-16',
            'cta_titulo' => 'La cantera alba también manda en Sub-16',
        ],
        [
            'id' => 3,
            'titulo' => 'Morales Águila y Raipán: el 1-2 albo en Sub-16 Nacional',
            'slug' => 'morales-aguila-raipan-goleadores-sub-16-colo-colo',
            'extracto' => 'Cristopher Morales Águila (18) y Felipe Raipán (16) ponen a Colo-Colo en la cima del goleo Sub-16. Clemente Reyes (Palestino) acecha con 15.',
            'imagen_destacada_url' => '/assets/brand/goleadores-proyeccion.jpg',
            'imagen_alt' => 'Goleadores Sub-16 Nacional',
            'categoria_slug' => 'sub-16',
            'categoria_nombre' => 'Sub-16',
            'categoria_id' => 12,
            'autor_nombre' => 'Redacción ChilenosProyección',
            'fecha_publicacion' => date('Y-m-d H:i:s', strtotime('-8 hours')),
            'destacada' => 1,
            'destacada_orden' => 3,
            'cta_slug' => 'aillapan-26-goles-sub-15-nacional-colo-colo',
            'cta_titulo' => 'En Sub-15, Aillapán ya va por 26',
        ],
        [
            'id' => 4,
            'titulo' => 'Empate en la cima del Sub-18: Araya y Cárdenas, 11 goles cada uno',
            'slug' => 'araya-cardenas-goleadores-sub-18-palestino',
            'extracto' => 'Emilio Araya y Álvaro Cárdenas, ambos de Palestino, comparten el liderato del Sub 18 Nacional Apertura 2026 con 11 goles. Cofré (U. de Chile) y Langenbach (UC) van con 10.',
            'imagen_destacada_url' => '/assets/brand/goleadores-regional.jpg',
            'imagen_alt' => 'Goleadores Sub-18 Nacional',
            'categoria_slug' => 'sub-18',
            'categoria_nombre' => 'Sub-18',
            'categoria_id' => 11,
            'autor_nombre' => 'Redacción ChilenosProyección',
            'fecha_publicacion' => date('Y-m-d H:i:s', strtotime('-12 hours')),
            'destacada' => 1,
            'destacada_orden' => 4,
            'cta_slug' => 'riquelme-lider-goleadores-sub-20-nacional-apertura-2026',
            'cta_titulo' => 'En Sub-20 también manda un árabe: Riquelme',
        ],
        [
            'id' => 5,
            'titulo' => 'Palestino marca la pauta en el gol formativo',
            'slug' => 'palestino-marca-la-pauta-en-el-golo-formativo',
            'extracto' => 'Del Sub-20 al Sub-18, la cantera de La Cisterna aparece una y otra vez en la cima de los listados oficiales de goleadores del Apertura 2026.',
            'imagen_destacada_url' => '/assets/brand/portada-preview.jpg',
            'imagen_alt' => 'Palestino formativas',
            'categoria_slug' => 'sub-20',
            'categoria_nombre' => 'Sub-20',
            'categoria_id' => 10,
            'autor_nombre' => 'Redacción ChilenosProyección',
            'fecha_publicacion' => date('Y-m-d H:i:s', strtotime('-18 hours')),
            'destacada' => 1,
            'destacada_orden' => 5,
            'cta_slug' => 'araya-cardenas-goleadores-sub-18-palestino',
            'cta_titulo' => 'El doblete árabe en Sub-18',
        ],
        [
            'id' => 6,
            'titulo' => 'Colo-Colo domina el gol en Sub-15 y Sub-16',
            'slug' => 'colo-colo-domina-el-golo-en-sub-15-y-sub-16',
            'extracto' => 'Aillapán, Delgado, Morales Águila, Raipán y Romero: la fábrica albo se hace sentir en las tablas oficiales de la ANFP para el Apertura 2026.',
            'imagen_destacada_url' => '/assets/brand/goleadores-sub20.jpg',
            'imagen_alt' => 'Colo-Colo formativas goleadores',
            'categoria_slug' => 'sub-16',
            'categoria_nombre' => 'Sub-16',
            'categoria_id' => 12,
            'autor_nombre' => 'Redacción ChilenosProyección',
            'fecha_publicacion' => date('Y-m-d H:i:s', strtotime('-1 day')),
            'destacada' => 0,
            'destacada_orden' => 0,
            'cta_slug' => 'morales-aguila-raipan-goleadores-sub-16-colo-colo',
            'cta_titulo' => 'Detalle del 1-2 en Sub-16',
        ],
        [
            'id' => 7,
            'titulo' => 'Gallardo acosa a Riquelme: Cobreloa sueña en el norte',
            'slug' => 'gallardo-cobreloa-11-goles-sub-20',
            'extracto' => 'Esteban Gallardo Cerda llega a 11 goles en el Sub-20 Nacional y se queda a uno del liderato. El naranja de Calama empuja desde la zona alta del ranking.',
            'imagen_destacada_url' => '/assets/brand/goleadores-proyeccion.jpg',
            'imagen_alt' => 'Esteban Gallardo Cobreloa Sub-20',
            'categoria_slug' => 'sub-20',
            'categoria_nombre' => 'Sub-20',
            'categoria_id' => 10,
            'autor_nombre' => 'Redacción ChilenosProyección',
            'fecha_publicacion' => date('Y-m-d H:i:s', strtotime('-36 hours')),
            'destacada' => 0,
            'destacada_orden' => 0,
            'cta_slug' => 'riquelme-lider-goleadores-sub-20-nacional-apertura-2026',
            'cta_titulo' => 'Así está el podio completo del Sub-20',
        ],
        [
            'id' => 8,
            'titulo' => 'Apertura 2026: el mapa del gol en formativas ANFP',
            'slug' => 'mapa-del-gol-formativas-anfp-apertura-2026',
            'extracto' => 'Sub-15, Sub-16, Sub-18 y Sub-20: un repaso a los listados oficiales de goleadores de la ANFP (COMET, 13 de julio de 2026) y lo que dicen del torneo.',
            'imagen_destacada_url' => '/assets/brand/goleadores-regional.jpg',
            'imagen_alt' => 'Mapa de goleadores formativas',
            'categoria_slug' => 'sub-20',
            'categoria_nombre' => 'Sub-20',
            'categoria_id' => 10,
            'autor_nombre' => 'Redacción ChilenosProyección',
            'fecha_publicacion' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'destacada' => 0,
            'destacada_orden' => 0,
            'cta_slug' => 'aillapan-26-goles-sub-15-nacional-colo-colo',
            'cta_titulo' => 'El número más alto: 26 de Aillapán',
        ],
    ];

    $cuerpos = [
        1 => static function (array $n) use ($g20): string {
            $cta = noticia_cta($n['cta_titulo'], $n['cta_slug'], 'Leer la nota');
            return <<<HTML
<p>El listado oficial de goleadores del <strong>Sub 20 Nacional Apertura 2026</strong> (ANFP / COMET, corte 13 de julio) tiene dueño: <strong>Benjamín Ignacio Riquelme Gómez</strong>, de Palestino, con <strong>12 goles</strong>.</p>
<p>No es un liderazgo holgado. A un tanto aparece <strong>Esteban Moisés Gallardo Cerda</strong> (Cobreloa), con 11. Y en el mismo podio de la pelea se mete otro árabe: <strong>Leonardo Ignacio Díaz Muñoz</strong>, también de Palestino, con 8, empatado con <strong>Santiago Nicolás Reinoso Narváez</strong> (Everton).</p>
<h2>Qué dice la tabla</h2>
<p>Palestino coloca dos nombres entre los cuatro primeros. Everton responde con Reinoso y, más abajo, con Chadwick (6). O’Higgins (Gutiérrez, 6) y Audax (Jara, 6) completan un pelotón que no suelta el hilo. Universidad de Concepción aparece con Ramírez y Sabando en el grupo de los 5 goles.</p>
{$cta}
<p>La lectura de cancha es clara: el gol está repartido, pero La Cisterna empuja con volumen. En ChilenosProyección publicamos la <a href="{$g20}">tabla completa de goleadores Sub-20</a> a partir del documento oficial de la ANFP.</p>
HTML;
        },
        2 => static function (array $n) use ($g15): string {
            $cta = noticia_cta($n['cta_titulo'], $n['cta_slug'], 'Ver Sub-16');
            return <<<HTML
<p>Hay números que detienen la lectura. <strong>Justin Jean Pierre Aillapán Cumian</strong>, de Colo-Colo, lleva <strong>26 goles</strong> en el <strong>Sub 15 Nacional Apertura 2026</strong> según el listado oficial de la ANFP.</p>
<p>La distancia con el segundo es enorme: <strong>Simón Alonso Rosas De La Jara</strong> (Universidad Católica) suma 20. El podio lo cierra otro albo, <strong>Amaro Manuel Delgado Domínguez</strong>, con 16. Es decir: Colo-Colo pone 1° y 3° en la tabla de artilleros de la categoría.</p>
<h2>El resto del pelotón</h2>
<p>Lucas Cariqueo (Unión Española, 12), Bruno Sepúlveda (UC, 11), Raimundo Tapia (Palestino, 11) y Pedro Torres (Unión Española, 11) mantienen viva la pelea por el tramo alto, pero el ritmo de Aillapán es de otra escala.</p>
{$cta}
<p>Estos datos salen del documento COMET de goleadores Sub-15 Nacional. La <a href="{$g15}">tabla completa</a> está disponible en nuestra sección de goleadores.</p>
HTML;
        },
        3 => static function (array $n) use ($g16): string {
            $cta = noticia_cta($n['cta_titulo'], $n['cta_slug'], 'Leer');
            return <<<HTML
<p>En Sub-16 el guion también se escribe en Macul. <strong>Cristopher Stevens Morales Águila</strong> lidera con <strong>18 goles</strong> y su compañero <strong>Felipe Andrés Raipán Olave</strong> lo sigue con 16. El 1-2 es albo; el tercero es árabe: <strong>Clemente Antonio Reyes Bozo</strong> (Palestino), con 15.</p>
<p>Más abajo, <strong>Xavier Jesús Romero Hernández</strong> (Colo-Colo, 12) y <strong>Mateo Nicolás Cardemil Norero</strong> (Palestino, 11) confirman que la pelea por el gol es, sobre todo, un duelo entre dos canteras que se miran de reojo en cada fecha.</p>
{$cta}
<p>Iquique (Castro, 10), Católica (Ferrada, 10) y Cobreloa (Gallardo, 10) meten nombres en el tramo de dos dígitos. Revisá el ranking completo en <a href="{$g16}">goleadores Sub-16</a>.</p>
HTML;
        },
        4 => static function (array $n) use ($g18): string {
            $cta = noticia_cta($n['cta_titulo'], $n['cta_slug'], 'Ir a Sub-20');
            return <<<HTML
<p>El <strong>Sub 18 Nacional Apertura 2026</strong> no tiene un dueño único: tiene un empate en la cima. <strong>Emilio Alejandro Araya Rojas</strong> y <strong>Álvaro Gabriel Cárdenas Maldonado</strong>, ambos de Palestino, figuran con <strong>11 goles</strong> en el listado oficial de la ANFP.</p>
<p>A un paso, con 10, aparecen <strong>Diego Antonio Cofré Núñez</strong> (Universidad de Chile) y <strong>Mathías Ignacio Langenbach Contreras</strong> (Universidad Católica). El norte suma con <strong>Víctor Fabiano Saavedra Rivera</strong> (Deportes Iquique, 9) y Coquimbo con Calderón (8).</p>
<h2>Doble amenaza árabe</h2>
<p>Que dos delanteros del mismo club compartan el liderato no es casualidad: habla de un plantel que genera llegadas y de un cuerpo técnico que reparte minutos sin bajar el volumen ofensivo.</p>
{$cta}
<p>La <a href="{$g18}">tabla de goleadores Sub-18</a> completa está cargada desde el documento COMET del 13 de julio de 2026.</p>
HTML;
        },
        5 => static function (array $n) use ($g20, $g18): string {
            $cta = noticia_cta($n['cta_titulo'], $n['cta_slug'], 'Detalle Sub-18');
            return <<<HTML
<p>Si se miran juntas las tablas de goleadores del Apertura 2026, hay un club que se repite en los titulares: <strong>Palestino</strong>.</p>
<p>En Sub-20, Riquelme (12) y Díaz (8) marcan la pauta. En Sub-18, Araya y Cárdenas comparten la cima con 11. En Sub-16, Reyes (15) y Cardemil (11) meten a La Cisterna en la pelea aunque el 1-2 sea albo. En Sub-15, Tapia (11) también aparece en el tramo alto.</p>
<p>No es un solo “9” suelto: es un sistema que produce goles en varias edades. Para un medio de formativas, eso es una señal de trabajo de cantera, no de una racha aislada.</p>
{$cta}
<p>Consultá los listados: <a href="{$g20}">Sub-20</a> y <a href="{$g18}">Sub-18</a>.</p>
HTML;
        },
        6 => static function (array $n) use ($g15, $g16): string {
            $cta = noticia_cta($n['cta_titulo'], $n['cta_slug'], 'Ver Sub-16');
            return <<<HTML
<p>Mientras Palestino pesa en Sub-18 y Sub-20, <strong>Colo-Colo</strong> se queda con el grito en las categorías más chicas del Nacional.</p>
<p>En Sub-15, Aillapán (26) y Delgado (16) encienden el ranking. En Sub-16, Morales Águila (18), Raipán (16) y Romero (12) arman un tridente albo difícil de igualar. Son goles, pero también jerarquía de club en edades donde el físico y la repetición de partidos definen campañas.</p>
{$cta}
<p>Tablas oficiales: <a href="{$g15}">goleadores Sub-15</a> y <a href="{$g16}">Sub-16</a>.</p>
HTML;
        },
        7 => static function (array $n) use ($g20): string {
            $cta = noticia_cta($n['cta_titulo'], $n['cta_slug'], 'Ver podio');
            return <<<HTML
<p>Desde Calama llega la amenaza más clara al liderato de Riquelme. <strong>Esteban Moisés Gallardo Cerda</strong> suma <strong>11 goles</strong> en el Sub-20 Nacional Apertura 2026 y se queda a un tanto del primer puesto.</p>
<p>Cobreloa no solo aparece con Gallardo: en el tramo de 4 goles también figuran nombres naranjas (Navarro, Quezada, Tejerina). El norte insiste en el ranking y obliga a Palestino a no confiarse.</p>
{$cta}
<p>El listado completo está en <a href="{$g20}">goleadores Sub-20</a>, con la misma fuente ANFP/COMET del 13 de julio.</p>
HTML;
        },
        8 => static function (array $n) use ($g15, $g16, $g18, $g20): string {
            $cta = noticia_cta($n['cta_titulo'], $n['cta_slug'], 'El caso Aillapán');
            return <<<HTML
<p>Los cuatro documentos oficiales de goleadores del <strong>Apertura 2026</strong> (Sub-15, Sub-16, Sub-18 y Sub-20 Nacional) permiten un mapa rápido del gol en formativas:</p>
<ul>
<li><strong>Sub-15:</strong> Aillapán (Colo-Colo) 26 — cifra disparada del torneo.</li>
<li><strong>Sub-16:</strong> Morales Águila 18 y Raipán 16 (ambos Colo-Colo).</li>
<li><strong>Sub-18:</strong> empate Araya–Cárdenas (Palestino) con 11.</li>
<li><strong>Sub-20:</strong> Riquelme (Palestino) 12, Gallardo (Cobreloa) 11.</li>
</ul>
<p>Fuente: reportes COMET de la ANFP impresos el 13 de julio de 2026. En este medio las tablas se cargaron tal cual el listado de goleadores; no inventamos goles ni alteramos el orden.</p>
{$cta}
<p>Entrá directo: <a href="{$g15}">Sub-15</a> · <a href="{$g16}">Sub-16</a> · <a href="{$g18}">Sub-18</a> · <a href="{$g20}">Sub-20</a>.</p>
HTML;
        },
    ];

    foreach ($base as &$n) {
        $id = (int) $n['id'];
        $n['contenido'] = isset($cuerpos[$id]) ? $cuerpos[$id]($n) : '<p>' . e($n['extracto']) . '</p>';
        $n['meta_titulo'] = $n['titulo'];
        $n['meta_descripcion'] = $n['extracto'];
        $n['imagen_credito'] = 'ChilenosProyección · datos ANFP';
    }
    unset($n);

    return $base;
}

function format_fecha(?string $datetime): string
{
    if (!$datetime) {
        return '';
    }
    $ts = strtotime($datetime);
    if ($ts === false) {
        return '';
    }
    return date('d/m/Y H:i', $ts);
}

/** Tiempo relativo tipo app (53m, 2h, 1d) */
function format_tiempo_relativo(?string $datetime): string
{
    if (!$datetime) {
        return '';
    }
    $ts = strtotime($datetime);
    if ($ts === false) {
        return '';
    }
    $diff = max(0, time() - $ts);
    if ($diff < 60) {
        return 'ahora';
    }
    if ($diff < 3600) {
        return (int) floor($diff / 60) . 'm';
    }
    if ($diff < 86400) {
        return (int) floor($diff / 3600) . 'h';
    }
    if ($diff < 86400 * 7) {
        return (int) floor($diff / 86400) . 'd';
    }
    return date('d/m', $ts);
}

function noticia_img_url(array $n): string
{
    $img = $n['imagen_destacada_url'] ?? '/assets/brand/goleadores-sub20.jpg';
    if ($img && !str_starts_with($img, 'http') && !str_starts_with($img, '/')) {
        $img = '/' . $img;
    }
    return str_starts_with((string) $img, 'http') ? (string) $img : app_url((string) $img);
}
