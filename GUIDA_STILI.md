# Opera+ Joomla 6 — Guida agli stili (v1.9.0)

La scheda **Guida agli stili · Moduli personalizzati** è disponibile anche in **Sistema → Stili template sito → Opera+**. Ogni futuro aggiornamento degli stili deve aggiornare questa guida e la scheda backend.

## Posizioni e moduli Joomla

- `menu`: modulo **Menu** (titolo nascosto), orizzontale su desktop, mobile con hamburger. Non serve una classe aggiuntiva.
- `sidebar-left`, `sidebar-right`: moduli **Menu**, **Login**, **Ricerca** stilizzati automaticamente. Per menu impostare layout predefinito.
- `hero`: modulo **Personalizzato** assegnato **solo alla Home**, con titolo modulo nascosto. Il logo laterale proviene da Branding > Logo dell’hero.
- `main-top`, `main-bottom`, `courses`, `story`, `mission`, `schools`, `association`, `contacts`: contenuti editoriali e moduli personalizzati.

## Classi generali

| Classe | Utilizzo |
|---|---|
| `op-container` | Massima larghezza centrata |
| `op-section`, `op-section-alt` | Sezione con padding / fondo alternativo |
| `op-split`, `op-split--reverse` | Layout affiancato testo e immagine |
| `op-cards`, `op-card` | Griglia e pannello |
| `op-eyebrow` / `op-kicker` | Soprattitolo in maiuscolo |
| `op-lead` | Testo introduttivo |
| `op-quote` | Citazione editoriale |
| `op-button` | Pulsante principale squadrato |
| `op-button op-button--outline` | Pulsante secondario a bordo |
| `op-media` | Contenitore immagine 16:10 |
| `op-tag` | Etichetta |
| `op-rule` | Separatore |

## Esempio modulo Hero

```html
<p class="op-eyebrow">L'opera lirica raccontata al grande pubblico</p>
<h1>Un nuovo modo<br>di avvicinarsi<br><em>all'opera lirica.</em></h1>
<p class="op-hero-lead">Storie, musica e immagini per scoprire il mondo dell'opera.</p>
<p><a class="op-button" href="/#progetto">Scopri il progetto ↓</a> <a class="op-button op-button--outline" href="/#contatti">Collabora con noi ↗</a></p>
```

## Pagine corsi

`op-course-intro`, `op-course-feature`, `op-course-feature__art`, `op-course-feature__body`, `op-course-meta`, `op-course-status`, `op-course-chapters`, `op-course-chapter`, `op-course-cta`.

Esempio:

```html
<div class="op-course-intro"><p class="op-eyebrow">I percorsi di Opera+</p><h1>L’opera, un ascolto alla volta.</h1><p>Corsi da guardare e ascoltare.</p></div>
<div class="op-course-feature">
  <div class="op-course-feature__art"><img src="/images/corsi/rigoletto.jpg" alt="Rigoletto"></div>
  <div class="op-course-feature__body"><span class="op-course-status">Di prossima pubblicazione</span><p class="op-eyebrow">Giuseppe Verdi</p><h2>Rigoletto</h2><p>Un corso gratuito di Opera+.</p></div>
</div>
```

## Dimensioni titoli

Nello stile template, scheda **Tipografia · Titoli H1–H5**, seleziona la dimensione massima desktop dei titoli H1, H2, H3, H4, H5 e Hero H1. Le dimensioni si riducono automaticamente su viewport stretti. Per preservare le impostazioni, non definire font-size inline nei moduli personalizzati.

## Note

L’editor visuale Joomla può rimuovere alcune classi/HTML a seconda della configurazione dei filtri; usare la modalità sorgente e controllare i filtri del gruppo utente. Il template non modifica i dati e le assegnazioni dei moduli esistenti.


## Navigazione multilivello e mega menu (v1.6.0)

