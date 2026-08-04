<?php declare(strict_types=1); ?>
  </main>

  <footer class="site-footer">
    <div class="container footer-grid">
      <div>
        <a class="logo logo--footer" href="<?= e(app_url('/')) ?>">
          <img
            class="logo-img logo-img--static"
            src="<?= e(app_url('/assets/brand/logo-fc-rojo.png')) ?>?v=20260804j"
            width="44"
            height="44"
            alt="Futbolistas Chilenos"
            decoding="async"
          />
          <div class="logo-text">
            <strong>Chilenos Proyección</strong>
            <span>Familia Futbolistas Chilenos</span>
          </div>
        </a>
        <p class="footer-tagline">
          <strong class="footer-family">Chilenos Proyección es parte de la familia de Futbolistas Chilenos.</strong>
          Medio digital chileno de fútbol juvenil y formativas. Cobertura de cancha a pantalla.
        </p>
      </div>
      <div>
        <h4>Secciones</h4>
        <ul>
          <li><a href="<?= e(app_url('/futbol-joven')) ?>">Fútbol joven</a></li>
          <li><a href="<?= e(app_url('/goleadores/sub-20')) ?>">Goleadores</a></li>
          <li><a href="<?= e(app_url('/posiciones/sub-20')) ?>">Posiciones</a></li>
          <li><a href="<?= e(app_url('/programacion/sub-20')) ?>">Programación</a></li>
        </ul>
      </div>
      <div>
        <h4>Sitio</h4>
        <ul>
          <li><a href="<?= e(app_url('/quienes-somos')) ?>">Quiénes somos</a></li>
          <li><a href="<?= e(app_url('/contacto')) ?>">Contacto</a></li>
        </ul>
        <div class="footer-social">
          <a href="<?= e(social_facebook_url()) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook" title="Facebook">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 8h3V4h-3c-2.8 0-5 2.2-5 5v2H7v4h2v7h4v-7h3l1-4h-4V9c0-.6.4-1 1-1z"/></svg>
          </a>
          <a href="<?= e(social_instagram_url()) ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <rect x="3" y="3" width="18" height="18" rx="5"/>
              <circle cx="12" cy="12" r="4"/>
              <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
            </svg>
          </a>
        </div>
      </div>
      <div>
        <h4>Legales</h4>
        <ul>
          <li><a href="<?= e(app_url('/legales/politica-privacidad')) ?>">Privacidad</a></li>
          <li><a href="<?= e(app_url('/legales/terminos-y-condiciones')) ?>">Términos</a></li>
          <li><a href="<?= e(app_url('/legales/politica-cookies')) ?>">Cookies</a></li>
          <li><a href="<?= e(app_url('/legales/aviso-legal')) ?>">Aviso legal</a></li>
          <li><a href="<?= e(app_url('/legales/propiedad-intelectual')) ?>">Propiedad intelectual</a></li>
          <li><a href="<?= e(app_url('/legales/contacto-legal')) ?>">Contacto legal</a></li>
          <li><a href="<?= e(app_url('/legales/politica-editorial')) ?>">Política editorial</a></li>
        </ul>
      </div>
    </div>
    <div class="container footer-bottom">
      <div class="footer-bottom-inner">
        <span class="footer-copy">© <?= date('Y') ?> Futbolistas Chilenos · Chile</span>
        <span class="footer-dev">Desarrollado por Atlas Tecnologic</span>
      </div>
    </div>
  </footer>

  <div class="cookie-consent" data-cookie-banner hidden role="dialog" aria-modal="false" aria-labelledby="cookie-consent-title" aria-describedby="cookie-consent-desc">
    <div class="cookie-consent-backdrop" data-cookie-backdrop aria-hidden="true"></div>
    <div class="cookie-consent-panel">
      <div class="cookie-consent-accent" aria-hidden="true"></div>
      <div class="cookie-consent-content">
        <div class="cookie-consent-icon" aria-hidden="true">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
            <circle cx="12" cy="12" r="9"/>
            <circle cx="9" cy="10" r="1.2" fill="currentColor" stroke="none"/>
            <circle cx="14.5" cy="9" r="1" fill="currentColor" stroke="none"/>
            <circle cx="11" cy="14.5" r="1.1" fill="currentColor" stroke="none"/>
            <circle cx="15" cy="14" r="0.9" fill="currentColor" stroke="none"/>
          </svg>
        </div>
        <div class="cookie-consent-copy">
          <h2 id="cookie-consent-title" class="cookie-consent-title">Tu privacidad en este sitio</h2>
          <p id="cookie-consent-desc" class="cookie-consent-text">
            Utilizamos cookies <strong>necesarias</strong> para el funcionamiento del sitio (por ejemplo, preferencias de visualización y el registro de su elección).
            Con su consentimiento también podemos emplear cookies para <strong>medir visitas</strong> y, si corresponde, <strong>publicidad</strong>.
            Puede aceptar todas o continuar solo con las necesarias. La información se trata conforme a la normativa chilena vigente y a las políticas de
            <strong>Futbolistas Chilenos</strong> / <strong>Chilenos Proyección</strong>.
          </p>
          <p class="cookie-consent-links">
            <a href="<?= e(app_url('/legales/politica-cookies')) ?>">Política de cookies</a>
            <span aria-hidden="true">·</span>
            <a href="<?= e(app_url('/legales/politica-privacidad')) ?>">Privacidad</a>
          </p>
        </div>
        <div class="cookie-consent-actions">
          <button type="button" class="cookie-btn cookie-btn--ghost" data-cookie-reject>Solo necesarias</button>
          <button type="button" class="cookie-btn cookie-btn--primary" data-cookie-accept>Aceptar todas</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="<?= e(app_url('/js/main.js')) ?>?v=20260804h"></script>
</body>
</html>
