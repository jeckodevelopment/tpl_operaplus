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
      toggle.textContent = ''; // decorative chevron provided via CSS
      const name = label.textContent.trim() || 'Sottomenu';
      toggle.setAttribute('aria-label', 'Espandi o comprimi ' + name);
      const targetId = child.id || ('op-side-sub-' + Math.random().toString(36).slice(2));
      child.id = targetId;
      toggle.setAttribute('aria-controls', targetId);
      const initiallyOpen = li.classList.contains('active') || li.classList.contains('current') || !!li.querySelector('li.active, li.current');
      li.classList.add('op-side-parent');
      li.classList.toggle('op-side-open', initiallyOpen);
      toggle.setAttribute('aria-expanded', String(initiallyOpen));
      toggle.textContent = ''; // CSS chevron rotates on expansion
      row.appendChild(toggle);
      toggle.addEventListener('click', () => {
        const open = !li.classList.contains('op-side-open');
        li.classList.toggle('op-side-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        toggle.textContent = ''; // update aria-expanded; CSS handles icon
      });
    });
  });
});

// Opera+ 1.10.0 — align mega panels within the viewport, even when the trigger is the last item.
document.addEventListener('DOMContentLoaded', () => {
 const panels = document.querySelectorAll('.op-navigation li.op-has-mega > ul, .op-navigation li.op-has-mega > .mod-menu__sub');
 const align = () => {
  if (window.matchMedia('(max-width: 900px)').matches) { panels.forEach(p => p.style.removeProperty('margin-left')); return; }
  panels.forEach(panel => {
   panel.style.removeProperty('margin-left');
   const rect = panel.getBoundingClientRect();
   const gutter = 24;
   const shift = rect.left < gutter ? gutter - rect.left : rect.right > innerWidth - gutter ? innerWidth - gutter - rect.right : 0;
   if (shift) panel.style.marginLeft = `${shift}px`;
  });
 };
 window.addEventListener('resize', align, {passive:true});
 document.querySelectorAll('.op-navigation li.op-has-mega').forEach(li=>{li.addEventListener('mouseenter',align);li.addEventListener('focusin',align);li.querySelectorAll('.op-submenu-toggle').forEach(b=>b.addEventListener('click',()=>requestAnimationFrame(align)));});
 align();
});