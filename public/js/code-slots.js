/**
 * CodeSlots — Animated OTP Input (Vanilla JS)
 * Inspired by React Bits / reactbits.dev CodeSlots component.
 *
 * Usage:
 *   const cs = new CodeSlots(containerElement, {
 *     length: 6,
 *     onComplete: (code) => { ... },
 *     onChange: (code) => { ... },
 *     accentColor: '#f5f5f5',
 *     slotColor: '#27272a',
 *     ...
 *   });
 *
 *   cs.setStatus('error');   // triggers drain animation
 *   cs.setStatus('success'); // triggers wash/check animation
 *   cs.setStatus('idle');    // resets
 *   cs.destroy();            // cleanup
 */
class CodeSlots {
  /**
   * @param {HTMLElement} container  - Root element to render into
   * @param {object}      opts       - Configuration options
   */
  constructor(container, opts = {}) {
    this.container = container;
    this.opts = Object.assign(
      {
        length:      6,
        defaultValue: '',
        accentColor: '#f5f5f5',
        inkColor:    '#f5f5f5',
        slotColor:   '#27272a',
        digitColor:  '#18181b',
        dangerColor: '#ff3b30',
        slotSize:    52,
        gap:         10,
        radius:      14,
        cascade:     22,      // ms delay between slots on multi-insert / drain
        settle:      280,     // ms duration of fill spring
        autoFocus:   false,
        mask:        false,
        onChange:    null,
        onComplete:  null,
        ariaLabel:   'Kode OTP',
        inputName:   'otp',   // name attr for the hidden real input
      },
      opts
    );

    this._slots  = Array(this.opts.length).fill('');
    this._active = 0;
    this._status = 'idle';
    this._focused = false;
    this._draining = false;
    this._drainTimer = null;
    this._emitted = '';

    // pre-fill defaultValue
    if (this.opts.defaultValue) {
      const digits = this._digitsOf(this.opts.defaultValue).slice(0, this.opts.length);
      for (let i = 0; i < digits.length; i++) this._slots[i] = digits[i];
      this._active = Math.min(digits.length, this.opts.length - 1);
    }

    this._build();
    this._bindEvents();

    if (this.opts.autoFocus) this._input.focus();
  }

  /* ------------------------------------------------------------------ */
  /* Public API                                                           */
  /* ------------------------------------------------------------------ */

  /** Set status: 'idle' | 'error' | 'success' */
  setStatus(status) {
    const prev = this._status;
    this._status = status;
    this._row.dataset.status = status;

    if (status === 'error') {
      this._runDrain();
    }

    if (status === 'success') {
      this._row.dataset.status = 'success';
      this._input.readOnly = true;
      this._updateCaret();
    }

    if (status === 'idle' && prev !== 'idle') {
      this._input.readOnly = false;
      this._updateCaret();
    }
  }

  /** Get current code string */
  getValue() { return this._slots.join(''); }

  /** Programmatically set value */
  setValue(raw) {
    const digits = this._digitsOf(raw).slice(0, this.opts.length);
    const next   = Array(this.opts.length).fill('');
    for (let i = 0; i < digits.length; i++) next[i] = digits[i];

    for (let i = 0; i < this.opts.length; i++) {
      const had = this._slots[i];
      const has = next[i];
      if (had !== has) {
        this._slots[i] = has;
        if (has) this._landSlot(i);
        else this._drainSlotVisual(i);
      }
    }
    this._active = this._firstEmpty(next);
    this._slots  = next;
    this._updateAll();
  }

  destroy() {
    clearTimeout(this._drainTimer);
    this._input.removeEventListener('keydown',  this._onKeyDown);
    this._input.removeEventListener('paste',    this._onPaste);
    this._input.removeEventListener('input',    this._onInput);
    this._input.removeEventListener('focus',    this._onFocus);
    this._input.removeEventListener('blur',     this._onBlur);
    this._row.removeEventListener('mousedown',  this._onMouseDown);
  }

  /* ------------------------------------------------------------------ */
  /* Build DOM                                                            */
  /* ------------------------------------------------------------------ */

