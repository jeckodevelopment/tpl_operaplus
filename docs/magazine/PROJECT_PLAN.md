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
