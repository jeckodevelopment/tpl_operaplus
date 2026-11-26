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

 // Native read-more, TinyMCE interview classes and Joomla automatic fields.
 await page.setContent(`<style>${readFileSync('media/css/template.css','utf8')}\n${readFileSync('media/css/magazine.css','utf8')}</style><body class="operaplus op-magazine theme-light is-internal"><main class="op-mag-main"><div class="op-mag-content"><div class="op-content"><div class="op-mag-category"><div class="blog-item"><div class="item-content"><ul class="fields-container"><li class="field-entry teatro"><span class="field-label">Teatro: </span><span class="field-value">Teatro Regio di Torino</span></li><li class="field-entry citta"><span class="field-label">Città: </span><span class="field-value">Torino</span></li></ul><div class="op-mag-category-intro"><ul id="ordinary-list"><li>Testo con elenco normale</li></ul></div><p class="readmore"><a class="btn btn-secondary" href="#article"><span class="icon-chevron-right" aria-hidden="true"></span> Leggi tutto</a></p></div></div></div><div class="op-mag-article op-mag-article--interview"><div class="com-content-article__body"><p class="op-interview-question">Come nasce questa produzione?</p><p class="op-interview-answer">Da un lungo percorso di ricerca musicale e teatrale.</p><blockquote>Una citazione.</blockquote></div></div><div class="op-mag-article op-mag-article--review"><ul class="fields-container"><li class="field-entry teatro"><span class="field-label">Teatro: </span><span class="field-value">Teatro Regio di Torino</span></li></ul></div></div></div></main></body>`);
 for(const theme of ['light','dark']){
  await page.evaluate(theme=>{document.body.classList.toggle('theme-light',theme==='light');document.body.classList.toggle('theme-dark',theme==='dark')},theme);
  for(const width of [1200,375,320]){
   await page.setViewportSize({width,height:900});
   const more=page.locator('.readmore a');
   const colors=()=>more.evaluate(el=>{const s=getComputedStyle(el);return [s.color,s.backgroundColor]});
   assert.deepEqual(await colors(),['rgb(255, 255, 255)','rgb(99, 43, 58)']);
   await more.hover();assert.deepEqual(await colors(),['rgb(255, 255, 255)','rgb(78, 36, 49)']);await page.mouse.move(0,0);
   for(const [selector,letter] of [['.op-interview-question','D'],['.op-interview-answer','R']]){
    const p=page.locator(selector);
    assert.equal(await p.evaluate(el=>getComputedStyle(el,'::before').content),'"'+letter+'"');
    assert.ok(await p.evaluate(el=>parseFloat(getComputedStyle(el).paddingInlineStart)>=40));
   }
   assert.equal(await page.locator('.op-interview-question').evaluate(el=>getComputedStyle(el).fontWeight),'700');
   assert.equal(await page.locator('.op-mag-article--review .field-label').evaluate(el=>getComputedStyle(el).fontWeight),'700');
   assert.equal(await page.locator('.op-mag-article--review .field-value').evaluate(el=>getComputedStyle(el).color),theme==='light'?'rgb(36, 28, 29)':'rgb(244, 236, 228)');
   const fields=page.locator('.op-mag-category .fields-container');
   assert.equal(await fields.evaluate(el=>getComputedStyle(el).listStyleType),'none');
   assert.equal(await fields.evaluate(el=>getComputedStyle(el).display),'flex');
   for(const li of await fields.locator('.field-entry').all()){
    assert.equal(await li.evaluate(el=>getComputedStyle(el).listStyleType),'none');
    assert.equal(await li.evaluate(el=>getComputedStyle(el).fontSize),'13px');
    assert.equal(await li.locator('.field-value').evaluate(el=>getComputedStyle(el).color),theme==='light'?'rgb(36, 28, 29)':'rgb(244, 236, 228)');
   }
   assert.equal(await page.locator('#ordinary-list').evaluate(el=>getComputedStyle(el).listStyleType),'disc');
   assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth));
  }
 }
 console.log('Editorial read-more, interview D/R and native review fields passed.');

 // RC11 representative native breadcrumb, metadata and archive layout.
 await page.setContent(`<style>${readFileSync('media/css/template.css','utf8')}\n${readFileSync('media/css/magazine.css','utf8')}</style><body class="operaplus op-magazine theme-light is-internal"><header class="op-mag-masthead op-mag-masthead--breadcrumb-only"><div class="op-container op-mag-masthead__body"><div class="op-breadcrumb op-mag-masthead__breadcrumb"><nav><ol class="breadcrumb"><li><a href="#home">Home</a></li><li>Magazine</li><li>Un articolo con titolo lungo</li></ol></nav></div></div></header><main class="op-mag-main"><div class="op-container"><div class="op-mag-article"><div class="com-content-article"><dl class="article-info"><dt>Details</dt><dd class="createdby">Redazione Opera+</dd><dd>26 novembre 2026</dd><dd class="op-mag-reading-time">5 min di lettura</dd></dl></div></div><div class="op-mag-archive"><div class="filter-search"><div class="mb-2"><input type="text" placeholder="Cerca"></div><select><option>2026</option></select><button class="btn">Filtra</button></div><div id="archive-items"><div class="row0 op-mag-archive__item op-mag-archive__item--image"><div class="op-mag-archive__image"><img alt="" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='100'/%3E"></div><div class="op-mag-archive__copy"><h2>Titolo lungo della recensione archiviata</h2><p>Testo</p></div></div></div></div></div></main></body>`);
 for(const theme of ['light','dark']){
  await page.evaluate(theme=>{document.body.classList.toggle('theme-light',theme==='light');document.body.classList.toggle('theme-dark',theme==='dark')},theme);
  for(const width of [1200,375,320]){
   await page.setViewportSize({width,height:900});
   assert.equal(await page.locator('.op-breadcrumb a').evaluate(el=>getComputedStyle(el).color),'rgb(255, 255, 255)');
   assert.equal(await page.locator('.article-info').evaluate(el=>getComputedStyle(el).display),'flex');
   assert.equal(await page.locator('.filter-search .btn').evaluate(el=>getComputedStyle(el).color),'rgb(255, 255, 255)');
   const cols=await page.locator('.op-mag-archive__item').evaluate(el=>getComputedStyle(el).gridTemplateColumns.split(' ').length);
   assert.equal(cols,width>480?2:1);
   assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth));
  }
 }
 console.log('RC11 breadcrumb contrast, editorial byline and archive responsive grid passed.');
} finally {await browser.close();}