  _build() {
    const o  = this.opts;
    const sz = o.slotSize;
    const h  = Math.round(sz * 1.18);
    const r  = Math.min(o.radius, sz / 2);
    const pitch = sz + o.gap;

    // root
    const root = document.createElement('div');
    root.className = 'code-slots';
    root.style.cssText = [
      `--cs-accent:${o.accentColor}`,
      `--cs-ink:${o.inkColor}`,
      `--cs-slot:${o.slotColor}`,
      `--cs-digit:${o.digitColor}`,
      `--cs-danger:${o.dangerColor}`,
      `--cs-size:${sz}px`,
      `--cs-height:${h}px`,
      `--cs-gap:${o.gap}px`,
      `--cs-radius:${r}px`,
      `--cs-font:${Math.round(sz * 0.5)}px`,
    ].join(';');

    // row
    const row = document.createElement('div');
    row.className = 'code-slots__row';
    row.dataset.status = 'idle';

    // hidden real input
    const input = document.createElement('input');
    input.type          = 'text';
    input.inputMode     = 'numeric';
    input.autocomplete  = 'one-time-code';
    input.pattern       = '[0-9]*';
    input.maxLength     = o.length;
    input.value         = '';
    input.ariaLabel     = o.ariaLabel;
    input.name          = o.inputName;
    input.className     = 'code-slots__input';
    row.appendChild(input);
    this._input = input;

    // slot elements
    this._slotEls = [];
    this._fillEls = [];
    this._digitEls = [];

    for (let i = 0; i < o.length; i++) {
      const slot = document.createElement('span');
      slot.className = 'code-slots__slot';

      const fill = document.createElement('span');
      fill.className = 'code-slots__fill';
      slot.appendChild(fill);

      const digit = document.createElement('span');
      digit.className = 'code-slots__digit';
      digit.textContent = '';
      slot.appendChild(digit);

      // pre-filled from defaultValue
      if (this._slots[i]) {
        slot.dataset.filled = '';
        digit.textContent = o.mask ? '•' : this._slots[i];
      }

      row.appendChild(slot);
      this._slotEls.push(slot);
      this._fillEls.push(fill);
      this._digitEls.push(digit);
    }

    // success wash overlay
    const wash = document.createElement('span');
    wash.className = 'code-slots__wash';
    wash.ariaHidden = 'true';

    const check = document.createElement('span');
    check.className = 'code-slots__check';
    // SVG checkmark (similar to Tick02Icon)
    check.innerHTML = `<svg width="${Math.round(sz * 0.6)}" height="${Math.round(sz * 0.6)}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`;
    wash.appendChild(check);
    row.appendChild(wash);
    this._wash  = wash;
    this._check = check;

    // caret
    const caret = document.createElement('span');
    caret.className = 'code-slots__caret';
    caret.ariaHidden = 'true';
    caret.style.left = `${(sz / 2) - 1}px`;
    row.appendChild(caret);
    this._caret = caret;

    root.appendChild(row);
    this._row = row;

    // hidden real submit input (the one Laravel reads)
    this._hiddenInput = document.createElement('input');
    this._hiddenInput.type   = 'hidden';
    this._hiddenInput.name   = o.inputName;
    this._hiddenInput.value  = this._slots.join('');
    root.appendChild(this._hiddenInput);

    // sr-only live region
    const sr = document.createElement('span');
    sr.className = 'code-slots__sr';
    sr.setAttribute('aria-live', 'polite');
    sr.id = `cs-sr-${Math.random().toString(36).slice(2)}`;
    root.appendChild(sr);
    this._sr = sr;

    this._pitch = pitch;
    this._slotSize = sz;

    // replace container contents
    this.container.innerHTML = '';
    this.container.appendChild(root);
    this._root = root;

    // set active slot visuals
    this._updateActiveSlot();
    this._updateCaret();
  }

  /* ------------------------------------------------------------------ */
  /* Events                                                               */
  /* ------------------------------------------------------------------ */

  _bindEvents() {
    this._onKeyDown  = this._handleKeyDown.bind(this);
    this._onPaste    = this._handlePaste.bind(this);
    this._onInput    = this._handleInput.bind(this);
    this._onFocus    = () => { this._focused = true;  this._updateCaret(); };
    this._onBlur     = () => { this._focused = false; this._updateCaret(); };
    this._onMouseDown = this._handleMouseDown.bind(this);

    this._input.addEventListener('keydown',  this._onKeyDown);
    this._input.addEventListener('paste',    this._onPaste);
    this._input.addEventListener('input',    this._onInput);
    this._input.addEventListener('focus',    this._onFocus);
    this._input.addEventListener('blur',     this._onBlur);
    this._row.addEventListener('mousedown',  this._onMouseDown);
  }

