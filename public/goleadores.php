<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$cat = trim($_GET['categoria'] ?? 'sub-20') ?: 'sub-20';
$etiqueta = categoria_etiqueta($cat);
$filas = GoleadorModel::porCategoria($cat, 15);
$meta = GoleadorModel::metaCategoria($cat);
$torneo = $meta['torneo'] !== '' ? $meta['torneo'] : ($filas[0]['torneo'] ?? ('Goleadores ' . $etiqueta['titulo']));
$fuente = $meta['fuente'] ?? '';
$reglas = reglas_clasificacion($cat);
$secciones = categoria_secciones($cat);

$showPj = false;
foreach ($filas as $f) {
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

$pageTitle = 'Goleadores ' . $etiqueta['titulo'] . ' | ChilenosProyección';
$metaDescription = 'Tabla de goleadores — ' . $torneo;
$navActive = 'goleadores';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head"><h1>Goleadores</h1></div>
    <p class="page-intro">
      <span class="cat-full"><?= e($etiqueta['titulo']) ?></span>
      <span class="cat-ini" title="<?= e($etiqueta['titulo']) ?>"><?= e($etiqueta['iniciales']) ?></span>
      <?php if ($fuente): ?>
        <span class="meta"> · Fuente: <?= e($fuente) ?>.</span>
      <?php endif; ?>
    </p>
    <?php if (($reglas['descripcion'] ?? '') !== ''): ?>
      <p class="reglas-torneo" role="note">
        <strong>Contexto:</strong> <?= e($reglas['descripcion']) ?>
        <?php if (count($secciones) > 1): ?>
          · Secciones:
          <?php
            $bits = [];
            foreach ($secciones as $s) {
                $bits[] = $s['label'] . ' (' . $s['iniciales'] . ')';
            }
            echo e(implode(', ', $bits));
          ?>
        <?php endif; ?>
      </p>
    <?php endif; ?>

    <div class="cat-pills" role="navigation" aria-label="Categorías">
      <?php foreach ($pills as $slug => $et): ?>
        <a
          class="pill <?= $cat === $slug ? 'is-active' : '' ?>"
          href="<?= e(app_url('/goleadores/' . $slug)) ?>"
          title="<?= e($et['titulo']) ?>"
        >
          <span class="pill-full"><?= e($et['nombre']) ?> <small><?= e($et['apellido']) ?></small></span>
          <span class="pill-ini"><?= e($et['iniciales']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>

    <?php if (count($secciones) > 1): ?>
      <div class="sec-legend" aria-label="Grupos o zonas">
        <?php foreach ($secciones as $sec): ?>
          <span class="sec-chip" title="<?= e($sec['label']) ?>">
            <strong class="sec-ini"><?= e($sec['iniciales']) ?></strong>
            <span class="sec-full-inline"><?= e($sec['corto'] ?? $sec['label']) ?></span>
            <span class="sec-count"><?= count($sec['equipos'] ?? []) ?> clubes</span>
          </span>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

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
          <?php foreach ($filas as $i => $f): ?>
            <?php
              $pos = $i + 1;
              $jSlug = $f['jugador_slug'] ?? null;
              $cSlug = $f['club_slug'] ?? null;
              $clubNom = $f['club'] ?? '';
              $esc = $f['escudo_url'] ?? ($cSlug ? club_escudo_url($cSlug) : '');
              $pj = (int) ($f['partidos'] ?? 0);
              $g = (int) ($f['goles'] ?? 0);
              $prom = $f['promedio'] ?? ($pj > 0 ? round($g / $pj, 2) : 0);
            ?>
            <tr class="<?= $pos <= 3 ? 'row-top' : '' ?>">
              <td class="col-pos"><span class="pos-badge"><?= $pos ?></span></td>
              <td>
                <div class="club-cell club-cell--player">
                  <?php if ($esc): ?>
                    <img class="club-mini" src="<?= e(app_url($esc)) ?>" alt="<?= e($clubNom) ?>" title="<?= e($clubNom) ?>" width="28" height="28" loading="lazy" onerror="this.style.display='none'" />
                  <?php endif; ?>
                  <?php if ($jSlug): ?>
                    <a class="club-cell-name" href="<?= e(app_url('/jugador/' . $jSlug)) ?>"><strong><?= e($f['jugador'] ?? '') ?></strong></a>
                  <?php else: ?>
                    <strong class="club-cell-name"><?= e($f['jugador'] ?? '') ?></strong>
                  <?php endif; ?>
                </div>
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
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
