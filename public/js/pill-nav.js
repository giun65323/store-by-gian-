/**
 * PillNav — Vanilla JS implementation of the React Bits PillNav component
 * Uses GSAP for the hover circle bubble-up animation.
 */
(function () {
  'use strict';

  function initPillNav(container, options) {
    if (!container || container.dataset.pillNavInit) return;
    container.dataset.pillNavInit = '1';

    var ease = options.ease || 'power3.out';
    var pills = Array.from(container.querySelectorAll('.pill'));
    var circleRefs = [];
    var tlRefs = [];
    var activeTweenRefs = [];

    // Hamburger / mobile
    var hamburger = container.querySelector('.pill-hamburger');
    var mobileMenu = document.getElementById('pill-mobile-menu');
    var isMobileOpen = false;

    function layout() {
      pills.forEach(function (pill, i) {
        var circle = pill.querySelector('.hover-circle');
        if (!circle) return;
        circleRefs[i] = circle;

        var rect = pill.getBoundingClientRect();
        var w = rect.width;
        var h = rect.height;
        if (w === 0 || h === 0) return;

        var R = ((w * w) / 4 + h * h) / (2 * h);
        var D = Math.ceil(2 * R) + 2;
        var delta = Math.ceil(R - Math.sqrt(Math.max(0, R * R - (w * w) / 4))) + 1;
        var originY = D - delta;

        circle.style.width = D + 'px';
        circle.style.height = D + 'px';
        circle.style.bottom = '-' + delta + 'px';

        gsap.set(circle, {
          xPercent: -50,
          scale: 0,
          transformOrigin: '50% ' + originY + 'px'
        });

        var label = pill.querySelector('.pill-label');
        var hoverLabel = pill.querySelector('.pill-label-hover');

        if (label) gsap.set(label, { y: 0 });
        if (hoverLabel) {
          gsap.set(hoverLabel, { y: h + 12, opacity: 0 });
        }

        if (tlRefs[i]) tlRefs[i].kill();

        var tl = gsap.timeline({ paused: true });
        tl.to(circle, { scale: 1.2, xPercent: -50, duration: 0.6, ease: ease, overwrite: 'auto' }, 0);
        if (label) {
          tl.to(label, { y: -(h + 8), duration: 0.6, ease: ease, overwrite: 'auto' }, 0);
        }
        if (hoverLabel) {
          gsap.set(hoverLabel, { y: Math.ceil(h + 100), opacity: 0 });
          tl.to(hoverLabel, { y: 0, opacity: 1, duration: 0.6, ease: ease, overwrite: 'auto' }, 0);
        }
        tlRefs[i] = tl;
      });
    }

    function handleEnter(i) {
      var tl = tlRefs[i];
      if (!tl) return;
      if (activeTweenRefs[i]) activeTweenRefs[i].kill();
      activeTweenRefs[i] = tl.tweenTo(tl.duration(), {
        duration: 0.3,
        ease: ease,
        overwrite: 'auto'
      });
    }

    function handleLeave(i) {
      var tl = tlRefs[i];
      if (!tl) return;
      if (activeTweenRefs[i]) activeTweenRefs[i].kill();
      activeTweenRefs[i] = tl.tweenTo(0, {
        duration: 0.2,
        ease: ease,
        overwrite: 'auto'
      });
    }

    pills.forEach(function (pill, i) {
      pill.addEventListener('mouseenter', function () { handleEnter(i); });
      pill.addEventListener('mouseleave', function () { handleLeave(i); });

      // Navigate with delay to let animation play
      pill.addEventListener('click', function (e) {
        var href = pill.getAttribute('href');
        if (href && href !== '#' && href !== '') {
          e.preventDefault();
          setTimeout(function () {
            window.location.href = href;
          }, 280);
        }
      });
    });

    // Mobile toggle
    if (hamburger && mobileMenu) {
      hamburger.addEventListener('click', function () {
        isMobileOpen = !isMobileOpen;
        var lines = hamburger.querySelectorAll('.hamburger-line');
        if (isMobileOpen) {
          mobileMenu.classList.add('is-open');
          if (window.gsap && lines.length >= 2) {
            gsap.to(lines[0], { rotation: 45, y: 3, duration: 0.3 });
            gsap.to(lines[1], { rotation: -45, y: -3, duration: 0.3 });
          }
        } else {
          mobileMenu.classList.remove('is-open');
          if (window.gsap && lines.length >= 2) {
            gsap.to(lines[0], { rotation: 0, y: 0, duration: 0.3 });
            gsap.to(lines[1], { rotation: 0, y: 0, duration: 0.3 });
          }
        }
      });
    }

    layout();

    // Initial load animation using GSAP
    var navItems = container.querySelector('.pill-nav-items');
    if (navItems && window.gsap) {
      gsap.from(navItems, { opacity: 0, x: -10, duration: 0.5, ease: ease });
    }

    window.addEventListener('resize', layout, { passive: true });
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(layout);
    }
  }

  function init() {
    var container = document.getElementById('pill-nav-container');
    if (container) {
      initPillNav(container, {
        ease: 'power3.out'
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
