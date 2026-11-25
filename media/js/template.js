document.addEventListener('DOMContentLoaded', () => {
  const trigger = document.querySelector('.op-menu-toggle');
  const menu = document.getElementById('op-navigation');
  if (!menu || !trigger) return;
  const small = () => window.matchMedia('(max-width: 900px)').matches;
  const clickMode = () => document.body.dataset.opMenuMode === 'click';
  const closeChildren = (root) => root.querySelectorAll('li.op-menu-open').forEach(li => {
    li.classList.remove('op-menu-open');
    const btn = li.querySelector(':scope > .op-link-row > .op-submenu-toggle');
    if (btn) btn.setAttribute('aria-expanded', 'false');
  });
  const close = () => {
    trigger.setAttribute('aria-expanded','false');
    menu.classList.remove('is-open');
    closeChildren(menu);
  };
  trigger.addEventListener('click', () => {
    const open = trigger.getAttribute('aria-expanded') !== 'true';
    trigger.setAttribute('aria-expanded',String(open));
    menu.classList.toggle('is-open',open);
    if (!open) closeChildren(menu);
  });
  // Support both mod_menu's nested lists and its native collapsible markup.
  menu.querySelectorAll('li').forEach((li, index) => {
    const anchor = li.querySelector(':scope > a, :scope > .mod-menu__heading, :scope > button');
    const submenu = li.querySelector(':scope > ul, :scope > .mod-menu__sub');
    if (!submenu || !anchor) return;
    if (anchor.classList.contains('op-mega') || li.classList.contains('op-mega')) li.classList.add('op-mega');
    if (li.classList.contains('op-mega')) li.classList.add('op-has-mega');
    const row = document.createElement('span');
    row.className = 'op-link-row';
    li.insertBefore(row, anchor);
    row.appendChild(anchor);
    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'op-submenu-toggle';
    toggle.textContent = ''; // decorative chevron provided via CSS
    toggle.setAttribute('aria-label', 'Apri o chiudi ' + (anchor.textContent.trim() || 'sottomenu'));
    toggle.setAttribute('aria-expanded', 'false');
    const id = submenu.id || 'op-submenu-' + index;
    submenu.id = id;
    toggle.setAttribute('aria-controls', id);
    row.appendChild(toggle);
    if (li.parentElement.closest('li.op-has-mega')) toggle.hidden = true;
    toggle.addEventListener('click', (event) => {
      event.stopPropagation();
      const open = !li.classList.contains('op-menu-open');
      Array.from(li.parentElement.children).filter(el => el !== li).forEach(sibling => {
        sibling.classList.remove('op-menu-open');
        sibling.querySelectorAll('.op-submenu-toggle').forEach(button => button.setAttribute('aria-expanded','false'));
      });
      li.classList.toggle('op-menu-open', open);
      toggle.setAttribute('aria-expanded',String(open));
    });
    anchor.addEventListener('click', (event) => {
      if (anchor.tagName === 'BUTTON') {
        // Joomla's native toggler may otherwise interfere with template toggles.
        event.preventDefault(); event.stopPropagation(); toggle.click(); return;
      }
      if ((small() || clickMode()) && anchor.tagName === 'A' && (!anchor.getAttribute('href') || anchor.getAttribute('href') === '#')) {
        event.preventDefault(); toggle.click();
      }
    });
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
      const focused = document.activeElement.closest('li.op-menu-open');
      if (focused) {
        focused.classList.remove('op-menu-open');
        focused.querySelector(':scope > .op-link-row > .op-submenu-toggle')?.setAttribute('aria-expanded','false');
        focused.querySelector(':scope > .op-link-row > a')?.focus();
      } else { close(); trigger.focus(); }
    }
  });
  document.addEventListener('click', event => {
    if (!menu.contains(event.target) && !trigger.contains(event.target)) close();
  });
});


