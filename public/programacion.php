<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$cat = strtolower(trim($_GET['categoria'] ?? 'sub-20'));
$filas = ProgramacionModel::porCategoria($cat ?: null);
$nombreCat = strtoupper(str_replace('-', ' ', $cat));

$pageTitle = "Programación {$nombreCat} | ChilenosProyección";
$metaDescription = "Próximos partidos {$nombreCat}.";
$navActive = 'programacion';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <h1>Programación <?= e($nombreCat) ?></h1>
      <div class="chip-row">
        <?php foreach (categorias_futbol_joven() as $c): ?>
          <a class="chip <?= $c['slug'] === $cat ? 'is-active' : '' ?>" href="<?= e(app_url('/programacion/' . $c['slug'])) ?>"><?= e($c['nombre']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="table-wrap">
      <table class="data-table data-table--programacion">
        <thead>
          <tr><th>Fecha</th><th>Hora</th><th>Local</th><th>Visita</th><th>Recinto</th></tr>
        </thead>
        <tbody>
          <?php foreach ($filas as $r): ?>
            <?php
              $lName = (string) ($r['local'] ?? '');
              $vName = (string) ($r['visita'] ?? '');
              $lSlug = (string) ($r['club_local_slug'] ?? '');
              $vSlug = (string) ($r['club_visita_slug'] ?? '');
              $escL = (string) ($r['escudo_local'] ?? '');
              $escV = (string) ($r['escudo_visita'] ?? '');
            ?>
            <tr>
              <td><?= e($r['fecha'] ?? '') ?></td>
              <td><?= e(substr((string) ($r['hora'] ?? ''), 0, 5)) ?></td>
              <td>
                <?= render_entity_with_crest($lName, $lSlug !== '' ? $lSlug : null, $escL !== '' ? $escL : null, [
                    'size' => 28,
                    'class' => 'club-cell--table',
                ]) ?>
              </td>
              <td>
                <?= render_entity_with_crest($vName, $vSlug !== '' ? $vSlug : null, $escV !== '' ? $escV : null, [
                    'size' => 28,
                    'class' => 'club-cell--table',
                ]) ?>
              </td>
              <td><?= e($r['recinto'] ?? '—') ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$filas): ?>
            <tr><td colspan="5">Sin partidos programados.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
