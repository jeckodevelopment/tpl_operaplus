# Opera+ — Guida operativa alle funzionalità e agli stili

Questa guida descrive **come configurare Joomla e usare le classi CSS**. Non è un changelog: la cronologia delle release si trova in [CHANGELOG.md](CHANGELOG.md). Nel backend Joomla è disponibile una sintesi per argomenti nella scheda **Documentazione** dello stile del template.

## Configurazione e tipografia

- **Branding:** logo, footer, favicon PNG/SVG o percorso ICO, immagine Open Graph predefinita. Dimensione OG consigliata: 1200×630 px.
- **Aspetto:** stile chiaro/scuro e larghezza del contenitore. Si possono assegnare stili Joomla differenti a voci di menu differenti.
- **Titoli:** H1–H5 del contenuto e H1 Hero sono **due impostazioni distinte**. Il valore predefinito H1 nel corpo pagina è **48 px**. Gli stili già configurati conservano il valore salvato.
- **Navigazione:** mega menu, colonne, larghezza e modalità apertura.

## Moduli e navigazione Joomla

- `menu`: modulo nativo **Menu**, titolo nascosto, visualizzazione figli attiva. Per mega menu aggiungere `op-mega` in **Tipo link → Classe CSS collegamento** sulla voce padre; secondo livello = colonne, terzo = collegamenti.
- `sidebar-left` e `sidebar-right`: moduli Menu, Login e Ricerca già stilizzati, senza HTML personalizzato.
- `hero`: modulo Personalizzato assegnato solo alla Home, con titolo del modulo nascosto. Logo in Branding o modulo nella posizione `hero-side`.
- `breadcrumb`: modulo nativo **Percorso di navigazione**.
- `pre-footer`: modulo Personalizzato con titolo, testo e CTA `.op-button`.
- `main-top`, `main-bottom`, `courses`, `story`, `mission`, `schools`, `association`, `contacts`: posizioni editoriali.

## Corsi: utilizzare gli articoli di Joomla

Crea una categoria alias `corsi` e una voce di menu **Blog di categoria**. Per ogni articolo usa Titolo, Alias autore (compositore), Immagine introduttiva, Immagine articolo completo, Tag e contenuto. Gli override del template leggono questi campi automaticamente; il nome della categoria rispetta le opzioni native. Il collegamento alla scheda usa `TPL_OPERAPLUS_DISCOVER_COURSE`. Le copertine non vengono ritagliate.

## Catalogo delle classi CSS riutilizzabili

| Classe | Quando utilizzarla |
|---|---|
| `.op-container` | Contenuto centrato alla larghezza massima |
| `.op-section`, `.op-section-alt` | Sezione con spaziatura e variante di sfondo |
| `.op-split`, `.op-split--reverse` | Testo e media affiancati, anche invertiti |
| `.op-grid-2`, `.op-grid-3`, `.op-grid-4`, `.op-cards` | Griglie responsive di due, tre o quattro colonne e card |
| `.op-editorial-grid`, `.op-editorial-main`, `.op-editorial-aside` | Impaginazione editoriale a colonne 7/5 |
| `.op-section-heading` | Titolo e introduzione della sezione affiancati |
| `.op-card` | Card generica |
| `.op-panel-light`, `.op-panel-dark`, `.op-panel-bordeaux` | Riquadri tematici con colori preimpostati |
| `.op-eyebrow`, `.op-kicker`, `.op-overline` | Soprattitoli in maiuscolo |
| `.op-display` | Titolo grande decorativo |
| `.op-lead` | Testo introduttivo più evidente |
| `.op-quote` | Citazione editoriale |
| `.op-number`, `.op-stat` | Numeri o indicatori in evidenza |
| `.op-button`, `.op-button--outline` | CTA principale e variante con bordo; usare insieme per la variante |
| `.op-tag`, `.op-course-tag`, `.op-pill`, `.op-pill-list` | Etichette, tag e gruppi di pillole |
| `.op-media` | Media con rapporto dimensionale e ritaglio: **non** usare per copertine da mostrare integralmente |
| `.op-divider`, `.op-rule` | Separatori |
| `.op-course-intro` | Apertura editoriale di un corso |
| `.op-course-feature`, `.op-course-feature__art`, `.op-course-feature__body` | Copertina e testo affiancati in un modulo personalizzato |
| `.op-course-meta`, `.op-course-status`, `.op-course-note` | Metadati, stato e avvisi editoriali |
| `.op-course-chapters`, `.op-course-chapter`, `.op-course-lessons` | Elenco capitoli e lezioni |
| `.op-course-cta` | Invito all’azione conclusivo |

