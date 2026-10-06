/**
 * Dock — Vanilla JS port of the React Bits Dock component.
 * No external dependencies required. Pure spring-physics magnification.
 *
 * Usage:
 *   new Dock(containerElement, { items, panelHeight, baseItemSize, magnification, distance });
 *
 * Or use the static helper:
 *   Dock.mount('#dock-root', { items: [...] });
 *
 * items: Array of { icon (HTML string), label (string), onClick (function), href (string|null), active (bool) }
 */

(function (global) {
  'use strict';

  /* ─────────────────────────────────────────
     Simple spring integrator (replaces motion/react useSpring)
     ───────────────────────────────────────── */
  class Spring {
    constructor({ mass = 0.1, stiffness = 150, damping = 12 } = {}) {
      this.mass      = mass;
      this.stiffness = stiffness;
      this.damping   = damping;
      this.current   = 0;
      this.target    = 0;
      this.velocity  = 0;
      this._raf      = null;
      this._cbs      = [];
    }

    setTarget(target) {
      this.target = target;
      this._start();
    }

    setCurrent(val) {
      this.current  = val;
      this.target   = val;
      this.velocity = 0;
    }

    onChange(cb) {
      this._cbs.push(cb);
      return () => { this._cbs = this._cbs.filter(f => f !== cb); };
    }

    _step(dt) {
      const force      = -this.stiffness * (this.current - this.target);
      const dampForce  = -this.damping   * this.velocity;
      const accel      = (force + dampForce) / this.mass;
      this.velocity   += accel  * dt;
      this.current    += this.velocity * dt;
    }

    _start() {
      if (this._raf) return;
      let last = performance.now();
      const tick = (now) => {
        const dt = Math.min((now - last) / 1000, 0.064); // cap at 64ms
        last = now;
        this._step(dt);
        this._cbs.forEach(cb => cb(this.current));

        const settled = Math.abs(this.current - this.target) < 0.05
                     && Math.abs(this.velocity) < 0.05;
        if (settled) {
          this.current = this.target;
          this._cbs.forEach(cb => cb(this.current));
          this._raf = null;
        } else {
          this._raf = requestAnimationFrame(tick);
        }
      };
      this._raf = requestAnimationFrame(tick);
    }

    destroy() {
      if (this._raf) { cancelAnimationFrame(this._raf); this._raf = null; }
      this._cbs = [];
    }
  }

  /* ─────────────────────────────────────────
     Dock class
     ───────────────────────────────────────── */
  class Dock {
    /**
     * @param {HTMLElement} container  – The element to inject the dock into.
     * @param {Object}      options
     * @param {Array}       options.items          – [{ icon, label, onClick, href, active }]
     * @param {number}      options.panelHeight    – px height of the panel bar (default 68)
     * @param {number}      options.baseItemSize   – px base size of each icon (default 50)
     * @param {number}      options.magnification  – px size at peak magnification (default 70)
     * @param {number}      options.distance       – px radius of the magnification zone (default 200)
     * @param {string}      options.className      – extra classes for the panel
     * @param {Object}      options.spring         – spring config { mass, stiffness, damping }
     */
    constructor(container, options = {}) {
      this.container      = container;
      this.items          = options.items          ?? [];
      this.panelHeight    = options.panelHeight    ?? 68;
      this.baseItemSize   = options.baseItemSize   ?? 50;
      this.magnification  = options.magnification  ?? 70;
      this.distance       = options.distance       ?? 200;
      this.className      = options.className      ?? '';
      this.springCfg      = options.spring         ?? { mass: 0.1, stiffness: 150, damping: 12 };

      this._mouseX        = Infinity;
      this._hovered       = false;
      this._springs       = [];    // one Spring per item
      this._itemEls       = [];
      this._cleanups      = [];

      this._build();
      this._bind();
    }

    /* ── DOM construction ── */
    _build() {
      // outer wrapper (fixed positioned by CSS)
      this._outer = document.createElement('div');
      this._outer.className = 'dock-outer';
      this._outer.setAttribute('aria-label', 'Application dock');

      // panel
      this._panel = document.createElement('div');
      this._panel.className = ['dock-panel', this.className].filter(Boolean).join(' ');
      this._panel.setAttribute('role', 'toolbar');
      this._panel.setAttribute('aria-label', 'Application dock');
      this._panel.style.height = this.panelHeight + 'px';

      this.items.forEach((item, i) => {
        const el = this._buildItem(item, i);
        this._itemEls.push(el);
        this._panel.appendChild(el);

        const spring = new Spring(this.springCfg);
        spring.setCurrent(this.baseItemSize);
        this._springs.push(spring);

        // react to spring changes → resize element
        const unsub = spring.onChange(size => {
          el.style.width  = size + 'px';
          el.style.height = size + 'px';
          const iconEl = el.querySelector('.dock-icon');
          if (iconEl) {
            const scale = size / this.baseItemSize;
            iconEl.style.transform = `scale(${scale})`;
          }
        });
        this._cleanups.push(unsub);
      });

      this._outer.appendChild(this._panel);
      this.container.appendChild(this._outer);

      // initialise sizes
      this._itemEls.forEach((el, i) => {
        el.style.width  = this.baseItemSize + 'px';
        el.style.height = this.baseItemSize + 'px';
      });
    }

    _buildItem(item, index) {
      const el = document.createElement(item.href ? 'a' : 'div');
      el.className = ['dock-item', item.active ? 'dock-item--active' : ''].filter(Boolean).join(' ');
      el.setAttribute('tabindex', '0');
      el.setAttribute('role', 'button');
      el.setAttribute('aria-label', item.label ?? '');
      if (item.href) el.href = item.href;

      // icon
      const iconWrap = document.createElement('div');
      iconWrap.className = 'dock-icon';
      iconWrap.innerHTML = item.icon ?? '';
      el.appendChild(iconWrap);

      // label tooltip
      const label = document.createElement('span');
      label.className = 'dock-label';
      label.setAttribute('role', 'tooltip');
      label.textContent = item.label ?? '';
      el.appendChild(label);

      return el;
    }

    /* ── events ── */
    _bind() {
      const panel = this._panel;

      const onMouseMove = (e) => {
        this._mouseX  = e.pageX;
        this._hovered = true;
        this._update();
      };

      const onMouseLeave = () => {
        this._mouseX  = Infinity;
        this._hovered = false;
        this._update();
      };

      // keyboard activation
      const onKeyDown = (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          const item = this.items[this._itemEls.indexOf(e.target)];
          if (item?.onClick) item.onClick(e);
        }
      };

      panel.addEventListener('mousemove',  onMouseMove);
      panel.addEventListener('mouseleave', onMouseLeave);

      this._itemEls.forEach((el, i) => {
        const item = this.items[i];

        el.addEventListener('keydown', onKeyDown);

        if (item?.onClick) {
          el.addEventListener('click', item.onClick);
          this._cleanups.push(() => el.removeEventListener('click', item.onClick));
        }

        // focus magnifies the hovered item
        el.addEventListener('focus', () => {
          const rect = el.getBoundingClientRect();
          this._mouseX  = rect.left + rect.width / 2;
          this._hovered = true;
          this._update();
        });
        el.addEventListener('blur', () => {
          this._mouseX  = Infinity;
          this._hovered = false;
          this._update();
        });
      });

      this._cleanups.push(() => {
        panel.removeEventListener('mousemove',  onMouseMove);
        panel.removeEventListener('mouseleave', onMouseLeave);
      });
    }

    /* ── magnification calculation ── */
    _targetSizeForItem(index) {
      const el   = this._itemEls[index];
      const rect = el.getBoundingClientRect();
      const itemCenter = rect.left + rect.width / 2;
      const diff = this._mouseX - itemCenter;

      if (!this._hovered || Math.abs(diff) > this.distance) {
        return this.baseItemSize;
      }

      // linear interpolation: 0 diff → magnification, ±distance → baseItemSize
      const t = 1 - Math.abs(diff) / this.distance;   // 0..1
      return this.baseItemSize + (this.magnification - this.baseItemSize) * t;
    }

    _update() {
      this._itemEls.forEach((_, i) => {
        this._springs[i].setTarget(this._targetSizeForItem(i));
      });
    }

    /* ── public API ── */
    destroy() {
      this._cleanups.forEach(fn => fn());
      this._springs.forEach(s => s.destroy());
      if (this._outer?.parentNode) this._outer.parentNode.removeChild(this._outer);
    }

    /**
     * Static mount helper — creates a container and initialises Dock.
     * @param {string|HTMLElement} selector
     * @param {Object} options
     * @returns {Dock}
     */
    static mount(selector, options) {
      const container = typeof selector === 'string'
        ? document.querySelector(selector)
        : selector;
      if (!container) {
        console.warn('Dock.mount: container not found:', selector);
        return null;
      }
      return new Dock(container, options);
    }
  }

  global.Dock = Dock;

})(window);
