class GooeyNav {
  constructor(container, options = {}) {
    this.container = container;
    this.nav = container.querySelector('nav');
    this.ul = container.querySelector('nav ul');
    this.lis = Array.from(container.querySelectorAll('nav ul li'));
    this.filterRef = container.querySelector('.effect.filter');
    this.textRef = container.querySelector('.effect.text');

    this.animationTime = options.animationTime ?? 600;
    this.particleCount = options.particleCount ?? 15;
    this.particleDistances = options.particleDistances ?? [90, 10];
    this.particleR = options.particleR ?? 100;
    this.timeVariance = options.timeVariance ?? 300;
    this.colors = options.colors ?? [1, 2, 3, 1, 2, 3, 1, 4];

    this.activeIndex = this.lis.findIndex(li => li.classList.contains('active'));
    if (this.activeIndex === -1) this.activeIndex = 0;

    this.init();
  }

  noise(n = 1) {
    return n / 2 - Math.random() * n;
  }

  getXY(distance, pointIndex, totalPoints) {
    const angle = ((360 + this.noise(8)) / totalPoints) * pointIndex * (Math.PI / 180);
    return [distance * Math.cos(angle), distance * Math.sin(angle)];
  }

  createParticle(i, t, d, r) {
    let rotate = this.noise(r / 10);
    return {
      start: this.getXY(d[0], this.particleCount - i, this.particleCount),
      end: this.getXY(d[1] + this.noise(7), this.particleCount - i, this.particleCount),
      time: t,
      scale: 1 + this.noise(0.2),
      color: this.colors[Math.floor(Math.random() * this.colors.length)],
      rotate: rotate > 0 ? (rotate + r / 20) * 10 : (rotate - r / 20) * 10
    };
  }

  makeParticles(element) {
    const d = this.particleDistances;
    const r = this.particleR;
    const bubbleTime = this.animationTime * 2 + this.timeVariance;
    element.style.setProperty('--time', `${bubbleTime}ms`);

    for (let i = 0; i < this.particleCount; i++) {
      const t = this.animationTime * 2 + this.noise(this.timeVariance * 2);
      const p = this.createParticle(i, t, d, r);
      element.classList.remove('active');

      setTimeout(() => {
        const particle = document.createElement('span');
        const point = document.createElement('span');
        particle.classList.add('particle');
        particle.style.setProperty('--start-x', `${p.start[0]}px`);
        particle.style.setProperty('--start-y', `${p.start[1]}px`);
        particle.style.setProperty('--end-x', `${p.end[0]}px`);
        particle.style.setProperty('--end-y', `${p.end[1]}px`);
        particle.style.setProperty('--time', `${p.time}ms`);
        particle.style.setProperty('--scale', `${p.scale}`);
        particle.style.setProperty('--color', `var(--color-${p.color}, white)`);
        particle.style.setProperty('--rotate', `${p.rotate}deg`);

        point.classList.add('point');
        particle.appendChild(point);
        element.appendChild(particle);
        
        requestAnimationFrame(() => {
          element.classList.add('active');
        });
        
        setTimeout(() => {
          try {
            element.removeChild(particle);
          } catch (e) {
            // Do nothing
          }
        }, t);
      }, 30);
    }
  }

  updateEffectPosition(element) {
    if (!this.container || !this.filterRef || !this.textRef) return;
    const containerRect = this.container.getBoundingClientRect();
    const pos = element.getBoundingClientRect();

    const styles = {
      left: `${pos.x - containerRect.x}px`,
      top: `${pos.y - containerRect.y}px`,
      width: `${pos.width}px`,
      height: `${pos.height}px`
    };
    Object.assign(this.filterRef.style, styles);
    Object.assign(this.textRef.style, styles);
    this.textRef.innerText = element.innerText;
  }

  handleClick(liEl, index) {
    if (this.activeIndex === index) return;

    this.lis[this.activeIndex].classList.remove('active');
    this.activeIndex = index;
    liEl.classList.add('active');

    this.updateEffectPosition(liEl);

    if (this.filterRef) {
      const particles = this.filterRef.querySelectorAll('.particle');
      particles.forEach(p => this.filterRef.removeChild(p));
    }

    if (this.textRef) {
      this.textRef.classList.remove('active');
      void this.textRef.offsetWidth; // Trigger reflow
      this.textRef.classList.add('active');
    }

    if (this.filterRef) {
      this.makeParticles(this.filterRef);
    }
  }

  init() {
    const activeLi = this.lis[this.activeIndex];
    if (activeLi) {
      this.updateEffectPosition(activeLi);
      this.textRef?.classList.add('active');
    }

    this.lis.forEach((li, index) => {
      const a = li.querySelector('a');

      a.addEventListener('click', (e) => {
        const href = a.getAttribute('href');
        if (href && href !== '#' && href !== '') {
          e.preventDefault();
          this.handleClick(li, index);
          setTimeout(() => {
            window.location.href = href;
          }, 350);
        } else {
          this.handleClick(li, index);
        }
      });

      a.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          a.click();
        }
      });
    });

    const resizeObserver = new ResizeObserver(() => {
      const currentActiveLi = this.lis[this.activeIndex];
      if (currentActiveLi) {
        this.updateEffectPosition(currentActiveLi);
      }
    });
    resizeObserver.observe(this.container);
  }
}

window.GooeyNav = GooeyNav;
window.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('main-gooey-nav');
  if (container) {
    new GooeyNav(container, {
      particleCount: 15,
      particleDistances: [90, 10],
      particleR: 100,
      animationTime: 600,
      timeVariance: 300,
      colors: [1, 2, 3, 1, 2, 3, 1, 4]
    });
  }
});
