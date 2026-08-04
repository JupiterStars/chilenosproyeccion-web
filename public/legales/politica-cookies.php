<?php
declare(strict_types=1);
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Política de cookies | Chilenos Proyección';
$metaDescription = 'Política de cookies y aviso de consentimiento — Chilenos Proyección · Futbolistas Chilenos.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Política de cookies</h1></div>

    <p class="legal-lead">
      Esta política explica el uso de cookies y tecnologías similares en el sitio de
      <strong><?= e(media_brand_name()) ?></strong>, operado por la familia
      <strong><?= e(legal_entity_name()) ?></strong>, y cómo se obtiene su consentimiento.
    </p>

    <h2>1. Qué son las cookies</h2>
    <p>
      Las cookies son pequeños archivos o identificadores que el sitio o terceros autorizados pueden
      almacenar en su navegador. Permiten recordar preferencias, mantener la seguridad del servicio y,
      si usted lo autoriza, medir el uso del sitio o gestionar publicidad.
    </p>

    <h2>2. Aviso de consentimiento</h2>
    <p>
      Al ingresar al sitio se muestra un <strong>aviso de consentimiento</strong> que le permite:
    </p>
    <ul class="legal-list">
      <li><strong>Aceptar todas:</strong> habilita cookies necesarias y las opcionales (analítica y, si corresponde, publicidad).</li>
      <li><strong>Solo necesarias:</strong> limita el uso a cookies imprescindibles para el funcionamiento del sitio.</li>
    </ul>
    <p>
      Su elección se almacena de forma local en el navegador para no interrumpirle en cada visita. Puede
      cambiarla borrando los datos de este sitio desde la configuración de su navegador y volviendo a
      cargar la página.
    </p>

    <h2>3. Categorías que utilizamos</h2>
    <ul class="legal-list">
      <li>
        <strong>Cookies necesarias (propias del sitio):</strong> permiten el funcionamiento básico
        (por ejemplo, preferencias de visualización y el registro de su respuesta al aviso de cookies).
        No requieren un consentimiento adicional más allá del uso del servicio.
      </li>
      <li>
        <strong>Cookies de analítica de terceros (opcionales):</strong> herramientas de proveedores
        externos que ayudan a comprender, de forma agregada o seudonimizada, cómo se utiliza el sitio.
        Solo se activan si usted acepta las cookies opcionales. Esos proveedores tratan la información
        conforme a sus propias políticas y a la normativa aplicable.
      </li>
      <li>
        <strong>Cookies de publicidad de terceros (opcionales, cuando estén habilitadas):</strong>
        permiten a redes o plataformas publicitarias de terceros mostrar o medir anuncios. Solo se
        activan con su consentimiento.
      </li>
    </ul>

    <h2>4. Cookies de terceros</h2>
    <p>
      Las cookies de medición y publicidad no son gestionadas por <?= e(legal_entity_name()) ?> de forma
      directa: corresponden a servicios de terceros (por ejemplo, plataformas de analítica o de anuncios)
      que se incorporan al sitio cuando usted las autoriza. Cada proveedor es responsable del tratamiento
      que realiza con sus propias tecnologías. Si elige solo cookies necesarias, esas herramientas de
      terceros no se activan con fines de medición o publicidad.
    </p>

    <h2>5. Gestión desde el navegador</h2>
    <p>
      Además del aviso del sitio, puede bloquear o eliminar cookies desde las opciones de su navegador
      (Chrome, Firefox, Safari, Edge u otros). Si bloquea todas las cookies, algunas preferencias del
      sitio podrían no guardarse.
    </p>

    <h2>6. Datos personales y derechos</h2>
    <p>
      Cuando las cookies de terceros traten o se asocien a datos personales, resultará aplicable la
      <a href="<?= e(app_url('/legales/politica-privacidad')) ?>">Política de privacidad</a>
      y, en lo que corresponda, la política de cada proveedor. Para ejercer derechos o formular consultas
      ante este medio, utilice el
      <a href="<?= e(app_url('/contacto')) ?>">formulario de contacto</a>
      con el asunto Legal / Privacidad.
    </p>

    <h2>7. Actualizaciones</h2>
    <p>
      Esta política puede modificarse para reflejar cambios en el sitio o en la normativa. La versión
      vigente se publica en esta dirección.
    </p>
  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
