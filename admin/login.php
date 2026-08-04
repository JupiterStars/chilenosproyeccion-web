<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/bootstrap.php';

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip = client_ip();
    if (!rate_limit_allow('admin_login_' . $ip, 10, 600)) {
        $error = 'Demasiados intentos. Espera unos minutos.';
    } elseif (!csrf_verify($_POST['_csrf'] ?? null)) {
        $error = 'CSRF inválido';
    } else {
        $email = trim((string) ($_POST['email'] ?? ''));
        $pass = (string) ($_POST['password'] ?? '');
        $pdo = Database::pdo();
        if (!$pdo) {
            $error = 'BD no disponible. Importa sql/schema.sql';
        } else {
            $st = $pdo->prepare('SELECT * FROM usuarios_admin WHERE email = ? LIMIT 1');
            $st->execute([$email]);
            $user = $st->fetch();
            if ($user && password_verify($pass, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = (int) $user['id'];
                $_SESSION['admin_nombre'] = $user['nombre'];
                $_SESSION['admin_rol'] = $user['rol'];
                header('Location: ' . app_url('/admin'));
                exit;
            }
            // Delay fijo ante fallo (mitiga timing/brute force ligero)
            usleep(250_000);
            $error = 'Credenciales incorrectas';
        }
    }
}

$pageTitle = 'Admin login | ChilenosProyección';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head"><h1>Admin</h1></div>
    <div class="form-box">
      <?php if ($error): ?><div class="alert alert-err"><?= e($error) ?></div><?php endif; ?>
      <form method="post">
        <?= csrf_field() ?>
        <label>Email</label>
        <input type="email" name="email" required />
        <label>Contraseña</label>
        <input type="password" name="password" required />
        <button class="btn btn-primary" type="submit">Entrar</button>
      </form>
      <p class="meta" style="margin-top:1rem">En seed demo la hash es placeholder — regenera con password_hash() al activar admin real.</p>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
