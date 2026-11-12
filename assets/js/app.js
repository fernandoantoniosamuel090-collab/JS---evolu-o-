(() => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('#nav-principal');
  const year = document.querySelector('#year');
  if (year) year.textContent = String(new Date().getFullYear());
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!open));
      nav.classList.toggle('is-open', !open);
    });
    nav.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
      toggle.setAttribute('aria-expanded', 'false');
      nav.classList.remove('is-open');
    }));
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        toggle.setAttribute('aria-expanded', 'false');
        nav.classList.remove('is-open');
        toggle.focus();
      }
    });
  }
  if ('serviceWorker' in navigator && location.protocol === 'https:') {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js').catch((error) => {
        console.error('PWA service worker registration failed:', error);
      });
    });
  }
})();
