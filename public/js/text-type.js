class TextType {
  constructor(element, options = {}) {
    this.element = element;
    this.text = options.text || [];
    this.textArray = Array.isArray(this.text) ? this.text : [this.text];
    this.typingSpeed = options.typingSpeed ?? 50;
    this.initialDelay = options.initialDelay ?? 0;
    this.pauseDuration = options.pauseDuration ?? 2000;
    this.deletingSpeed = options.deletingSpeed ?? 30;
    this.loop = options.loop ?? true;
    this.showCursor = options.showCursor ?? true;
    this.hideCursorWhileTyping = options.hideCursorWhileTyping ?? false;
    this.cursorCharacter = options.cursorCharacter ?? '|';
    this.cursorBlinkDuration = options.cursorBlinkDuration ?? 0.5;
    this.cursorClassName = options.cursorClassName ?? '';
    this.textColors = options.textColors || [];
    this.variableSpeed = options.variableSpeed; // { min, max }
    this.onSentenceComplete = options.onSentenceComplete;
    this.startOnVisible = options.startOnVisible ?? false;
    this.reverseMode = options.reverseMode ?? false;

    this.displayedText = '';
    this.currentCharIndex = 0;
    this.isDeleting = false;
    this.currentTextIndex = 0;
    this.isVisible = !this.startOnVisible;
    this.timeout = null;

    this.init();
  }

  init() {
    this.element.classList.add('text-type');
    this.element.innerHTML = '';

    this.contentSpan = document.createElement('span');
    this.contentSpan.className = 'text-type__content';
    this.element.appendChild(this.contentSpan);

    if (this.showCursor) {
      this.cursorSpan = document.createElement('span');
      this.cursorSpan.className = `text-type__cursor ${this.cursorClassName}`;
      this.cursorSpan.textContent = this.cursorCharacter;
      this.element.appendChild(this.cursorSpan);

      if (window.gsap) {
        window.gsap.set(this.cursorSpan, { opacity: 1 });
        window.gsap.to(this.cursorSpan, {
          opacity: 0,
          duration: this.cursorBlinkDuration,
          repeat: -1,
          yoyo: true,
          ease: 'power2.inOut'
        });
      } else {
        // Fallback CSS animation
        const style = document.createElement('style');
        style.textContent = `
          @keyframes textTypeBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
          }
          .text-type__cursor {
            animation: textTypeBlink 0.8s infinite;
          }
        `;
        document.head.appendChild(style);
      }
    }

    if (this.startOnVisible) {
      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              this.isVisible = true;
              observer.disconnect();
              this.start();
            }
          });
        },
        { threshold: 0.1 }
      );
      observer.observe(this.element);
    } else {
      this.start();
    }
  }

  start() {
    if (this.initialDelay > 0) {
      setTimeout(() => this.tick(), this.initialDelay);
    } else {
      this.tick();
    }
  }

  getRandomSpeed() {
    if (!this.variableSpeed) return this.typingSpeed;
    const { min, max } = this.variableSpeed;
    return Math.random() * (max - min) + min;
  }

  getCurrentTextColor() {
    if (this.textColors.length === 0) return 'inherit';
    return this.textColors[this.currentTextIndex % this.textColors.length];
  }

  tick() {
    if (!this.isVisible) return;

    const currentText = this.textArray[this.currentTextIndex];
    const processedText = this.reverseMode
      ? currentText.split('').reverse().join('')
      : currentText;

    if (this.isDeleting) {
      if (this.displayedText === '') {
        this.isDeleting = false;
        if (this.currentTextIndex === this.textArray.length - 1 && !this.loop) {
          return;
        }

        if (this.onSentenceComplete) {
          this.onSentenceComplete(this.textArray[this.currentTextIndex], this.currentTextIndex);
        }

        this.currentTextIndex = (this.currentTextIndex + 1) % this.textArray.length;
        this.currentCharIndex = 0;
        this.timeout = setTimeout(() => this.tick(), this.pauseDuration);
      } else {
        this.timeout = setTimeout(() => {
          this.displayedText = this.displayedText.slice(0, -1);
          this.contentSpan.textContent = this.displayedText;
          this.updateCursorVisibility();
          this.tick();
        }, this.deletingSpeed);
      }
    } else {
      if (this.currentCharIndex < processedText.length) {
        const speed = this.variableSpeed ? this.getRandomSpeed() : this.typingSpeed;
        this.timeout = setTimeout(() => {
          this.displayedText += processedText[this.currentCharIndex];
          this.contentSpan.textContent = this.displayedText;
          this.contentSpan.style.color = this.getCurrentTextColor();
          this.currentCharIndex += 1;
          this.updateCursorVisibility();
          this.tick();
        }, speed);
      } else if (this.textArray.length >= 1) {
        if (!this.loop && this.currentTextIndex === this.textArray.length - 1) return;
        this.timeout = setTimeout(() => {
          this.isDeleting = true;
          this.tick();
        }, this.pauseDuration);
      }
    }
  }

  updateCursorVisibility() {
    if (!this.showCursor || !this.cursorSpan) return;
    const isTyping = this.currentCharIndex < this.textArray[this.currentTextIndex].length || this.isDeleting;
    const shouldHide = this.hideCursorWhileTyping && isTyping;
    this.cursorSpan.classList.toggle('text-type__cursor--hidden', shouldHide);
  }

  destroy() {
    if (this.timeout) {
      clearTimeout(this.timeout);
    }
  }
}

window.TextType = TextType;