- Il modulo nativo **Menu** va nella posizione `menu`, con **Mostra voci sottomenu = Sì** e **Livello finale = Tutti**.
- Organizza le voci in **Menu → [nome menu]** con relazioni padre/figlio. I livelli 2 e 3 diventano dropdown; i livelli successivi sono supportati ma si raccomanda una gerarchia non troppo profonda.
- Per un **mega menu** usa `op-mega` nel campo **Classe CSS collegamento** della voce di primo livello. Crea le voci figlie di livello 2 come intestazioni di colonna e il livello 3 come link delle colonne.
- Nello stile del template, scheda **Navigazione e mega menu**, configura larghezza del pannello, colonne (2–4) e apertura con hover/click.
- Su smartphone il menu principale si apre dal pulsante hamburger, ogni livello si espande dal pulsante con freccia con `aria-expanded` e supporto Escape.
- Il mega menu organizza voci di menu Joomla, **non** moduli arbitrari dentro il pannello: questa funzionalità richiederebbe un'estensione del renderer.

## Tipografia Opera+ /corsi/

- **Georgia** per i titoli (serif, peso 400). **Manrope** per testi, pulsanti e navigazione (400–700).
- Gli H1–H5 e Hero H1 restano configurabili dallo stile del template. Le dimensioni adattive evitano titoli sovradimensionati sui telefoni.
- Titoli dei corsi: usa `op-course-intro`, `op-course-feature__body`, `op-course-chapters`; soprattitoli: `op-eyebrow`.
- Se il font Manrope non è disponibile per mancanza di connessione, viene utilizzato il font sans-serif di sistema.


## v1.7 — Guida rapida per l'amministratore

### Menu normale e mega menu
- Modulo Menu: posizione `menu`, titolo nascosto, mostra sottomenu, livello finale Tutti.
- Dropdown: voci annidate normalmente senza classi aggiuntive.
- Mega menu: `op-mega` nel campo Classe CSS collegamento del padre; figlie = intestazioni colonna; nipoti = link.
- Per menu laterale usa `sidebar-right` o `sidebar-left` senza classe.

### Modulo Login
- Posizionare in una sidebar, senza HTML personalizzato. Icone decorative nascoste; Remember me resta leggibile.

### Hero personalizzato
```html
<p class="op-eyebrow">L'OPERA LIRICA</p>
<h1>Un nuovo modo di avvicinarsi all'opera.</h1>
<p class="op-hero-lead">Storie, musica e immagini.</p>
<a class="op-button" href="/corsi/">Scopri i corsi</a>
```

### Classi principali
- `op-section`, `op-container`, `op-section-alt`: struttura di pagina.
- `op-split`, `op-cards`, `op-card`: layout a colonne e card.
- `op-eyebrow`, `op-lead`, `op-quote`: testo editoriale.
- `op-button`, `op-button--outline`, `op-tag`: azioni e tag.
- `op-course-intro`, `op-course-feature`, `op-course-chapters`, `op-course-chapter`, `op-course-cta`: pagine corsi.


## v1.8 — Password e menu verticali multilivello

- **Login**: nella posizione `sidebar-left` o `sidebar-right`, il pulsante/area decorativa di visualizzazione password è nascosto per evitare icone vuote. Il campo password resta normalmente utilizzabile; non è necessario modificare il modulo nativo.
- **Menu laterale**: pubblica il modulo *Menu* in `sidebar-left` o `sidebar-right`, scegli il layout standard, attiva **Mostra voci sottomenu** e seleziona **Livello finale: Tutti**. I nodi con figli hanno ora un comando `+`/`−` separato dal collegamento. I genitori della pagina attiva rimangono aperti all'avvio.
- Il click sul testo del collegamento apre la pagina, mentre il click sul controllo `+` espande il livello; funziona su desktop, smartphone e tastiera.
- Il mega menu orizzontale rimane gestito unicamente nella posizione `menu`. Non assegnare `op-mega` alle voci della sidebar.

## Navigazione compatta (v1.9.0)

- **Menu orizzontale** (posizione `menu`): accanto alle voci con sottomenu appare una piccola freccia, non un simbolo `+`. La freccia ruota all’apertura. Il link principale rimane navigabile.
- **Mega menu**: alla voce padre applicare la classe link `op-mega`; le intestazioni delle colonne non mostrano pulsanti superflui.
- **Menu verticale** (`sidebar-left` / `sidebar-right`): margini e padding tra genitore e figli sono compatti; il ramo attivo si espande. Non serve una classe personalizzata.
- Dopo l’aggiornamento svuotare cache Joomla e cache browser per caricare il nuovo JavaScript e CSS.

## v1.10.0 — Spaziature sidebar e allineamento mega menu

