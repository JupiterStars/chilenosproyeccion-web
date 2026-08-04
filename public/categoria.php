<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$slug = strtolower(trim($_GET['cat'] ?? ''));
$pagina = max(1, (int) ($_GET['pagina'] ?? 1));
$porPagina = 12;

if ($slug === '' || !CategoriaModel::esValida($slug)) {
    abort_404('Categoría no válida');
}

$categoria = CategoriaModel::porSlug($slug);
if (!$categoria) {
    abort_404('Categoría no encontrada');
}

// Noticias de la categoría; si no hay, mostrar recientes del mismo “bloque” de edad
$total = NoticiaModel::contarPublicadas($slug);
$offset = ($pagina - 1) * $porPagina;
$noticias = NoticiaModel::recientes($porPagina, $offset, $slug);

// Fallback: mapear slugs regionales/infantiles a noticias con slug base si no hay filas
if (!$noticias) {
    $baseSlug = preg_replace('/-(regional|infantil)$/', '', $slug) ?? $slug;
    if ($baseSlug !== $slug) {
        $noticias = NoticiaModel::recientes($porPagina, 0, $baseSlug);
        $total = max($total, NoticiaModel::contarPublicadas($baseSlug));
    }
}
// Si sigue vacío, no 404: página de categoría con empty state + accesos
$totalPaginas = max(1, (int) ceil(max(1, $total) / $porPagina));
if ($total === 0) {
    $totalPaginas = 1;
}

$nombre = $categoria['nombre'] ?? strtoupper($slug);
$divisionLabel = $categoria['division_label'] ?? '';
$pageTitle = $nombre . ' | ChilenosProyección';
$metaDescription = $categoria['descripcion'] ?? ('Noticias ' . $nombre);
// Highlight menú padre
$div = $categoria['division'] ?? 'nacional';
$navActive = in_array($div, ['nacional', 'regional', 'infantil'], true) ? $div : 'futbol-joven';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <div>
        <?php if ($divisionLabel !== ''): ?>
          <p class="entity-kicker" style="margin-bottom:.35rem"><?= e($divisionLabel) ?></p>
        <?php endif; ?>
        <h1><?= e($nombre) ?></h1>
      </div>
      <div class="chip-row">
        <a class="chip" href="<?= e(app_url('/goleadores/' . preg_replace('/-(regional|infantil)$/', '', $slug))) ?>">Goleadores</a>
        <a class="chip" href="<?= e(app_url('/posiciones/' . preg_replace('/-(regional|infantil)$/', '', $slug))) ?>">Posiciones</a>
        <a class="chip" href="<?= e(app_url('/programacion/' . preg_replace('/-(regional|infantil)$/', '', $slug))) ?>">Programación</a>
      </div>
    </div>

    <?php if (!empty($categoria['descripcion'])): ?>
      <p class="page-intro"><?= e($categoria['descripcion']) ?></p>
    <?php endif; ?>

    <?php if ($noticias): ?>
      <div class="card-grid featured">
        <?php foreach ($noticias as $noticia): ?>
          <?php require INCLUDES_PATH . '/partials/news-card.php'; ?>
        <?php endforeach; ?>
      </div>
      <?php if ($total > $porPagina && $totalPaginas > 1): ?>
        <nav class="pagination" aria-label="Paginación">
          <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
            <?php if ($p === $pagina): ?>
              <span class="is-current"><?= $p ?></span>
            <?php else: ?>
              <a href="<?= e(app_url('/futbol-joven/' . $slug . '/pagina/' . $p)) ?>"><?= $p ?></a>
            <?php endif; ?>
          <?php endfor; ?>
        </nav>
      <?php endif; ?>
    <?php else: ?>
      <div class="empty-state">
        <p>Aún no hay notas publicadas en esta categoría.</p>
        <p style="margin-top:.75rem">
          <a class="btn btn-primary" href="<?= e(app_url('/goleadores/' . preg_replace('/-(regional|infantil)$/', '', $slug))) ?>">Ver goleadores</a>
          <a class="btn btn-ghost" href="<?= e(app_url('/')) ?>" style="margin-left:.5rem">Ir al inicio</a>
        </p>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
