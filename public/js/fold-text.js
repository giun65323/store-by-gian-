/**
 * FoldText — Vanilla JS port of the React Bits FoldText component.
 * Depends on GSAP (+ ScrollTrigger plugin for trigger='scroll').
 *
 * Usage:
 *   new FoldText(element, { options });
 *
 * Options mirror the React Bits props table exactly:
 *   text, splitBy, hinge, duration, stagger, ease, perspective,
 *   creaseShading, trigger, fontSize, fontWeight, color, className, style
 */

(function (global) {
  'use strict';

  /* ─── hinge config ─── */
  const HINGE_CONFIG = {
    top:    { origin: '50% 0%',   rotateX: -92, rotateY:   0 },
    bottom: { origin: '50% 100%', rotateX:  92, rotateY:   0 },
    left:   { origin: '0% 50%',   rotateX:   0, rotateY:  92 },
    right:  { origin: '100% 50%', rotateX:   0, rotateY: -92 },
  };

  const clamp = (v, min, max) => Math.min(max, Math.max(min, v));

  /* ─── helpers ─── */

  /**
   * Build a single `.fold-text-segment > .fold-text-piece` pair
   * and append it to `parent`.
   */
  function createSegment(content, hinge, hingeConfig, safePerspective, splitBy) {
    const segment = document.createElement('span');
    segment.className = 'fold-text-segment';
    segment.dataset.foldSplit = splitBy;
    segment.style.setProperty('--fold-perspective', `${safePerspective}px`);

    const piece = document.createElement('span');
    piece.className = 'fold-text-piece';
    piece.dataset.foldHinge = hinge;
    piece.style.transformOrigin = hingeConfig.origin;
    piece.style.setProperty('--fold-crease', '0');

    if (content === ' ') {
      piece.innerHTML = '&nbsp;';
    } else {
      piece.textContent = content || '\u00A0';
    }

    segment.appendChild(piece);
    return segment;
  }

  /**
   * Build the visual DOM inside `visualSpan` based on `splitBy`.
   */
  function buildSegments(text, splitBy, hinge, hingeConfig, safePerspective, visualSpan) {
    visualSpan.innerHTML = '';

    if (splitBy === 'line') {
      text.split('\n').forEach((line) => {
        const lineWrap = document.createElement('span');
        lineWrap.className = 'fold-text-line';
        const seg = createSegment(line || '\u00A0', hinge, hingeConfig, safePerspective, 'line');
        lineWrap.appendChild(seg);
        visualSpan.appendChild(lineWrap);
      });
      return;
    }

    if (splitBy === 'word') {
      const parts = text.split(/(\s+)/);
      parts.forEach((part, index) => {
        if (!part) return;
        if (/^\s+$/.test(part)) {
          // whitespace — render as nbsp spans (handle embedded newlines)
          part.split(/(\n)/).forEach((sub, si) => {
            if (sub === '\n') {
              visualSpan.appendChild(document.createElement('br'));
            } else if (sub) {
              const ws = document.createElement('span');
              ws.className = 'fold-text-whitespace';
              ws.textContent = sub.replace(/ /g, '\u00A0');
              visualSpan.appendChild(ws);
            }
          });
        } else {
          visualSpan.appendChild(
            createSegment(part, hinge, hingeConfig, safePerspective, 'word')
          );
        }
      });
      return;
    }

    // splitBy === 'char' (default)
    Array.from(text).forEach((char, index) => {
      if (char === '\n') {
        visualSpan.appendChild(document.createElement('br'));
      } else {
        visualSpan.appendChild(
          createSegment(char === ' ' ? '\u00A0' : char, hinge, hingeConfig, safePerspective, 'char')
        );
      }
    });
  }

  /* ─── FoldText class ─── */

  class FoldText {
    /**
     * @param {HTMLElement} element  – The host element to transform into a FoldText.
     * @param {Object}      options  – Configuration (see prop table in docs).
     */
    constructor(element, options = {}) {
      this.root = element;

      // props
      this.text         = options.text         ?? 'Design unfolds';
      this.splitBy      = options.splitBy      ?? 'char';
      this.hinge        = options.hinge        ?? 'top';
      this.duration     = options.duration     ?? 0.65;
      this.stagger      = options.stagger      ?? 0.045;
      this.ease         = options.ease         ?? 'power3.out';
      this.perspective  = options.perspective  ?? 700;
      this.creaseShading= options.creaseShading?? 0.55;
      this.trigger      = options.trigger      ?? 'mount';
      this.fontSize     = options.fontSize     ?? 80;
      this.fontWeight   = options.fontWeight   ?? 800;
      this.color        = options.color        ?? '#f7f2e8';
      this.className    = options.className    ?? '';
      this.style        = options.style        ?? {};

      // derived
      this.hingeConfig    = HINGE_CONFIG[this.hinge] || HINGE_CONFIG.top;
      this.safeCrease     = clamp(this.creaseShading, 0, 1);
      this.safePerspective= Math.max(120, this.perspective);

      this._timeline      = null;
      this._scrollTrigger = null;
      this._hoverHandler  = null;

      this._init();
    }

    /* ── DOM setup ── */
    _init() {
      const root = this.root;

      // apply root classes
      root.classList.add('fold-text');
      if (this.className) {
        this.className.split(' ').filter(Boolean).forEach(c => root.classList.add(c));
      }

      // apply CSS custom properties
      const fs = typeof this.fontSize === 'number' ? `${this.fontSize}px` : this.fontSize;
      root.style.setProperty('--fold-text-font-size',   fs);
      root.style.setProperty('--fold-text-font-weight', this.fontWeight);
      root.style.setProperty('--fold-text-color',       this.color);

      // apply any inline style overrides
      Object.entries(this.style).forEach(([k, v]) => (root.style[k] = v));

      // sr-only span
      const srOnly = document.createElement('span');
      srOnly.className = 'fold-text-sr-only';
      srOnly.textContent = this.text;
      root.appendChild(srOnly);

      // visual span
      this._visualSpan = document.createElement('span');
      this._visualSpan.className = 'fold-text-visual';
      this._visualSpan.setAttribute('aria-hidden', 'true');
      root.appendChild(this._visualSpan);

      buildSegments(
        this.text,
        this.splitBy,
        this.hinge,
        this.hingeConfig,
        this.safePerspective,
        this._visualSpan
      );

      this._setupTrigger();
    }

    /* ── animation ── */
    _pieces() {
      return Array.from(this.root.querySelectorAll('.fold-text-piece'));
    }

    _fromVars(reduceMotion) {
      return {
        opacity:          0,
        rotateX:          reduceMotion ? 0 : this.hingeConfig.rotateX,
        rotateY:          reduceMotion ? 0 : this.hingeConfig.rotateY,
        '--fold-crease':  reduceMotion ? 0 : this.safeCrease,
        transformOrigin:  this.hingeConfig.origin,
        force3D:          true,
      };
    }

    _toVars(reduceMotion, repeat) {
      const activeDuration = reduceMotion ? Math.min(this.duration, 0.22) : this.duration;
      const activeStagger  = reduceMotion ? Math.min(this.stagger,  0.02) : this.stagger;
      return {
        opacity:         1,
        rotateX:         0,
        rotateY:         0,
        '--fold-crease': 0,
        duration:        activeDuration,
        ease:            reduceMotion ? 'power1.out' : this.ease,
        stagger:         activeStagger,
        clearProps:      'willChange',
        ...(repeat ? {} : {}),
      };
    }

    _killTimeline() {
      if (this._timeline) {
        this._timeline.kill();
        this._timeline = null;
      }
      const pieces = this._pieces();
      if (pieces.length && window.gsap) gsap.killTweensOf(pieces);
    }

    _play(repeat = false) {
      if (!window.gsap) {
        console.warn('FoldText: GSAP not found. Load gsap before fold-text.js.');
        return null;
      }

      const reduceMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches ?? false;
      const pieces = this._pieces();
      if (!pieces.length) return null;

      this._killTimeline();

      const tl = gsap.timeline({
        repeat:      repeat ? -1  : 0,
        repeatDelay: repeat ? 0.75 : 0,
      });
      tl.fromTo(pieces, this._fromVars(reduceMotion), this._toVars(reduceMotion, repeat));
      this._timeline = tl;
      return tl;
    }

    /* ── trigger setup ── */
    _setupTrigger() {
      const root    = this.root;
      const trigger = this.trigger;

      if (trigger === 'hover') {
        // Start in "visible" state; re-animate on mouseenter
        if (window.gsap) {
          gsap.set(this._pieces(), {
            opacity: 1, rotateX: 0, rotateY: 0,
            '--fold-crease': 0,
            transformOrigin: this.hingeConfig.origin,
          });
        }
        this._hoverHandler = () => this._play(false);
        root.addEventListener('mouseenter', this._hoverHandler);

      } else if (trigger === 'scroll') {
        if (!window.gsap) return;
        // Register ScrollTrigger if available
        if (gsap.ScrollTrigger) {
          gsap.set(this._pieces(), this._fromVars(false));
          this._scrollTrigger = gsap.ScrollTrigger.create({
            trigger: root,
            start:   'top 82%',
            once:    true,
            onEnter: () => this._play(false),
          });
        } else {
          // Fallback: IntersectionObserver when ScrollTrigger plugin is absent
          gsap.set(this._pieces(), this._fromVars(false));
          const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                this._play(false);
                io.disconnect();
              }
            });
          }, { threshold: 0.18 });
          io.observe(root);
          this._io = io;
        }

      } else if (trigger === 'loop') {
        this._play(true);

      } else {
        // 'mount' (default)
        this._play(false);
      }
    }

    /* ── public API ── */

    /** Re-run the animation immediately. */
    play(repeat = false) {
      return this._play(repeat);
    }

    /** Fully tear-down events, timelines, and ScrollTrigger. */
    destroy() {
      this._killTimeline();
      if (this._hoverHandler) {
        this.root.removeEventListener('mouseenter', this._hoverHandler);
        this._hoverHandler = null;
      }
      if (this._scrollTrigger) {
        this._scrollTrigger.kill();
        this._scrollTrigger = null;
      }
      if (this._io) {
        this._io.disconnect();
        this._io = null;
      }
    }
  }

  /* ─── expose globally ─── */
  global.FoldText = FoldText;

})(window);
