import assert from 'node:assert/strict';
import {readFileSync} from 'node:fs';
import {chromium} from 'playwright';
const browser=await chromium.launch();
try {
 const page=await browser.newPage({viewport:{width:1200,height:900}});
 await page.setContent(`<style>${readFileSync('media/css/template.css','utf8')}</style><body class="operaplus theme-light is-internal"><aside class="op-sidebar" style="width:360px"><div class="moduletable" id="cta"><div class="op-cta-shell"><a class="op-cta__button" style="color:white;background:#632b3a" href="#cta">Newsletter</a></div></div><div class="card" id="atlas"><div class="card-body" id="atlas-body"><div class="op-atlas-module"><a class="op-atlas-link" style="color:white;background:#632b3a" href="#atlas">Atlas</a><p class="op-atlas-credit"><a style="color:#42252c" href="#credit">Credit</a></p></div></div></div><div class="moduletable" id="ordinary"><h3>Menu</h3><a href="#menu">Ordinary module</a></div></aside></body>`);
 for(const theme of ['light','dark']){
  await page.evaluate(theme=>{document.body.classList.toggle('theme-light',theme==='light');document.body.classList.toggle('theme-dark',theme==='dark')},theme);
  for(const id of ['cta','atlas','atlas-body']){
   const style=await page.locator('#'+id).evaluate(el=>{const s=getComputedStyle(el);return {padding:s.padding,border:s.borderTopWidth,bg:s.backgroundColor,shadow:s.boxShadow}});
   assert.equal(style.padding,'0px',id+' redundant padding');assert.equal(style.border,'0px',id+' redundant border');assert.equal(style.bg,'rgba(0, 0, 0, 0)',id+' redundant background');
  }
  for(const selector of ['.op-cta__button','.op-atlas-link'])assert.equal(await page.locator(selector).evaluate(el=>getComputedStyle(el).color),'rgb(255, 255, 255)');
  const ordinary=await page.locator('#ordinary').evaluate(el=>{const s=getComputedStyle(el);return {padding:parseFloat(s.paddingTop),border:parseFloat(s.borderTopWidth)}});
  assert.ok(ordinary.padding>0&&ordinary.border>0,'Ordinary module chrome lost');
 }
 console.log('Editorial chrome: standalone panels, button contrast and ordinary module regression passed.');

 // Actual native Joomla pagenavigation markup, including the two label modes.
 await page.setContent(`<style>${readFileSync('media/css/template.css','utf8')}\n${readFileSync('media/css/magazine.css','utf8')}</style><body class="operaplus op-magazine theme-light"><main class="op-mag-main"><div class="op-mag-content"><div class="op-content"><article class="op-mag-article"><nav class="pagenavigation"><span class="pagination ms-0"><a class="btn btn-sm btn-secondary previous" href="#previous" rel="prev"><span class="visually-hidden">Previous article</span><span class="icon-chevron-left" aria-hidden="true"></span> <span aria-hidden="true">Precedente</span></a><a class="btn btn-sm btn-secondary next" href="#next" rel="next"><span class="visually-hidden">Next article</span><span aria-hidden="true">Successivo — titolo particolarmente lungo di un articolo del Magazine</span> <span class="icon-chevron-right" aria-hidden="true"></span></a></span></nav></article><nav class="pagination__wrapper"><ul class="pagination"><li class="page-item active"><a aria-current="true" href="#" class="page-link">2</a></li></ul></nav></div></div></main></body>`);
 for(const theme of ['light','dark']){
  await page.evaluate(theme=>{document.body.classList.toggle('theme-light',theme==='light');document.body.classList.toggle('theme-dark',theme==='dark')},theme);
  for(const width of [1200,375,320]){
   await page.setViewportSize({width,height:900});
   for(const selector of ['.pagenavigation .previous','.pagenavigation .next']){
    const a=page.locator(selector);
    const style=await a.evaluate(el=>{const s=getComputedStyle(el);return {fg:s.color,bg:s.backgroundColor,h:el.getBoundingClientRect().height,right:el.getBoundingClientRect().right}});
    assert.equal(style.fg,'rgb(255, 255, 255)');assert.equal(style.bg,'rgb(99, 43, 58)');assert.ok(style.h>=44&&style.right<=width,'Navigation clips or is too small');
    await a.focus();assert.equal(await a.evaluate(el=>getComputedStyle(el).color),'rgb(255, 255, 255)');
    await a.blur();await a.hover();assert.equal(await a.evaluate(el=>getComputedStyle(el).color),'rgb(255, 255, 255)');
    await page.mouse.move(0,0);
   }
   for(const side of ['left','right'])assert.notEqual(await page.locator('.icon-chevron-'+side).evaluate(el=>getComputedStyle(el,'::before').content),'none');
   assert.equal(await page.locator('.page-link').evaluate(el=>getComputedStyle(el).color),'rgb(255, 255, 255)');
  }
 }
 console.log('Native article previous/next markup: contrast, arrows, responsive size and category pagination regression passed.');
} finally {await browser.close();}