// Opera+ v1.8 — separate accordion controller for Joomla sidebar menus.
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.op-sidebar ul.mod-menu, .op-sidebar ul.menu').forEach(root => {
    // Process only top-level menus: descendants are handled with the same controller.
    if (root.parentElement.closest('ul.mod-menu, ul.menu')) return;
    root.querySelectorAll('li').forEach((li, index) => {
      const child = Array.from(li.children).find(el => el.matches('ul, .mod-menu__sub'));
      if (!child || li.classList.contains('op-side-parent')) return;
      const label = Array.from(li.children).find(el => el.matches('a, .mod-menu__heading, .mod-menu__separator, span'));
      if (!label) return;
      const row = document.createElement('div');
      row.className = 'op-side-row';
      li.insertBefore(row, label);
      row.appendChild(label);
      const toggle = document.createElement('button');
      toggle.type = 'button';
      toggle.className = 'op-side-toggle';
      // Chevron is rendered via CSS; the accessible name is in aria-label.
      const name = label.textContent.trim() || 'Sottomenu';
      toggle.setAttribute('aria-label', 'Espandi o comprimi ' + name);
      const targetId = child.id || ('op-side-sub-' + index + '-' + Array.from(document.querySelectorAll('.op-sidebar ul.mod-menu, .op-sidebar ul.menu')).indexOf(root));
      child.id = targetId;
      toggle.setAttribute('aria-controls', targetId);
      const initiallyOpen = li.classList.contains('active') || li.classList.contains('current') || !!li.querySelector('li.active, li.current');
      li.classList.add('op-side-parent');
      li.classList.toggle('op-side-open', initiallyOpen);
      toggle.setAttribute('aria-expanded', String(initiallyOpen));
      row.appendChild(toggle);
      toggle.addEventListener('click', () => {
        const open = !li.classList.contains('op-side-open');
        li.classList.toggle('op-side-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        });
    });
  });
});

// v1.14: single viewport-fixed mega panel positioner, independent of ancestors.
document.addEventListener('DOMContentLoaded', () => {
  const panels = [...document.querySelectorAll('.op-navigation li.op-has-mega > ul, .op-navigation li.op-has-mega > .mod-menu__sub')];
  const desktop = () => window.matchMedia('(min-width: 901px)').matches;
  function positionPanels() {
    panels.forEach(panel => {
      if (!desktop()) {
        ['position','left','right','top','width','max-width','margin-left','transform'].forEach(k => panel.style.removeProperty(k));
        return;
      }
      const item = panel.parentElement;
      const navRect = item.querySelector(':scope > .op-link-row')?.getBoundingClientRect() || item.getBoundingClientRect();
      const viewport = document.documentElement.clientWidth;
      const gutter = Math.min(32, Math.max(12, viewport * 0.04));
      const configured = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--op-mega-width')) || 760;
      const width = Math.max(240, Math.min(configured, viewport - 2*gutter));
      const left = Math.max(gutter, Math.min(navRect.left + navRect.width/2 - width/2, viewport - gutter - width));
      panel.style.setProperty('position','fixed','important');
      panel.style.setProperty('left',`${left}px`,'important');
      panel.style.setProperty('right','auto','important');
      panel.style.setProperty('top',`${navRect.bottom}px`,'important');
      panel.style.setProperty('width',`${width}px`,'important');
      panel.style.setProperty('max-width',`${viewport - 2*gutter}px`,'important');
      panel.style.setProperty('margin-left','0','important');
      panel.style.setProperty('transform','none','important');
    });
  }
  positionPanels();
  window.addEventListener('resize',positionPanels,{passive:true});
  window.addEventListener('scroll',positionPanels,{passive:true});
  panels.forEach(panel=>{
    panel.parentElement.addEventListener('mouseenter',positionPanels);
    panel.parentElement.addEventListener('focusin',positionPanels);
  });
});
// Magazine article carousel: progressive enhancement, never autoplay.
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.op-magazine [data-op-mag-carousel]').forEach(root => {
    const track = root.querySelector('.op-mag-articles--carousel');
    const controls = root.querySelector('.op-mag-carousel__controls');
    const prev = root.querySelector('[data-op-mag-prev]');
    const next = root.querySelector('[data-op-mag-next]');
    if (!track || !controls || !prev || !next) return;
    const update = () => {
      const max = Math.max(0, track.scrollWidth - track.clientWidth);
      const offset = Math.abs(track.scrollLeft);
      controls.hidden = max <= 1;
      prev.setAttribute('aria-disabled', String(offset <= 1));
      next.setAttribute('aria-disabled', String(offset >= max - 1));
    };
    const move = (direction, button) => {
      if (button.getAttribute('aria-disabled') === 'true') return;
      const card = track.querySelector('.op-mag-card');
      if (!card) return;
      const css = getComputedStyle(track);
      const step = card.getBoundingClientRect().width + (parseFloat(css.columnGap) || 0);
      track.scrollBy({left: direction * (css.direction === 'rtl' ? -1 : 1) * step, behavior: 'instant'});
      update();
    };
    prev.addEventListener('click', () => move(-1, prev));
    next.addEventListener('click', () => move(1, next));
    track.addEventListener('scroll', update, {passive:true});
    window.addEventListener('resize', update, {passive:true});
    if ('ResizeObserver' in window) new ResizeObserver(update).observe(track);
    update();
  });
});
