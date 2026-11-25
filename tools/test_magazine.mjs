import assert from 'node:assert/strict';
import {chromium} from 'playwright';
import {pathToFileURL} from 'node:url';
import {resolve} from 'node:path';
const browser=await chromium.launch();
try{
 const page=await browser.newPage();
 for(const width of [1200,768,375,320]){
  await page.setViewportSize({width,height:900});
  for(const theme of ['light','dark']){
   await page.goto(pathToFileURL(resolve('dist/magazine/home.html')).href);
   await page.evaluate(theme=>{document.body.classList.toggle('theme-light',theme==='light');document.body.classList.toggle('theme-dark',theme==='dark')},theme);
   const rows=await page.locator('.op-mag-row').evaluateAll(nodes=>nodes.map(r=>{const m=r.querySelector('.op-mag-row__main').getBoundingClientRect();const a=r.querySelector('aside')?.getBoundingClientRect();return {main:m.width,mainRight:m.right,aside:a?.width,asideLeft:a?.left,asideTop:a?.top,mainBottom:m.bottom}}));
   assert.equal(rows.length,3);
   assert.ok(rows.every(r=>Math.abs(r.mainRight-rows[0].mainRight)<1),'Section axes diverge');
   if(width>900){assert.ok(Math.abs(rows[0].main/rows[0].aside-2)<.02,'Not 8+4');assert.ok(Math.abs(rows[0].asideLeft-rows[1].asideLeft)<1);}
   else{assert.ok(rows[0].asideTop>=rows[0].mainBottom,'Mobile aside not below content');}
   assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth),false);
   await page.screenshot({path:'dist/magazine/home-'+width+'-'+theme+'.png',fullPage:true});
   await page.goto(pathToFileURL(resolve('dist/magazine/detail.html')).href);
   await page.evaluate(theme=>{document.body.classList.toggle('theme-light',theme==='light');document.body.classList.toggle('theme-dark',theme==='dark')},theme);
   if(width<=900) await page.locator('.op-menu-toggle').click();
   const nav=page.locator('#op-navigation');
   await nav.locator('.op-link-row').first().waitFor();
   const active=nav.locator('a[aria-current=page]');
   assert.equal(await active.evaluate(el=>getComputedStyle(el).color),theme==='light'?'rgb(99, 43, 58)':'rgb(228, 185, 165)');
   assert.equal(await nav.locator('.op-submenu-toggle').first().evaluate(el=>getComputedStyle(el).color),theme==='light'?'rgb(99, 43, 58)':'rgb(228, 185, 165)');
   await nav.locator('.op-submenu-toggle').last().click();
   assert.ok(await nav.getByText('Lezione',{exact:true}).isVisible());
   const sidebar=page.locator('.op-mag-content aside.op-sidebar');
   assert.equal(await sidebar.locator('ul.mod-menu').evaluate(el=>getComputedStyle(el).listStyleType),'none');
   assert.ok(await sidebar.locator('.op-side-toggle').count()>0,'Sidebar controller missing');
   const sideToggle=sidebar.locator('.op-side-toggle').first();
   const expanded=await sideToggle.getAttribute('aria-expanded');
   await sideToggle.click();
   assert.notEqual(await sideToggle.getAttribute('aria-expanded'),expanded);
   const login=sidebar.locator('.mod-login');
   const user=login.locator('input[type=text]');
   const password=login.locator('input[type=password]');
   assert.equal(await user.evaluate(el=>getComputedStyle(el).backgroundColor),theme==='light'?'rgb(255, 255, 255)':'rgb(22, 15, 20)');
   assert.equal(await login.locator('.input-password-toggle').isVisible(),false);
   assert.ok(Math.abs(await user.evaluate(el=>el.getBoundingClientRect().width)-await password.evaluate(el=>el.getBoundingClientRect().width))<2);
   const widthsLogin=await login.evaluate(el=>[el.clientWidth,el.querySelector('button[type=submit]').getBoundingClientRect().width]);assert.ok(Math.abs(widthsLogin[0]-widthsLogin[1])<=2);
   assert.equal(await sidebar.locator('.moduletable:has(>.op-cta-shell)').evaluate(el=>getComputedStyle(el).padding),'0px');
   assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth),false);
   await page.screenshot({path:'dist/magazine/detail-'+width+'-'+theme+'.png',fullPage:true});
   await page.goto(pathToFileURL(resolve('dist/magazine/empty.html')).href);
   assert.equal(await page.locator('aside').count(),0);
   const widths=await page.locator('.op-mag-content').evaluate(el=>[el.clientWidth,el.firstElementChild.clientWidth]);assert.ok(Math.abs(widths[0]-widths[1])<=1);
  }
 }
 console.log('Magazine 8+4 alignment, responsive and collapsed sidebar checks passed.');
}finally{await browser.close();}
