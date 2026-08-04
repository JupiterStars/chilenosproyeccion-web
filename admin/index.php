<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: ' . app_url('/admin/login'));
    exit;
}

$pageTitle = 'Panel admin | ChilenosProyección';
require INCLUDES_PATH . '/header.php';
$noticias = NoticiaModel::recientes(20);
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <h1>Panel · <?= e($_SESSION['admin_nombre'] ?? 'Admin') ?></h1>
    </div>
    <p class="page-intro">Scaffold del admin. CRUD completo de noticias se amplía en el siguiente sprint.</p>
    <div style="overflow-x:auto">
      <table class="data-table">
        <thead><tr><th>Título</th><th>Slug</th><th>Fecha</th></tr></thead>
        <tbody>
          <?php foreach ($noticias as $n): ?>
            <tr>
              <td><a href="<?= e(app_url('/noticia/' . $n['slug'])) ?>"><?= e($n['titulo']) ?></a></td>
              <td><?= e($n['slug']) ?></td>
              <td><?= e(format_fecha($n['fecha_publicacion'] ?? null)) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
