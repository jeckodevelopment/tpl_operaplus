import assert from 'node:assert/strict';
import {chromium} from 'playwright';
import {pathToFileURL} from 'node:url';
import {resolve} from 'node:path';
const browser=await chromium.launch();
try{
 const page=await browser.newPage();
 for(const width of [1200,768,375]){
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
   await page.screenshot({path:'dist/magazine/articles-'+width+'-'+theme+'.png',fullPage:true});
  }
 }
 console.log('Editorial module layouts: overlay, responsive, image and text fallbacks passed.');
}finally{await browser.close();}
