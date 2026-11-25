# Piano e registro di collaudo — Opera+ Magazine

**Fonte ufficiale:** questo file Markdown versionato, mantenuto con ogni PR. Sostituisce il Word della revisione 01; nessun documento parallelo. Revisione 02 — 25 novembre 2026. Baseline Extensions 1.0.0, template 1.23.0 (PR #15); nuovi casi 1.24.0 (PR #16).

Le CI verificano il comportamento tecnico, non equivalgono all'accettazione manuale o grafica. I collaudi intermedi sono rinviati su richiesta dell'utente. Confermati: installazione Extensions 1.0.0, plugin/campi, box Atlas e CTA, ritratto Verdi senza credito, stile Magazine distinto e correzione menu/login 1.21.2. Ripetere come regressione.

## Dati della sessione

- Collaudatore, data, ambiente di staging:
- Versioni Joomla/PHP, template/commit, estensioni:
- Browser, dispositivo e viewport:
- Esito: OK / KO / N.A. (motivato) / Bloccato; per ogni caso registrare data, URL, evidenza e issue. Non inserire credenziali o dati personali nel repository pubblico.

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

### REG-04 — Confronto con mockup originali UX-01–08, UX-11–15

- **Procedura:** Confrontare desktop/mobile con mockup UX-01–15 applicabili: header, fascia teatro, 8+4, proporzioni, colori, font, CTA, spazi e footer. Tavole definitive non ancora versionate: usare originali approvati e screenshot comparativi.
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

## Requisiti successivi

Archivio generale degli articoli pubblicati (distinto dall'archivio nativo), eventuale pagina autore paginata completa, carosello, mosaico, CSS TinyMCE, correlazione automatica Atlas, deduplicazione, audit baseline, sicurezza, accessibilità, SEO, prestazioni, responsive, dark, packaging e rilascio stabile. Aggiornare questi casi quando implementati. Le tavole definitive restano da versionare (#2).

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

- **Procedura:** Con Extensions 1.0.0 e template 1.26.0, pubblicare Atlas con Mostra titolo sì e Nascondi se vuoto sì in `magazine-sidebar`; visitare pagina senza associazioni, pagina con associazioni valide, pagina con campo non accessibile e pagina non articolo. Ripetere con altro modulo nella stessa sidebar, in posizione `sidebar-right`, con Nascondi se vuoto no e modalità manuale con riferimento valido. Provare CTA Light/Dark/Horizontal in main/aside, anche senza configurazione, con testo lungo e tema chiaro/scuro.
- **Atteso:** Nessun wrapper/titolo/colonna senza output in Magazine; altri moduli restano visibili, modalità manuale e messaggio esplicito conservati. CTA senza cornice doppia in sidebar, contrasto e pulsante integri.
- [ ] Esito / data / evidenza / note:
