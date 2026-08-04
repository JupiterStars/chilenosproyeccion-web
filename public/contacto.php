<?php
declare(strict_types=1);
require_once (is_file(__DIR__ . '/includes/bootstrap.php')
    ? __DIR__ . '/includes/bootstrap.php'
    : dirname(__DIR__) . '/includes/bootstrap.php');

$ok = null;
$err = null;
// Destino interno (no se muestra al visitante)
$to = contact_email();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? null)) {
        $err = 'La sesión de seguridad expiró. Recargue la página e intente nuevamente.';
    } elseif (!rate_limit_allow('contacto:' . client_ip(), 5, 3600)) {
        $err = 'Ha enviado demasiados mensajes en poco tiempo. Intente más tarde.';
    } else {
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $asunto = trim((string) ($_POST['asunto'] ?? 'Consulta general'));
        $mensaje = trim((string) ($_POST['mensaje'] ?? ''));
        $consent = isset($_POST['consentimiento']) && (string) $_POST['consentimiento'] === '1';

        if ($nombre === '' || mb_strlen($nombre) > 120) {
            $err = 'Indique un nombre válido.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 180) {
            $err = 'Indique un correo electrónico válido.';
        } elseif ($mensaje === '' || mb_strlen($mensaje) > 5000) {
            $err = 'Escriba un mensaje (máximo 5.000 caracteres).';
        } elseif (!$consent) {
            $err = 'Debe aceptar el tratamiento de sus datos para enviar el mensaje.';
        } else {
            $asuntoSafe = mb_substr(preg_replace('/[\r\n]+/', ' ', $asunto) ?? 'Consulta', 0, 120);
            $body = "Nuevo mensaje desde " . media_brand_name() . " (familia " . legal_entity_name() . ")\n\n"
                . "Nombre: {$nombre}\n"
                . "Email: {$email}\n"
                . "Asunto: {$asuntoSafe}\n"
                . "IP: " . client_ip() . "\n"
                . "Fecha: " . date('d/m/Y H:i:s') . "\n\n"
                . "Mensaje:\n{$mensaje}\n";

            $host = parse_url((string) env('APP_URL', 'https://futbolistaschilenos.cl'), PHP_URL_HOST) ?: 'futbolistaschilenos.cl';
            $headers = [
                'MIME-Version: 1.0',
                'Content-Type: text/plain; charset=UTF-8',
                'From: ' . media_brand_name() . ' <noreply@' . $host . '>',
                'Reply-To: ' . $nombre . ' <' . $email . '>',
                'X-Mailer: ChilenosProyeccion-Contact',
            ];

            $subject = '[' . media_brand_name() . '] ' . $asuntoSafe;
            $sent = @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));

            $logDir = ROOT_PATH . '/storage/contact';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0700, true);
            }
            if (is_dir($logDir) && is_writable($logDir)) {
                $logFile = $logDir . '/' . date('Y-m-d') . '.log';
                @file_put_contents(
                    $logFile,
                    "----\n" . date('c') . " sent=" . ($sent ? '1' : '0') . "\n" . $body . "\n",
                    FILE_APPEND | LOCK_EX
                );
            }

            $ok = 'Gracias. Su mensaje ha sido recibido. Le responderemos a la brevedad al correo que indicó en el formulario.';
        }
    }
}

$pageTitle = 'Contacto | Chilenos Proyección';
$metaDescription = 'Contacto de Chilenos Proyección — familia Futbolistas Chilenos.';

require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container">
    <div class="section-head"><h1>Contacto</h1></div>
    <p class="page-intro">
      <?= e(media_brand_name()) ?> forma parte de la familia de <?= e(legal_entity_name()) ?>.
      Este es el canal oficial para consultas editoriales, correcciones, asuntos legales y propuestas.
      Complete el formulario y le responderemos a la dirección de correo que indique.
    </p>

    <div class="form-box form-box--contact">
      <?php if ($ok): ?><div class="alert alert-ok" role="status"><?= e($ok) ?></div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-err" role="alert"><?= e($err) ?></div><?php endif; ?>
      <form method="post" action="" novalidate>
        <?= csrf_field() ?>
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" required maxlength="120" autocomplete="name" value="<?= e((string) ($_POST['nombre'] ?? '')) ?>" />

        <label for="email">Su correo electrónico</label>
        <input id="email" type="email" name="email" required maxlength="180" autocomplete="email" placeholder="Para poder responderle" value="<?= e((string) ($_POST['email'] ?? '')) ?>" />

        <label for="asunto">Asunto</label>
        <select id="asunto" name="asunto">
          <?php
            $asuntos = [
                'Consulta general' => 'Consulta general',
                'Corrección de contenido' => 'Corrección de contenido',
                'Legal / Privacidad' => 'Legal / Privacidad',
                'Publicidad o auspicios' => 'Publicidad o auspicios',
                'Otro' => 'Otro',
            ];
            $sel = (string) ($_POST['asunto'] ?? 'Consulta general');
            foreach ($asuntos as $val => $lab):
          ?>
            <option value="<?= e($val) ?>" <?= $sel === $val ? 'selected' : '' ?>><?= e($lab) ?></option>
          <?php endforeach; ?>
        </select>

        <label for="mensaje">Mensaje</label>
        <textarea id="mensaje" name="mensaje" rows="6" required maxlength="5000"><?= e((string) ($_POST['mensaje'] ?? '')) ?></textarea>

        <label class="consent-check">
          <input type="checkbox" name="consentimiento" value="1" required <?= !empty($_POST['consentimiento']) ? 'checked' : '' ?> />
          <span>
            He leído y acepto la
            <a href="<?= e(app_url('/legales/politica-privacidad')) ?>" target="_blank" rel="noopener">Política de privacidad</a>
            y autorizo el tratamiento de mis datos para gestionar esta solicitud, conforme a la normativa chilena vigente.
          </span>
        </label>

        <button class="btn btn-primary" type="submit">Enviar mensaje</button>
      </form>
    </div>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
