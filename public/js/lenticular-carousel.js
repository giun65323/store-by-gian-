(function () {
  'use strict';

  function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
  }

  function initCarousel(root) {
    if (root.dataset.lcInit) return;
    root.dataset.lcInit = '1';

    var viewport = root.querySelector('[data-lenticular-viewport]');
    var track = root.querySelector('[data-lenticular-track]');
    var cards = Array.prototype.slice.call(root.querySelectorAll('[data-lenticular-card]'));
    var dots = Array.prototype.slice.call(root.querySelectorAll('[data-lenticular-dot]'));
    var prev = root.querySelector('[data-lenticular-prev]');
    var next = root.querySelector('[data-lenticular-next]');
    var index = clamp(parseInt(root.dataset.initialIndex || '0', 10), 0, Math.max(cards.length - 1, 0));
    var dragStart = null;

    if (!viewport || !track || !cards.length) return;

    function cardGap() {
      var styles = window.getComputedStyle(track);
      return parseFloat(styles.columnGap || styles.gap) || 0;
    }

    function setCardTurn(card, event) {
      // Keep turn static for regular flat card look
      card.style.setProperty('--lc-turn', '0');
      card.style.setProperty('--lc-shine-x', '50%');
    }

    function resetCardTurn(card) {
      card.style.setProperty('--lc-turn', '0');
      card.style.setProperty('--lc-shine-x', '50%');
    }

    function update() {
      var active = cards[index];
      var width = active.offsetWidth;
      var gap = cardGap();
      var offset = viewport.clientWidth / 2 - width / 2 - index * (width + gap);

      track.style.transform = 'translate3d(' + offset.toFixed(2) + 'px, 0, 0)';

      cards.forEach(function (card, i) {
        var distance = Math.min(Math.abs(i - index), 3) / 3;
        card.classList.toggle('is-active', i === index);
        card.setAttribute('aria-pressed', i === index ? 'true' : 'false');
        card.style.setProperty('--lc-distance', distance.toFixed(3));
        resetCardTurn(card);
      });

      dots.forEach(function (dot, i) {
        dot.classList.toggle('is-active', i === index);
        dot.setAttribute('aria-current', i === index ? 'true' : 'false');
      });
    }

    function setIndex(nextIndex) {
      index = clamp(nextIndex, 0, cards.length - 1);
      update();
    }

    cards.forEach(function (card, i) {
      card.addEventListener('click', function () {
        setIndex(i);
      });

      card.addEventListener('pointermove', function (event) {
        if (i !== index) setIndex(i);
        setCardTurn(card, event);
      });

      card.addEventListener('pointerleave', function () {
        resetCardTurn(card);
      });

      card.addEventListener('focus', function () {
        setIndex(i);
        card.style.setProperty('--lc-turn', '1');
      });

      card.addEventListener('blur', function () {
        resetCardTurn(card);
      });
    });

    dots.forEach(function (dot, i) {
      dot.addEventListener('click', function () {
        setIndex(i);
      });
    });

    if (prev) {
      prev.addEventListener('click', function () {
        setIndex(index - 1);
      });
    }

    if (next) {
      next.addEventListener('click', function () {
        setIndex(index + 1);
      });
    }

    root.addEventListener('keydown', function (event) {
      if (event.key === 'ArrowLeft') {
        event.preventDefault();
        setIndex(index - 1);
      }

      if (event.key === 'ArrowRight') {
        event.preventDefault();
        setIndex(index + 1);
      }
    });

    viewport.addEventListener('pointerdown', function (event) {
      dragStart = { x: event.clientX, y: event.clientY };
    });

    viewport.addEventListener('pointerup', function (event) {
      if (!dragStart) return;
      var dx = event.clientX - dragStart.x;
      var dy = event.clientY - dragStart.y;
      dragStart = null;

      if (Math.abs(dx) > 42 && Math.abs(dx) > Math.abs(dy)) {
        setIndex(index + (dx < 0 ? 1 : -1));
      }
    });

    window.addEventListener('resize', update, { passive: true });
    update();
  }

  function initAll() {
    document.querySelectorAll('[data-lenticular-carousel]').forEach(initCarousel);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }
})();
