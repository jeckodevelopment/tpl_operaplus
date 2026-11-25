import assert from 'node:assert/strict';
import { chromium } from 'playwright';
import {readFileSync,mkdirSync} from 'node:fs';
import {resolve} from 'node:path';
import {pathToFileURL} from 'node:url';
const root=resolve('docs/magazine/mockups/boards');
const manifest=JSON.parse(readFileSync(root+'/manifest.json','utf8'));
const browser=await chromium.launch();
mkdirSync('dist/design-boards',{recursive:true});
try {
 const page=await browser.newPage({viewport:{width:1200,height:900}});
 const errors=[];page.on('requestfailed',r=>errors.push(r.url()));
 for(const b of manifest.boards){
  for(const width of [...new Set([b.viewport,390,320])]){
   await page.setViewportSize({width,height:900});
   await page.goto(pathToFileURL(root+'/'+b.file).href);
   assert.equal(await page.locator('main').count(),1,b.id);
   assert.ok(await page.locator('.review-strip').textContent().then(x=>x.includes('PROPOSTA DA APPROVARE')));
   assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth),false,b.id+' overflow '+width);
   assert.ok(await page.locator('svg.photo image').count()>0,b.id+' missing original imagery');
   if(width===b.viewport)await page.screenshot({path:'dist/design-boards/'+b.id+'.png',fullPage:true});
  }
 }
 await page.goto(pathToFileURL(root+'/index.html').href);
 assert.equal(await page.locator('.gallery .grid > a').count(),15);
 assert.deepEqual(errors,[]);
 console.log('8 design boards + gallery verified at 1200/390/320 as applicable (not manual acceptance).');
}finally{await browser.close();}
