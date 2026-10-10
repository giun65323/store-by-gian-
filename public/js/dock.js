/**
 * Dock — Vanilla JS port of the React Bits <Dock /> component.
 * Exact React Bits spring-physics magnification, cosine-squared swell, and macOS-style launch hop physics.
 *
 * Usage:
 *   new Dock(containerElement, { items, panelHeight, baseItemSize, magnification, distance, spring, bounce });
 *   Dock.mount('#dock-root', { items: [...] });
 */

(function (global) {
  'use strict';

  const HOP = 0.4;
  const HOP_TIME = 0.38;

  const reducedMotion = () =>
    typeof window !== 'undefined' && !!window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;

  class Dock {
    /**
     * @param {HTMLElement|string} container
     * @param {object} options
     * @param {Array}  options.items           – [{ icon, label, onClick, href, active }] or 'separator'
     * @param {number} [options.panelHeight=64]
     * @param {number} [options.baseItemSize=46]
     * @param {number} [options.magnification=70]
     * @param {number} [options.distance=160]
     * @param {number} [options.gap=10]
     * @param {boolean} [options.bounce=true]
     * @param {string} [options.className='']
     * @param {object} [options.spring]        – { mass: 0.1, stiffness: 150, damping: 12 }
     */
    constructor(container, options = {}) {
      if (typeof container === 'string') {
        container = document.querySelector(container);
      }
      if (!container) return;

      this.container     = container;
      this.items         = options.items          ?? [];
      this.panelHeight   = options.panelHeight    ?? 64;
      this.baseItemSize  = options.baseItemSize   ?? 46;
      this.magnification = options.magnification  ?? 70;
      this.distance      = Math.max(1, options.distance ?? 160);
      this.gap           = options.gap            ?? 10;
      this.bounce        = options.bounce         ?? true;
      this.className     = options.className      ?? '';

      const spr = options.spring ?? {};
      this.springCfg = {
        mass:      Math.max(0.01, spr.mass      ?? 0.1),
        stiffness: Math.max(1,    spr.stiffness ?? 150),
        damping:   Math.max(0,    spr.damping   ?? 12),
      };

      this._itemEls  = []; // elements corresponding to this.items (null for separators)
      this._cleanups = [];
      this._alive    = true;

      this._build();
      this._initAnimation();
    }

    /* ── DOM Generation ── */
    _build() {
      // Clear container in case of remount
      this.container.innerHTML = '';

      this._outer = document.createElement('div');
      this._outer.className = 'dock-outer';

      this._panel = document.createElement('nav');
      this._panel.className = ['dock-panel', this.className].filter(Boolean).join(' ');
      this._panel.setAttribute('role', 'toolbar');
      this._panel.setAttribute('aria-label', 'Application dock');
      this._panel.style.height = this.panelHeight + 'px';

      this.items.forEach((item, i) => {
        const isSep = item === 'separator' || item?.separator === true;
        if (isSep) {
          const sep = document.createElement('div');
          sep.className = 'dock-separator';
          sep.setAttribute('aria-hidden', 'true');
          this._panel.appendChild(sep);
          this._itemEls.push(null);
          return;
        }

        const tag = item.href ? 'a' : 'button';
        const el  = document.createElement(tag);
        if (tag === 'button') el.type = 'button';

        el.className = [
          'dock-item',
          item.active    ? 'dock-item--active' : '',
          item.className ? item.className      : ''
        ].filter(Boolean).join(' ');

        el.setAttribute('tabindex', '0');
        el.setAttribute('role', item.href ? 'link' : 'button');
        el.setAttribute('aria-label', item.label ?? '');
        if (item.href) el.href = item.href;

        // Icon wrapper
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

        // Set initial resting size
        el.style.width  = this.baseItemSize + 'px';
        el.style.height = this.baseItemSize + 'px';

        this._itemEls.push(el);
        this._panel.appendChild(el);
      });

      this._outer.appendChild(this._panel);
      this.container.appendChild(this._outer);
    }

    /* ── React Bits Animation Engine ── */
    _initAnimation() {
      const panel = this._panel;
      const reduce = reducedMotion();

      // State per interactive item
      const motion = new Map();
      const entry = (index) => {
        let state = motion.get(index);
        if (!state) {
          state = {
            size: this.baseItemSize,
            velocity: 0,
            lift: 0,
            rise: 0,
            launch: 0,
            gravity: 0,
            hopping: false,
          };
          motion.set(index, state);
        }
        return state;
      };

      const pointer = { x: 0, y: 0, inside: false };
      let focused = -1;
      let raf = 0;
      let last = 0;

      // Single synchronized tick loop for all items
      const tick = (now) => {
        raf = 0;
        if (!this._alive) return;

        const dt = Math.min(1 / 30, Math.max(0.001, (now - last) / 1000));
        last = now;

        const rect = panel.getBoundingClientRect();
        const center = rect.left + rect.width / 2;

        // Pre-calculate resting item positions relative to dock center
        const spans = this.items.map(item => (item === 'separator' || item?.separator ? 1 : this.baseItemSize));
        let cursor = -(spans.reduce((sum, span) => sum + span, 0) + this.gap * Math.max(0, spans.length - 1)) / 2;
        const rest = spans.map(span => {
          const middle = cursor + span / 2;
          cursor += span + this.gap;
          return middle;
        });

        // Determine focal point
        let focus = null;
        if (pointer.inside) {
          focus = pointer.x - center;
        } else if (focused >= 0 && rest[focused] !== undefined) {
          focus = rest[focused];
        }

        const steps = Math.max(1, Math.ceil(dt * 240));
        const h = dt / steps;
        const k = this.springCfg.stiffness / this.springCfg.mass;
        const c = this.springCfg.damping   / this.springCfg.mass;
        let moving = false;

        this.items.forEach((item, index) => {
          const node = this._itemEls[index];
          if (!node || item === 'separator' || item?.separator) return;

          const state = entry(index);
          const reach = focus === null ? 1 : Math.abs(focus - rest[index]) / this.distance;

          // Parabolic cosine-squared curve from React Bits
          const target = this.baseItemSize + (this.magnification - this.baseItemSize) * (reach < 1 ? Math.cos((reach * Math.PI) / 2) ** 2 : 0);

          if (reduce) {
            state.size = target;
            state.velocity = 0;
            state.lift = 0;
            state.hopping = false;
          } else {
            // Sub-stepped semi-implicit Euler integration
            for (let s = 0; s < steps; s++) {
              state.velocity = (state.velocity + h * k * (target - state.size)) / (1 + h * c + h * h * k);
              state.size += state.velocity * h;

              // macOS-style Launch & Hop physics
              if (state.hopping) {
                state.rise -= state.gravity * h;
                state.lift += state.rise * h;
                if (state.lift <= 0) {
                  state.lift = 0;
                  state.rise *= -0.36; // Ground collision bounce restitution
                  if (Math.abs(state.rise) < state.launch * 0.2) {
                    state.rise = 0;
                    state.hopping = false;
                    node.removeAttribute('data-hop');
                  }
                }
              }
            }
          }

          if (Math.abs(state.velocity) < 0.01 && Math.abs(target - state.size) < 0.01) {
            state.size = target;
            state.velocity = 0;
          } else {
            moving = true;
          }

          if (state.hopping) moving = true;

          // Render updated size and lift
          const size = Math.max(1, state.size);
          node.style.width  = size.toFixed(2) + 'px';
          node.style.height = size.toFixed(2) + 'px';

          const iconEl = node.querySelector('.dock-icon');
          if (iconEl) {
            iconEl.style.transform = `scale(${(size / this.baseItemSize).toFixed(3)})`;
          }

          if (state.lift > 0.01) {
            node.style.transform = `translateY(${-state.lift.toFixed(2)}px)`;
          } else if (node.style.transform) {
            node.style.transform = '';
          }
        });

        if (moving) raf = requestAnimationFrame(tick);
      };

      const wake = () => {
        if (raf || !this._alive) return;
        last = performance.now();
        raf = requestAnimationFrame(tick);
      };

      // Hop trigger
      this.hop = (index) => {
        const node = this._itemEls[index];
        if (reduce || !this.bounce || !node) return;

        const state = entry(index);
        if (state.hopping) return;

        const height = state.size * HOP;
        state.gravity = (8 * height) / (HOP_TIME * HOP_TIME);
        state.launch  = (state.gravity * HOP_TIME) / 2;
        state.rise    = state.launch;
        state.hopping = true;
        node.setAttribute('data-hop', '');
        wake();
      };

      // Pointer event listeners
      const onMove = (e) => {
        pointer.x = e.clientX;
        pointer.y = e.clientY;
        pointer.inside = true;
        wake();
      };

      const onLeave = () => {
        pointer.inside = false;
        wake();
      };

      panel.addEventListener('pointermove',   onMove);
      panel.addEventListener('pointerenter',  onMove);
      panel.addEventListener('pointerleave',  onLeave);
      panel.addEventListener('pointercancel', onLeave);

      this._cleanups.push(() => {
        panel.removeEventListener('pointermove',   onMove);
        panel.removeEventListener('pointerenter',  onMove);
        panel.removeEventListener('pointerleave',  onLeave);
        panel.removeEventListener('pointercancel', onLeave);
      });

      // Item interactions & keyboard support
      const getNextIndex = (current, dir) => {
        const len = this.items.length;
        let idx = current;
        for (let step = 0; step < len; step++) {
          idx = (idx + dir + len) % len;
          if (this._itemEls[idx] !== null) return idx;
        }
        return -1;
      };

      this._itemEls.forEach((el, i) => {
        if (!el) return;
        const item = this.items[i];

        // Click / Hop handler
        const onClick = (e) => {
          this.hop(i);

          if (item.onClick) {
            item.onClick(e);
          }

          if (item.href) {
            // If navigating to a different page, let the bounce launch before navigating
            if (item.href !== window.location.pathname) {
              e.preventDefault();
              setTimeout(() => {
                window.location.href = item.href;
              }, 200);
            }
          }
        };

        el.addEventListener('click', onClick);
        this._cleanups.push(() => el.removeEventListener('click', onClick));

        // Focus → Magnify
        const onFocus = () => {
          focused = i;
          wake();
        };

        const onBlur = () => {
          if (focused === i) focused = -1;
          wake();
        };

        el.addEventListener('focus', onFocus);
        el.addEventListener('blur',  onBlur);
        this._cleanups.push(() => {
          el.removeEventListener('focus', onFocus);
          el.removeEventListener('blur',  onBlur);
        });

        // Keyboard arrow navigation
        const onKeyDown = (e) => {
          if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            e.preventDefault();
            const next = getNextIndex(i, 1);
            if (next !== -1) this._itemEls[next]?.focus();
          } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            e.preventDefault();
            const prev = getNextIndex(i, -1);
            if (prev !== -1) this._itemEls[prev]?.focus();
          } else if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            el.click();
          }
        };

        el.addEventListener('keydown', onKeyDown);
        this._cleanups.push(() => el.removeEventListener('keydown', onKeyDown));
      });

      wake();
    }

    /* ── Public API ── */

    /**
     * Update active dot state for an item by index
     */
    setActive(index, active = true) {
      const el = this._itemEls[index];
      if (!el) return;
      if (active) el.classList.add('dock-item--active');
      else        el.classList.remove('dock-item--active');
    }

    /**
     * Cleanup and remove dock
     */
    destroy() {
      this._alive = false;
      this._cleanups.forEach(fn => fn());
      this._outer?.parentNode?.removeChild(this._outer);
    }

    /**
     * Static mount helper
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

})(typeof window !== 'undefined' ? window : this);
