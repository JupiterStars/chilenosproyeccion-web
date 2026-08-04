<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

$q = trim($_GET['q'] ?? '');
$cat = trim($_GET['categoria'] ?? '');
$tag = trim($_GET['tag'] ?? '');
$club = trim($_GET['club'] ?? '');
$page = max(1, (int) ($_GET['pagina'] ?? 1));

$result = NoticiaModel::buscarAvanzado([
    'q' => $q,
    'categoria' => $cat,
    'tag' => $tag,
    'club' => $club,
    'page' => $page,
    'per_page' => 12,
]);
$items = $result['items'];
$total = $result['total'];
$per = $result['per_page'];
$pages = max(1, (int) ceil($total / $per));

$pageTitle = ($q !== '' ? 'Buscar: ' . $q : 'Buscador') . ' | ChilenosProyección';
$metaDescription = 'Buscá noticias, jugadores y clubes del fútbol joven chileno.';
$navActive = 'buscador';

$clubes = ClubModel::listar(null, 40);

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head"><h1>Buscador</h1></div>
    <p class="page-intro">Título, resumen y cuerpo. Filtrá por categoría, tag o club.</p>

    <form class="search-panel" method="get" action="<?= e(app_url('/buscador')) ?>" role="search">
      <div class="search-panel-row">
        <label class="sr-only" for="q">Consulta</label>
        <input id="q" class="search-input-lg" type="search" name="q" value="<?= e($q) ?>" placeholder="Jugador, club, torneo…" />
        <button class="btn btn-primary" type="submit">Buscar</button>
      </div>
      <div class="search-filters">
        <label>
          <span>Categoría</span>
          <select name="categoria">
            <option value="">Todas</option>
            <?php foreach (categorias_futbol_joven() as $c): ?>
              <option value="<?= e($c['slug']) ?>" <?= $cat === $c['slug'] ? 'selected' : '' ?>><?= e($c['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>
          <span>Tag</span>
          <select name="tag">
            <option value="">Todos</option>
            <?php foreach (['goles','resultados','fichajes','debuts','entrevistas','tablas','formativas'] as $t): ?>
              <option value="<?= e($t) ?>" <?= $tag === $t ? 'selected' : '' ?>><?= e(ucfirst($t)) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>
          <span>Club</span>
          <select name="club">
            <option value="">Todos</option>
            <?php foreach ($clubes as $cl): ?>
              <option value="<?= e($cl['slug']) ?>" <?= $club === ($cl['slug'] ?? '') ? 'selected' : '' ?>><?= e($cl['nombre'] ?? '') ?></option>
            <?php endforeach; ?>
          </select>
        </label>
      </div>
    </form>

    <?php if ($q === '' && $cat === '' && $tag === '' && $club === ''): ?>
      <div class="empty-state">Escribí un término o elegí un filtro para ver resultados.</div>
    <?php elseif (!$items): ?>
      <div class="empty-state">Sin resultados<?= $q !== '' ? ' para «' . e($q) . '»' : '' ?>.</div>
    <?php else: ?>
      <p class="search-count"><?= (int) $total ?> resultado<?= $total === 1 ? '' : 's' ?></p>
      <div class="card-grid card-grid-3">
        <?php foreach ($items as $n): ?>
          <?php require INCLUDES_PATH . '/partials/news-card.php'; ?>
        <?php endforeach; ?>
      </div>
      <?php if ($pages > 1): ?>
        <nav class="pagination" aria-label="Paginación">
          <?php for ($p = 1; $p <= $pages; $p++): ?>
            <?php
              $qs = http_build_query(array_filter([
                  'q' => $q ?: null,
                  'categoria' => $cat ?: null,
                  'tag' => $tag ?: null,
                  'club' => $club ?: null,
                  'pagina' => $p > 1 ? $p : null,
              ]));
            ?>
            <a class="<?= $p === $page ? 'is-active' : '' ?>" href="<?= e(app_url('/buscador' . ($qs ? '?' . $qs : ''))) ?>"><?= $p ?></a>
          <?php endfor; ?>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
