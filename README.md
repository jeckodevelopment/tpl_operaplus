# Template Opera+ per Joomla 6 — 1.3.0

Aggiornamento della v1.2.0: non rimuovere il template precedentemente installato. Installa lo ZIP da Sistema → Installa → Estensioni e svuota la cache.

## Hero
Solo nella voce Home predefinita: crea un modulo **Personalizzato**, posizione `hero`, **Mostra titolo: No**, assegnazione menu **Solo Home**. Il template rende automaticamente il logo configurato nello stile (opzione Logo dell’hero, altrimenti Logo principale) a fianco del modulo. La posizione `hero-side`, se popolata con un altro modulo, ha priorità sul logo automatico.

Esempio di contenuto da incollare in modalità codice HTML di un modulo Personalizzato:

```html
<p class="op-eyebrow">L’opera lirica raccontata al grande pubblico</p>
<h1>Un nuovo modo<br>di avvicinarsi<br><em>all’opera lirica.</em></h1>
<p class="op-hero-lead">Storie, musica e immagini per scoprire il mondo dell’opera. Un percorso di ascolto e conoscenza, prima e oltre l’esperienza in teatro.</p>
<p><a class="op-button" href="#progetto">Scopri il progetto ↓</a> <a class="op-button op-button--outline" href="#contatti">Collabora con noi ↗</a></p>
```

L'editor WYSIWYG di Joomla può rimuovere classi o tag: usare la modalità codice e controllare i filtri editor.

## Footer
In **Sistema → Template sito → Stili → Opera+ → Branding** configura Logo principale o Logo footer, nome associazione, codice fiscale, sede, email, PEC, testo del link torna su e claim finale. I valori predefiniti riprendono i dati pubblici su opera.plus al 28 ottobre 2026. Le posizioni footer-*, se utilizzate, consentono ulteriori moduli opzionali.

## Verifiche
Manifest XML valido, PHP lint (ove disponibile), ZIP integro. Non eseguita installazione runtime in Joomla 6: prima provare su staging.


## v1.4.0 — Moduli Joomla e pagine corsi
- Sidebar sinistra e destra: stile Joomla `html5` per contenitore e titolo affidabili.
- Moduli nativi Menu (anche nidificato), Login/Logout, ricerca, articoli e controlli formulario tematizzati con CSS automatico; mantenere i moduli sulle posizioni `sidebar-right` / `sidebar-left`.
- Il menu nella sidebar viene trasformato in un elenco editoriale senza punti, con stato attivo bordeaux.
- Componenti di pagina per corsi: `.op-course-intro`, `.op-course-feature`, `.op-course-feature__art`, `.op-course-feature__body`, `.op-course-meta`, `.op-course-status`, `.op-course-note`, `.op-course-chapters`, `.op-course-chapter`, `.op-course-cta`, `.op-course-lessons`. Si applicano al contenuto di articoli Joomla o moduli personalizzati.
- Il pacchetto contiene solo stili/layout, nessun articolo o modulo è creato automaticamente.
- Non collaudato in istanza Joomla: testare su staging.


## v1.5.0
Menu orizzontale, login privo delle icone decorative nei campi, pulsanti squadrati, dimensioni H1-H5/Hero configurabili e scheda guida agli stili nel backend. Consulta GUIDA_STILI.md.


Versione 1.6.0: navigazione dropdown multilivello e mega menu a colonne; tipografia corsi; documentazione backend e guida aggiornate.

## v1.7.0
Correzione posizione mega menu, form login, checkbox Remember me e guida backend organizzata in sezioni.

## v1.9.0
Rimossi i controlli icona adiacenti alla password nel login sidebar. Sottomenu laterali con toggle indipendente, espansione multilivello e supporto per il ramo attivo. Guida backend e GUIDA_STILI aggiornate.

## Versione 1.9.0
- Menu orizzontale: sostituiti i simboli + con chevron discreti, senza dipendenze da font icone.
- Menu laterale: ridotte le spaziature tra genitori e figli, su più livelli.
- Guida nel backend aggiornata con le istruzioni di navigazione.

## Versione 1.10.0
- Sidebar: ulteriormente compattate righe e sottomenu, mantenendo il controllo di espansione.
- Mega menu: margine di sicurezza 24px da entrambi i bordi e riallineamento automatico del pannello.
- Guida agli stili aggiornata.
- Sorgenti versionati nella repository GitHub `jeckodevelopment/tpl_operaplus`.