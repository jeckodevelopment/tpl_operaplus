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
   await page.goto(pathToFileURL(resolve('dist/magazine/articles.html')).href);
   await page.evaluate(theme=>{document.body.classList.toggle('theme-light',theme==='light');document.body.classList.toggle('theme-dark',theme==='dark')},theme);
   assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth),false);
   const cover=page.locator('[data-case=cover] .op-mag-card');
   assert.equal(await cover.count(),1);
   assert.equal(await cover.locator('.op-mag-card__title a').evaluate(e=>getComputedStyle(e).color),'rgb(255, 255, 255)');
   assert.equal(await cover.locator('img').evaluate(e=>e.complete&&e.naturalWidth>0),true);
   assert.equal(await page.locator('[data-case=sidebar] .op-mag-card').count(),3);
   const columns=await page.locator('[data-case=grid] .op-mag-articles').evaluate(e=>getComputedStyle(e).gridTemplateColumns.split(' ').length);
   assert.equal(columns,width<=480?1:width<=720?2:3);
   assert.equal(await page.locator('[data-case=no-image] img').count(),0);
   assert.ok(await page.locator('[data-case=no-image] .op-mag-card__title').isVisible());
   const carousel=page.locator('[data-case=carousel]');
   const track=carousel.locator('.op-mag-articles');
   const next=carousel.locator('[data-op-mag-next]');
   const prev=carousel.locator('[data-op-mag-prev]');
   assert.equal(await track.locator('.op-mag-card').count(),3);
   assert.ok(await next.isVisible());
   assert.equal(await prev.getAttribute('aria-disabled'),'true');
   await next.focus(); await page.keyboard.press('Enter');
   await page.waitForFunction(()=>document.querySelector('[data-case=carousel] .op-mag-articles').scrollLeft>0);
   assert.equal(await prev.getAttribute('aria-disabled'),'false');
   await prev.click();
   await page.waitForFunction(()=>document.querySelector('[data-case=carousel] .op-mag-articles').scrollLeft===0);
   const mosaic=page.locator('[data-case=mosaic] .op-mag-card');
   assert.equal(await mosaic.count(),3);
   const sizes=await mosaic.evaluateAll(nodes=>nodes.map(n=>n.getBoundingClientRect().width));
   assert.ok(width<=480?Math.abs(sizes[0]-sizes[1])<2:sizes[0]>sizes[1]*1.9);
   const visual=page.locator('[data-case=grid] .op-mag-card__visual').first();
   assert.equal(await visual.evaluate(e=>getComputedStyle(e).aspectRatio),'16 / 10');
   await page.evaluate(()=>document.body.classList.add('op-mag-ratio-square','op-mag-overlay-strong','op-mag-no-separators'));
   assert.equal(await visual.evaluate(e=>getComputedStyle(e).aspectRatio),'1 / 1');
   assert.equal(await page.locator('[data-case=portraits] .op-mag-card__visual').first().evaluate(e=>getComputedStyle(e).aspectRatio),'4 / 5');
   assert.equal(await page.locator('[data-case=list] .op-mag-card').first().evaluate(e=>getComputedStyle(e).borderBottomColor),'rgba(0, 0, 0, 0)');
   await page.evaluate(()=>{document.body.classList.remove('op-mag-ratio-square');document.body.classList.add('op-mag-ratio-wide')});
   assert.equal(await visual.evaluate(e=>getComputedStyle(e).aspectRatio),'16 / 9');
   await page.evaluate(()=>document.body.classList.remove('op-mag-ratio-wide','op-mag-overlay-strong','op-mag-no-separators'));
   await page.screenshot({path:'dist/magazine/articles-'+width+'-'+theme+'.png',fullPage:true});
  }
 }
 console.log('Editorial module layouts: overlay, responsive, image and text fallbacks passed.');
}finally{await browser.close();}
