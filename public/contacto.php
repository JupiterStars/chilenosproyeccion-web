<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$ok = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify($_POST['_csrf'] ?? null)) {
    $ok = 'Gracias. Recibimos tu mensaje y te responderemos pronto.';
}

$pageTitle = 'Contacto | ChilenosProyección';
$metaDescription = 'Contacto ChilenosProyección.';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head"><h1>Contacto</h1></div>
    <div class="form-box">
      <?php if ($ok): ?><div class="alert alert-ok"><?= e($ok) ?></div><?php endif; ?>
      <form method="post">
        <?= csrf_field() ?>
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" required />
        <label for="email">Email</label>
        <input id="email" type="email" name="email" required />
        <label for="mensaje">Mensaje</label>
        <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
        <button class="btn btn-primary" type="submit">Enviar</button>
      </form>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
