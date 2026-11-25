# Opera+ — Template 2.0.0 / Magazine

Piano approvato il 24 novembre 2026. Target Joomla 6. Milestone 2.0.0.
## Confini
- Tutti i layout standard e aggiuntivi 1.x restano invariati; nuovi layout alternativi opt-in, CSS isolato, asset condizionali.
- Template: presentazione. operaplus-extensions 1.0.0: mod_operaplus_atlas, plg_fields_operaplusatlas, mod_operaplus_cta, plg_editorsxtd_operaplus_templates, servizi condivisi. JD Learn rimandato: moduli generici nel suo progetto, override futuri qui.
- Atlas autonomo: export integrativo additivo, schema v2 e ID invariati; pipeline non bloccante, ultimo indice valido; ritratti verificati per tutti i compositori. Dipendenza jeckodevelopment/opera-atlas#833.
## Decisioni UX
Homepage: copertina overlay predefinita, contrasto AA indipendente dall'immagine, griglia unica 8+4 per tutte le sezioni e sidebar. mod_articles con layout magazine_cover, magazine_sidebar, magazine_grid, magazine_latest, magazine_list, magazine_portraits, magazine_dossier, magazine_ranked. Titoli sezioni configurabili, no categorie hardcoded. Recensioni: immagini identiche anche per la prima, fondo differenziato opzionale; teatro/città/data da campi facoltativi. Layout correlati sidebar, horizontal, compact; preferire moduli nativi, correlazione Atlas in fase successiva. Atlas e newsletter: conservare grafica approvata (ritratto a destra, fondo caldo, CTA bordeaux; newsletter rosata, busta in filigrana). TinyMCE: stili D/R e modelli contenuto tramite plugin. Tempo lettura automatico, 200 parole/min configurabile e disattivabile.
## Fasi
1. Censimento e matrice (#1).
2. Mockup singoli UX-01–15 approvati e versionati (#2).
3. Layout e override (#3).
4. Integrazioni grafiche (#4), coordinate con operaplus-extensions#1.
5. Collaudi e release (#5).
## Governance
PR organiche, label macro-requisito e priorità, milestone, comportamenti da testare, CI, documentazione contestuale, link ZIP per staging. Avanzamento implementativo separato dal collaudo: percentuale solo su criteri effettivamente verificati, non sulla mera apertura di issue. Nessun tag stabile prima del collaudo. La matrice è preliminare e sarà espansa a criteri atomici dopo censimento e approvazione grafica.

## Decisione architetturale 25 novembre 2026
Un solo template con parametro di stile `presentationMode` (standard default, magazine opt-in). Menu Joomla assegnati a stili duplicati; homepage editoriale individuata tramite menu selezionato e query effettiva, non soltanto Itemid. Asset condizionali, shell comune, posizioni dedicate, griglia 8+4; override alternativi devono controllare la modalità o ricadere sul comportamento nativo. Prima fase infrastruttura e collaudo, poi layout dei moduli, categorie/articoli e confronto con le tavole approvate. Nessuna nuova reinterpretazione del mockup.

### Precisazione 25 novembre 2026 — stili nativi
L'installazione crea automaticamente lo stile Magazine come record separato in Joomla, con modalità magazine, copiando il branding dello stile Standard. Non rinomina, sostituisce o modifica gli stili già presenti, non cambia il default e non assegna menu. Riconosce uno stile Magazine esistente anche rinominato; nessun duplicato in aggiornamento. La modalità rimane il discriminante tecnico interno dei due stili, non sostituisce i record Joomla.

### Layout mod_articles 1.22.0
Nomi effettivi con trattini anziché underscore: il selettore ModulelayoutField Joomla 6 esclude file con underscore. Layout editoriali alternativi; fallback core per Standard e configurazioni raggruppate/solo titoli. Le presentazioni related non implementano ancora correlazione automatica. Accettazione visiva sul sito e versionamento delle tavole definitive restano aperti.

### 1.24.0 — archivio, autore, recensione e collaudo
Il registro ufficiale è [COLLAUDO.md](COLLAUDO.md), aggiornato in ogni PR insieme alla matrice; nessun Word parallelo. Archivio nativo limitato agli articoli archiviati; eventuale archivio generale degli articoli pubblicati resta distinto. Autore basato sul contatto collegato all'utente, con ultimi articoli filtrati da ArticlesModel, non su un profilo utente pubblico. Scheda recensione basata su campi Joomla facoltativi con prefisso review-, ACL e auto-display disabilitato; riferimenti Atlas già disponibili.

### 1.25.0 — layout moduli residui
Carosello manuale progressivamente migliorato con controlli tastiera, senza autoplay; mosaico editoriale con prima scheda larga. Riuso di `mod_articles`, filtri e fallback esistenti. Restano aperti il confronto con le tavole definitive e l’accettazione visiva; la scheda recensione conserva immagini uniformi nel layout elenco.

### 1.26.0 — integrazione Extensions stabile
I requisiti MOD-09–11, CTA-01, EDIT-02 e ATLAS-01–03 sono implementati dalle Extensions 1.0.0 (commit `74e2bd02c4383ace61af0b56908d8797574d1b15`); il template non ne replica il codice. EDIT-01 aggiunge CSS TinyMCE e istruzioni di configurazione. ATLAS-04 usa il renderer nativo html5 e la misura dell'output nel Magazine, con test nativo su Joomla 6. Standard mantiene la struttura preesistente. MAG-01 riconciliato con la shell e i layout già implementati. Nessuna accettazione visiva dichiarata e nessuna modifica ai dati del sito.

### 1.27.0 — consolidamento SEO e mobile
Metadati con API documento Joomla, dati articolo già disponibili (nessun SQL aggiuntivo), canonical esistente preservato, fallback tracking-only, font remoti facoltativi e reflow a 320 px. Budget asset locali e test CI nativi. I requisiti trasversali restano in verifica: non si certificano WCAG, Core Web Vitals o retrocompatibilità integrale con sole fixture.

### 1.28.0 — chiusura configurazione e consolidamento
CFG-02: rapporti immagini, overlay e separatori per stile, metadati e colonne nativi. Default grafici invariati. Corretto il fallback ACL dei corsi, pubblicazione OG e doppia elaborazione SEO; riconoscimento route home Standard. Budget locali più stretti. DOC-02 e DOC-03: guide operative distinte, collaudo separato. RESP-01, DARK-01, SEO-01 e PERF-01: implementazione verificata in CI con i limiti descritti nel registro; accettazione manuale, misure reali e compatibilità plugin rimangono non eseguite. BASE-02, A11Y-01 e SEC-01 restano in corso fino alla copertura delle verifiche residue. REL-01 e tavole UX restano aperti; nessun rilascio stabile anticipato.

### 1.29.0 — consolidamento tecnico
BASE-02, A11Y-01 e SEC-01: navigazione senza JS, focus/Escape contestuale, riduzione lavoro mega menu, validazione URL immagini e ACL catalogo/campi recensione, verifiche browser e regressioni Standard su Joomla 6. La chiusura implementativa riguarda il codice di proprietà del template; il collaudo manuale resta distinto. WCAG AA globale, contrasto dei contenuti redazionali/estensioni e lettori di schermo non sono certificati dalla CI. REL-01 e versionamento delle tavole originali rimangono distinti.

### 2.0.0-rc.1 — candidata al rilascio
Packaging riproducibile con SHA-256, esclusione strumenti di sviluppo e pulizia mirata in aggiornamento 1.29.0, CI riutilizzabile, pubblicazione GitHub esclusivamente prerelease. REL-01 passa in corso: canale stabile registrato ma vuoto, prova staging e tag stabile subordinati ad accettazione esplicita. Le 15 tavole UX restano aperte finché i riferimenti grafici originali non saranno archiviati e verificati; non sono sostituite dalle fixture CI. Il registro distingue collaudo manuale da implementazione.

### Formalizzazione grafica 25 novembre 2026
Ritrovati dieci originali della progettazione, 13 riferimenti/estratti e due tavole mancanti (articolo mobile/dark). Specifiche grafiche, provenienza, hash e precedenza delle decisioni approvate in `docs/magazine/mockups/`. I file PNG sono ancora da caricare in Git; nessun criterio UX completato anticipatamente. UX-01–15 passano in corso per la formalizzazione testuale. Collaudo visuale e rilascio stabile separati; RC invariata.

### Archivio grafico — 25 novembre 2026
I 10 originali e i 13 estratti sono stati caricati dall'utente e organizzati in `docs/magazine/mockups/originals/` e `extracts/`. Identità dei blob verificata rispetto all'archivio locale; verifica SHA-256 automatica in CI. UX-14 e UX-15 completati per approvazione grafica già documentata e archiviazione; i collaudi manuali restano non eseguiti. UX-01–13 rimangono in corso: cinque tavole con riferimento recuperato, sei parziali, due mancanti. Nessun cambio della RC o del pacchetto.

### Completamento tavole grafiche — revisione 01
Otto tavole HTML/CSS autonome, UX-03, 05, 08–13, completano i riferimenti parziali o mancanti senza modificare gli originali PNG. Indice unico per tutte le 15 tavole, fonti e viewport documentati. La CI verifica la galleria, gli asset locali e il reflow; genera PNG e archivio offline. Cinque originali completi in attesa di registrazione dell'approvazione; due già approvati. Nessuna modifica runtime, nessuna nuova approvazione presunta: matrice 46/60 invariata fino a conferma esplicita. Collaudo manuale distinto.

### 2.0.0-rc.2 — audit trasversale
Consolidamento SEO/SEC/PERF/A11Y: canonical privacy, URL media, date legacy, descrizioni, eliminazione helper e letture ripetute, stato menu e resize, caroselli. Regressioni automatiche; nessuna modifica alla grafica, matrice e approvazioni manuali invariate. RC2 separata, RC1 immutabile.

### Piano collaudo RC2 — revisione 03
60 requisiti collegati a 95 casi in nove sessioni, registro ESITI_COLLAUDO.csv con tentativi, controllo CI di copertura. Nessuna prova manuale eseguita; due approvazioni grafiche storiche distinte. Matrice 46/60 invariata. Gate stabile e ricollaudo definiti in COLLAUDO.md.