**Classi interne, da non copiare nei moduli:** `.op-course-index*`, `.op-course-item*`, `.op-course-detail*`, `.op-sidebar`, `.op-navigation`, `.op-side-*`, `.op-has-mega`. Sono gestite automaticamente dagli override o dal template.

## Esempio HTML per un modulo Personalizzato

```html
<p class="op-eyebrow">IL PROGETTO</p>
<h2>Scopri Opera+</h2>
<p class="op-lead">Un percorso di ascolto.</p>
<a class="op-button" href="/corsi/">Scopri i corsi</a>
```

Per la homepage usa la posizione `hero`, un `h1`, `.op-hero-lead`, `.op-eyebrow` e `.op-button`. Non usare direttamente stili inline per la dimensione dei titoli; si configurano nella scheda Titoli.

## Metadati e social

La favicon e l’immagine Open Graph fallback si scelgono nella scheda Branding. Per l’immagine OG, precedenza: metadati SEO già presenti, immagine specifica del menu se esistente, immagine introduttiva/completa dell’articolo, fallback del template.

## Traduzioni

Per personalizzare un testo usa **Sistema → Lingue → Sostituzioni lingua**. Le principali costanti sono:

| Costante | Utilizzo |
|---|---|
| `TPL_OPERAPLUS_DISCOVER_COURSE` | Pulsante dettaglio corso |
| `TPL_OPERAPLUS_NO_COURSES` | Nessun corso disponibile |
| `TPL_OPERAPLUS_SKIP` | Salta al contenuto |
| `TPL_OPERAPLUS_MENU` | Etichetta accessibile della navigazione |
| `TPL_OPERAPLUS_XML_DESCRIPTION` | Descrizione nell’amministrazione |
| `TPL_OPERAPLUS_FAVICON`, `TPL_OPERAPLUS_FAVICON_PATH`, `TPL_OPERAPLUS_OG_IMAGE` | Etichette Branding e relativi campi `_DESC` |
| `TPL_OPERAPLUS_BRANDING`, `TPL_OPERAPLUS_APPEARANCE`, `TPL_OPERAPLUS_ACCENT`, `TPL_OPERAPLUS_BACKGROUND`, `TPL_OPERAPLUS_CONTAINER`, `TPL_OPERAPLUS_STICKY` | Etichette dei parametri amministrativi |
| `TPL_OPERAPLUS_LOGO`, `TPL_OPERAPLUS_BRAND_TEXT`, `TPL_OPERAPLUS_TAGLINE` | Etichette di branding e testi |

Per **l’elenco esatto di tutte le chiavi**, consultare `language/it-IT/tpl_operaplus.ini`, `language/en-GB/tpl_operaplus.ini` e i rispettivi file `.sys.ini`.

## Nota di manutenzione

Documentare ogni nuova classe riutilizzabile, campo backend e stringa lingua in questa guida e nella scheda Documentazione del template. Le informazioni sulle release appartengono esclusivamente al changelog.


## Messaggi di sistema Joomla

I messaggi di sistema sono gestiti dal componente nativo Joomla tramite `<jdoc:include type="message" />` e `<joomla-alert>`. Il template applica automaticamente stili coerenti a `success`, `danger`/`error`, `warning`, `info`/`notice`. Non occorrono override o classi personalizzate per Login, Logout e altri messaggi Joomla. Il controllo di chiusura resta gestito da Joomla.
### Moduli editoriali autonomi
I contenitori con `.op-cta-shell` e `.op-atlas-module` come figli diretti (anche nel `.card-body`) non ricevono la seconda cornice del template. I moduli ordinari conservano il proprio stile. Pubblicare i box con titolo modulo nascosto. La gestione della sidebar vuota rimane un requisito separato da collaudare.
