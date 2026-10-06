(function () {
  'use strict';

  var PAD = 20;
  var DEFAULT_PROXIMITY = 250;
  var DEFAULT_RADIUS = 18;
  var DEFAULT_LINE_COLOR = '#ffffff';
  var DEFAULT_BASE_COLOR = '#525252';
  var DEFAULT_INTENSITY = 1;
  var DEFAULT_SPEED = 0.16;

  function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
  }

  function lerp(a, b, t) {
    return a + (b - a) * t;
  }

  function cssVar(el, name) {
    return getComputedStyle(el).getPropertyValue(name).trim();
  }

  function numericSetting(el, dataName, cssName, fallback) {
    var raw = el.dataset[dataName] || cssVar(el, cssName);
    var value = parseFloat(raw);
    return Number.isFinite(value) ? value : fallback;
  }

  function colorSetting(el, dataName, cssName, fallback) {
    return el.dataset[dataName] || cssVar(el, cssName) || fallback;
  }

  function colorToRgba(color, alpha) {
    var probe = colorToRgba.probe;
    if (!probe) {
      probe = document.createElement('span');
      probe.style.display = 'none';
      document.body.appendChild(probe);
      colorToRgba.probe = probe;
    }

    probe.style.color = '';
    probe.style.color = color;
    var match = getComputedStyle(probe).color.match(/rgba?\(([^)]+)\)/);
    if (!match) return 'rgba(255,255,255,' + alpha + ')';

    var parts = match[1].split(',').map(function (part) {
      return parseFloat(part.trim());
    });

    return 'rgba(' + parts[0] + ',' + parts[1] + ',' + parts[2] + ',' + alpha + ')';
  }

  function roundedRectPath(ctx, x, y, w, h, r) {
    r = Math.min(r, w / 2, h / 2);
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.lineTo(x + w - r, y);
    ctx.arcTo(x + w, y, x + w, y + r, r);
    ctx.lineTo(x + w, y + h - r);
    ctx.arcTo(x + w, y + h, x + w - r, y + h, r);
    ctx.lineTo(x + r, y + h);
    ctx.arcTo(x, y + h, x, y + h - r, r);
    ctx.lineTo(x, y + r);
    ctx.arcTo(x, y, x + r, y, r);
    ctx.closePath();
  }

  function closestOnRect(px, py, bx, by, bw, bh) {
    var cx = clamp(px, bx, bx + bw);
    var cy = clamp(py, by, by + bh);

    if (cx === px && cy === py) {
      var left = px - bx;
      var right = bx + bw - px;
      var top = py - by;
      var bottom = by + bh - py;
      var nearest = Math.min(left, right, top, bottom);

      if (nearest === left) return { x: bx, y: py };
      if (nearest === right) return { x: bx + bw, y: py };
      if (nearest === top) return { x: px, y: by };
      return { x: px, y: by + bh };
    }

    return { x: cx, y: cy };
  }

  function wrapContent(btn) {
    if (btn.querySelector(':scope > .sb-label')) return;

    var label = document.createElement('span');
    label.className = 'sb-label';

    while (btn.firstChild) {
      label.appendChild(btn.firstChild);
    }

    btn.appendChild(label);
  }

  function initButton(btn) {
    if (btn.dataset.sbInit) return;
    btn.dataset.sbInit = '1';

    wrapContent(btn);

    var fx = document.createElement('span');
    fx.className = 'sb-fx';

    var canvas = document.createElement('canvas');
    fx.appendChild(canvas);
    btn.appendChild(fx);

    var ctx = canvas.getContext('2d');
    if (!ctx) return;

    var dpr = window.devicePixelRatio || 1;
    var mouse = null;
    var targetAlpha = 0;
    var currentAlpha = 0;
    var lastWidth = 0;
    var lastHeight = 0;
    var rafId = 0;

    function resize(width, height) {
      dpr = window.devicePixelRatio || 1;
      var canvasWidth = Math.max(1, width + PAD * 2);
      var canvasHeight = Math.max(1, height + PAD * 2);

      canvas.width = Math.round(canvasWidth * dpr);
      canvas.height = Math.round(canvasHeight * dpr);
      canvas.style.width = canvasWidth + 'px';
      canvas.style.height = canvasHeight + 'px';
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

      lastWidth = width;
      lastHeight = height;
    }

    function syncSize(rect) {
      var width = rect.width || 0;
      var height = rect.height || 0;

      if (Math.abs(width - lastWidth) > 0.5 || Math.abs(height - lastHeight) > 0.5) {
        resize(width, height);
      }
    }

    function updatePointer(e) {
      mouse = { x: e.clientX, y: e.clientY };

      if (btn.disabled || btn.getAttribute('aria-disabled') === 'true') {
        targetAlpha = 0;
        return;
      }

      var rect = btn.getBoundingClientRect();
      var dx = Math.max(rect.left - mouse.x, 0, mouse.x - rect.right);
      var dy = Math.max(rect.top - mouse.y, 0, mouse.y - rect.bottom);
      var dist = Math.hypot(dx, dy);
      var proximity = numericSetting(btn, 'proximity', '--sb-proximity', DEFAULT_PROXIMITY);
      var t = clamp(1 - dist / Math.max(proximity, 1), 0, 1);
      targetAlpha = t * t * (3 - 2 * t);
    }

    function drawBaseStroke(bx, by, bw, bh, radius, baseColor) {
      ctx.save();
      roundedRectPath(ctx, bx, by, bw, bh, radius);
      ctx.lineWidth = 1;
      ctx.strokeStyle = colorToRgba(baseColor, 0.32);
      ctx.stroke();
      ctx.restore();
    }

    function drawHighlight(rect, alpha) {
      var width = rect.width;
      var height = rect.height;
      var bx = PAD;
      var by = PAD;
      var radius = numericSetting(btn, 'radius', '--sb-radius', DEFAULT_RADIUS);
      var baseColor = colorSetting(btn, 'baseColor', '--sb-base-color', DEFAULT_BASE_COLOR);
      var lineColor = colorSetting(btn, 'lineColor', '--sb-line-color', DEFAULT_LINE_COLOR);
      var intensity = numericSetting(btn, 'intensity', '--sb-intensity', DEFAULT_INTENSITY);

      drawBaseStroke(bx, by, width, height, radius, baseColor);

      if (!mouse || alpha <= 0.004) return;

      var mx = mouse.x - rect.left + PAD;
      var my = mouse.y - rect.top + PAD;
      var localAlpha = clamp(alpha * intensity, 0, 1);

      var rimRadius = Math.max(width, height) * 1.18;
      var rim = ctx.createRadialGradient(mx, my, 0, mx, my, rimRadius);
      rim.addColorStop(0, colorToRgba(lineColor, 0.9 * localAlpha));
      rim.addColorStop(0.42, colorToRgba(lineColor, 0.34 * localAlpha));
      rim.addColorStop(1, colorToRgba(lineColor, 0));

      ctx.save();
      roundedRectPath(ctx, bx, by, width, height, radius);
      ctx.lineWidth = 1.5;
      ctx.strokeStyle = rim;
      ctx.stroke();
      ctx.restore();

      var closest = closestOnRect(mx, my, bx, by, width, height);
      var shineRadius = 42 + Math.max(width, height) * 0.06;
      var shine = ctx.createRadialGradient(closest.x, closest.y, 0, closest.x, closest.y, shineRadius);
      shine.addColorStop(0, colorToRgba(lineColor, 0.62 * localAlpha));
      shine.addColorStop(0.34, colorToRgba(lineColor, 0.2 * localAlpha));
      shine.addColorStop(1, colorToRgba(lineColor, 0));

      ctx.save();
      roundedRectPath(ctx, bx - 4, by - 4, width + 8, height + 8, radius + 4);
      ctx.clip();
      ctx.fillStyle = shine;
      ctx.fillRect(0, 0, width + PAD * 2, height + PAD * 2);
      ctx.restore();

      var gloss = ctx.createLinearGradient(bx, by, bx, by + 14);
      gloss.addColorStop(0, colorToRgba(lineColor, 0.12 * localAlpha));
      gloss.addColorStop(1, colorToRgba(lineColor, 0));

      ctx.save();
      roundedRectPath(ctx, bx, by, width, height, radius);
      ctx.clip();
      ctx.fillStyle = gloss;
      ctx.fillRect(bx, by, width, 16);
      ctx.restore();
    }

    function draw() {
      var rect = btn.getBoundingClientRect();
      syncSize(rect);

      ctx.clearRect(0, 0, rect.width + PAD * 2, rect.height + PAD * 2);
      currentAlpha = lerp(currentAlpha, targetAlpha, DEFAULT_SPEED);

      if (rect.width > 0 && rect.height > 0) {
        drawHighlight(rect, currentAlpha);
      }

      rafId = requestAnimationFrame(draw);
    }

    if (window.ResizeObserver) {
      var observer = new ResizeObserver(function (entries) {
        if (!entries.length) return;
        var box = entries[0].contentRect;
        resize(box.width, box.height);
      });
      observer.observe(btn);
    }

    window.addEventListener('pointermove', updatePointer, { passive: true });
    window.addEventListener('resize', function () {
      var rect = btn.getBoundingClientRect();
      resize(rect.width || 0, rect.height || 0);
    }, { passive: true });

    resize(btn.offsetWidth || 0, btn.offsetHeight || 0);
    rafId = requestAnimationFrame(draw);
  }

  function initAll(root) {
    (root || document).querySelectorAll('button:not([data-no-specular]), .specular-btn').forEach(function (el) {
      el.classList.add('specular-btn');
      initButton(el);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initAll(document);
    });
  } else {
    initAll(document);
  }

  window.SpecularButtons = {
    init: initAll
  };
})();