  _handleKeyDown(e) {
    if (this._busy() || e.metaKey || e.ctrlKey || e.altKey) return;
    const k = e.key;

    if (/^[0-9]$/.test(k)) {
      e.preventDefault();
      this._insert(k);
    } else if (k === 'Backspace') {
      e.preventDefault();
      if (this._slots[this._active]) {
        this._clearSlot(this._active);
      } else if (this._active > 0) {
        this._clearSlot(this._active - 1);
        this._active = this._active - 1;
        this._updateActiveSlot();
        this._updateCaret();
      }
    } else if (k === 'Delete') {
      e.preventDefault();
      this._clearSlot(this._active);
    } else if (k === 'ArrowLeft') {
      e.preventDefault();
      this._active = Math.max(this._active - 1, 0);
      this._updateActiveSlot(); this._updateCaret();
    } else if (k === 'ArrowRight') {
      e.preventDefault();
      this._active = Math.min(this._active + 1, this.opts.length - 1);
      this._updateActiveSlot(); this._updateCaret();
    } else if (k === 'Home') {
      e.preventDefault();
      this._active = 0;
      this._updateActiveSlot(); this._updateCaret();
    } else if (k === 'End') {
      e.preventDefault();
      this._active = this.opts.length - 1;
      this._updateActiveSlot(); this._updateCaret();
    }
  }

  _handlePaste(e) {
    if (this._busy()) return;
    e.preventDefault();
    this._insert(e.clipboardData.getData('text'), 0);
  }

  _handleInput(e) {
    if (this._busy()) return;
    const d = this._digitsOf(e.target.value);
    if (!d) { e.target.value = ''; return; }
    e.target.value = '';
    const from = d.length === 1 ? this._active : 0;
    this._insert(d, from);
  }

  _handleMouseDown(e) {
    if (this._busy()) return;
    e.preventDefault();
    const rect  = this._row.getBoundingClientRect();
    const zoom  = rect.width / (this._row.offsetWidth || rect.width) || 1;
    const i     = Math.floor((e.clientX - rect.left) / zoom / this._pitch);
    const clamped = Math.max(0, Math.min(i, this._firstEmpty()));
    this._active = clamped;
    this._updateActiveSlot();
    this._updateCaret();
    this._input.focus();
  }

  /* ------------------------------------------------------------------ */
  /* Core logic                                                           */
  /* ------------------------------------------------------------------ */

  _busy() {
    return this._draining || this._status === 'success';
  }

  _digitsOf(raw) {
    return String(raw ?? '').replace(/\D/g, '');
  }

  _firstEmpty(arr) {
    const s = arr || this._slots;
    const i = s.indexOf('');
    return i === -1 ? s.length - 1 : i;
  }

  _isFull(arr) {
    return (arr || this._slots).every(Boolean);
  }

  /** Insert one or more digits starting at `from` */
  _insert(raw, from) {
    if (from === undefined) from = this._active;
    const digits = this._digitsOf(raw);
    if (!digits) return;

    const wasFull = this._isFull();
    let i = from;
    for (const ch of digits) {
      if (i >= this.opts.length) break;
      this._slots[i] = ch;
      // land with cascade delay
      const delay = (i - from) * this.opts.cascade;
      this._landSlotDelayed(i, delay);
      i++;
    }

    this._active = Math.min(i, this.opts.length - 1);
    this._updateActiveSlot();
    this._updateCaret();
    this._commit(wasFull);
  }

  _clearSlot(i) {
    if (!this._slots[i]) return;
    const wasFull = this._isFull();
    this._slots[i] = '';
    this._drainSlotVisual(i);
    this._commit(wasFull);
    this._updateActiveSlot();
    this._updateCaret();
  }

  _commit(wasFull) {
    const code = this._slots.join('');
    this._hiddenInput.value = code;
    // sync the visible input value too (keep empty for display)
    this._input.value = '';

    if (code !== this._emitted) {
      this._emitted = code;
      this.opts.onChange?.(code);
    }

    this._updateSR();

    if (!wasFull && this._isFull()) {
      this.opts.onComplete?.(code);
    }
  }

  /* ------------------------------------------------------------------ */
  /* Slot visual updates                                                  */
  /* ------------------------------------------------------------------ */

  _landSlotDelayed(i, delayMs) {
    if (delayMs <= 0) {
      this._landSlot(i);
    } else {
      setTimeout(() => this._landSlot(i), delayMs);
    }
  }

  _landSlot(i) {
    const slot  = this._slotEls[i];
    const digit = this._digitEls[i];
    if (!slot) return;

    const ch = this._slots[i];
    digit.textContent = this.opts.mask && ch ? '•' : (ch || '');

    if (ch) {
      // Remove and re-add data-filled to restart CSS transition
      delete slot.dataset.filled;
      slot.classList.remove('cs-draining');
      // Force reflow so browser resets transition
      void slot.offsetWidth;
      slot.dataset.filled = '';
    } else {
      delete slot.dataset.filled;
      slot.classList.remove('cs-draining');
    }
  }

