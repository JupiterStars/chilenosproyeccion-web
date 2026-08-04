<?php
declare(strict_types=1);
// incluido vía legales.php o router directo
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Propiedad intelectual | ChilenosProyección';
$metaDescription = 'Propiedad intelectual de ChilenosProyección — legislación chilena.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Propiedad intelectual</h1></div>
    <p class="legal-updated">Última actualización: 3 de agosto de 2026 · Responsable del sitio: ChilenosProyección · Chile · <a href="<?= e(app_url('/legales/contacto-legal')) ?>">contacto legal</a></p>

    <h2>Contenidos propios</h2>
    <p>Textos, fotografías propias, diseño, logotipo y selección editorial son de ChilenosProyección o se usan con permiso. Queda prohibida la reproducción total o el uso comercial sin autorización escrita, salvo citas breves con enlace y crédito.</p>
    <h2>Licencias de terceros</h2>
    <p>Algunos elementos del sitio (tipografías u otros recursos) pueden ser de terceros con su licencia; las respetamos.</p>
    <h2>Escudos y material de clubes</h2>
    <p>Los escudos se muestran para identificar clubes en coberturas de formativas. No implica afiliación. Si un titular solicita retiro o ajuste, contactanos por el canal legal y atenderemos con diligencia.</p>
    <h2>Contenido generado con IA</h2>
    <p>Podemos usar herramientas digitales como apoyo a la redacción. La responsabilidad editorial final es del medio.</p>
    <h2>Denuncias de infracción</h2>
    <p>Si considerás que un contenido vulnera tus derechos, escribí a <a href="<?= e(app_url("/legales/contacto-legal")) ?>">contacto legal</a> con URL, descripción del derecho y prueba de titularidad. Evaluaremos y, si corresponde, retiraremos o modificaremos el material.</p>

  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
