'use strict';

// These functions are kept independent of the UI so the inquiry flow can be checked.
function buildInquiry(values) {
  const clean = key => String(values[key] || '').trim();
  return [
    'Hallo Sebastian,', '',
    'ich möchte ein Projekt mit Sport Voice besprechen.', '',
    'Leistung: ' + clean('service'),
    'Event / Format: ' + clean('event'),
    'Zeitraum: ' + (clean('date') || 'noch offen'), '',
    'Weitere Details:', clean('message') || 'Details besprechen wir gerne gemeinsam.', '',
    'Viele Grüße', clean('name')
  ].join('\n');
}
function buildMailto(values) {
  const event = String(values.event || '').trim();
  const subject = 'Sport Voice – ' + String(values.service || 'Projektanfrage') + (event ? ': ' + event : '');
  return 'mailto:info@sport-voice.de?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(buildInquiry(values));
}

const menuButton = document.querySelector('.menu-toggle');
const mobileNav = document.getElementById('mobile-nav');
function closeMenu() {
  if (!menuButton || !mobileNav) return;
  mobileNav.hidden = true;
  menuButton.setAttribute('aria-expanded', 'false');
  menuButton.setAttribute('aria-label', 'Menü öffnen');
}
if (menuButton && mobileNav) {
  menuButton.addEventListener('click', () => {
    const open = menuButton.getAttribute('aria-expanded') !== 'true';
    mobileNav.hidden = !open;
    menuButton.setAttribute('aria-expanded', String(open));
    menuButton.setAttribute('aria-label', open ? 'Menü schließen' : 'Menü öffnen');
  });
  mobileNav.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && !mobileNav.hidden) { closeMenu(); menuButton.focus(); }
  });
  window.matchMedia('(min-width: 901px)').addEventListener('change', event => { if (event.matches) closeMenu(); });
}

document.querySelectorAll('.filter-button').forEach(button => {
  button.addEventListener('click', () => {
    document.querySelectorAll('.filter-button').forEach(item => {
      const selected = item === button;
      item.classList.toggle('active', selected);
      item.setAttribute('aria-pressed', String(selected));
    });
    document.querySelectorAll('.reference-row').forEach(row => {
      row.hidden = button.dataset.filter !== 'all' && row.dataset.category !== button.dataset.filter;
    });
  });
});

document.querySelectorAll('.reference-logo').forEach(link => {
  link.addEventListener('click', () => {
    const reference = document.querySelector(link.hash);
    if (reference?.hidden) document.querySelector('.filter-button[data-filter="all"]').click();
  });
});

function showDialog(dialog) {
  dialog.showModal();
  document.body.classList.add('modal-open');
}
document.querySelectorAll('dialog').forEach(dialog => {
  dialog.querySelector('.dialog-close').addEventListener('click', () => dialog.close());
  dialog.addEventListener('close', () => document.body.classList.remove('modal-open'));
  dialog.addEventListener('click', event => {
    if (event.target !== dialog) return;
    const rect = dialog.getBoundingClientRect();
    if (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom) dialog.close();
  });
});
document.querySelectorAll('[data-dialog]').forEach(button => {
  button.addEventListener('click', () => showDialog(document.getElementById(button.dataset.dialog)));
});
document.querySelectorAll('[data-image]').forEach(button => {
  button.addEventListener('click', () => {
    const photo = document.getElementById('dialog-image');
    photo.src = button.dataset.image;
    photo.alt = button.dataset.caption;
    document.getElementById('photo-caption').textContent = button.dataset.caption;
    showDialog(document.getElementById('photo-dialog'));
  });
});

const form = document.getElementById('inquiry-form');
const status = document.getElementById('form-status');
if (form && status) {
  const getValues = () => Object.fromEntries(new FormData(form));
  form.addEventListener('submit', event => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    window.location.href = buildMailto(getValues());
    status.textContent = 'Die E-Mail-App wird geöffnet. Falls sie nicht startet, kopiere den Anfragetext und sende ihn an info@sport-voice.de.';
  });
  const copyButton = document.getElementById('copy-inquiry');
  if (copyButton) copyButton.addEventListener('click', async () => {
    if (!form.reportValidity()) return;
    const draft = buildInquiry(getValues());
    try {
      if (navigator.clipboard && window.isSecureContext) await navigator.clipboard.writeText(draft);
      else {
        const area = document.createElement('textarea');
        area.value = draft; area.style.position = 'fixed'; area.style.opacity = '0';
        document.body.appendChild(area); area.focus(); area.select();
        const copied = document.execCommand('copy'); area.remove();
        if (!copied) throw new Error('Clipboard unavailable');
      }
      status.textContent = 'Anfragetext kopiert. Füge ihn in eine E-Mail an info@sport-voice.de ein.';
    } catch (_) {
      status.textContent = 'Kopieren ist in diesem Browser nicht möglich. Nutze „Anfrage in E-Mail öffnen“ oder schreibe direkt an info@sport-voice.de.';
    }
  });
}
const year = document.getElementById('year');
if (year) year.textContent = new Date().getFullYear();

if ('IntersectionObserver' in window) {
  const navLinks = [...document.querySelectorAll('.desktop-nav a')];
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      navLinks.forEach(link => {
        const active = link.hash === '#' + entry.target.id;
        link.classList.toggle('current', active);
        if (active) link.setAttribute('aria-current', 'location'); else link.removeAttribute('aria-current');
      });
    });
  }, {rootMargin: '-15% 0px -65% 0px', threshold: 0});
  document.querySelectorAll('#leistungen, #einsaetze, #referenzen, #sebastian, #kontakt').forEach(section => observer.observe(section));
}
