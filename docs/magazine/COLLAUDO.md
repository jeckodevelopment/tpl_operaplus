# Piano e registro di collaudo — Opera+ Magazine

**Fonte ufficiale:** questo file Markdown versionato, mantenuto con ogni PR. Sostituisce il Word della revisione 01; nessun documento parallelo. Revisione 05 — 25 novembre 2026. Baseline operativa: template 2.0.0-rc.4 (build PR #32 `1b324324c7e5b97c3175638de0e81d8c81fe0798`, merge `bacfce3e433b2393fdec27f00c284b397451c6d3`), Extensions 1.0.1 (build PR #15 `0ddc3c92d75ebfda070cd041f0d2b0d0aa35f981`, merge `31069fce95a261f4065255ab7c73b6886cd69a74`). Le indicazioni delle versioni precedenti sono storiche.

Le CI verificano il comportamento tecnico, non equivalgono all'accettazione manuale o grafica. I collaudi intermedi sono rinviati su richiesta dell'utente. Confermati: installazione Extensions 1.0.0, plugin/campi, box Atlas e CTA, ritratto Verdi senza credito, stile Magazine distinto e correzione menu/login 1.21.2. Ripetere come regressione.

## Lotto 1A — esiti 25 novembre 2026

Ambiente staging, Joomla 6.2.1 / PHP 8.5.13, Extensions 1.0.0. Inizialmente RC1 effettiva: SET-01, SET-02, BASE-T05 dichiarati OK nelle verifiche funzionali; versione errata rispetto alla baseline (BUG-001). Aggiornamento RC2: versione corretta, ma BUG-002 (data), BUG-004 (anteprima), BUG-005 (descrizione in entrambi gli stili), BUG-006 (selettore modalità) confermati, oltre a BUG-003 (date delle estensioni). [Issue #28](https://github.com/jeckodevelopment/tpl_operaplus/issues/28) e [PR Extensions #15](https://github.com/jeckodevelopment/operaplus-extensions/pull/15). Commit dei pacchetti installati non attestato dal collaudatore: non attribuire arbitrariamente un commit. Non trasferire gli OK RC1 a RC2 o RC3.

Procedura prevista per RC3: ripetere SET-01 (versione e date dei sei manifest Extensions e template; anteprima in Template sito), SET-02 (descrizione IT/EN in entrambi gli stili, nessun selettore, opzioni editoriali solo in Aspetto Magazine; rinominare/duplicare/salvare verificando tipo e assegnazioni), BASE-T05, SET-05 e REL-02. 

**Ricollaudo 25 novembre 2026:** collaudatore conferma BUG-001–006 e REG (salvataggio stili e funzionamento frontend) tutti OK, nessuna nota, dopo installazione dei pacchetti RC3/1.0.1. Riferimenti build sopra, identificati dagli artifact forniti in chat; checksum locale e browser non attestati. Chiusi i rilievi del lotto 1A. SET-01 OK per installazione/metadati; SET-02 e BASE-T05: riscontro positivo parziale, da completare con controllo esplicito di branding, default, assegnazioni e ID; SET-05, REL-02 e BASE-T10 ancora da eseguire. Nessuna approvazione stabile implicita.

**Lotto 1B — 25 novembre 2026:** confermati OK BASE-T01–T07 (incluso BASE-T05) e SET-02, nessuna anomalia; staging RC3 / Extensions 1.0.1. 9/15 casi sessione 1, 9/95 piano. Residui: SET-05, REL-02, BASE-T08/09/10 e BASE-INV. I test di integrità, reinstallazione e upgrade storico restano distinti.

**Lotto 1C — 25 novembre 2026:** BASE-T08, SET-05 e REL-02 OK; BASE-T09 KO (schede e impostazioni template rimangono in italiano cambiando lingua amministratore), [BUG-007](https://github.com/jeckodevelopment/tpl_operaplus/issues/31). Totale RC3: 12/95 casi OK, 1 KO, 82 non conclusi; sessione 1: 12/15 OK, 1 KO, 2 residui. RC4 corregge esclusivamente localizzazione; nuova baseline e regressioni impattate dopo CI e ricollaudo. Non trasferire automaticamente gli esiti.

**Ricollaudo BUG-007 — 25 novembre 2026:** RC4 installata; traduzioni inglesi Standard e Magazine, documentazione inglese, traduzioni italiane, conservazione parametri/assegnazioni e funzionamento frontend tutti OK, nessuna nota. Difetto chiuso. BASE-T09: ricollaudo mirato del solo KO segnalato; per completezza verificare separatamente override di lingua, menu, carosello e messaggi se non già provati. Le etichette REG-01/02 della scheda di ricollaudo erano locali: non equivalgono ai casi omonimi della sessione 8. I precedenti OK RC3 restano storici; modifica circoscritta ai cataloghi e manifest, regressione configurazione/frontend confermata, CI RC4 positiva. Non si presume un nuovo collaudo completo. BASE-INV e BASE-T10 restano da eseguire. Si può procedere alla sessione 2.

**Sessione 2 — 25 novembre 2026:** SET-03, SET-04 e CFG-02 tutti OK, nessuna nota, su staging RC4 / Extensions 1.0.1. Configurazione landing, navigazione e indipendenza delle impostazioni confermate. Avanzamento cumulativo 16/95 casi OK (13 su 15 della sessione 1 e 3 su 3 della sessione 2), senza KO residui. Restano i due casi tecnici della sessione 1 e le verifiche specifiche già annotate; proseguire con sessione 3.

**Lotto 3A — 25 novembre 2026:** HOME-01–05 tutti OK, nessuna nota, su staging RC4 / Extensions 1.0.1. Copertina, tre notizie, griglia, asse editoriale 8+4 e gestione righe vuote/componente confermati dal collaudatore. Avanzamento cumulativo 21/95, sessione 3: 5/17. Nessun nuovo difetto; approvazioni grafiche UX e verifiche specifiche successive restano distinte.

**Lotto 3B — 25 novembre 2026:** MOD-01/02/03/11/12 OK; MOD-04/05 KO: Approfondimenti e Da leggere anche non visibili nonostante configurazione dichiarata corretta. Entrambi in magazine-sidebar. [BUG-008](https://github.com/jeckodevelopment/tpl_operaplus/issues/35). 26/95 casi OK, 2 KO; sessione 3: 10/17 OK, 2 KO, 5 da eseguire. Verificare Itemid effettivo, assegnazione menu nativa, selezione articoli; non modificare ACL, routing o filtri per forzare visualizzazione. Test nativo aggiuntivo con articolo e moduli correlati. Diagnostica staging: anche assegnando entrambi i moduli a tutte le pagine, modalità normale e cache disabilitata, i moduli restano invisibili. La prima CI ha superato le asserzioni sui tre layout in articolo e modalità dinamica; il successivo controllo landing è fallito per redirect HTTP 301 non seguito. Nessuna causa definitiva attribuita prima delle evidenze.

**Ricollaudo BUG-008 — 25 novembre 2026:** il modulo Test Sidebar duplicato da Recensioni è visibile nella posizione magazine-sidebar; cambiando soltanto layout, related-sidebar e related-compact risultano entrambi visibili. Ricreando i due moduli dalla configurazione funzionante, il collaudatore conferma entrambi visibili contemporaneamente nelle pagine interne. La mancata visualizzazione nella landing con componente nascosto è intenzionale (posizioni homepage dedicate); per eventuali copie in homepage usare magazine-insights-aside o magazine-latest-aside. Problema risolto mediante riconfigurazione; parametro originale responsabile non identificato. CI nativa PR #36 verde per tutti e tre i layout, filtri normali/dinamici, Itemid ereditato e soppressione sidebar landing. MOD-04 e MOD-05 OK per ricollaudo mirato dei KO segnalati; la variante orizzontale non era stata segnalata come difettosa e va confermata individualmente nel lotto 3C. Avanzamento 28/95, sessione 3: 12/17; nessun KO residuo noto. Nessuna nuova versione necessaria.

**Lotto 3C — 25 novembre 2026:** MOD-06–10 e conferma integrativa MOD-05 tutti OK, nessuna nota. Confermata anche la configurazione delle copie dei moduli sidebar nella homepage. RC4 / Extensions 1.0.1. Sessione 3 completata: 17/17; avanzamento cumulativo 33/95 casi OK, nessun KO residuo noto. Restano BASE-INV e BASE-T10 e gli altri casi delle sessioni 4–9; approvazione grafica distinta. Nessuna modifica runtime.

**Lotto 4A — 26 novembre 2026:** CAT-01–04 KO, CAT-05 OK su RC4. BUG-009 intro sempre visibile, BUG-010 metadati elenco/dossier difformi, BUG-011 contrasto e icone paginazione. [Issue #39](https://github.com/jeckodevelopment/tpl_operaplus/issues/39). 34/95 OK, 4 KO, 57 non conclusi. RC5 candidata correttiva: ricollaudare CAT-01–04 e CAT-05 come regressione; non trasferire automaticamente gli esiti. Approvazione grafica distinta.

**Ricollaudo lotto 4A — 26 novembre 2026:** RC5, Extensions 1.0.1. CAT-01/02/03/05 OK; CAT-04 KO residuo: paginazione delle categorie OK, contrasto dei link precedente/successivo nel singolo articolo insufficiente. [Issue #39](https://github.com/jeckodevelopment/tpl_operaplus/issues/39). 37/95 casi OK, 1 KO, 57 non conclusi. RC6 interviene esclusivamente sul plugin nativo pagenavigation tramite CSS Magazine, senza alterare routing o paginazione categorie. Ricollaudo CAT-04 mirato e verifica regressione della paginazione; approvazione grafica generale separata.

**Ricollaudo CAT-04 — 26 novembre 2026:** RC6, Extensions 1.0.1: OK, nessuna nota. Verifica mirata dei pulsanti precedente/successivo e regressione paginazione categorie positiva. CAT-01/02/03/05 già OK su RC5, non interessati dalla correzione CSS circoscritta alla navigazione del singolo articolo; verifica paginazione compresa in CAT-04. Lotto 4A: 5/5, 38/95 casi cumulativi OK, nessun KO residuo noto. BUG-009–011 risolti; chiusura issue #39 dopo integrazione di questo registro. Approvazione grafica generale resta distinta. Passaggio a lotto 4B (ARC-01–03) su RC6.

**Lotto 4B — 26 novembre 2026:** RC6, Extensions 1.0.1. ARC-01/02/03 OK, nessuna nota. Sessione 4: 8/11, avanzamento cumulativo 41/95 casi OK; nessun KO residuo noto. Passaggio a lotto 4C AUT-01–03. Approvazione grafica delle tavole distinta dal collaudo funzionale.

**Lotto 4C — 26 novembre 2026:** RC6, Extensions 1.0.1. AUT-01/02/03 KO: grafica profilo corretta, elenco articoli non visibile. [BUG-012, issue #44](https://github.com/jeckodevelopment/tpl_operaplus/issues/44). 41/95 OK, 3 KO, 51 non conclusi. RC7: isolare parametri Contenuti, rispettare scelta esplicita del menu contatto, costruire route da ID/alias; test nativo con contatto, articoli pubblici, riservati, futuri e altro autore, opzione elenco disattivata e utente scollegato. Causa esatta sullo staging non confermata, ricollaudo richiesto.

**Ricollaudo lotto 4C — 26 novembre 2026:** RC7 / Extensions 1.0.1: AUT-01/02/03 ancora KO, nessuna nota aggiuntiva. Il test nativo RC7 era positivo su Joomla 6.0.0 / PHP 8.3 / Extensions 1.0.0; non riproduceva lo staging (Joomla 6.2.1 / PHP 8.5.13 / Extensions 1.0.1). Aggiunta matrice CI per entrambe le combinazioni, pacchetti ufficiali con checksum pubblicati e commit extensions fissati. Nessuna modifica runtime e nessuna nuova RC finché non emergono evidenze. BUG-012 aperto; 41/95 OK, 3 KO, 51 non eseguiti. Distinguere se la sezione articoli è assente, se mostra il messaggio vuoto o se il markup non è visibile.

**Ricollaudo definitivo lotto 4C — 26 novembre 2026:** RC7 / Extensions 1.0.1. La diagnostica ha mostrato l'assenza dell'intera sezione articoli; dopo aver impostato esplicitamente Articoli utente = Mostra nel contatto e nella voce di menu (10 articoli, utente collegato), salvato e svuotato la cache, il collaudatore ha confermato la visualizzazione. Il parametro ereditato originario non è stato identificato. AUT-01/02/03 OK, nessuna nota. BUG-012 risolto nella configurazione verificata; nessuna RC8 necessaria. PR #46 integrata con CI positiva anche su Joomla 6.2.1 / PHP 8.5.13 / Extensions 1.0.1. Sessione 4 completata: 11/11; complessivo 44/95 OK, 51 residui, nessun KO noto. Approvazione grafica generale separata. Passaggio alla sessione 5 su RC7.

**Lotto 5A — 26 novembre 2026:** RC7 / Extensions 1.0.1. ART-01/02/03 OK, ART-04/05 KO; riscontrato anche difetto collaterale Leggi tutto nella categoria (riaprire verifica CAT-04 mirata senza annullare storico). [BUG-013–015, issue #48](https://github.com/jeckodevelopment/tpl_operaplus/issues/48). 47/95 OK dichiarati, 2 KO, 46 non eseguiti; verifica regressione aggiuntiva Leggi tutto aperta. RC8: CSS circoscritto Magazine, D/R anche nell'editor, campi automatici Joomla distinti dalla scheda strutturata REV-01–04. Ricollaudo ART-04/05, Leggi tutto, controllo longform. Approvazione grafica definitiva distinta.

## Dati della sessione

- Collaudatore, data, ambiente di staging:
- Versioni Joomla/PHP, template/commit, estensioni:
- Browser, dispositivo e viewport:
- Esito: OK / KO / N.A. (motivato) / Bloccato; per ogni caso registrare data, URL, evidenza e issue. Non inserire credenziali o dati personali nel repository pubblico.

## Ordine di esecuzione guidato

**Sessione 1 — Installazione e regressioni Standard** (15 casi): REL-02, SET-01, SET-02, SET-05, BASE-T01, BASE-T02, BASE-T03, BASE-T04, BASE-T05, BASE-T06, BASE-T07, BASE-T08, BASE-T09, BASE-T10, BASE-INV.

**Sessione 2 — Configurazione Magazine** (3 casi): SET-03, SET-04, CFG-02.

**Sessione 3 — Homepage e moduli** (17 casi): HOME-01, HOME-02, HOME-03, HOME-04, HOME-05, MOD-01, MOD-02, MOD-03, MOD-04, MOD-05, MOD-06, MOD-07, MOD-08, MOD-09, MOD-10, MOD-11, MOD-12.

**Sessione 4 — Categorie, dossier, archivio e autore** (11 casi): CAT-01, CAT-02, CAT-03, CAT-04, CAT-05, ARC-01, ARC-02, ARC-03, AUT-01, AUT-02, AUT-03.

**Sessione 5 — Articoli, recensioni e interviste** (9 casi): ART-01, ART-02, ART-03, ART-04, ART-05, REV-01, REV-02, REV-03, REV-04.

**Sessione 6 — Atlas, CTA e TinyMCE** (9 casi): INT-01, INT-02, INT-03, INT-04, INT-05, INT-06, INT-07, INT-08, INT-09.

**Sessione 7 — Approvazione grafica, mobile e dark** (18 casi): REG-04, UX-01, UX-02, UX-03, UX-04, UX-05, UX-06, UX-07, UX-08, UX-09, UX-10, UX-11, UX-12, UX-13, UX-14, UX-15, RESP-01, DARK-01.

**Sessione 8 — SEO, prestazioni, sicurezza, accessibilità** (10 casi): REG-01, REG-02, REG-03, REG-05, REG-06, SEO-01, PERF-01, SEC-01, SEC-02, A11Y-01.

**Sessione 9 — Accettazione finale** (3 casi): DOC-01, DOC-02/03, REL-03.

## Registrazione e gate

Il [registro CSV](ESITI_COLLAUDO.csv) è la fonte degli esiti: un tentativo per caso, nuove righe per ricollaudi, storico conservato. Prima della sessione registrare versione, commit, Joomla/PHP, Extensions, dispositivo e viewport. Usare alias staging, evidenze anonimizzate; nessuna credenziale, token o URL privato. Stati `non_eseguito`, `OK`, `KO`, `NA` (motivazione e accettazione obbligatorie), `bloccato`. KO richiede issue e ricollaudo. Per UX registrare anche approvazione grafica distinta, già acquisita per UX-14/15. Aggiornare matrice requisiti dopo ogni sessione: `non_eseguito`, `in_corso`, `superato`, `non_superato`, `bloccato`; superato solo quando tutti i casi associati applicabili sono OK o NA motivato e accettato e l'approvazione UX è registrata. Riportare avanzamento manuale separato da implementazione. Dopo nuove RC rivalutare casi impattati; nessun trasferimento automatico di esiti tra versioni. Gate stabile: tutti i casi pre-rilascio conclusi, UX approvate e conformi su staging, nessun KO/blocco aperto, backup/rollback, CI verde sulla versione finale e approvazione esplicita. REL-03 comprende verifica pre-rilascio (feed non propone RC) e post-pubblicazione (ZIP/feed stabile su staging); REL-01 si chiude dopo entrambe. Nessun deploy produzione implicito.

## Prerequisiti

Backup file e DB; staging; amministratore, ospite e registrato; almeno due categorie editoriali, corsi e sei articoli con immagini autorizzate (anche senza immagine, riservato, futuro, non pubblicato). Non cambiare produzione. Usare moduli Articoli nativi e i layout con trattini.

### SET-01 — Installare ZIP come aggiornamento, pulire cache

- **Procedura:** Sistema → Installa estensioni: caricare lo ZIP interno installabile, non l'archivio degli artifact. Annotare versioni prima e dopo, non disinstallare; controllare gestione estensioni e pulire cache Joomla/browser.
- **Atteso:** Dati e configurazioni conservati.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### SET-02 — Sistema → Stili template sito: Standard e Magazine

- **Procedura:** Aprire Standard e Opera+ – Magazine in Sistema → Stili template sito; annotare ID, branding, tema, default, parametri e menu assegnati. Confermato in parte dall'utente.
- **Atteso:** Due record distinti; il preesistente non viene rinominato o duplicato, default e assegnazioni invariati..
- **Stato iniziale:** Confermato in tutto o in parte; regressione da eseguire.
- [ ] Esito / data / evidenza / note:

### SET-03 — Voce menu landing, fascia immagine/titolo/sottotitolo

- **Procedura:** Creare una voce Articoli in evidenza o Blog di categoria, assegnare Magazine e selezionarla come homepage Magazine nello stile. Impostare titolo, sottotitolo e immagine teatrale autorizzata. Aprire anche home Standard e dettaglio.
- **Atteso:** Masthead solo sulla landing.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### SET-04 — Seguire link articolo con Itemid ereditato, categoria e corsi

- **Procedura:** Seguire il link copertina → articolo → categoria anche con Itemid ereditato; aprire home istituzionale e corsi. Verificare URL, stile, layout e navigazione.
- **Atteso:** Route corretta; Standard invariato.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### SET-05 — Ripetere installazione e confrontare ID/assegnazioni

- **Procedura:** Registrare ID e assegnazioni, reinstallare stessa versione su staging; confrontare default, parametri, ID e assegnazioni.
- **Atteso:** Nessun duplicato.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### HOME-01 — Articoli, magazine-cover, posizione magazine-cover, 1, immagine e titolo collegato, modulo senza titolo

- **Procedura:** Modulo Articoli, posizione `magazine-cover`, Avanzate → Layout `operaplus:magazine-cover`, numero 1, titolo modulo nascosto, titolo articolo collegato, immagine intro o completa, categoria e data, estratto facoltativo. Usare anche foto chiara e titolo lungo.
- **Atteso:** Una copertina con testo intero e leggibile anche su foto chiara; link corretto..
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### HOME-02 — Articoli, magazine-sidebar, magazine-news, 3, immagini/metadati

- **Procedura:** Modulo Articoli, posizione `magazine-news`, layout `magazine-sidebar`, numero 3, titolo visibile, immagine intro, categoria e data; selezione diversa dalla copertina. Cambiare numero e verificare conteggio.
- **Atteso:** Tre notizie compatte.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### HOME-03 — Articoli, magazine-grid, magazine-featured, 3, colonne 2–4

- **Procedura:** Modulo Articoli, posizione `magazine-featured`, layout `magazine-grid`, numero 3, colonne 3, titolo configurabile. Ripetere con 2 e 4 colonne e immagini differenti.
- **Atteso:** Schede uniformi.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### HOME-04 — Coppie di posizioni featured/reviews/interviews/dossier/insights/latest e -aside

- **Procedura:** Pubblicare moduli in `magazine-featured-aside`, `magazine-reviews` / `magazine-reviews-aside`, `magazine-interviews` / `magazine-interviews-aside`, `magazine-dossier` / `magazine-dossier-aside`, `magazine-insights` / `magazine-insights-aside`, `magazine-latest` / `magazine-latest-aside`. Confrontare le coordinate delle due colonne; disabilitare una riga e una sola colonna.
- **Atteso:** Asse 8+4 comune.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### HOME-05 — Disattivare moduli; mostra componente sì/no

- **Procedura:** Disattivare tutte le posizioni Magazine; riattivarne una. Provare Mostra anche il componente nella homepage Magazine sì/no.
- **Atteso:** Fallback nativo, righe vuote assenti.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### MOD-01 — magazine-list in reviews, magazine-portraits in interviews

- **Procedura:** Duplicare il modulo Articoli, layout `magazine-list` in `magazine-reviews`, `magazine-portraits` in `magazine-interviews`, 3 articoli, immagini verticali e orizzontali. Confrontare dimensioni prima/altre immagini.
- **Atteso:** Immagini uniformi e ritratti verticali.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### MOD-02 — magazine-dossier, magazine-latest

- **Procedura:** Layout `magazine-dossier` in `magazine-dossier`; `magazine-latest` in `magazine-latest`; cambiare categorie, numero e ordine.
- **Atteso:** Filtri, numero e ordinamento nativi.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### MOD-03 — magazine-ranked, magazine-topics

- **Procedura:** Layout `magazine-ranked` in una posizione -aside e `magazine-topics` in altra. Cambiare numero e selezione. Non implica algoritmo ranking.
- **Atteso:** Numerazione/elenco testuale.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### MOD-04 — magazine-related-sidebar in magazine-sidebar

- **Procedura:** Layout `magazine-related-sidebar` in `magazine-sidebar`, 3 articoli, titolo collegato, categoria, data e immagine attivi.
- **Atteso:** Miniatura, titolo, categoria, data.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### MOD-05 — magazine-related-horizontal in main-bottom e magazine-related-compact

- **Procedura:** Layout `magazine-related-horizontal` in `main-bottom` (2–4 colonne), `magazine-related-compact` in `magazine-sidebar`; scegliere filtri nativi e cambiare numero. Correlazione Atlas automatica futura.
- **Atteso:** Griglia ed elenco testuale; nessun algoritmo automatico.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### MOD-06 — Immagini mancanti, disattivare titolo/link/metadati/estratto

- **Procedura:** Provare articolo senza immagine; disattivare singolarmente immagine, link titolo, categoria, autore, data, estratto, titolo; verificare fallback nativo quando titolo assente, nessuna immagine vuota.
- **Atteso:** Opzioni e fallback rispettati.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### MOD-07 — Filtri, categorie vuote, ACL e stato pubblicazione

- **Procedura:** Impostare categorie, tag/filtri, ordine e numero; includere articolo riservato, futuro, scaduto, non pubblicato e categoria vuota. Ripetere come ospite e registrato; confrontare modulo core con stessa configurazione.
- **Atteso:** Comportamento Joomla nativo.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### MOD-08 — Standard, raggruppamento, solo titoli

- **Procedura:** Assegnare lo stesso modulo a Standard; in Magazine attivare raggruppamento e solo titoli; verificare layout core.
- **Atteso:** Fallback core.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### MOD-09 — 1200/768/375 px, titoli lunghi

- **Procedura:** Provare viewport 1200, 768, 375 px; titoli molto lunghi, 2–4 colonne, immagini verticali e mancanti. Nessuno scroll orizzontale.
- **Atteso:** Nessun overflow.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### MOD-10 — Temi, tastiera, foto chiare

- **Procedura:** Provare Magazine chiaro/scuro, copertina su foto luminosa, navigazione Tab/Shift+Tab/Invio. Verificare contrasto e focus.
- **Atteso:** Contrasto e focus.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### CAT-01 — magazine-grid, colonne e leading

- **Procedura:** Articoli → tipo menu Magazine – Griglia, stile Magazine. Impostare numero introduttivi, leading, colonne 1–4; mostrare/nascondere categoria, descrizione, foto, tag, titolo, autore e date.
- **Atteso:** Griglia e metadati.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### CAT-02 — magazine-list, fondo prima voce sì/no

- **Procedura:** Tipo menu Magazine – Elenco; impostare fondo prima voce nello stile su sì/no. Usare immagini di proporzioni diverse e una mancante; confrontare larghezza/altezza delle miniature.
- **Atteso:** Immagini identiche.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### CAT-03 — magazine-dossier e tag

- **Procedura:** Tipo menu Magazine – Dossier; scegliere categoria/tag, ordinamento e numero nativi; verificare collegamenti e sottocategorie.
- **Atteso:** Raccolta nativa.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### CAT-04 — Paginazione, figli, vuoto

- **Procedura:** Usare categoria con più pagine, link aggiuntivi, sottocategorie e categoria vuota. Provare avanti/indietro e contatore.
- **Atteso:** Navigazione nativa.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### CAT-05 — Permessi, Standard e corsi

- **Procedura:** Provare accesso pubblico/registrato e categorie/record riservati o non pubblicati. Aprire categoria normale Standard e corsi; verificare baseline.
- **Atteso:** ACL e baseline.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### ART-01 — magazine-article, titolo/immagine/metadata/tag

- **Procedura:** Articolo singolo Magazine – Articolo o layout alternativo `magazine-article`; titolo, intestazione, autore, categoria, date, tag, foto completa, didascalia, intro separato dal testo completo. Alternare opzioni native.
- **Atteso:** Titoli corretti, intro non duplicato.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### ART-02 — Tempo lettura 200/400 parole/min e off

- **Procedura:** Stile → Contenuti Magazine; attivare velocità 200. Articoli di 200 e 201 parole, parole accentate/apostrofi; ripetere a 400 e disattivare. Provare articolo riservato.
- **Atteso:** Unicode, ceil, nessun tempo per testo riservato.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### ART-03 — magazine-longform, TOC e pagebreak

- **Procedura:** Layout `magazine-longform`, testo lungo, H2/H3, immagini e citazioni, pagebreak/TOC nativo se disponibile. Verificare colonna raccolta e leggibilità.
- **Atteso:** Lettura estesa e indice nativo.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### ART-04 — magazine-interview, modello TinyMCE D/R

- **Procedura:** Layout `magazine-interview`; TinyMCE → Contenuti CMS → Modelli Opera+ → Intervista; inserire, modificare e salvare D/R e citazioni.
- **Atteso:** Domande/risposte e citazioni leggibili.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### ART-05 — magazine-review con campi Joomla pubblicati

- **Procedura:** Layout `magazine-review`; configurare campi personalizzati pubblicati con visualizzazione automatica per verificare pannello nativo. Per la scheda strutturata usare invece REV-01–04.
- **Atteso:** Campi nativi formattati coerentemente; scheda strutturata separata (REV-01–04)..
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### INT-01 — Sei modelli TinyMCE, annullamento, salvataggio

- **Procedura:** Sistema → Plugin: Campi Opera+ Atlas e Modelli Opera+ attivi. In TinyMCE provare Articolo, Intervista, Recensione, Approfondimento, Dossier, Notizia; modificare, annullare e salvare. Confermato dall'utente, ripetere come regressione.
- **Atteso:** Già confermato; regressione.
- **Stato iniziale:** Confermato in tutto o in parte; regressione da eseguire.
- [ ] Esito / data / evidenza / note:

### INT-02 — atlas-entities multiplo, atlas-primary singolo, entrambi facoltativi

- **Procedura:** Contenuti → Campi: `atlas-entities` multiplo e `atlas-primary` singolo, facoltativi, auto-display disabilitato. Selezionare Rigoletto e Verdi, salvare, riaprire e svuotare; confermato, regressione.
- **Atteso:** Già confermato; regressione.
- **Stato iniziale:** Confermato in tutto o in parte; regressione da eseguire.
- [ ] Esito / data / evidenza / note:

### INT-03 — Atlas contestuale in magazine-sidebar, principale e vuoto

- **Procedura:** Modulo Atlas in `magazine-sidebar` o `sidebar-right`, contestuale, campo `atlas-entities`, principale `atlas-primary`, mostra tutte No, nascondi se vuoto Sì, titolo nascosto. Aprire articolo associato e senza associazioni.
- **Atteso:** Entità associata, titolo, ritratto a destra, CTA leggibile; nessun wrapper, margine o sidebar vuota quando non c'è entità valida..
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### INT-04 — Rigoletto/Verdi PD, CC0/CC BY/SA

- **Procedura:** Rigoletto/Verdi; foto CC BY/CC BY-SA se disponibile; aprire crediti anche da tastiera. Provare entità senza immagine, PD/CC0. Verdi confermato.
- **Atteso:** Crediti secondo licenza; Verdi confermato.
- **Stato iniziale:** Confermato in tutto o in parte; regressione da eseguire.
- [ ] Esito / data / evidenza / note:

### INT-05 — CTA chiara/scura/orizzontale/Atlas

- **Procedura:** Modulo CTA in posizione -aside, titolo modulo nascosto, newsletter chiara: titolo Resta aggiornato con Opera+, testo, busta, filigrana e link pulsante. Ripetere scura, orizzontale e Atlas. Chiara confermata.
- **Atteso:** Grafica approvata, niente doppia cornice.
- **Stato iniziale:** Confermato in tutto o in parte; regressione da eseguire.
- [ ] Esito / data / evidenza / note:

### REG-01 — Menu attivo, sottomenu, login e mobile

- **Procedura:** Magazine chiaro/scuro: voci attive, sottomenu clic/tastiera/touch, menu mobile, login/logout/recupero password. Confermato 1.21.2, regressione.
- **Atteso:** Confermato 1.21.2; regressione.
- **Stato iniziale:** Confermato in tutto o in parte; regressione da eseguire.
- [ ] Esito / data / evidenza / note:

### REG-02 — Sidebar vuota, contenuti riservati

- **Procedura:** Disattivare tutti i moduli laterali; poi lasciare Atlas senza associazione. Provare articolo riservato con/senza autenticazione: non esporre dati riservati.
- **Atteso:** Piena larghezza, ACL.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### REG-03 — SEO, plugin, edit, titoli e link

- **Procedura:** Controllare title, canonical se prodotto da Joomla/plugin, OG, titoli H1/H2, tag, plugin, edit frontend autorizzato, stampa se presente, link e paginazione; console e HTTP.
- **Atteso:** Nessuna regressione.
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### REG-04 — Confronto con riferimenti UX-01–15

- **Procedura:** Confrontare desktop/mobile con mockup UX-01–15 applicabili: header, fascia teatro, 8+4, proporzioni, colori, font, CTA, spazi e footer. Usare catalogo versionato e otto proposte HTML; registrare separatamente approvazione e confronto staging.
- **Atteso:** Fedeltà visiva ai mockup originali, senza reinterpretazioni; approvazione esplicita..
- **Stato iniziale:** Da eseguire.
- [ ] Esito / data / evidenza / note:

### ARC-01 — Archivio nativo

- **Procedura:** Creare voce Articoli → Magazine – Archivio, assegnare Magazine, scegliere categorie; preparare articoli archiviati e pubblicati. Impostare filtro titolo, mese, anno, numero.
- **Atteso:** Solo articoli dell'archivio nativo; titoli e filtri corretti, nessuna alterazione dello stato.
- **Stato iniziale:** Da eseguire dopo integrazione 1.24.0.
- [ ] Esito / data / evidenza / note:

### ARC-02 — Filtri e paginazione

- **Procedura:** Combinare titolo, anno e mese; cambiare numero per pagina; navigare avanti/indietro, provare ricerca senza risultati. Ripetere come ospite/registrato con categorie e articoli riservati.
- **Atteso:** Filtri mantenuti, ACL native, risultato vuoto leggibile; nessun articolo riservato.
- **Stato iniziale:** Da eseguire dopo integrazione 1.24.0.
- [ ] Esito / data / evidenza / note:

### ARC-03 — Resa archivio

- **Procedura:** Provare desktop/tablet/mobile, chiaro/scuro, tastiera e Standard.
- **Atteso:** Filtri leggibili, nessun overflow, fallback core Standard.
- **Stato iniziale:** Da eseguire dopo integrazione 1.24.0.
- [ ] Esito / data / evidenza / note:

### AUT-01 — Profilo editoriale

- **Procedura:** Creare contatto pubblico associato a un utente Joomla, nome/ritratto/ruolo/Altre informazioni. Creare voce Contatti → Magazine – Autore. Attivare immagine, posizione, altre informazioni, articoli; impostare numero.
- **Atteso:** Nome, foto, bio e articoli recenti dell'utente; nessun recapito o dato del profilo privato.
- **Stato iniziale:** Da eseguire dopo integrazione 1.24.0.
- [ ] Esito / data / evidenza / note:

### AUT-02 — ACL e identità autore

- **Procedura:** Creare articoli di due utenti, alias autore omonimo, categoria riservata, articolo riservato, futuro, scaduto, non pubblicato e altra lingua. Provare ospite e registrato.
- **Atteso:** Solo articoli pubblicati accessibili, filtrati per ID e lingua; alias non attribuisce articoli all'utente errato.
- **Stato iniziale:** Da eseguire dopo integrazione 1.24.0.
- [ ] Esito / data / evidenza / note:

### AUT-03 — Link e fallback

- **Procedura:** Configurare Contenuti – Contatto e link autore nativi; aprire link da articolo. Provare senza foto, senza articoli, contatto senza utente collegato, disattivare lista e Standard.
- **Atteso:** Route corretta con menu assegnato, fallback senza foto, nessun articolo se utente assente, lista nascosta quando disattivata; Standard nativo.
- **Stato iniziale:** Da eseguire dopo integrazione 1.24.0.
- [ ] Esito / data / evidenza / note:

### REV-01 — Campi spettacolo

- **Procedura:** Creare campi facoltativi Testo con nomi `review-opera`, `review-composer`, `review-theatre`, `review-city`, `review-date`, `review-season`, `review-conductor`, `review-director`, `review-orchestra`, `review-chorus`, `review-cast`, `review-credits` (Area di testo per cast/crediti); auto-display Non visualizzare; solo categorie recensioni. Layout `magazine-review`. Compilare tutti i campi.
- **Atteso:** Scheda nell'ordine previsto; data recita distinta da pubblicazione; cast e crediti leggibili.
- **Stato iniziale:** Da eseguire dopo integrazione 1.24.0.
- [ ] Esito / data / evidenza / note:

### REV-02 — Campi facoltativi e data

- **Procedura:** Svuotare alcuni campi e poi tutti; data ISO valida, non valida, multilinea; attivare auto-display di un campo.
- **Atteso:** Solo valori presenti; scheda assente quando vuota; data non valida omessa; campo con auto-display non duplicato.
- **Stato iniziale:** Da eseguire dopo integrazione 1.24.0.
- [ ] Esito / data / evidenza / note:

### REV-03 — Accesso e sicurezza

- **Procedura:** Impostare un campo e gruppo riservato, articolo riservato e valori con markup. Provare ospite e registrato, controllare sorgente pagina.
- **Atteso:** Nessun dato riservato, valori testuali escape, nessuna esecuzione HTML inserito nei campi.
- **Stato iniziale:** Da eseguire dopo integrazione 1.24.0.
- [ ] Esito / data / evidenza / note:

### REV-04 — Integrazione e layout

- **Procedura:** Associare opera/teatro con campi Atlas esistenti; modulo contestuale. Confrontare chiaro/scuro e mobile con UX-06; provare layout articolo normale e Standard.
- **Atteso:** Scheda e Atlas convivono, data di recita distinta, nessuna scheda negli altri layout; grafica conforme da accettare.
- **Stato iniziale:** Da eseguire dopo integrazione 1.24.0.
- [ ] Esito / data / evidenza / note:

## Chiusura della sessione

- [ ] Accettato
- [ ] Accettato con riserve (issue)
- [ ] Non accettato

Decisione, data e issue aperte:

## Perimetro

Fuori perimetro 2.0.0: archivio generale distinto da quello nativo, pagina autore paginata completa, correlazione automatica Atlas e deduplicazione automatica. Carosello, mosaico, editor, SEO, accessibilità, sicurezza e packaging sono inclusi nei casi sottostanti.

Riferimenti: [PROJECT_PLAN.md](PROJECT_PLAN.md), [REQUIREMENTS.csv](REQUIREMENTS.csv), [GUIDA_STILI.md](../../GUIDA_STILI.md), PR #11–16, [Extensions 1.0.0](https://github.com/jeckodevelopment/operaplus-extensions/releases/tag/v1.0.0).

### MOD-11 — Carosello manuale (1.25.0)

- **Procedura:** Configurare un modulo Articoli `magazine-carousel` con 5 elementi e 2–4 colonne. Verificare clic e tastiera sui pulsanti, scorrimento touch, nessun autoplay, inizio/fine, due istanze sulla stessa pagina; ripetere con JS disattivato e con un solo articolo. Provare tema chiaro/scuro e viewport 375/768/1200 px. Ripetere su Standard e con raggruppamenti.
- **Atteso:** Nessun articolo escluso, controlli localizzati e indipendenti, niente overflow della pagina; fallback core invariato.
- [ ] Esito / data / evidenza / note:

### MOD-12 — Mosaico editoriale (1.25.0)

- **Procedura:** Configurare `magazine-mosaic` con cinque articoli, alcuni senza immagini, titoli lunghi, categorie e date; ripetere con un articolo, lista vuota, Standard, mobile e dark.
- **Atteso:** Prima scheda larga e successive a due colonne su desktop; su mobile una colonna, nessuna card vuota e ordine nativo preservato.
- [ ] Esito / data / evidenza / note:

## Integrazioni — versione 1.26.0

### INT-06 — CSS e formati TinyMCE

- **Procedura:** Aprire il set TinyMCE dei redattori, abilitare CSS template oppure indicare l'URL HTTPS di `media/templates/site/operaplus/css/editor.css`; verificare che il file sia caricato nell'iframe editor. Applicare singolarmente `op-editorial-lead`, `op-interview-question`, `op-interview-answer` dal menu stili a tre paragrafi, salvare, riaprire e verificare il sorgente HTML e la pagina Magazine. Ripetere in articolo Standard per confermare assenza di regressioni; usare anche i sei modelli del plugin.
- **Atteso:** Classi conservate, gerarchia sommario/domanda/risposta coerente, normale editing e nessun cambio automatico al set.
- [ ] Esito / data / evidenza / note:

### INT-07 — Chrome reale di Atlas e CTA

- **Procedura:** Con Extensions 1.0.0 e template RC2, pubblicare Atlas con Mostra titolo sì e Nascondi se vuoto sì in `magazine-sidebar`; visitare pagina senza associazioni, pagina con associazioni valide, pagina con campo non accessibile e pagina non articolo. Ripetere con altro modulo nella stessa sidebar, in posizione `sidebar-right`, con Nascondi se vuoto no e modalità manuale con riferimento valido. Provare CTA Light/Dark/Horizontal in main/aside, anche senza configurazione, con testo lungo e tema chiaro/scuro.
- **Atteso:** Nessun wrapper/titolo/colonna senza output in Magazine; altri moduli restano visibili, modalità manuale e messaggio esplicito conservati. CTA senza cornice doppia in sidebar, contrasto e pulsante integri.
- [ ] Esito / data / evidenza / note:

## Consolidamento SEO, responsive e prestazioni — 1.27.0

### SEO-01 — Metadati e condivisione
- **Procedura:** Verificare sorgente HTML di home, categoria paginata, articolo e articolo con Itemid ereditato, con e senza plugin SEO. Impostare immagini articolo/menu/fallback diverse, titoli e descrizioni, provare articolo riservato, immagini non valide, URL con UTM e filtri/lingua. Verificare anteprima social con immagine 1200×630.
- **Atteso:** Una canonical, OG title/description/type/site_name/url/locale/image quando disponibili; articolo con foto propria, URL assoluti validi, nessun dato privato in pagina ospite. Plugin e robots preservati.
- [ ] Esito / data / evidenza / note:

### PERF-01 — Asset e Core Web Vitals
- **Procedura:** Network su Standard e Magazine, tema chiaro/scuro; verificare CSS Magazine solo nel suo stile, JS defer, modalità font, immagini lazy sotto la piega e priorità copertina. Lighthouse mobile su home/categoria/articolo con cache fredda e rete limitata; registrare LCP, CLS, INP da dati reali quando disponibili, senza attribuire al template costi di moduli terzi.
- **Atteso:** Nessuna nuova libreria, budget asset CI, nessuna query SEO articolo aggiuntiva, niente font remoti in Magazine automatico. Obiettivo CWV LCP ≤2,5 s, CLS ≤0,1, INP ≤200 ms; esiti reali da misurare.
- [ ] Esito / data / evidenza / note:

### RESP-01 — Mobile 320–430 px e zoom
- **Procedura:** Provare 320, 375, 390, 430 px e zoom 200%; titoli lunghi, menu multilivello, login, carousel, tabelle e iframe editoriali; touch, tastiera, dark.
- **Atteso:** Nessuno scorrimento orizzontale della pagina, scorrimento locale per tabelle/caroselli, menu aperto scorrevole, controlli principali 44 px, focus visibile. Verificare sul dispositivo reale.
- [ ] Esito / data / evidenza / note:

## Configurazione e consolidamento — 1.28.0

### CFG-02 — Parametri per stile
- **Procedura:** Duplicare lo stile Magazine di test. Provare 16:10, 16:9, 1:1 su griglia, elenco, categoria, dossier e carosello; overlay classico/forte con immagine chiara e testo lungo; separatori sì/no. Verificare miniature, ritratti e mosaico, titoli, colonne e metadati nativi. Ripetere in tema scuro e a 320 px, verificare che Standard sia identico.
- **Atteso:** Parametri indipendenti per stile, proporzioni uniformi e overlay leggibile; nessuna modifica a foto originali o filtri. Default identico alla 1.27.0.
- [ ] Esito / data / evidenza / note:

### SEC-01 — ACL e pubblicazione
- **Procedura:** Creare articolo corso con testo completo riservato e immagine privata, provare guest con show_noauth sì/no, utente autorizzato, URL diretta con Itemid ereditato; ripetere articolo Magazine, finestre pubblicazione future/scadute e campo Atlas non accessibile. Testare URL immagini non HTTP(S) e parametri di stile non validi in ambiente di prova.
- **Atteso:** Nessuna esposizione del testo completo o della copertina riservata agli ospiti; fallback Joomla; nessuna immagine OG da contenuto non accessibile/non pubblicato; parametri invalidi tornano ai default. Nessun bypass dei controlli nativi.
- [ ] Esito / data / evidenza / note:

### DOC-02/03 — Guide operative
- **Procedura:** Redattore e amministratore seguono le rispettive guide su staging; preparare homepage, articolo, recensione e CTA, verificare permessi, template style, metadati e ritorno a Standard.
- **Atteso:** Guida ripetibile senza modifica dei file core, del default globale o delle assegnazioni già esistenti.
- [ ] Esito / data / evidenza / note:

## Consolidamento tecnico — 1.29.0

### REG-05 — Menu e focus
- **Procedura:** Standard e Magazine, IT/EN, desktop e mobile, hover/click, mega menu, menu laterale. Aprire con tastiera, spostarsi su un link figlio, premere Esc, verificare ritorno al controllo; premere Esc in un campo login, senza spostamento del focus. Disabilitare JS e verificare raggiungibilità dei link mobili. Provare sticky header e skip link, zoom 200%, lettore di schermo e contrasto sul sito reale.
- **Atteso:** Menu navigabili, focus visibile, nessun salto involontario, etichette coerenti con lingua; nessuna regressione dei contenuti Standard. Non confondere test browser con certificazione WCAG.
- [ ] Esito / data / evidenza / note:

### SEC-02 — ACL e URL immagini
- **Procedura:** Catalogo corsi con articoli pubblici e riservati, show_noauth sì/no, utente guest e registrato. Verificare teaser/login nativi e assenza di testo riservato. Recensione con campi pubblici e riservati; provare URL immagine validi, protocollo non ammesso, riferimenti traversal, media field con frammento Joomla. Verificare branding, foto e link in entrambe le modalità.
- **Atteso:** Joomla gestisce il caso non autorizzato; nessun campo recensione fuori dai livelli autorizzati; URL immagine non validi non renderizzati. Contenuti HTML affidati ai filtri e plugin Joomla, senza rimuoverne i meccanismi nativi.
- [ ] Esito / data / evidenza / note:

## Gate Release Candidate 2.0.0-rc.2

### REL-02 — Installazione della candidata e rollback
- **Procedura:** Scaricare lo ZIP interno e SHA-256 dall'[artifact RC3 verificato](https://github.com/jeckodevelopment/tpl_operaplus/actions/runs/45687762495/artifacts/15057396971); verificarne l'integrità prima dell'installazione. Fare backup file e DB, installare come aggiornamento di staging e ripetere SET-01–05. Controllare che gli strumenti CI non siano installati, i file personalizzati non siano rimossi, i due stili, branding, default e menu siano preservati. Registrare versione e commit.
- **Atteso:** ZIP integro; versione 2.0.0-rc.3, nessun cambio dati o assegnazioni. In caso KO ripristinare l'intero backup.
- [ ] Esito / data / evidenza / note:

### REL-03 — Canale stabile e prerelease
- **Procedura:** Verificare Sistema → Aggiorna → Siti di aggiornamento, il feed stabile e il download GitHub. La candidata si installa solo manualmente; il feed non deve proporre RC. Dopo approvazione esplicita della 2.0.0 verificare feed stabile, URL ZIP immutabile, SHA-256 e aggiornamento Joomla su staging prima della produzione.
- **Atteso:** Nessuna RC pubblicata come stabile, nessun deployment automatico.
- [ ] Esito / data / evidenza / note:

### UX — Originali grafici
Per i quindici riferimenti vedere [mockups/README.md](mockups/README.md). Gli screenshot CI sono riproducibili, ma non costituiscono tavole originali approvate. Confronto visivo e approvazione esplicita da registrare per ogni UX-01–15.

### UX — Formalizzazione dei riferimenti recuperati
Il documento [SPECIFICHE.md](mockups/SPECIFICHE.md) elenca i quindici criteri e i riferimenti originali. Prima di registrare approvazione e confronto su staging verificare la presenza dei file PNG in Git e i relativi hash; applicare le decisioni scritte più recenti in caso di discordanza (8+4, tre notizie compatte, immagini uniformi, box approvati). UX-12/13 hanno proposte HTML, non originali; nessuna approvazione presunta.

### UX — Archivio originale versionato
I file delle 13 tavole recuperate sono consultabili nel [catalogo mockup](mockups/README.md); 10 originali conservati integralmente. `tools/verify_mockups.py` controlla l'integrità dei 23 PNG in CI. UX-14/15 formalizzati e approvati graficamente il 24 novembre; il confronto con staging resta da effettuare. Le tavole UX-01–13 richiedono approvazione definitiva; otto proposte integrative sono disponibili. Non confondere archiviazione con collaudo.

### UX — Revisione 01 dei riferimenti di completamento
Aprire `mockups/boards/index.html` offline o scaricare gli screenshot dall'artifact `magazine-design-boards`. Confrontare UX-03/05/08/09/10 a 1200 px, UX-11/12 a 390 e 320 px, UX-13 a 1200 e 390 px. Verificare gerarchia, proporzioni, immagini uniformi, palette, ordine mobile e leggibilità. I prototipi sono specifiche grafiche: non sostituiscono il test funzionale su Joomla. Registrare approvazione o correzioni per ogni tavola e confrontare poi il template reale.

### REG-06 — Canonical privacy e transizioni responsive (RC2)
- **Procedura:** aprire un articolo con query di tracking e token fittizi, verificare che canonical/OG non li includano e preservino parametri di route e paginazione; controllare descrizione multiparagrafo e date nulle. Aprire menu mobile, passare a desktop e tornare a mobile; verificare stato chiuso coerente e carosello ai limiti dopo resize.
- **Atteso:** nessun token del template nei metadati di fallback, plugin SEO preservati, layout invariato; nessuna regressione a 320 px. Misure CWV e verifiche manuali sul sito ancora da effettuare.
- [ ] Esito / data / evidenza / note:

## Casi integrativi — revisione 03

### BASE-INV — Inventario

- **Procedura:** Confrontare BASELINE.md, manifest, override, posizioni, token e struttura attuale.
- **Atteso:** Differenze intenzionali documentate.
- [ ] Esito / data / evidenza / note:

### BASE-T01 — Home Standard

- **Procedura:** Confrontare hero, branding, menu, moduli e footer con baseline pre-aggiornamento.
- **Atteso:** Struttura e stile preservati.
- [ ] Esito / data / evidenza / note:

### BASE-T02 — Categoria e articolo Standard

- **Procedura:** Aprire blog e articolo ordinari; alternare opzioni native, plugin e paginazione.
- **Atteso:** Comportamento nativo preservato.
- [ ] Esito / data / evidenza / note:

### BASE-T03 — Corsi Standard

- **Procedura:** Aprire catalogo alias corsi e dettaglio come ospite e registrato.
- **Atteso:** Copertine, CTA e ACL invariati.
- [ ] Esito / data / evidenza / note:

### BASE-T04 — Sidebar Standard

- **Procedura:** Provare nessuna sidebar, sinistra, destra, entrambe, moduli vuoti, desktop/mobile.
- **Atteso:** Griglia 7+5 ove prevista, nessun margine vuoto inatteso.
- [ ] Esito / data / evidenza / note:

### BASE-T05 — Stili e temi Standard

- **Procedura:** Confrontare parametri, colori, branding, default e assegnazioni prima/dopo.
- **Atteso:** Impostazioni preservate.
- [ ] Esito / data / evidenza / note:

### BASE-T06 — Menu Standard

- **Procedura:** Ripetere REG-05 su Standard, mega menu hover/click, 320/1200 px.
- **Atteso:** Navigazione e focus coerenti.
- [ ] Esito / data / evidenza / note:

### BASE-T07 — Pagine di servizio

- **Procedura:** Aprire login, recupero password, ricerca, messaggi, 404 e layout component; offline solo su staging isolato, ripristinare online.
- **Atteso:** Azioni native e presentazione preservate.
- [ ] Esito / data / evidenza / note:

### BASE-T08 — Branding e SEO Standard

- **Procedura:** Verificare favicon, logo, footer, OG fallback, plugin SEO, canonical, media non validi.
- **Atteso:** Precedenze e fallback rispettati.
- [ ] Esito / data / evidenza / note:

### BASE-T09 — Lingue

- **Procedura:** Provare IT/EN, stringhe e override, menu, carousel, messaggi.
- **Atteso:** Traduzioni e override corretti.
- [ ] Esito / data / evidenza / note:

### BASE-T10 — Aggiornamento 1.x

- **Procedura:** Ripetere SET-01/02/05 e REL-02 su copia staging 1.x; conservare ID, assegnazioni, file personalizzati.
- **Atteso:** Nessuna perdita o duplicazione.
- [ ] Esito / data / evidenza / note:

### INT-08 — Tipi e resilienza Atlas

- **Procedura:** Associare opera, compositore, teatro, festival e produzione; verificare ID e link, stagioni escluse. Simulare feed indisponibile con fixture isolata, cache valida e assente; ripristinare configurazione.
- **Atteso:** ID stabili, tipi ammessi, cache valida o occultamento, nessun errore fatale; non alterare feed pubblico.
- [ ] Esito / data / evidenza / note:

### INT-09 — CTA configurabile

- **Procedura:** Modificare titolo, testo, link, icona, filigrana, variante e campi facoltativi; provare URL non valido.
- **Atteso:** Configurazione rispettata, link sicuro e output coerente.
- [ ] Esito / data / evidenza / note:

### DARK-01 — Copertura dark

- **Procedura:** Provare tutti i layout articolo, categoria, archivio, autore e 14 moduli Articoli, Atlas, CTA e form; focus e contrasto.
- **Atteso:** Testo, link, superfici e controlli leggibili, nessun residuo chiaro incoerente.
- [ ] Esito / data / evidenza / note:

### A11Y-01 — Accessibilità manuale

- **Procedura:** Tastiera, zoom 200%, lettore di schermo disponibile, contrasto testo e controlli, titoli/landmark, alternative immagini, nomi accessibili; tutte le famiglie di layout, form e carousel. Annotare strumenti e limiti.
- **Atteso:** Criteri WCAG AA applicabili verificati; non dedurre certificazione globale dal campione.
- [ ] Esito / data / evidenza / note:

### DOC-01 — Piano e matrice

- **Procedura:** Controllare piano, matrice, tracciabilità, registri e gate rispetto al commit testato.
- **Atteso:** Tutti i 60 ID tracciati, stati distinti.
- [ ] Esito / data / evidenza / note:

### UX-01 — Homepage

- **Procedura:** Aprire UX-01 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare 8+4, copertina overlay, tre notizie compatte, sezioni e fascia teatrale.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-02 — Categoria elenco

- **Procedura:** Aprire UX-02 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Miniature identiche e fondo prima voce opzionale.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-03 — Categoria griglia

- **Procedura:** Aprire UX-03 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Colonne configurabili e immagini uniformi.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-04 — Articolo

- **Procedura:** Aprire UX-04 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Gerarchia titolo/sommario/metadati, foto, corpo e correlati.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-05 — Longform

- **Procedura:** Aprire UX-05 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Misura lettura, immagini, citazioni, indice se disponibile.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-06 — Recensione

- **Procedura:** Aprire UX-06 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Scheda spettacolo facoltativa, gerarchia e Atlas.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-07 — Intervista

- **Procedura:** Aprire UX-07 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Ritratto, D/R, citazioni, correlati.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-08 — Dossier

- **Procedura:** Aprire UX-08 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Copertina, descrizione e raccolta.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-09 — Archivio

- **Procedura:** Aprire UX-09 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Filtri nativi, lista e paginazione.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-10 — Autore

- **Procedura:** Aprire UX-10 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Bio pubblica, ritratto opzionale e articoli autorizzati.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-11 — Homepage mobile

- **Procedura:** Aprire UX-11 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Ordine editoriale, header, sidebar sotto main, 320/390 px.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-12 — Articolo mobile

- **Procedura:** Aprire UX-12 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Reflow, immagini fluide, metadati e focus a 320/390 px.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-13 — Tema scuro

- **Procedura:** Aprire UX-13 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Registrare approvazione del riferimento o correzioni; poi confronto staging. Verificare Palette, contrasto e componenti a 1200/390 px.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-14 — Box Atlas

- **Procedura:** Aprire UX-14 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Approvazione grafica storica; verificare staging. Verificare Fondo caldo, marchio, ritratto a destra e CTA bordeaux.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

### UX-15 — Box CTA

- **Procedura:** Aprire UX-15 dal [catalogo](mockups/README.md) e [specifiche](mockups/SPECIFICHE.md). Approvazione grafica storica; verificare staging. Verificare Fondo rosato, busta, filigrana, varianti e CTA.
- **Atteso:** Riferimento approvato e versionato; staging conforme. Approvazione tavola distinta dal collaudo.
- [ ] Esito / data / evidenza / note:

## Tracciabilità dei requisiti

La colonna `casi_collaudo` della matrice collega i requisiti ai casi; gli ID MOD-* delle procedure non coincidono necessariamente con gli ID requisito. REG-04 integra, non sostituisce, le 15 verifiche UX individuali.

| Requisito | Casi |
|---|---|
| BASE-01 | BASE-INV |
| BASE-02 | BASE-T01, BASE-T02, BASE-T03, BASE-T04, BASE-T05, BASE-T06, BASE-T07, BASE-T08, BASE-T09, BASE-T10, REG-01 |
| UX-01 | UX-01, REG-04 |
| UX-02 | UX-02, REG-04 |
| UX-03 | UX-03, REG-04 |
| UX-04 | UX-04, REG-04 |
| UX-05 | UX-05, REG-04 |
| UX-06 | UX-06, REG-04 |
| UX-07 | UX-07, REG-04 |
| UX-08 | UX-08, REG-04 |
| UX-09 | UX-09, REG-04 |
| UX-10 | UX-10, REG-04 |
| UX-11 | UX-11, REG-04 |
| UX-12 | UX-12, REG-04 |
| UX-13 | UX-13, REG-04 |
| UX-14 | UX-14, REG-04 |
| UX-15 | UX-15, REG-04 |
| MAG-01 | HOME-01, HOME-02, HOME-03, HOME-04, HOME-05, REG-04 |
| MAG-02 | CAT-01, CAT-04, CFG-02 |
| MAG-03 | CAT-02, CFG-02 |
| MAG-04 | ARC-01, ARC-02, ARC-03 |
| MAG-05 | CAT-03, CAT-04 |
| MAG-06 | ART-01, REG-03 |
| MAG-07 | ART-03 |
| MAG-08 | ART-04, INT-06 |
| MAG-09 | ART-05, REV-01, REV-02, REV-03, REV-04 |
| MOD-01 | HOME-01, HOME-02, MOD-06, MOD-07, MOD-08, MOD-10 |
| MOD-02 | MOD-11, REG-06 |
| MOD-03 | HOME-02, MOD-02, MOD-03, MOD-06 |
| MOD-04 | MOD-12 |
| MOD-05 | MOD-02, MOD-07 |
| MOD-06 | MOD-04, MOD-06 |
| MOD-07 | MOD-05, MOD-09 |
| MOD-08 | MOD-05, MOD-06 |
| MOD-09 | INT-05, INT-07, INT-09 |
| MOD-10 | INT-05, INT-07, DARK-01 |
| MOD-11 | INT-05, INT-07, INT-09 |
| MOD-12 | HOME-03, HOME-04, MOD-01, MOD-02, MOD-03, MOD-07, MOD-08, MOD-09 |
| EDIT-01 | INT-06, ART-04 |
| EDIT-02 | INT-01, ART-04 |
| ATLAS-01 | INT-03, INT-04, UX-14 |
| ATLAS-02 | INT-02, INT-08 |
| ATLAS-03 | INT-03, INT-04, INT-08 |
| CTA-01 | INT-05, INT-07, INT-09, UX-15 |
| CFG-01 | ART-02 |
| CFG-02 | CFG-02, HOME-03, CAT-01, MOD-09 |
| RESP-01 | RESP-01, MOD-09, REG-06, HOME-04 |
| DARK-01 | DARK-01, MOD-10, ARC-03, REV-04 |
| SEO-01 | SEO-01, REG-03, REG-06, BASE-T08 |
| A11Y-01 | A11Y-01, REG-05, MOD-11, RESP-01 |
| PERF-01 | PERF-01, REG-06, INT-08 |
| SEC-01 | SEC-01, SEC-02, MOD-07, CAT-05, AUT-02, REV-03, REG-06 |
| REL-01 | REL-02, REL-03 |
| DOC-01 | DOC-01, BASE-INV |
| DOC-02 | DOC-02/03 |
| DOC-03 | DOC-02/03 |
| ATLAS-04 | INT-03, INT-07, REG-02 |
| BASE-03 | SET-01, SET-02, SET-05, REL-02 |
| MAG-10 | SET-03, SET-04, HOME-04, HOME-05 |
| MAG-11 | AUT-01, AUT-02, AUT-03 |