  _drainSlotVisual(i) {
    const slot  = this._slotEls[i];
    const digit = this._digitEls[i];
    if (!slot) return;

    slot.classList.add('cs-draining');
    delete slot.dataset.filled;

    setTimeout(() => {
      slot.classList.remove('cs-draining');
      digit.textContent = '';
    }, this.opts.settle + 50);
  }

  _updateAll() {
    for (let i = 0; i < this.opts.length; i++) {
      this._landSlot(i);
    }
    this._updateActiveSlot();
    this._updateCaret();
    this._updateSR();
  }

  _updateActiveSlot() {
    this._slotEls.forEach((s, i) => {
      if (i === this._active) s.dataset.active = '';
      else delete s.dataset.active;
    });
  }

  _updateCaret() {
    const show = (
      this._focused &&
      !this._busy() &&
      this._status !== 'success' &&
      (this._status === 'error' || !this._slots[this._active])
    );

    const pitch = this._pitch;
    const sz    = this._slotSize;
    // center of active slot minus 1px (half caret width)
    const left  = this._active * pitch + sz / 2 - 1;
    this._caret.style.left = `${left}px`;

    if (show) {
      this._caret.dataset.show = '';
    } else {
      delete this._caret.dataset.show;
    }
  }

  _updateSR() {
    const filled = this._slots.filter(Boolean).length;
    const len    = this.opts.length;
    if (this._status === 'success') {
      this._sr.textContent = 'Kode diterima';
    } else {
      this._sr.textContent = `${filled} dari ${len} digit dimasukkan`;
    }
  }

  /* ------------------------------------------------------------------ */
  /* Drain (error) animation                                              */
  /* ------------------------------------------------------------------ */

  _runDrain() {
    const filled = this._slots
      .map((c, i) => (c ? i : -1))
      .filter(i => i >= 0)
      .reverse(); // drain last → first

    if (!filled.length) return;

    this._draining = true;
    clearTimeout(this._drainTimer);

    // update row status immediately so CSS changes color
    this._row.dataset.status = 'error';

    filled.forEach((i, k) => {
      const delay = k * this.opts.cascade;
      setTimeout(() => {
        this._slots[i] = '';
        this._drainSlotVisual(i);
      }, delay);
    });

    const totalDelay = (filled.length - 1) * this.opts.cascade + this.opts.settle + 80;

    this._drainTimer = setTimeout(() => {
      this._draining = false;
      this._slots    = Array(this.opts.length).fill('');
      this._active   = 0;
      this._emitted  = '';
      this._hiddenInput.value = '';
      this._input.value = '';
      this._updateActiveSlot();
      this._updateCaret();
      this._updateSR();
      this.opts.onChange?.('');
    }, totalDelay);
  }
}

/* ------------------------------------------------------------------ */
/* Auto-init helper                                                     */
/* Use:  <div data-code-slots data-length="6" ...></div>               */
/* ------------------------------------------------------------------ */

function initCodeSlots(root = document) {
  root.querySelectorAll('[data-code-slots]').forEach(el => {
    if (el._csInstance) return; // already initialized

    const d = el.dataset;
    const opts = {
      length:      parseInt(d.length  || 6),
      accentColor: d.accentColor || '#f5f5f5',
      inkColor:    d.inkColor    || '#f5f5f5',
      slotColor:   d.slotColor   || '#27272a',
      digitColor:  d.digitColor  || '#18181b',
      dangerColor: d.dangerColor || '#ff3b30',
      slotSize:    parseInt(d.slotSize || 52),
      gap:         parseInt(d.gap      || 10),
      radius:      parseInt(d.radius   || 14),
      cascade:     parseInt(d.cascade  || 22),
      mask:        d.mask === 'true',
      autoFocus:   d.autoFocus === 'true',
      inputName:   d.inputName  || 'otp',
      ariaLabel:   d.ariaLabel  || 'Kode OTP',
    };

    // onComplete / onChange resolved after instantiation so they can
    // reference the instance
    const cs = new CodeSlots(el, opts);

    // Wire up onComplete / onChange from data attrs if specified
    if (d.onComplete) {
      const fn = new Function('code', 'cs', d.onComplete);
      cs.opts.onComplete = code => fn(code, cs);
    }
    if (d.onChange) {
      const fn = new Function('code', 'cs', d.onChange);
      cs.opts.onChange = code => fn(code, cs);
    }

    el._csInstance = cs;
  });
}

// Auto-run on DOMContentLoaded
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => initCodeSlots());
} else {
  initCodeSlots();
}

// Expose globals
window.CodeSlots     = CodeSlots;
window.initCodeSlots = initCodeSlots;
