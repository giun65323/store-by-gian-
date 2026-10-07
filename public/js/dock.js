/**
 * Dock — Vanilla JS port of the React Bits Dock component.
 * Spring-physics magnification, no external dependencies.
 *
 * Usage:
 *   new Dock(containerElement, { items, panelHeight, baseItemSize, magnification, distance });
 *
 *   Dock.mount('#dock-root', { items: [...] });
 *
 * items: Array of:
 *   { icon (HTML string), label (string), onClick (fn|null), href (string|null), active (bool) }
 *   or the string 'separator' to insert a visual divider.
 */

(function (global) {
  'use strict';

  /* ─────────────────────────────────────────
     Simple spring integrator — replaces motion/react useSpring
     Uses semi-implicit Euler integration (stable at any dt).
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
      if (target === this.target && !this._raf) return;
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
      const force     = -this.stiffness * (this.current - this.target);
      const dampForce = -this.damping   * this.velocity;
      const accel     = (force + dampForce) / this.mass;
      this.velocity  += accel  * dt;
      this.current   += this.velocity * dt;
    }

    _notify() { this._cbs.forEach(cb => cb(this.current)); }

    _start() {
      if (this._raf) return;
      let last = performance.now();

      const tick = (now) => {
        const dt = Math.min((now - last) / 1000, 0.064);
        last = now;
        this._step(dt);
        this._notify();

        const settled =
          Math.abs(this.current - this.target) < 0.05 &&
          Math.abs(this.velocity) < 0.05;

        if (settled) {
          this.current = this.target;
          this._notify();
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
     * @param {HTMLElement} container
     * @param {object}      options
     * @param {Array}       options.items           – [{ icon, label, onClick, href, active }] or 'separator'
     * @param {number}      [options.panelHeight=64]
     * @param {number}      [options.baseItemSize=48]
     * @param {number}      [options.magnification=68]
     * @param {number}      [options.distance=160]
     * @param {string}      [options.className='']
     * @param {object}      [options.spring]
     */
    constructor(container, options = {}) {
      this.container     = container;
      this.items         = options.items          ?? [];
      this.panelHeight   = options.panelHeight    ?? 64;
      this.baseItemSize  = options.baseItemSize   ?? 48;
      this.magnification = options.magnification  ?? 68;
      this.distance      = options.distance       ?? 160;
      this.className     = options.className      ?? '';
      this.springCfg     = options.spring         ?? { mass: 0.1, stiffness: 150, damping: 12 };

      this._mouseX   = Infinity;
      this._hovered  = false;
      this._springs  = [];   // one Spring per interactive item (not separators)
      this._itemEls  = [];   // mirrors this.items indices (null for separators)
      this._cleanups = [];

      this._build();
      this._bind();
    }

    /* ── DOM ── */
    _build() {
      this._outer = document.createElement('div');
      this._outer.className = 'dock-outer';

      this._panel = document.createElement('nav');
      this._panel.className = ['dock-panel', this.className].filter(Boolean).join(' ');
      this._panel.setAttribute('role', 'toolbar');
      this._panel.setAttribute('aria-label', 'Application dock');
      this._panel.style.height = this.panelHeight + 'px';

      this.items.forEach((item, i) => {
        if (item === 'separator') {
          const sep = document.createElement('div');
          sep.className = 'dock-separator';
          sep.setAttribute('aria-hidden', 'true');
          this._panel.appendChild(sep);
          this._itemEls.push(null);
          this._springs.push(null);
          return;
        }

        const el = this._buildItem(item, i);
        this._itemEls.push(el);
        this._panel.appendChild(el);

        const spring = new Spring(this.springCfg);
        spring.setCurrent(this.baseItemSize);
        this._springs.push(spring);

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

      // Set initial sizes
      this._itemEls.forEach((el) => {
        if (!el) return;
        el.style.width  = this.baseItemSize + 'px';
        el.style.height = this.baseItemSize + 'px';
      });
    }

    _buildItem(item, index) {
      const tag = item.href ? 'a' : 'div';
      const el  = document.createElement(tag);

      el.className = [
        'dock-item',
        item.active    ? 'dock-item--active' : '',
        item.className ? item.className      : ''
      ].filter(Boolean).join(' ');

      el.setAttribute('tabindex', '0');
      el.setAttribute('role', 'button');
      el.setAttribute('aria-label', item.label ?? '');
      if (item.href) {
        el.href = item.href;
        el.setAttribute('role', 'link');
        delete el.dataset.role; // clean up
      }

      // Icon
      const iconWrap = document.createElement('div');
      iconWrap.className = 'dock-icon';
      iconWrap.innerHTML = item.icon ?? '';
      el.appendChild(iconWrap);

      // Tooltip label
      const label = document.createElement('span');
      label.className = 'dock-label';
      label.setAttribute('role', 'tooltip');
      label.textContent = item.label ?? '';
      el.appendChild(label);

      return el;
    }

    /* ── Events ── */
    _bind() {
      const panel = this._panel;

      const onMouseMove = (e) => {
        this._mouseX  = e.clientX;   // clientX for viewport-relative magnification
        this._hovered = true;
        this._update();
      };

      const onMouseLeave = () => {
        this._mouseX  = Infinity;
        this._hovered = false;
        this._update();
      };

      const onKeyDown = (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          const idx  = this._itemEls.indexOf(e.target);
          const item = this.items[idx];
          if (item?.onClick) item.onClick(e);
        }
      };

      panel.addEventListener('mousemove',  onMouseMove);
      panel.addEventListener('mouseleave', onMouseLeave);
      this._cleanups.push(() => {
        panel.removeEventListener('mousemove',  onMouseMove);
        panel.removeEventListener('mouseleave', onMouseLeave);
      });

      this._itemEls.forEach((el, i) => {
        if (!el) return;
        const item = this.items[i];

        el.addEventListener('keydown', onKeyDown);

        if (item?.onClick && !item.href) {
          el.addEventListener('click', item.onClick);
          this._cleanups.push(() => el.removeEventListener('click', item.onClick));
        }

        // Focus → magnify that item
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
    }

    /* ── Magnification ── */
    _targetSizeForItem(index) {
      const el = this._itemEls[index];
      if (!el) return this.baseItemSize;

      const rect       = el.getBoundingClientRect();
      const itemCenter = rect.left + rect.width / 2;
      const diff       = this._mouseX - itemCenter;

      if (!this._hovered || Math.abs(diff) > this.distance) {
        return this.baseItemSize;
      }

      // Smooth cosine falloff: peak at diff=0, base at ±distance
      const t = 1 - Math.abs(diff) / this.distance;
      const smooth = t * t * (3 - 2 * t); // smoothstep
      return this.baseItemSize + (this.magnification - this.baseItemSize) * smooth;
    }

    _update() {
      this._springs.forEach((spring, i) => {
        if (!spring) return;
        spring.setTarget(this._targetSizeForItem(i));
      });
    }

    /* ── Public API ── */

    /** Update the active state of an item by index */
    setActive(index, active = true) {
      const el = this._itemEls[index];
      if (!el) return;
      if (active) el.classList.add('dock-item--active');
      else        el.classList.remove('dock-item--active');
    }

    destroy() {
      this._cleanups.forEach(fn => fn());
      this._springs.forEach(s => s?.destroy());
      this._outer?.parentNode?.removeChild(this._outer);
    }

    /**
     * Static mount helper.
     * @param {string|HTMLElement} selector
     * @param {object} options
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
