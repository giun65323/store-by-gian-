/**
 * Shuffle Component (Vanilla JS + GSAP adaptation of React Bits <Shuffle />)
 */
(function () {
  'use strict';

  function initShuffle(el, options) {
    if (!el) return;

    options = Object.assign({
      text: el.getAttribute('data-text') || el.textContent.trim(),
      shuffleDirection: el.getAttribute('data-shuffle-direction') || 'right',
      duration: parseFloat(el.getAttribute('data-duration')) || 0.35,
      maxDelay: parseFloat(el.getAttribute('data-max-delay')) || 0,
      ease: el.getAttribute('data-ease') || 'power3.out',
      shuffleTimes: parseInt(el.getAttribute('data-shuffle-times'), 10) || 2,
      animationMode: el.getAttribute('data-animation-mode') || 'evenodd',
      loop: el.getAttribute('data-loop') === 'true',
      loopDelay: parseFloat(el.getAttribute('data-loop-delay')) || 0,
      stagger: parseFloat(el.getAttribute('data-stagger')) || 0.03,
      scrambleCharset: el.getAttribute('data-scramble-charset') || 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*',
      colorFrom: el.getAttribute('data-color-from') || null,
      colorTo: el.getAttribute('data-color-to') || null,
      triggerOnce: el.getAttribute('data-trigger-once') !== 'false',
      triggerOnHover: el.getAttribute('data-trigger-on-hover') !== 'false',
      respectReducedMotion: true
    }, options || {});

    if (options.respectReducedMotion && window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      el.classList.add('is-ready');
      return;
    }

    var text = options.text;
    var wrappers = [];
    var tl = null;
    var isPlaying = false;

    el.classList.add('shuffle-parent');

    function teardown() {
      if (tl) {
        tl.kill();
        tl = null;
      }
      if (wrappers.length) {
        wrappers.forEach(function (wrap) {
          var inner = wrap.firstElementChild;
          var orig = inner ? inner.querySelector('[data-orig="1"]') : null;
          if (orig && wrap.parentNode) {
            wrap.parentNode.replaceChild(orig, wrap);
          }
        });
        wrappers = [];
      }
      isPlaying = false;
    }

    function build() {
      teardown();

      // Clear existing content and split into character spans
      el.innerHTML = '';
      var chars = [];
      for (var i = 0; i < text.length; i++) {
        var char = text[i];
        var span = document.createElement('span');
        span.className = 'shuffle-char';
        span.textContent = char === ' ' ? '\u00A0' : char; // Preserve space
        el.appendChild(span);
        chars.push(span);
      }

      el.classList.add('is-ready');

      var rolls = Math.max(1, Math.floor(options.shuffleTimes));
      var rand = function (set) {
        return set.charAt(Math.floor(Math.random() * set.length)) || '';
      };

      chars.forEach(function (ch) {
        var parent = ch.parentElement;
        if (!parent) return;

        var rect = ch.getBoundingClientRect();
        var w = rect.width || 12;
        var h = rect.height || 24;

        var wrap = document.createElement('span');
        wrap.className = 'shuffle-char-wrapper';
        wrap.style.display = 'inline-block';
        wrap.style.overflow = 'hidden';
        wrap.style.width = w + 'px';
        wrap.style.height = (options.shuffleDirection === 'up' || options.shuffleDirection === 'down') ? h + 'px' : 'auto';
        wrap.style.verticalAlign = 'bottom';

        var inner = document.createElement('span');
        inner.style.display = 'inline-block';
        inner.style.whiteSpace = (options.shuffleDirection === 'up' || options.shuffleDirection === 'down') ? 'normal' : 'nowrap';
        inner.style.willChange = 'transform';

        parent.insertBefore(wrap, ch);
        wrap.appendChild(inner);

        var firstOrig = ch.cloneNode(true);
        firstOrig.style.display = (options.shuffleDirection === 'up' || options.shuffleDirection === 'down') ? 'block' : 'inline-block';
        firstOrig.style.width = w + 'px';
        firstOrig.style.textAlign = 'center';

        ch.setAttribute('data-orig', '1');
        ch.style.display = (options.shuffleDirection === 'up' || options.shuffleDirection === 'down') ? 'block' : 'inline-block';
        ch.style.width = w + 'px';
        ch.style.textAlign = 'center';

        inner.appendChild(firstOrig);
        for (var k = 0; k < rolls; k++) {
          var c = ch.cloneNode(true);
          if (options.scrambleCharset && ch.textContent !== '\u00A0') {
            c.textContent = rand(options.scrambleCharset);
          }
          c.style.display = (options.shuffleDirection === 'up' || options.shuffleDirection === 'down') ? 'block' : 'inline-block';
          c.style.width = w + 'px';
          c.style.textAlign = 'center';
          inner.appendChild(c);
        }
        inner.appendChild(ch);

        var steps = rolls + 1;

        if (options.shuffleDirection === 'right' || options.shuffleDirection === 'down') {
          var firstCopy = inner.firstElementChild;
          var real = inner.lastElementChild;
          if (real) inner.insertBefore(real, inner.firstChild);
          if (firstCopy) inner.appendChild(firstCopy);
        }

        var startX = 0, finalX = 0, startY = 0, finalY = 0;

        if (options.shuffleDirection === 'right') {
          startX = -steps * w;
          finalX = 0;
        } else if (options.shuffleDirection === 'left') {
          startX = 0;
          finalX = -steps * w;
        } else if (options.shuffleDirection === 'down') {
          startY = -steps * h;
          finalY = 0;
        } else if (options.shuffleDirection === 'up') {
          startY = 0;
          finalY = -steps * h;
        }

        if (options.shuffleDirection === 'left' || options.shuffleDirection === 'right') {
          if (window.gsap) gsap.set(inner, { x: startX, y: 0, force3D: true });
          inner.setAttribute('data-start-x', String(startX));
          inner.setAttribute('data-final-x', String(finalX));
        } else {
          if (window.gsap) gsap.set(inner, { x: 0, y: startY, force3D: true });
          inner.setAttribute('data-start-y', String(startY));
          inner.setAttribute('data-final-y', String(finalY));
        }

        if (options.colorFrom) inner.style.color = options.colorFrom;
        wrappers.push(wrap);
      });
    }

    function inners() {
      return wrappers.map(function (w) { return w.firstElementChild; }).filter(Boolean);
    }

    function randomizeScrambles() {
      if (!options.scrambleCharset) return;
      wrappers.forEach(function (w) {
        var strip = w.firstElementChild;
        if (!strip) return;
        var kids = Array.from(strip.children);
        for (var i = 1; i < kids.length - 1; i++) {
          if (kids[i].textContent !== '\u00A0') {
            kids[i].textContent = options.scrambleCharset.charAt(Math.floor(Math.random() * options.scrambleCharset.length));
          }
        }
      });
    }

    function cleanupToStill() {
      wrappers.forEach(function (w) {
        var strip = w.firstElementChild;
        if (!strip) return;
        var real = strip.querySelector('[data-orig="1"]');
        if (!real) return;
        strip.replaceChildren(real);
        strip.style.transform = 'none';
        strip.style.willChange = 'auto';
      });
    }

    function play() {
      if (!window.gsap) return;
      var strips = inners();
      if (!strips.length) return;

      isPlaying = true;
      var isVertical = options.shuffleDirection === 'up' || options.shuffleDirection === 'down';

      tl = gsap.timeline({
        smoothChildTiming: true,
        repeat: options.loop ? -1 : 0,
        repeatDelay: options.loop ? options.loopDelay : 0,
        onRepeat: function () {
          if (options.scrambleCharset) randomizeScrambles();
          if (isVertical) {
            gsap.set(strips, { y: function (i, t) { return parseFloat(t.getAttribute('data-start-y') || '0'); } });
          } else {
            gsap.set(strips, { x: function (i, t) { return parseFloat(t.getAttribute('data-start-x') || '0'); } });
          }
        },
        onComplete: function () {
          isPlaying = false;
          if (!options.loop) {
            cleanupToStill();
            if (options.colorTo) gsap.set(strips, { color: options.colorTo });
          }
        }
      });

      var addTween = function (targets, at) {
        var vars = {
          duration: options.duration,
          ease: options.ease,
          force3D: true,
          stagger: options.animationMode === 'evenodd' ? options.stagger : 0
        };
        if (isVertical) {
          vars.y = function (i, t) { return parseFloat(t.getAttribute('data-final-y') || '0'); };
        } else {
          vars.x = function (i, t) { return parseFloat(t.getAttribute('data-final-x') || '0'); };
        }

        tl.to(targets, vars, at);

        if (options.colorFrom && options.colorTo) {
          tl.to(targets, { color: options.colorTo, duration: options.duration, ease: options.ease }, at);
        }
      };

      if (options.animationMode === 'evenodd') {
        var odd = strips.filter(function (_, i) { return i % 2 === 1; });
        var even = strips.filter(function (_, i) { return i % 2 === 0; });
        var oddTotal = options.duration + Math.max(0, odd.length - 1) * options.stagger;
        var evenStart = odd.length ? oddTotal * 0.7 : 0;
        if (odd.length) addTween(odd, 0);
        if (even.length) addTween(even, evenStart);
      } else {
        strips.forEach(function (strip) {
          var d = Math.random() * options.maxDelay;
          var vars = {
            duration: options.duration,
            ease: options.ease,
            force3D: true
          };
          if (isVertical) {
            vars.y = parseFloat(strip.getAttribute('data-final-y') || '0');
          } else {
            vars.x = parseFloat(strip.getAttribute('data-final-x') || '0');
          }
          tl.to(strip, vars, d);
          if (options.colorFrom && options.colorTo) {
            tl.fromTo(strip, { color: options.colorFrom }, { color: options.colorTo, duration: options.duration, ease: options.ease }, d);
          }
        });
      }
    }

    function runAnimation() {
      build();
      if (options.scrambleCharset) randomizeScrambles();
      play();
    }

    // Arm hover
    if (options.triggerOnHover) {
      el.parentElement.addEventListener('mouseenter', function () {
        if (!isPlaying) {
          runAnimation();
        }
      });
    }

    // Run on init
    runAnimation();
  }

  window.initShuffle = initShuffle;

  // Auto initialize on DOM ready
  document.addEventListener('DOMContentLoaded', function () {
    var shuffleElements = document.querySelectorAll('[data-shuffle="true"]');
    shuffleElements.forEach(function (el) {
      initShuffle(el);
    });
  });
})();
