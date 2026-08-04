<?php
declare(strict_types=1);
// incluido vía legales.php o router directo
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Términos y condiciones | ChilenosProyección';
$metaDescription = 'Términos y condiciones de ChilenosProyección — legislación chilena.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Términos y condiciones</h1></div>
    <p class="legal-updated">Última actualización: 3 de agosto de 2026 · Responsable del sitio: ChilenosProyección · Chile · <a href="<?= e(app_url('/legales/contacto-legal')) ?>">contacto legal</a></p>

    <h2>1. Aceptación</h2>
    <p>Al acceder a chilenosproyeccion.cl (o dominio que lo reemplace) aceptás estos términos. Si no estás de acuerdo, no uses el sitio.</p>
    <h2>2. Objeto del servicio</h2>
    <p>Somos un medio digital de fútbol juvenil y formativas chilenas: noticias, tablas, programación, entrevistas y contenido relacionado. El servicio es informativo; no somos la ANFP ni un club afiliado.</p>
    <h2>3. Uso permitido</h2>
    <p>Podés navegar, compartir enlaces y citar extractos con atribución. Está prohibido: scrapear de forma abusiva, intentar vulnerar seguridad, suplantar identidad, publicar spam en formularios o usar el contenido para competir de mala fe sin autorización.</p>
    <h2>4. Cuentas y newsletter</h2>
    <p>Si te suscribís al newsletter o contactás el sitio, te comprometés a entregar datos veraces. Podemos suspender el envío ante abuso o baja voluntaria.</p>
    <h2>5. Contenido de terceros y enlaces</h2>
    <p>Podemos enlazar a sitios externos (clubes, federación, redes). No controlamos esos sitios ni respondemos por su contenido o políticas.</p>
    <h2>6. Limitación de responsabilidad</h2>
    <p>Hacemos esfuerzos razonables de exactitud, pero tablas, resultados y crónicas pueden contener errores o retrasos. El sitio se ofrece “tal cual”. En la medida permitida por la ley chilena (incl. normas de protección al consumidor cuando apliquen), no respondemos por daños indirectos derivados del uso de la información.</p>
    <h2>7. Propiedad intelectual</h2>
    <p>Textos, diseño, marca y selección editorial pertenecen a ChilenosProyección o a sus licenciantes. Escudos y marcas de clubes son de sus titulares; se usan con fines informativos/identificativos.</p>
    <h2>8. Publicidad</h2>
    <p>Podemos mostrar publicidad propia o de terceros. Los anunciantes son responsables de sus mensajes.</p>
    <h2>9. Legislación y jurisdicción</h2>
    <p>Estos términos se rigen por las leyes de la República de Chile. Para controversias, se privilegia la mediación y, en su caso, los tribunales competentes de Chile.</p>
    <h2>10. Cambios</h2>
    <p>Podemos actualizar estos términos; la fecha de actualización se indica arriba. El uso continuado implica aceptación de la versión vigente.</p>

  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
