# Baseline tecnica 1.20.2 — protezione regressioni

Rilevazione statica 24 novembre 2026, Joomla 6. Riferimento pre-progetto: commit 2f8dae9d2c69581a7d8ffd0179162829f9b8e137. Nessun collaudo runtime eseguito.

## Inventario
- Shell: index.php, component.php, error.php, offline.php. Manifest templateDetails.xml, Web Asset Manager joomla.asset.json, media/css/template.css e user.css, media/js/template.js, ornament.svg. Traduzioni it-IT/en-GB.
- Override: html/com_content/article/default.php e html/com_content/category/blog.php; ramo specifico categoria alias corsi. Nessun override mod_articles presente nel tree osservato.
- Design: Georgia per titoli, Manrope per testo (caricato da Google Fonts), contenitore configurabile 1200px, bordeaux #632b3a, avorio #f3ece1, accento #b78e63, sfondo configurato #171316. Logo e branding configurabili, non sostituire con logo inventato.
- Griglia esistente .op-editorial-grid: 12 colonne, main span 7 e aside span 5; breakpoint 900px. NON modificarla. Nuova griglia magazine isolata 8+4, stesso gutter e asse verticale fra sezioni; nessuna modifica globale alla shell o agli stili 1.x.
- Posizioni manifest: topbar, header-actions, menu, hero, hero-side, announcement, breadcrumb, search, intro, content-highlights, mission, featured, story, courses, partners, schools, association, contacts, main-top, sidebar-left, sidebar-right, main-bottom, pre-footer, bottom, footer, footer-social, footer-brand, footer-links, footer-legal, debug. Le posizioni effettivamente renderizzate vanno verificate in index.php; search non compare come include diretto nella rilevazione. L'hero è condizionato alla home Joomla, quindi non riutilizzarlo implicitamente per la home del magazine.
- Parametri esistenti: branding, appearance, typography, navigation, documentation. Mantenere valori salvati e default; nuove opzioni magazine opt-in. H1 corpo 48px, hero 72px di default.
- Guida stili GUIDA_STILI.md; changelog separato; tools/quality_check.py verifica coerenza versione asset/manifest, esistenza file e directory dichiarati, duplicati traduzioni, changelog.

## Matrice minima di regressione (collaudo ancora da fare)
| ID | Scenario | Esito atteso |
|---|---|---|
| BASE-T01 | Home istituzionale con hero, menu e moduli | Struttura e stile invariati |
| BASE-T02 | Blog categoria ordinaria / articolo ordinario | Rendering nativo e opzioni preservati |
| BASE-T03 | Catalogo e dettaglio corsi alias corsi | Copertine non ritagliate, contenuti e CTA invariati |
| BASE-T04 | Sidebar sinistra/destra/entrambe, griglia 7+5 | Proporzioni e comportamento esistenti invariati |
| BASE-T05 | Temi chiaro/scuro e stili menu distinti | Parametri salvati preservati |
| BASE-T06 | Menu multilivello e mega menu desktop/mobile/tastiera | Navigazione invariata |
| BASE-T07 | Login, ricerca, messaggi, errore/offline/component | Presentazione e accessibilità preservate |
| BASE-T08 | SEO, OG, favicon e branding | Precedenze e fallback invariati |
| BASE-T09 | Lingue it-IT/en-GB | Stringhe e override lingua funzionanti |
| BASE-T10 | Installazione aggiornamento 1.x → 2.0 | Nessuna perdita di configurazione |

## Nota packaging e CI
Il tree osservato non contiene .github/workflows né template_preview.png/template_thumbnail.png, benché queste immagini siano dichiarate nel manifest. Verificare prima di affermare che quality_check.py passa o che il repository è installabile direttamente. Nessuna rimozione del manifest o correzione funzionale in questa fase. Il rilascio dovrà disporre di CI e pacchetto verificato.

## Evidenze automatiche fino a 1.29.0
La baseline resta il riferimento storico. `template-lifecycle.yml` verifica installazione, aggiornamento e preservazione stili/menu su Joomla reale; `test_editorial_integration.sh` verifica home/hero Standard, articolo con Itemid ereditato, sidebar, ACL e SEO; i test browser verificano struttura responsive, chrome, tastiera, focus e fallback senza JS. Il registro COLLAUDO conserva i dieci scenari BASE-T01–T10 per il confronto visuale sul sito, incluse ricerca, offline ed errori. Nessuna dichiarazione di pixel-perfect o collaudo manuale già effettuato.
