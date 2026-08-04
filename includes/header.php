<?php
declare(strict_types=1);
$pageTitle = $pageTitle ?? 'ChilenosProyección';
$metaDescription = $metaDescription ?? 'Medio digital de fútbol joven chileno. Sub-20 a Infantil, goleadores, posiciones y proyecciones.';
$canonical = $canonical ?? app_url(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$ogImage = $ogImage ?? app_url('/assets/brand/logo-fj-naranja.png');
$bodyClass = $bodyClass ?? '';
$navActive = $navActive ?? '';
$divisiones = nav_divisiones();
$tickerBandas = ticker_bandas();
$socialFb = social_facebook_url();
$socialIg = social_instagram_url();
$logoFj = app_url('/assets/brand/logo-fj-naranja.png');
$logoFc = app_url('/assets/brand/logo-fc-rojo.png');
$faviconFc = app_url('/assets/brand/favicon-fc.png');
// Redes: solo menú móvil / footer — no en header superior
?>
<!DOCTYPE html>
<html lang="es-CL">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($metaDescription) ?>" />
  <link rel="canonical" href="<?= e($canonical) ?>" />
  <meta property="og:title" content="<?= e($pageTitle) ?>" />
  <meta property="og:description" content="<?= e($metaDescription) ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= e($canonical) ?>" />
  <meta property="og:image" content="<?= e($ogImage) ?>" />
  <meta property="og:locale" content="es_CL" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= e($pageTitle) ?>" />
  <meta name="twitter:description" content="<?= e($metaDescription) ?>" />
  <meta name="twitter:image" content="<?= e($ogImage) ?>" />
  <link rel="icon" href="<?= e($faviconFc) ?>" type="image/png" sizes="64x64" />
  <link rel="apple-touch-icon" href="<?= e($logoFc) ?>" />
  <meta name="theme-color" content="#FFFFFF" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)" />
  <meta name="color-scheme" content="light dark" />
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-5MHHWR6C');</script>
  <!-- End Google Tag Manager -->
  <!-- Google AdSense (auto ads — Google elige ubicaciones) -->
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9876535709659512"
     crossorigin="anonymous"></script>
  <script>
    (function () {
      try {
        var t = localStorage.getItem('cp-theme');
        if (t === 'light' || t === 'dark') {
          document.documentElement.setAttribute('data-theme', t);
        }
      } catch (e) {}
    })();
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="<?= e(app_url('/css/styles.css')) ?>?v=20260804g" />
</head>
<body class="<?= e($bodyClass) ?>">
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5MHHWR6C"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <a class="skip-link" href="#contenido">Ir al contenido</a>

  <div class="site-tickers" data-ticker-rotate aria-label="Goleadores, resultados y próximos">
    <?php
      $tickerIndex = 0;
      foreach ($tickerBandas as $banda):
        $items = $banda['items'] ?? [];
        if (!$items) {
            continue;
        }
        $loop = array_merge($items, $items);
        $kind = $banda['kind'] ?? 'goleadores';
        $isFirst = $tickerIndex === 0;
        $tickerIndex++;
    ?>
      <div
        class="site-ticker site-ticker--<?= e($kind) ?><?= $isFirst ? ' is-active' : '' ?>"
        role="region"
        aria-label="<?= e($banda['aria'] ?? $banda['label']) ?>"
        data-ticker-band
        <?= $isFirst ? '' : 'aria-hidden="true"' ?>
      >
        <div class="site-ticker-label"><?= e($banda['label']) ?></div>
        <div class="site-ticker-track-wrap">
          <div class="site-ticker-track">
            <?php foreach ($loop as $item): ?>
              <?php
                $kindItem = $item['kind'] ?? $kind;
                $escL = (string) ($item['escudo_local'] ?? $item['escudo_url'] ?? '');
                $escV = (string) ($item['escudo_visita'] ?? '');
                $clubL = (string) ($item['club_local'] ?? $item['club'] ?? '');
                $clubV = (string) ($item['club_visita'] ?? '');
                $score = (string) ($item['score'] ?? '');
                $cat = (string) ($item['categoria'] ?? '');
                $cuando = (string) ($item['cuando'] ?? '');
                $txt = (string) ($item['text'] ?? '');
              ?>
              <span class="site-ticker-item site-ticker-item--<?= e($kindItem) ?>">
                <?php if ($kindItem === 'goleador' || $kind === 'goleadores'): ?>
                  <?php if ($escL !== ''): ?>
                    <img class="site-ticker-crest" src="<?= e(app_url($escL)) ?>" alt="<?= e($clubL) ?>" width="18" height="18" loading="lazy" onerror="this.style.display='none'" />
                  <?php else: ?>
                    <span class="site-ticker-dot" aria-hidden="true"></span>
                  <?php endif; ?>
                  <span class="site-ticker-text"><?= e($txt) ?></span>
                <?php else: ?>
                  <?php if ($escL !== ''): ?>
                    <img class="site-ticker-crest" src="<?= e(app_url($escL)) ?>" alt="<?= e($clubL) ?>" title="<?= e($clubL) ?>" width="18" height="18" loading="lazy" onerror="this.style.display='none'" />
                  <?php endif; ?>
                  <span class="site-ticker-score"><?= e($score !== '' ? $score : 'vs') ?></span>
                  <?php if ($escV !== ''): ?>
                    <img class="site-ticker-crest" src="<?= e(app_url($escV)) ?>" alt="<?= e($clubV) ?>" title="<?= e($clubV) ?>" width="18" height="18" loading="lazy" onerror="this.style.display='none'" />
                  <?php endif; ?>
                  <?php if ($cuando !== ''): ?>
                    <span class="site-ticker-meta"><?= e($cuando) ?></span>
                  <?php endif; ?>
                  <?php if ($cat !== ''): ?>
                    <span class="site-ticker-meta"><?= e($cat) ?></span>
                  <?php endif; ?>
                <?php endif; ?>
              </span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <header class="site-header">
    <div class="container header-inner">
      <a class="logo" href="<?= e(app_url('/')) ?>" aria-label="Chilenos Proyección — ir al inicio" data-logo-brand>
        <span class="logo-swap" aria-hidden="true">
          <img
            class="logo-img logo-img--fj is-active"
            src="<?= e($logoFj) ?>"
            width="44"
            height="44"
            alt=""
            decoding="async"
          />
          <img
            class="logo-img logo-img--fc"
            src="<?= e($logoFc) ?>"
            width="44"
            height="44"
            alt=""
            decoding="async"
          />
        </span>
        <div class="logo-text">
          <strong data-logo-title>Chilenos Proyección</strong>
          <span data-logo-tagline class="logo-tagline">Fútbol joven</span>
        </div>
      </a>

      <nav class="nav-desktop" aria-label="Principal">
        <?php foreach ($divisiones as $key => $div): ?>
          <div class="nav-item has-dropdown <?= $navActive === $key ? 'is-active' : '' ?>">
            <button type="button" class="nav-link-btn" aria-expanded="false" aria-haspopup="true">
              <?= e($div['label']) ?>
              <svg class="chev" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="nav-dropdown" role="menu">
              <?php foreach ($div['items'] as $item): ?>
                <a role="menuitem" href="<?= e(app_url('/futbol-joven/' . $item['slug'])) ?>"><?= e($item['nombre']) ?></a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>

        <a class="<?= $navActive === 'goleadores' ? 'is-active' : '' ?>" href="<?= e(app_url('/goleadores/sub-20')) ?>">Goleadores</a>
        <a class="<?= $navActive === 'posiciones' ? 'is-active' : '' ?>" href="<?= e(app_url('/posiciones/sub-20')) ?>">Posiciones</a>
        <a class="<?= $navActive === 'programacion' ? 'is-active' : '' ?>" href="<?= e(app_url('/programacion/sub-20')) ?>">Programación</a>
      </nav>

      <div class="header-actions">
        <form class="header-search" action="<?= e(app_url('/buscador')) ?>" method="get" role="search">
          <input type="search" name="q" placeholder="Buscar…" value="<?= e($_GET['q'] ?? '') ?>" aria-label="Buscar" />
        </form>

        <button
          class="btn-theme"
          type="button"
          data-theme-toggle
          aria-label="Cambiar tema claro u oscuro"
          title="Cambiar tema"
        >
          <svg class="icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M21 14.5A8.5 8.5 0 1 1 9.5 3a7 7 0 0 0 11.5 11.5z"/>
          </svg>
          <svg class="icon-sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="12" cy="12" r="4"/>
            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
          </svg>
        </button>

        <button class="btn-menu" type="button" data-menu-open aria-label="Abrir menú" aria-controls="mobile-nav" aria-expanded="false">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
      </div>
    </div>
  </header>

  <div class="nav-backdrop" data-nav-backdrop hidden></div>
  <nav class="mobile-nav" id="mobile-nav" data-mobile-nav aria-label="Menú" hidden>
    <div class="mobile-nav-panel">
      <div class="mobile-nav-top">
        <div class="mobile-nav-brand" data-logo-brand>
          <span class="logo-swap logo-swap--sm" aria-hidden="true">
            <img class="logo-img logo-img--fj is-active" src="<?= e($logoFj) ?>" width="36" height="36" alt="" decoding="async" />
            <img class="logo-img logo-img--fc" src="<?= e($logoFc) ?>" width="36" height="36" alt="" decoding="async" />
          </span>
          <div class="logo-text">
            <strong data-logo-title>Chilenos Proyección</strong>
            <span data-logo-tagline class="logo-tagline">Fútbol joven</span>
          </div>
        </div>
        <button type="button" data-menu-close class="mobile-nav-close" aria-label="Cerrar menú">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
      </div>

      <form class="mobile-search" action="<?= e(app_url('/buscador')) ?>" method="get" role="search">
        <input type="search" name="q" placeholder="Buscar noticias…" value="<?= e($_GET['q'] ?? '') ?>" aria-label="Buscar" />
      </form>

      <div class="mobile-nav-scroll">
        <?php foreach ($divisiones as $div): ?>
          <details class="mobile-acc">
            <summary class="mobile-acc-summary">
              <span><?= e($div['label']) ?></span>
              <svg class="mobile-acc-chev" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
            </summary>
            <div class="mobile-acc-body">
              <?php foreach ($div['items'] as $item): ?>
                <a href="<?= e(app_url('/futbol-joven/' . $item['slug'])) ?>"><?= e($item['nombre']) ?></a>
              <?php endforeach; ?>
            </div>
          </details>
        <?php endforeach; ?>

        <div class="mobile-links">
          <a href="<?= e(app_url('/goleadores/sub-20')) ?>">Goleadores</a>
          <a href="<?= e(app_url('/posiciones/sub-20')) ?>">Posiciones</a>
          <a href="<?= e(app_url('/programacion/sub-20')) ?>">Programación</a>
          <a href="<?= e(app_url('/newsletter')) ?>">Newsletter</a>
          <a href="<?= e(app_url('/contacto')) ?>">Contacto</a>
        </div>
        <div class="mobile-social" aria-label="Redes sociales">
          <a class="btn-social" href="<?= e($socialFb) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook" title="Facebook">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 8h3V4h-3c-2.8 0-5 2.2-5 5v2H7v4h2v7h4v-7h3l1-4h-4V9c0-.6.4-1 1-1z"/></svg>
          </a>
          <a class="btn-social" href="<?= e($socialIg) ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <rect x="3" y="3" width="18" height="18" rx="5"/>
              <circle cx="12" cy="12" r="4"/>
              <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <main class="site-main" id="contenido">