- Menu nelle posizioni `sidebar-left` e `sidebar-right`: spaziatura verticale compatta (circa 4 px complessivi tra i link), senza gap automatico tra gli elementi. Nessuna classe custom necessaria.
- Sottomenu: struttura padre/figlio definita in Joomla; l'apertura resta controllata dalle frecce laterali.
- Mega menu: almeno 24 px di margine dal bordo destro e sinistro della finestra sul desktop, anche se la voce padre è l'ultima del menu; su mobile occupa la larghezza disponibile.
- Le impostazioni di larghezza e colonne continuano a trovarsi nella scheda **Navigazione e mega menu**.


## v1.11.0 — Pagine corsi, moduli e varianti chiare

### Categoria Corsi (nativo Joomla)
Crea una categoria Joomla con **alias `corsi`**. Crea una voce menu di tipo **Articoli → Blog di categoria**, categoria Corsi. Ogni corso è un articolo: **Titolo** = opera (es. Rigoletto), **Autore/Alias autore** = compositore (es. Giuseppe Verdi), **Immagine introduttiva** = copertina orizzontale, **Tag** = temi e caratteristiche. L'override `html/com_content/category/blog.php` gestisce automaticamente la grafica; non occorre scrivere HTML. Le altre categorie continuano a usare il blog Joomla standard.

### Tema chiaro
Duplica lo stile Opera+ in **Sistema → Stili template sito**. In *Aspetto*, seleziona **Tema cromatico della pagina = Chiaro** e assegna lo stile ai menu delle pagine interne. Lascia lo stile predefinito scuro per la homepage.

### Messaggi di Joomla
I messaggi di sistema (`alert-info`, `alert-warning`, `alert-danger`, `alert-success`) ricevono automaticamente una grafica sobria tipo “Il corso è in preparazione”. Per un testo editoriale facoltativo in un modulo Personalizzato puoi usare `alert alert-info`.

### Pre-footer
Pubblica un modulo **Personalizzato** in posizione `pre-footer`, assegnato ai menu desiderati. Usa un titolo H2 come “Vuoi conoscere Opera+?”, un paragrafo e un collegamento con classe `op-button`. La banda bordeaux viene aggiunta dal template.

### Breadcrumb
Pubblica il modulo nativo **Percorso di navigazione** in posizione `breadcrumb`, preferibilmente nelle sole pagine interne. Non serve alcuna classe CSS.

### Mega menu
Il JS corregge automaticamente la posizione dei pannelli quando la voce padre è vicina al bordo destro della finestra, mantenendo un margine minimo di 32 px.


## v1.13.0 — Fondi chiari, moduli Joomla e mega menu
- Il tema predefinito è **Chiaro**: il contenuto della homepage e delle pagine interne è avorio; header, hero e footer restano scuri. Nelle opzioni dello stile `Tema cromatico della pagina` scegli Scuro solo dove necessario. Negli stili già esistenti il valore salvato resta invariato: seleziona manualmente Chiaro.
- Il modulo `pre-footer` usa una fascia chiara `#eee7e0`, con titoli scuri e pulsanti bordeaux squadrati. Per la CTA usa un modulo Personalizzato con H2, paragrafo e link `class="op-button"`.
- I componenti Joomla utenti (reset password, password dimenticata, accesso, registrazione, profilo) hanno distanze tra input e pulsanti, campi e controlli coerenti. Previsti anche form, paginazione e pulsanti standard.
- Il mega menu desktop usa coordinate viewport: margine minimo **32px** da entrambi i lati, incluso il menu all'estrema destra. I controlli precedenti di allineamento sono stati eliminati.
- Quando vengono aggiunte o modificate classi CSS, aggiornare sempre questa guida e quella del backend.


## v1.13.0 — Layout singolo corso e navigazione

Gli articoli della categoria con alias `corsi` utilizzano automaticamente `html/com_content/article/default.php`: titolo, autore alias (compositore), tag, immagine articolo completa (fallback immagine introduttiva), intro e testo completo. Gli altri articoli seguono il layout Joomla standard. I moduli sidebar nel tema chiaro utilizzano testo scuro, campi leggibili e pulsanti bordeaux. Il mega menu è posizionato da un solo controller JS, relativamente al suo contenitore reale e con margini di sicurezza.