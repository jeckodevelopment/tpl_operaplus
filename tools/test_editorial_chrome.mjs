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
} finally {await browser.close();}
