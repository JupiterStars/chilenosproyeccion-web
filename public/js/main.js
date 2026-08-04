/* ChilenosProyección — theme, menú móvil, Swiper, cookies */
(function () {
  var STORAGE_KEY = "cp-theme";
  var COOKIE_KEY = "cp-cookie-consent";

  function systemTheme() {
    return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
  }

  function currentTheme() {
    var forced = document.documentElement.getAttribute("data-theme");
    if (forced === "light" || forced === "dark") return forced;
    return systemTheme();
  }

  function applyTheme(theme) {
    if (theme === "light" || theme === "dark") {
      document.documentElement.setAttribute("data-theme", theme);
      try {
        localStorage.setItem(STORAGE_KEY, theme);
      } catch (e) {}
    }
    updateThemeMeta(theme);
    updateToggleLabel();
  }

  function updateThemeMeta(theme) {
    var meta = document.querySelector('meta[name="theme-color"]:not([media])');
    if (!meta) {
      meta = document.createElement("meta");
      meta.setAttribute("name", "theme-color");
      document.head.appendChild(meta);
    }
    meta.setAttribute("content", theme === "dark" ? "#000000" : "#FFFFFF");
  }

  function updateToggleLabel() {
    var btn = document.querySelector("[data-theme-toggle]");
    if (!btn) return;
    var next = currentTheme() === "dark" ? "claro" : "oscuro";
    btn.setAttribute("aria-label", "Cambiar a modo " + next);
    btn.setAttribute("title", "Modo " + next);
  }

  function toggleTheme() {
    applyTheme(currentTheme() === "dark" ? "light" : "dark");
  }

  document.addEventListener("DOMContentLoaded", function () {
    updateToggleLabel();
    updateThemeMeta(currentTheme());
    initCookies();
    initMobileNav();
    initDesktopDropdowns();
    initTickerRotate();
    initLogoSwap();
    initMatchRotate();
    initScorersRotate();
    initNavSelects();
    initSectionScroll();
  });

  /** Alterna logo Fútbol Joven (naranja) y Futbolistas Chilenos (rojo) cada 5s + textos */
  function initLogoSwap() {
    var brands = document.querySelectorAll("[data-logo-brand]");
    var swaps = document.querySelectorAll(".logo-swap");
    if (!swaps.length) return;
    var reduce = false;
    try {
      reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    } catch (e) {}

    function applyState(showFc) {
      swaps.forEach(function (root) {
        var a = root.querySelector(".logo-img--fj");
        var b = root.querySelector(".logo-img--fc");
        if (!a || !b) return;
        a.classList.toggle("is-active", !showFc);
        b.classList.toggle("is-active", showFc);
      });
      brands.forEach(function (brand) {
        var title = brand.querySelector("[data-logo-title]");
        var tag = brand.querySelector("[data-logo-tagline]");
        if (title) {
          title.textContent = showFc ? "Futbolistas Chilenos" : "Chilenos Proyección";
        }
        if (tag) {
          tag.textContent = "Fútbol joven";
          tag.classList.toggle("is-fc", showFc);
        }
        brand.classList.toggle("is-fc-brand", showFc);
      });
    }

    var showFc = false;
    applyState(false);
    if (reduce) return;
    window.setInterval(function () {
      showFc = !showFc;
      applyState(showFc);
    }, 5000);
  }

  document.querySelectorAll("[data-theme-toggle]").forEach(function (btn) {
    btn.addEventListener("click", toggleTheme);
  });

  try {
    window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", function () {
      if (!localStorage.getItem(STORAGE_KEY)) {
        updateToggleLabel();
        updateThemeMeta(currentTheme());
      }
    });
  } catch (e) {}

  /**
   * Desktop: menús Nacional/Regional/Infantil estables al bajar el mouse.
   * - Hover mantiene abierto (CSS + puente)
   * - Click en el botón fija is-open (útil si el hover falla)
   * - Cierra al salir del item con pequeño delay o al click afuera
   */
  function initDesktopDropdowns() {
    var items = document.querySelectorAll(".nav-desktop .nav-item.has-dropdown");
    if (!items.length) return;

    var closeTimers = new WeakMap();

    function clearTimer(item) {
      var t = closeTimers.get(item);
      if (t) {
        window.clearTimeout(t);
        closeTimers.delete(item);
      }
    }

    function openItem(item) {
      clearTimer(item);
      items.forEach(function (other) {
        if (other !== item) {
          other.classList.remove("is-open");
          var ob = other.querySelector(".nav-link-btn");
          if (ob) ob.setAttribute("aria-expanded", "false");
        }
      });
      item.classList.add("is-open");
      var btn = item.querySelector(".nav-link-btn");
      if (btn) btn.setAttribute("aria-expanded", "true");
    }

    function scheduleClose(item) {
      clearTimer(item);
      var t = window.setTimeout(function () {
        if (!item.matches(":hover") && !item.contains(document.activeElement)) {
          item.classList.remove("is-open");
          var btn = item.querySelector(".nav-link-btn");
          if (btn) btn.setAttribute("aria-expanded", "false");
        }
        closeTimers.delete(item);
      }, 160);
      closeTimers.set(item, t);
    }

    items.forEach(function (item) {
      var btn = item.querySelector(".nav-link-btn");
      if (!btn) return;

      item.addEventListener("mouseenter", function () {
        openItem(item);
      });
      item.addEventListener("mouseleave", function () {
        scheduleClose(item);
      });

      btn.addEventListener("click", function (ev) {
        ev.preventDefault();
        ev.stopPropagation();
        if (item.classList.contains("is-open") && item.matches(":hover")) {
          // segundo click con hover: mantener abierto (no toggle confuso)
          openItem(item);
          return;
        }
        if (item.classList.contains("is-open")) {
          item.classList.remove("is-open");
          btn.setAttribute("aria-expanded", "false");
        } else {
          openItem(item);
        }
      });

      item.querySelectorAll(".nav-dropdown a").forEach(function (a) {
        a.addEventListener("focus", function () {
          openItem(item);
        });
      });
    });

    document.addEventListener("click", function (ev) {
      var t = ev.target;
      if (!(t instanceof Element)) return;
      if (t.closest(".nav-desktop .nav-item.has-dropdown")) return;
      items.forEach(function (item) {
        item.classList.remove("is-open");
        var btn = item.querySelector(".nav-link-btn");
        if (btn) btn.setAttribute("aria-expanded", "false");
      });
    });

    document.addEventListener("keydown", function (ev) {
      if (ev.key !== "Escape") return;
      items.forEach(function (item) {
        item.classList.remove("is-open");
        var btn = item.querySelector(".nav-link-btn");
        if (btn) btn.setAttribute("aria-expanded", "false");
      });
    });
  }

  function initMobileNav() {
    var menuBtn = document.querySelector("[data-menu-open]");
    var menuClose = document.querySelector("[data-menu-close]");
    var drawer = document.querySelector("[data-mobile-nav]");
    var backdrop = document.querySelector("[data-nav-backdrop]");

    function openNav() {
      if (!drawer) return;
      drawer.hidden = false;
      if (backdrop) {
        backdrop.hidden = false;
        requestAnimationFrame(function () {
          backdrop.classList.add("is-open");
        });
      }
      requestAnimationFrame(function () {
        drawer.classList.add("is-open");
      });
      document.body.classList.add("nav-open");
      if (menuBtn) menuBtn.setAttribute("aria-expanded", "true");
    }

    function closeNav() {
      if (!drawer) return;
      drawer.classList.remove("is-open");
      if (backdrop) backdrop.classList.remove("is-open");
      document.body.classList.remove("nav-open");
      if (menuBtn) menuBtn.setAttribute("aria-expanded", "false");
      window.setTimeout(function () {
        if (!drawer.classList.contains("is-open")) {
          drawer.hidden = true;
          if (backdrop) backdrop.hidden = true;
        }
      }, 280);
    }

    if (menuBtn) menuBtn.addEventListener("click", openNav);
    if (menuClose) menuClose.addEventListener("click", closeNav);
    if (backdrop) backdrop.addEventListener("click", closeNav);
    if (drawer) {
      drawer.querySelectorAll("a").forEach(function (a) {
        a.addEventListener("click", closeNav);
      });
      // Un solo desplegable de primer nivel abierto a la vez
      drawer.querySelectorAll(".mobile-acc").forEach(function (acc) {
        acc.addEventListener("toggle", function () {
          if (!acc.open) return;
          drawer.querySelectorAll(".mobile-acc").forEach(function (other) {
            if (other !== acc) other.open = false;
          });
        });
      });
      // Nested (Nacional / Sub-X / zona): un solo hermano abierto por nivel
      drawer.querySelectorAll(".mobile-acc-nested").forEach(function (nested) {
        nested.addEventListener("toggle", function () {
          if (!nested.open) return;
          var parent = nested.parentElement;
          if (!parent) return;
          Array.prototype.forEach.call(parent.children, function (child) {
            if (
              child !== nested &&
              child.classList &&
              child.classList.contains("mobile-acc-nested")
            ) {
              child.open = false;
            }
          });
        });
      });
    }
    document.addEventListener("keydown", function (ev) {
      if (ev.key === "Escape") closeNav();
    });
  }

  if (typeof Swiper !== "undefined" && document.querySelector(".hero-swiper")) {
    new Swiper(".hero-swiper", {
      loop: true,
      autoplay: { delay: 6000, disableOnInteraction: false },
      pagination: { el: ".swiper-pagination", clickable: true },
      effect: "fade",
      fadeEffect: { crossFade: true },
      speed: 700,
    });
  }

  function initCookies() {
    var banner = document.querySelector("[data-cookie-banner]");
    if (!banner) return;
    var stored = null;
    try {
      stored = localStorage.getItem(COOKIE_KEY);
    } catch (e) {}

    function openBanner() {
      banner.hidden = false;
      // forzar reflow para animación
      void banner.offsetWidth;
      banner.classList.add("is-open");
      document.body.classList.add("cookie-open");
    }

    function closeBanner() {
      banner.classList.remove("is-open");
      document.body.classList.remove("cookie-open");
      window.setTimeout(function () {
        if (!banner.classList.contains("is-open")) {
          banner.hidden = true;
        }
      }, 320);
    }

    function save(v) {
      try {
        localStorage.setItem(COOKIE_KEY, v);
      } catch (e) {}
      closeBanner();
    }

    var accept = document.querySelector("[data-cookie-accept]");
    var reject = document.querySelector("[data-cookie-reject]");
    if (accept) {
      accept.addEventListener("click", function (ev) {
        ev.preventDefault();
        ev.stopPropagation();
        save("all");
      });
    }
    if (reject) {
      reject.addEventListener("click", function (ev) {
        ev.preventDefault();
        ev.stopPropagation();
        save("essential");
      });
    }

    // Solo mostrar si no hay preferencia; la página sigue clickeable debajo
    if (!stored) {
      window.setTimeout(openBanner, 400);
    }
  }

  /**
   * Un solo ticker visible: turna Goleadores → Resultados → Próximos.
   * No usa display:none ni hidden: las pistas siguen animando debajo
   * para que al volver no empiecen de cero.
   */
  function initTickerRotate() {
    var root = document.querySelector("[data-ticker-rotate]");
    if (!root) return;
    var bands = Array.prototype.slice.call(root.querySelectorAll("[data-ticker-band]"));
    if (bands.length < 2) return;

    var idx = 0;
    var INTERVAL_MS = 8000;

    function show(i) {
      bands.forEach(function (band, j) {
        var on = j === i;
        band.classList.toggle("is-active", on);
        if (on) {
          band.removeAttribute("aria-hidden");
        } else {
          band.setAttribute("aria-hidden", "true");
        }
      });
    }

    show(0);
    window.setInterval(function () {
      idx = (idx + 1) % bands.length;
      show(idx);
    }, INTERVAL_MS);
  }

  /**
   * Home móvil: páginas de 4 partidos (próximos/resultados).
   * Cada página completa rota cada 4s. Si hay 1 sola página, no rota.
   */
  function initMatchRotate() {
    var roots = document.querySelectorAll("[data-match-rotate]");
    if (!roots.length) return;
    var reduce = false;
    try {
      reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    } catch (e) {}
    if (reduce) return;

    roots.forEach(function (root) {
      var slides = Array.prototype.slice.call(root.querySelectorAll("[data-match-slide]"));
      if (slides.length < 2) return;
      var idx = 0;
      var ms = parseInt(root.getAttribute("data-interval") || "4000", 10) || 4000;

      function show(i) {
        slides.forEach(function (slide, j) {
          var on = j === i;
          slide.classList.toggle("is-active", on);
          if (on) {
            slide.removeAttribute("hidden");
          } else {
            slide.setAttribute("hidden", "");
          }
        });
      }

      show(0);
      window.setInterval(function () {
        idx = (idx + 1) % slides.length;
        show(idx);
      }, ms);
    });
  }

  /**
   * Home móvil: tabla de goleadores rota Sub-20 → Sub-18 → … cada 8s.
   */
  function initScorersRotate() {
    var roots = document.querySelectorAll("[data-scorers-rotate]");
    if (!roots.length) return;
    var reduce = false;
    try {
      reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    } catch (e) {}
    if (reduce) return;

    roots.forEach(function (root) {
      var slides = Array.prototype.slice.call(root.querySelectorAll("[data-scorers-slide]"));
      if (slides.length < 2) return;
      var idx = 0;
      var ms = parseInt(root.getAttribute("data-interval") || "8000", 10) || 8000;
      var sub = root.closest(".home-mobile")
        ? document.querySelector("[data-scorers-sub]")
        : null;

      function show(i) {
        slides.forEach(function (slide, j) {
          var on = j === i;
          slide.classList.toggle("is-active", on);
          if (on) {
            slide.removeAttribute("hidden");
            if (sub) {
              sub.textContent = slide.getAttribute("data-scorers-label") || "";
            }
          } else {
            slide.setAttribute("hidden", "");
          }
        });
      }

      show(0);
      window.setInterval(function () {
        idx = (idx + 1) % slides.length;
        show(idx);
      }, ms);
    });
  }

  /** Select de categoría/zona en móvil: navega o hace scroll a ancla */
  function initNavSelects() {
    document.querySelectorAll("[data-nav-select]").forEach(function (sel) {
      sel.addEventListener("change", function () {
        var val = sel.value || "";
        if (!val || val === "#top") return;
        if (val.charAt(0) === "#") {
          scrollToSectionId(val.slice(1), true);
          return;
        }
        window.location.href = val;
      });
    });
  }

  /**
   * Scroll a #sec-zona dejando espacio para header + ticker + subnav sticky.
   * Evita aterrizar a mitad de la tabla (p. ej. puesto 5 de otra zona).
   */
  function stickyOffset() {
    var h = 12;
    var tickers = document.querySelector(".site-tickers");
    var header = document.querySelector(".site-header");
    var subnav = document.querySelector(".subnav-sticky");
    if (tickers) h += tickers.offsetHeight || 0;
    if (header) h += header.offsetHeight || 0;
    if (subnav) h += subnav.offsetHeight || 0;
    // holgura extra en móvil (subnav multi-línea)
    if (window.matchMedia && window.matchMedia("(max-width: 767px)").matches) {
      h += 16;
    }
    return h;
  }

  function scrollToSectionId(id, smooth) {
    if (!id) return false;
    var el = document.getElementById(id);
    if (!el) return false;
    var y = el.getBoundingClientRect().top + window.pageYOffset - stickyOffset();
    window.scrollTo({
      top: Math.max(0, y),
      behavior: smooth ? "smooth" : "auto",
    });
    return true;
  }

  function initSectionScroll() {
    function go(smooth) {
      var hash = window.location.hash || "";
      if (!hash || hash.length < 2) {
        // ?grupo=sin hash: ir al bloque de zona si existe
        try {
          var params = new URLSearchParams(window.location.search);
          var g = params.get("grupo");
          if (g) {
            scrollToSectionId("sec-" + g, smooth);
          }
        } catch (e) {}
        return;
      }
      var id = decodeURIComponent(hash.replace(/^#/, ""));
      // doble rAF: tras layout de sticky
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          scrollToSectionId(id, smooth);
        });
      });
    }

    // Carga inicial: sin smooth (más preciso)
    go(false);
    // Reajustar tras fuentes/imágenes
    window.setTimeout(function () {
      go(false);
    }, 120);
    window.setTimeout(function () {
      go(false);
    }, 400);

    window.addEventListener("hashchange", function () {
      go(true);
    });
  }
})();
