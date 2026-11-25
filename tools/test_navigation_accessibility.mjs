import assert from 'node:assert/strict';
import {chromium} from 'playwright';
import {pathToFileURL} from 'node:url';
import {resolve} from 'node:path';
const url=pathToFileURL(resolve('dist/magazine/detail.html')).href;
const browser=await chromium.launch();
try {
 const page=await browser.newPage({viewport:{width:375,height:760}});
 for (const theme of ['light','dark']){
  await page.goto(url);
  await page.evaluate(t=>{document.documentElement.lang='en';document.body.classList.toggle('theme-light',t==='light');document.body.classList.toggle('theme-dark',t==='dark')},theme);
  const trigger=page.locator('.op-menu-toggle'),nav=page.locator('#op-navigation');
  await trigger.click();
  const toggle=nav.locator('.op-submenu-toggle').last();
  await toggle.focus();await page.keyboard.press('Enter');
  assert.equal(await toggle.getAttribute('aria-expanded'),'true');
  await nav.getByText('Lezione',{exact:true}).focus();
  await page.keyboard.press('Escape');
  assert.equal(await toggle.getAttribute('aria-expanded'),'false');
  assert.equal(await toggle.evaluate(e=>e===document.activeElement),true);
  assert.equal(await nav.getByText('Lezione',{exact:true}).isVisible(),false);
  await page.keyboard.press('Escape');
  assert.equal(await trigger.getAttribute('aria-expanded'),'false');
  assert.equal(await trigger.evaluate(e=>e===document.activeElement),true);
  const input=page.locator('.mod-login input[type=text]');
  await input.focus();await page.keyboard.press('Escape');
  assert.equal(await input.evaluate(e=>e===document.activeElement),true,'Escape stole form focus');
  const side=page.locator('.op-side-toggle').first();
  if(await side.getAttribute('aria-expanded')==='false')await side.click();
  await side.focus();await page.keyboard.press('Escape');
  assert.equal(await side.getAttribute('aria-expanded'),'false');
  assert.equal(await side.evaluate(e=>e===document.activeElement),true);
  assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth),false);
 }
 const nojs=await browser.newPage({javaScriptEnabled:false,viewport:{width:320,height:760}});
 await nojs.goto(url);
 assert.equal(await nojs.locator('#op-navigation').isVisible(),true);
 assert.equal(await nojs.locator('#op-navigation').getByText('Lezione',{exact:true}).isVisible(),true);
 assert.equal(await nojs.locator('.op-menu-toggle').isVisible(),false);
 console.log('Keyboard Escape scope, focus restoration, sidebar and no-JS mobile navigation passed.');
} finally {await browser.close();}
