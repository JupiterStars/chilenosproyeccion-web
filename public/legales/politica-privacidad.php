<?php
declare(strict_types=1);
// incluido vía legales.php o router directo
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
}
$pageTitle = 'Política de privacidad | ChilenosProyección';
$metaDescription = 'Política de privacidad de ChilenosProyección — legislación chilena.';
require INCLUDES_PATH . '/header.php';
?>
<section class="section">
  <div class="container legal-content">
    <div class="section-head"><h1>Política de privacidad</h1></div>
    <p class="legal-updated">Última actualización: 3 de agosto de 2026 · Responsable del sitio: ChilenosProyección · Chile · <a href="<?= e(app_url('/legales/contacto-legal')) ?>">contacto legal</a></p>

    <h2>1. Responsable</h2>
    <p><strong>ChilenosProyección</strong>, medio digital con domicilio en Chile. Contacto de privacidad: vía <a href="<?= e(app_url("/legales/contacto-legal")) ?>">contacto legal</a> o el correo publicado en esa página.</p>
    <h2>2. Marco legal</h2>
    <p>Tratamos datos personales conforme a la <strong>Ley N° 19.628</strong> sobre Protección de la Vida Privada y a las actualizaciones y lineamientos aplicables en Chile (incluida la evolución hacia un marco reforzado de protección de datos y derechos de las personas). Cuando corresponda, también consideramos principios de minimización, finalidad y seguridad.</p>
    <h2>3. Datos que recogemos</h2>
    <ul class="legal-list">
      <li><strong>Newsletter:</strong> correo electrónico, fecha de alta y estado (activo/baja).</li>
      <li><strong>Contacto:</strong> nombre, correo, mensaje y metadatos técnicos del envío.</li>
      <li><strong>Navegación:</strong> IP, user-agent, páginas vistas, vía cookies o similares (ver política de cookies), si consentís analítica/publicidad.</li>
      <li><strong>Equipo del medio:</strong> datos de acceso del personal autorizado (no públicos).</li>
    </ul>
    <h2>4. Finalidades</h2>
    <p>Enviar el boletín si te suscribís; responder consultas; mejorar el sitio; medir visitas y, si aceptás, publicidad; seguridad del servicio; y cumplir la ley.</p>
    <h2>5. Base y legitimación</h2>
    <p>Consentimiento (formularios y cookies no esenciales); interés legítimo en seguridad del servicio; ejecución de medidas precontractuales o contractuales cuando exista relación con auspiciadores o proveedores.</p>
    <h2>6. Encargados y transferencias</h2>
    <p>Para alojar el sitio, enviar correos, medir visitas o mostrar publicidad podemos trabajar con proveedores de servicio. Algunos pueden operar desde fuera de Chile; en ese caso buscamos medidas razonables de protección.</p>
    <h2>7. Plazos de conservación</h2>
    <p>Newsletter: mientras la suscripción esté activa y un período razonable tras la baja para gestionar bloqueos. Mensajes de contacto: el tiempo necesario para gestionar la solicitud. Logs técnicos: plazos cortos de seguridad. Datos de publicidad/analítica: según configuración del proveedor y del consentimiento.</p>
    <h2>8. Derechos (ARCO y afines)</h2>
    <p>Podés solicitar <strong>acceso, rectificación, cancelación/eliminación y oposición</strong> al tratamiento de tus datos, y revocar el consentimiento cuando la base sea el consentimiento. También podés pedir información sobre cesiones. Ejercé tus derechos por el canal de <a href="<?= e(app_url("/legales/contacto-legal")) ?>">contacto legal</a>, acreditando identidad de forma razonable. Responderemos en plazos compatibles con la normativa vigente.</p>
    <h2>9. Menores</h2>
    <p>El sitio informa sobre fútbol juvenil. No dirigimos captación de datos a menores sin intervención de adultos responsables. Imágenes y menciones de menores se tratan con cuidado editorial (ver política editorial y Ley 21.430 en lo que corresponda).</p>
    <h2>10. Seguridad</h2>
    <p>Cuidamos la seguridad del sitio con medidas razonables. Ningún servicio en internet es 100 % seguro: no envíes contraseñas ni datos muy sensibles por el formulario de contacto.</p>
    <h2>11. Cambios</h2>
    <p>Publicaremos la versión actualizada en esta URL con nueva fecha.</p>

  </div>
</section>
<?php require INCLUDES_PATH . '/footer.php'; ?>
