<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

$ok = null;
$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? null)) {
        $err = 'Token de seguridad inválido. Recarga la página.';
    } else {
        $email = (string) ($_POST['email'] ?? '');
        if (SuscriptorModel::registrar($email)) {
            $ok = 'Listo. Te sumamos a la lista (o ya estabas suscrito).';
        } else {
            $err = 'No pudimos registrar ese email. Revisa el formato.';
        }
    }
}

$pageTitle = 'Newsletter | ChilenosProyección';
$metaDescription = 'Suscríbete al digest de fútbol joven chileno.';
$navActive = 'newsletter';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head"><h1>Newsletter</h1></div>
    <p class="page-intro">Resumen semanal de goleadores, debuts y proyecciones. Sin spam.</p>
    <div class="form-box">
      <?php if ($ok): ?><div class="alert alert-ok"><?= e($ok) ?></div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-err"><?= e($err) ?></div><?php endif; ?>
      <form method="post" action="">
        <?= csrf_field() ?>
        <label for="email">Email</label>
        <input id="email" type="email" name="email" required placeholder="tu@email.cl" />
        <button class="btn btn-primary" type="submit">Suscribirme</button>
      </form>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
