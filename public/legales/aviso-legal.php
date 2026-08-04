<?php
declare(strict_types=1);
// incluido vía legales.php o router directo
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Aviso legal | ChilenosProyección';
$metaDescription = 'Aviso legal de ChilenosProyección — legislación chilena.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Aviso legal</h1></div>
    <p class="legal-updated">Última actualización: 3 de agosto de 2026 · Responsable del sitio: ChilenosProyección · Chile · <a href="<?= e(app_url('/legales/contacto-legal')) ?>">contacto legal</a></p>

    <h2>Identificación del medio</h2>
    <p><strong>Nombre comercial:</strong> ChilenosProyección<br>
    <strong>Actividad:</strong> medio digital de información deportiva (fútbol juvenil y formativas)<br>
    <strong>País:</strong> Chile<br>
    <strong>Sitio:</strong> chilenosproyeccion.cl (o el dominio activo en su momento)<br>
    <strong>Contacto:</strong> <a href="<?= e(app_url("/legales/contacto-legal")) ?>">canal legal</a> y formulario de <a href="<?= e(app_url("/contacto")) ?>">contacto</a>.</p>
    <h2>Carácter de la información</h2>
    <p>Los contenidos tienen fines informativos y de opinión periodística. No constituyen asesoría legal, médica ni de inversión. Resultados y tablas se basan en fuentes disponibles al momento de publicación.</p>
    <h2>Propiedad y marcas</h2>
    <p>Las marcas, escudos y nombres de clubes, ligas y la ANFP son de sus respectivos titulares. Su mención o reproducción en el sitio se realiza con fines de información periodística e identificación.</p>
    <h2>Transparencia</h2>
    <p>En línea con principios de la Ley 20.285 (transparencia de la función pública) no somos un órgano del Estado; sin embargo, procuramos claridad sobre quién edita el medio, cómo contactarnos y cómo tratamos datos personales.</p>
    <h2>Reclamos de consumo</h2>
    <p>Si actuamos como proveedor de un servicio digital de cara a consumidores en Chile, aplican las normas de protección al consumidor en lo pertinente. Preferimos resolver reclamos por el canal de contacto legal.</p>

  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
