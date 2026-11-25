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

## Modalità Magazine (anteprima 1.21.0)
Dalla 1.21.1 l'installer crea lo stile nativo Opera+ – Magazine se non ne esiste già uno in modalità Magazine. In Sistema → Stili template sito apri lo stile Magazine già creato (non occorre duplicarlo), seleziona la voce di menu della homepage Magazine, configura il titolo, sottotitolo e una tua immagine autorizzata per la fascia. Assegna lo stile ai menu editoriali. Mantieni lo stile Standard per gli altri menu. Gli stili preesistenti, il default del sito e le assegnazioni dei menu non sono modificati dall'installer. Non cambiare il default globale. Le posizioni `magazine-cover` / `magazine-news`, `magazine-featured` / `magazine-featured-aside`, `magazine-reviews` / `magazine-reviews-aside`, `magazine-interviews` / `magazine-interviews-aside`, `magazine-dossier` / `magazine-dossier-aside`, `magazine-insights` / `magazine-insights-aside`, `magazine-latest` / `magazine-latest-aside` sono coppie main/aside con asse comune. Titoli tramite impostazioni native dei moduli. Nelle pagine interne `magazine-sidebar` e `sidebar-right` costituiscono la colonna laterale; sidebar sinistra e sezioni istituzionali restano Standard. Una colonna senza output non viene renderizzata, e le pagine interne senza sidebar occupano tutta la larghezza. La homepage senza moduli mostra il componente nativo. I layout editoriali dei contenuti e il collaudo visuale finale seguono nella milestone 2.0.0.

Dalla 1.21.2 le colonne Magazine riutilizzano il chrome e il controller dei menu laterali Standard. Menu e login mantengono formattazione, interazione e contrasto; le regole dedicate al magazine correggono gli stati attivi e le frecce della navigazione chiara. Atlas e CTA conservano il pannello autonomo già approvato.

## Moduli articoli Magazine (1.22.0)
Crea moduli nativi Articoli (`mod_articles`), assegnali alle voci di menu Magazine e scegli in Avanzate → Layout `operaplus:magazine-*`. Copertina: `magazine-cover` in posizione `magazine-cover`, numero 1, titolo modulo nascosto, immagine intro/full, link titolo. Notizie: `magazine-sidebar` in `magazine-news`, numero 3. In evidenza: `magazine-grid` in `magazine-featured`, 2–4 colonne, numero configurabile. Sezioni: `magazine-list`, `magazine-portraits`, `magazine-dossier`, `magazine-latest`, `magazine-ranked`, `magazine-topics`; `magazine-related-sidebar`, `magazine-related-horizontal`, `magazine-related-compact` presentano articoli selezionati con i filtri nativi, senza nuovo algoritmo di correlazione. Le impostazioni Joomla governano immagini, categoria, autore, data, link e numero. Estratti in testo semplice, omessi nelle varianti compatte; raggruppamenti e solo titoli mantengono il layout core. Non sono creati automaticamente moduli o contenuti.

## Pagine Magazine (1.23.0)
Nelle voci di menu Articoli seleziona i tipi Magazine griglia, elenco, dossier (categoria), o articolo, approfondimento, intervista, recensione (articolo singolo). Assegna lo stile Magazine. Il normale blog e articolo in Magazine usano griglia e articolo; Standard e corsi mantengono gli override precedenti. Configura le opzioni Joomla native per titoli, immagini, metadati, paginazione, tag e campi. Nello stile Contenuti Magazine: tempo di lettura, parole/minuto e fondo opzionale del primo elemento dell’elenco. Gli articoli possono scegliere layout alternativo nelle opzioni articolo; l'assegnazione del menu ha la precedenza Joomla. I campi di recensione pubblicati vengono stilizzati; la scheda spettacolo strutturata non è ancora completa. Longform usa l'indice nativo se disponibile. Nessuna duplicazione manuale del testo introduttivo.

## Archivio, autore e recensioni (1.24.0)
Archivio: voce Articoli → Magazine – Archivio, stile Magazine, categorie e filtro titolo attivo. L'archivio nativo mostra gli articoli Joomla archiviati, non tutti gli articoli pubblicati; non cambiare lo stato dei contenuti per ottenere un archivio editoriale generale. Autore: crea un contatto pubblico con nome, immagine, ruolo e biografia (Altre informazioni), collegato all'utente Joomla. Voce Contatti → Magazine – Autore, stile Magazine; attiva mostra immagine, posizione, altre informazioni, articoli e numero articoli. Il modello articoli filtra per ID utente (non alias autore), pubblicazione, lingua e accesso a categoria/articolo; mostra solo gli ultimi articoli pubblicati, non un archivio paginato completo. Configura il link autore nativo tramite il plugin Contenuti – Contatto e una voce di menu dedicata. Nessun dato del profilo utente o recapito viene mostrato nel layout autore. Recensioni: crea campi articolo di tipo Testo (o Area di testo per cast e crediti), facoltativi, pubblicati, solo categorie recensioni, visualizzazione automatica Non visualizzare. Nomi esatti: `review-opera`, `review-composer`, `review-theatre`, `review-city`, `review-date` (ISO YYYY-MM-DD), `review-season`, `review-conductor`, `review-director`, `review-orchestra`, `review-chorus`, `review-cast`, `review-credits`. Usa `magazine-review`: la scheda mostra i soli campi compilati e accessibili, senza doppia visualizzazione; data della recita separata dai metadati Joomla. Le associazioni Atlas continuano a usare i campi esistenti e il modulo contestuale. Non vengono creati automaticamente campi, contatti o menu.
