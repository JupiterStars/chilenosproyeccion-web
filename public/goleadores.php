<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$cat = strtolower(trim($_GET['categoria'] ?? 'sub-20')) ?: 'sub-20';
$grupoParam = strtolower(trim($_GET['grupo'] ?? ''));
$etiqueta = categoria_etiqueta($cat);
$filas = GoleadorModel::porCategoria($cat, 50);
$secciones = categoria_secciones($cat);

// Solo Regional (y multi-grupo real): botones de zona — una sola fila
$esMultiGrupo = count($secciones) > 1
    && !((isset($secciones[0]['key']) && $secciones[0]['key'] === 'unica'));
$gruposNav = $esMultiGrupo ? $secciones : [];

// Validar grupo activo
$grupoKeys = array_map(static fn ($s) => (string) ($s['key'] ?? ''), $gruposNav);
if ($grupoParam !== '' && !in_array($grupoParam, $grupoKeys, true)) {
    $grupoParam = '';
}

// Anotar cada fila con su zona (si aplica) y filtrar
$filasTagged = [];
foreach ($filas as $f) {
    $clubNom = (string) ($f['club'] ?? '');
    $cSlug = (string) ($f['club_slug'] ?? '');
    if ($cSlug === '' && $clubNom !== '') {
        $cSlug = slugify($clubNom);
    }
    $f['_grupo'] = $gruposNav ? club_seccion_key($cat, $clubNom, $cSlug) : '';
    $filasTagged[] = $f;
}

$filasVista = $filasTagged;
if ($grupoParam !== '') {
    $filasVista = array_values(array_filter(
        $filasTagged,
        static fn (array $f): bool => ($f['_grupo'] ?? '') === $grupoParam
    ));
}

$showPj = false;
foreach ($filasVista as $f) {
    if ((int) ($f['partidos'] ?? 0) > 0) {
        $showPj = true;
        break;
    }
}

// Pills con apellido
$pills = [];
foreach (goleadores_categorias_slugs() as $slug) {
    $pills[$slug] = categoria_etiqueta($slug);
}

$baseGol = static function (string $slug, string $grupo = '') : string {
    $url = app_url('/goleadores/' . $slug);
    if ($grupo !== '') {
        $url .= '?grupo=' . rawurlencode($grupo);
    }
    return $url;
};

$pageTitle = 'Goleadores ' . $etiqueta['titulo'] . ' | ChilenosProyección';
$metaDescription = 'Tabla de goleadores — ' . $etiqueta['titulo'];
$navActive = 'goleadores';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head"><h1>Goleadores</h1></div>
    <p class="page-intro">
      <span class="cat-full"><?= e($etiqueta['titulo']) ?></span>
      <span class="cat-ini" title="<?= e($etiqueta['titulo']) ?>"><?= e($etiqueta['iniciales']) ?></span>
      <?php if ($grupoParam !== ''): ?>
        <?php
          $gLabel = '';
          foreach ($gruposNav as $s) {
              if (($s['key'] ?? '') === $grupoParam) {
                  $gLabel = $s['corto'] ?? $s['label'] ?? $grupoParam;
                  break;
              }
          }
        ?>
        · <?= e((string) $gLabel) ?>
      <?php endif; ?>
    </p>

    <div class="subnav-sticky" data-subnav-sticky>
      <?= nav_misma_categoria($cat, 'goleadores') ?>

      <div class="cat-pills cat-pills--scroll" role="navigation" aria-label="Categorías">
        <?php foreach ($pills as $slug => $et): ?>
          <a
            class="pill <?= $cat === $slug ? 'is-active' : '' ?>"
            href="<?= e($baseGol($slug)) ?>"
            title="<?= e($et['titulo']) ?>"
          >
            <span class="pill-full"><?= e($et['nombre']) ?> <small><?= e($et['apellido']) ?></small></span>
            <span class="pill-ini"><?= e($et['iniciales']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <?php if ($gruposNav): ?>
        <div class="grupo-jump" role="navigation" aria-label="Filtrar por zona">
          <a
            class="grupo-jump-btn <?= $grupoParam === '' ? 'is-active' : '' ?>"
            href="<?= e($baseGol($cat)) ?>"
          >
            <strong>TODOS</strong>
            <span>General</span>
          </a>
          <?php foreach ($gruposNav as $sec): ?>
            <?php $key = (string) ($sec['key'] ?? ''); ?>
            <a
              class="grupo-jump-btn <?= $grupoParam === $key ? 'is-active' : '' ?>"
              href="<?= e($baseGol($cat, $key)) ?>"
            >
              <strong><?= e($sec['iniciales'] ?? '') ?></strong>
              <span><?= e($sec['corto'] ?? $sec['label'] ?? '') ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th class="col-pos">#</th>
            <th>Jugador</th>
            <th class="col-club-ico" title="Club">Club</th>
            <th>Goles</th>
            <?php if ($showPj): ?><th>PJ</th><th>Prom.</th><?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php
            $vacio = !$filasVista
                || (count($filasVista) === 1 && (
                    ($filasVista[0]['jugador'] ?? '') === '—'
                    || (int) ($filasVista[0]['goles'] ?? 0) === 0
                ));
          ?>
          <?php if ($vacio): ?>
            <tr>
              <td colspan="<?= $showPj ? 6 : 4 ?>" class="prog-empty">
                Aún no hay goleadores cargados para <?= e($etiqueta['titulo']) ?>
                <?php if ($grupoParam !== ''): ?> en esta zona<?php endif; ?>.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($filasVista as $i => $f): ?>
              <?php
                $pos = $i + 1;
                $jSlug = $f['jugador_slug'] ?? null;
                $cSlug = $f['club_slug'] ?? null;
                $clubNom = $f['club'] ?? '';
                $esc = $f['escudo_url'] ?? ($cSlug ? club_escudo_url((string) $cSlug) : '');
                $pj = (int) ($f['partidos'] ?? 0);
                $g = (int) ($f['goles'] ?? 0);
                $prom = $f['promedio'] ?? ($pj > 0 ? round($g / $pj, 2) : 0);
              ?>
              <tr data-grupo="<?= e((string) ($f['_grupo'] ?? '')) ?>">
                <td class="col-pos"><span class="pos-badge"><?= $pos ?></span></td>
                <td>
                  <?php if ($jSlug): ?>
                    <a class="player-name-link" href="<?= e(app_url('/jugador/' . $jSlug)) ?>"><strong><?= e($f['jugador'] ?? '') ?></strong></a>
                  <?php else: ?>
                    <strong class="player-name"><?= e($f['jugador'] ?? '') ?></strong>
                  <?php endif; ?>
                </td>
                <td class="cell-club-ico">
                  <?php if ($esc): ?>
                    <a
                      class="club-crest-only"
                      href="<?= e($cSlug ? app_url('/club/' . $cSlug) : '#') ?>"
                      title="<?= e($clubNom) ?>"
                      aria-label="<?= e($clubNom) ?>"
                    >
                      <img
                        src="<?= e(app_url($esc)) ?>"
                        alt="<?= e($clubNom) ?>"
                        width="32"
                        height="32"
                        loading="lazy"
                        onerror="this.closest('a').classList.add('is-missing')"
                      />
                    </a>
                  <?php else: ?>
                    <span class="club-crest-fallback" title="<?= e($clubNom) ?>"><?= e(mb_substr($clubNom, 0, 1)) ?></span>
                  <?php endif; ?>
                </td>
                <td class="col-pts"><strong><?= $g ?></strong></td>
                <?php if ($showPj): ?>
                  <td><?= $pj ?></td>
                  <td><?= e((string) $prom) ?></td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
