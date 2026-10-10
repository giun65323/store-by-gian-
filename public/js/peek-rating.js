/**
 * PeekRating — Vanilla JS & Web Component port of React Bits <PeekRating />
 * Hover-scrubbing, star lift & pop physics, animated tooltip pill.
 *
 * Usage:
 *   const pr = new PeekRating(containerElement, {
 *     defaultValue: 5,
 *     count: 5,
 *     labels: ['Kecewa', 'Kurang', 'Cukup', 'Puas', 'Sangat Puas'],
 *     activeColor: '#f5b400',
 *     idleColor: '#52525b',
 *     tipColor: '#27272a',
 *     tipTextColor: '#f5f5f5',
 *     size: 32,
 *     lift: 7,
 *     magnify: 1.15,
 *     popScale: 1.3,
 *     inputName: 'rating',
 *     onChange: (val) => console.log('Rated:', val)
 *   });
 *
 * Auto-mounts on elements with `[data-peek-rating]`.
 */

(function (global) {
  'use strict';

  const EASE_OUT = 'cubic-bezier(0.23, 1, 0.32, 1)';
  const clamp = (val, min, max) => Math.min(max, Math.max(min, val));
  const reducedMotion = () =>
    typeof window !== 'undefined' && !!window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;

  // Standard Star SVG Path from Hugeicons / React Bits
  const STAR_SVG = `<svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.524 3.244a.53.53 0 0 1 .952 0l2.673 5.416a.53.53 0 0 0 .4.29l5.977.868c.45.066.63.621.305.939l-4.325 4.216a.53.53 0 0 0-.152.469l1.021 5.952a.53.53 0 0 1-.77.559L12.24 19.14a.53.53 0 0 0-.48 0l-5.365 2.822a.53.53 0 0 1-.77-.559l1.021-5.952a.53.53 0 0 0-.152-.469L2.17 10.757a.53.53 0 0 1 .305-.939l5.977-.868a.53.53 0 0 0 .4-.29l2.672-5.416Z"/></svg>`;

  class PeekRating {
    constructor(container, opts = {}) {
      if (typeof container === 'string') {
        container = document.querySelector(container);
      }
      if (!container) return;

      this.container = container;
      const prevRoot = this.container.querySelector('.peek-rating');
      if (prevRoot) prevRoot.remove();

      this.opts = Object.assign(
        {
          defaultValue: 0,
          value: undefined,
          count: 5,
          labels: ['1 of 5 (Kecewa)', '2 of 5 (Kurang)', '3 of 5 (Cukup)', '4 of 5 (Puas)', '5 of 5 (Sangat Puas)'],
          activeColor: '#f5b400',
          idleColor: '#cbd5e1',
          tipColor: '#1e293b',
          tipTextColor: '#f8fafc',
          size: 32,
          lift: 7,
          magnify: 1.15,
          riseDuration: 320,
          popScale: 1.3,
          showTip: true,
          allowClear: false,
          readOnly: false,
          disabled: false,
          ariaLabel: 'Rating',
          className: '',
          inputName: 'rating',
          onChange: null,
          onPreview: null,
        },
        opts
      );

      // Parse data-attributes on container if present
      if (container.dataset) {
        if (container.dataset.defaultValue !== undefined) this.opts.defaultValue = Number(container.dataset.defaultValue);
        if (container.dataset.value !== undefined) this.opts.value = Number(container.dataset.value);
        if (container.dataset.size !== undefined) this.opts.size = Number(container.dataset.size);
        if (container.dataset.readOnly === 'true') this.opts.readOnly = true;
        if (container.dataset.inputName) this.opts.inputName = container.dataset.inputName;
      }

      this.value = clamp(this.opts.value ?? this.opts.defaultValue, 0, this.opts.count);
      this.interactive = !this.opts.readOnly && !this.opts.disabled;

      this.st = {
        hover: null,
        pressing: false,
        pointerId: null,
        settled: false,
        rect: null,
        rtl: false,
      };

      this.starEls = [];
      this.liftEls = [];
      this.glyphEls = [];

      this._render();
      this._bindEvents();
      this.paint();
    }

    _render() {
      const { count, activeColor, idleColor, tipColor, tipTextColor, size, lift, riseDuration, showTip, readOnly, disabled, ariaLabel, className, inputName } = this.opts;

      const tipRoom = this.interactive && showTip ? Math.round(size * 0.9) : 0;

      this.root = document.createElement('div');
      this.root.role = readOnly ? 'img' : 'radiogroup';
      this.root.setAttribute('aria-label', readOnly ? `${this.value} of ${count}` : ariaLabel);
      if (disabled) this.root.setAttribute('aria-disabled', 'true');
      this.root.className = `peek-rating${className ? ' ' + className : ''}`;

      this.root.style.setProperty('--pr-active', activeColor);
      this.root.style.setProperty('--pr-idle', idleColor);
      this.root.style.setProperty('--pr-tip', tipColor);
      this.root.style.setProperty('--pr-tip-text', tipTextColor);
      this.root.style.setProperty('--pr-size', `${size}px`);
      this.root.style.setProperty('--pr-gap', `${Math.round(size * 0.22)}px`);
      this.root.style.setProperty('--pr-room', `${lift + tipRoom}px`);
      this.root.style.setProperty('--pr-rise', `${riseDuration}ms`);

      this.row = document.createElement('div');
      this.row.className = 'peek-rating__row';
      this.root.appendChild(this.row);

      // Tooltip pill
      if (this.interactive && showTip) {
        this.tipEl = document.createElement('span');
        this.tipEl.className = 'peek-rating__tip';
        this.tipEl.setAttribute('aria-hidden', 'true');
        this.row.appendChild(this.tipEl);
      }

      // Hidden form input for Laravel POST submission
      if (inputName && !readOnly) {
        let existingInput = this.container.querySelector(`input[name="${inputName}"]`) ||
                            (this.container.closest?.('form')?.querySelector(`input[name="${inputName}"]`));
        if (!existingInput) {
          this.hiddenInput = document.createElement('input');
          this.hiddenInput.type = 'hidden';
          this.hiddenInput.name = inputName;
          this.hiddenInput.value = this.value;
          this.container.appendChild(this.hiddenInput);
        } else {
          this.hiddenInput = existingInput;
          this.hiddenInput.value = this.value;
        }
      }

      // Generate Stars
      for (let i = 0; i < count; i++) {
        const star = document.createElement(readOnly ? 'span' : 'button');
        if (!readOnly) star.type = 'button';
        star.className = 'peek-rating__star';
        star.role = readOnly ? undefined : 'radio';
        star.setAttribute('aria-label', this.opts.labels[i] || `${i + 1} of ${count}`);
        star.tabIndex = !this.interactive ? -1 : (this.value === 0 ? i === 0 : this.value === i + 1) ? 0 : -1;

        const liftEl = document.createElement('span');
        liftEl.className = 'peek-rating__lift';

        const glyphEl = document.createElement('span');
        glyphEl.className = 'peek-rating__glyph';
        glyphEl.innerHTML = STAR_SVG;

        liftEl.appendChild(glyphEl);
        star.appendChild(liftEl);
        this.row.appendChild(star);

        this.starEls.push(star);
        this.liftEls.push(liftEl);
        this.glyphEls.push(glyphEl);
      }

      this.container.appendChild(this.root);
    }

    paint() {
      const { count, lift, magnify, showTip, labels, size } = this.opts;
      const { hover, settled, rtl } = this.st;
      const previewing = hover !== null && !settled;
      const shown = previewing ? hover + 1 : this.value;
      const still = reducedMotion();

      for (let i = 0; i < count; i++) {
        const liftEl = this.liftEls[i];
        const glyphEl = this.glyphEls[i];
        if (!liftEl || !glyphEl) continue;

        const lifted = previewing && !still && i <= hover;
        liftEl.style.transform = lifted
          ? `translateY(${-lift}px) scale(${i === hover ? magnify : 1})`
          : 'translateY(0px) scale(1)';
        glyphEl.dataset.lit = i < shown;
      }

      if (!this.tipEl) return;
      if (previewing && showTip) {
        const slot = this.row ? this.row.clientWidth / count : size;
        const visual = rtl ? count - 1 - hover : hover;
        const wasHidden = this.tipEl.dataset.show !== 'true';
        if (wasHidden) this.tipEl.style.transition = 'none';

        this.tipEl.textContent = labels[hover] ?? String(hover + 1);
        this.tipEl.style.transform = `translate(calc(${slot * (visual + 0.5)}px - 50%), 0)`;

        if (wasHidden) {
          void this.tipEl.offsetWidth;
          this.tipEl.style.transition = '';
        }
        this.tipEl.dataset.show = 'true';
      } else {
        this.tipEl.dataset.show = 'false';
      }
    }

    setHover(index) {
      if (index === this.st.hover) return;
      this.st.hover = index;
      if (index !== null) this.st.settled = false;
      this.paint();
      if (this.opts.onPreview) {
        this.opts.onPreview(index === null ? null : index + 1);
      }
    }

    measure() {
      if (!this.row) return;
      this.st.rect = this.row.getBoundingClientRect();
      this.st.rtl = getComputedStyle(this.row).direction === 'rtl';
    }

    indexAt(x, y) {
      const { rect, pressing, rtl } = this.st;
      const { count, size } = this.opts;
      if (!rect || !rect.width) return null;
      if (pressing && (y < rect.top - size || y > rect.bottom + size)) return null;
      const index = clamp(Math.floor(((x - rect.left) / rect.width) * count), 0, count - 1);
      return rtl ? count - 1 - index : index;
    }

    commit(next, pop = true) {
      this.value = next;
      if (this.hiddenInput) {
        this.hiddenInput.value = next;
        this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
      }
      if (this.opts.onChange) {
        this.opts.onChange(next);
      }

      this.st.settled = true;
      this.paint();

      const glyph = this.glyphEls[next - 1];
      if (pop && next > 0 && this.opts.popScale > 1 && glyph && typeof glyph.animate === 'function' && !reducedMotion()) {
        try {
          glyph.getAnimations?.().forEach(a => a.cancel());
        } catch (_) {}
        glyph.animate(
          [
            { transform: 'scale(1)', easing: EASE_OUT },
            { transform: `scale(${this.opts.popScale})`, offset: 0.35, easing: EASE_OUT },
            { transform: 'scale(1)' }
          ],
          { duration: 300 }
        );
      }
    }

    _bindEvents() {
      if (!this.interactive) return;

      const row = this.row;

      row.addEventListener('pointerenter', (e) => {
        if (e.pointerType !== 'mouse') return;
        this.root.removeAttribute('data-instant');
        this.measure();
      });

      row.addEventListener('pointerdown', (e) => {
        if (e.button !== 0 || this.st.pointerId !== null) return;
        this.root.removeAttribute('data-instant');
        try {
          row.setPointerCapture(e.pointerId);
        } catch (_) {}
        this.st.pointerId = e.pointerId;
        this.st.pressing = true;
        this.measure();
        this.setHover(this.indexAt(e.clientX, e.clientY));
      });

      row.addEventListener('pointermove', (e) => {
        const { pressing, pointerId } = this.st;
        if (e.pointerType !== 'mouse' && !pressing) return;
        if (pressing && e.pointerId !== pointerId) return;
        if (!pressing && !this.st.rect) this.measure();
        this.setHover(this.indexAt(e.clientX, e.clientY));
      });

      const endPress = (e) => {
        const { pressing, pointerId, hover } = this.st;
        if (!pressing || e.pointerId !== pointerId) return;
        this.st.pressing = false;
        this.st.pointerId = null;

        if (e.type === 'pointerup' && hover !== null) {
          const next = hover + 1;
          this.commit(this.opts.allowClear && next === this.value ? 0 : next);
        }
        if (e.pointerType !== 'mouse') this.setHover(null);
      };

      row.addEventListener('pointerup', endPress);
      row.addEventListener('pointercancel', endPress);
      row.addEventListener('lostpointercapture', endPress);

      row.addEventListener('pointerleave', () => {
        if (!this.st.pressing) this.setHover(null);
      });

      // Keyboard support
      this.root.addEventListener('keydown', (e) => {
        const min = this.opts.allowClear ? 0 : 1;
        let next;
        switch (e.key) {
          case 'ArrowRight':
          case 'ArrowUp':
            next = clamp(this.value + 1, min, this.opts.count);
            break;
          case 'ArrowLeft':
          case 'ArrowDown':
            next = clamp(this.value - 1, min, this.opts.count);
            break;
          case 'Home':
            next = 1;
            break;
          case 'End':
            next = this.opts.count;
            break;
          case 'Backspace':
          case 'Delete':
            if (!this.opts.allowClear) return;
            next = 0;
            break;
          case ' ':
          case 'Enter': {
            const index = this.starEls.indexOf(e.target);
            if (index === -1) return;
            next = this.opts.allowClear && index + 1 === this.value ? 0 : index + 1;
            break;
          }
          default:
            return;
        }
        e.preventDefault();
        this.root.setAttribute('data-instant', 'true');
        this.st.hover = null;
        this.commit(next, false);
        this.starEls[Math.max(next, 1) - 1]?.focus();
      });
    }

    setValue(next) {
      this.commit(clamp(next, 0, this.opts.count));
    }

    refresh() {
      this.measure();
      this.paint();
    }
  }

  // Static mount helper
  PeekRating.mount = function (selector, opts = {}) {
    const els = typeof selector === 'string' ? document.querySelectorAll(selector) : [selector];
    return Array.from(els).map(el => new PeekRating(el, opts));
  };

  // Auto-init on DOMContentLoaded
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-peek-rating]').forEach(el => {
      new PeekRating(el);
    });
  });

  global.PeekRating = PeekRating;
})(typeof window !== 'undefined' ? window : this);
