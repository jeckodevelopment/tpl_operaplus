# Changelog — Opera+ per Joomla 6

Cronologia delle modifiche al template, dalla versione più recente alla meno recente.

Le date sono riportate solo quando documentate con certezza. Per installazione e utilizzo consulta il [README](README.md) e la [guida agli stili](GUIDA_STILI.md).

## [2.0.0-rc.12] — 2026-11-26 (ricollaudo grafico 7B)

- Breadcrumb dopo titolo e sottotitolo, quando presenti. Byline Magazine esplicita con autore, data senza prefissi e tempo di lettura, separatori e filetto; rispetto delle opzioni Joomla e fallback nativo Standard. Archivio, longform e scheda spettacolo invariati.

## [2.0.0-rc.11] — 2026-11-26 (collaudo grafico 7B)

- Breadcrumb nativo nella fascia sfumata anche senza intestazione; metadati e tempo di lettura in byline editoriale; scheda spettacolo a fine testo; archivio con immagini, lista e filtri coerenti. Longform e Standard invariati. Ricollaudo mirato richiesto, issue #60.

## [2.0.0-rc.10] — 2026-11-26 (estensione fascia editoriale)

- Pagine editoriali interne: intestazione nativa Joomla come fascia sfumata. Titolo nativo, immagine generale o personalizzata, sottotitolo e altezza via plugin facoltativo Extensions 1.0.2. Homepage, Standard, corsi e Itemid ereditati invariati.

## [2.0.0-rc.9] — 2026-11-26 (collaudo 5B)

- Recensione Magazine: scheda spettacolo dopo l'immagine completa, prima del testo. In assenza di immagine resta all'inizio del corpo. Eventi Joomla, testo originale, tempo di lettura e controlli ACL preservati. BUG-016: conferma grafica mirata richiesta.

## [2.0.0-rc.8] — 2026-11-26 (collaudo 5A)

- Magazine: contrasto Leggi tutto nativo, marcatori editoriali D/R e gerarchia dell'intervista anche nell'editor, campi automatici Joomla della recensione con etichette e valori leggibili; nelle categorie, Teatro e altri campi diventano metadati compatti senza punti elenco. Nessun cambiamento alla navigazione longform, ai campi strutturati o allo stile Standard. BUG-013–015: ricollaudo manuale richiesto.

## [2.0.0-rc.7] — 2026-11-26 (collaudo 4C)

- Scheda autore Magazine: opzione articoli esplicita della voce di menu, recupero tramite modello Contenuti con parametri globali isolati e collegamenti nativi costruiti dall'identità dell'articolo. Conservati filtri autore, ACL, stato, lingua e pubblicazione; profilo grafico invariato. BUG-012 in ricollaudo manuale.

## [2.0.0-rc.6] — 2026-11-26 (ricollaudo 4A)

- Navigazione precedente/successivo del singolo articolo Magazine: testo bianco su bordeaux, frecce indipendenti dal font delle icone, focus visibile e disposizione responsive; paginazione categorie invariata. Test browser chiaro/scuro, link lunghi, tastiera e viewport mobile. Ricollaudo manuale CAT-04 necessario.

## [2.0.0-rc.5] — 2026-11-26 (collaudo 4A)

- Griglia, elenco e dossier Magazine rispettano la scelta del testo introduttivo; metadati editoriali compatti con opzioni e link nativi preservati.
- Paginazione Magazine: numero corrente bianco su bordeaux, controlli separati e icone indipendenti dal font, anche in tema scuro. Standard e corsi conservano il fallback.
- BUG-009–011: ricollaudo manuale CAT-01–04 necessario; nessuna pubblicazione stabile.

## [2.0.0-rc.4] — 2026-11-25 (collaudo 1C)

- Tradotte integralmente le etichette, le opzioni, le descrizioni e la documentazione integrata dell'editor stili in italiano e inglese; corretti i testi italiani residui nel catalogo inglese (BUG-007). Valori dei parametri e layout invariati. Controlli statici e caricamento nativo dei cataloghi in CI.

## [2.0.0-rc.3] — 2026-11-25 (collaudo 1A)

- Data di release coerente e verificata dalla CI; descrizione tradotta anche nella modifica degli stili, anteprima e miniatura backend dai riferimenti editoriali versionati.
- Rimosso il selettore di modalità e i tab editoriali globali; opzioni Magazine in Aspetto solo per lo stile Magazine. Il filtro del campo tecnico conserva il tipo già registrato, senza dipendere dal titolo.
- Registrati i rilievi manuali RC1/RC2; ricollaudo RC3 necessario, nessun rilascio stabile o deploy.

## [2.0.0-rc.2] — Audit trasversale

- Canonical senza parametri sensibili e token Joomla; route e paginazione conservate. Descrizioni con separazione dei blocchi, media URL più robusti, date legacy non valorizzate.
- Eliminato helper inutilizzato; riuso URL base, menu e parametri dei moduli. Allineato fallback H1 al manifest.
- Ridotte ricerche DOM dei sottomenu e letture dei mega menu; reset responsivo dello stato mobile, aggiornamento caroselli accorpato per frame. Test SEO e browser aggiuntivi.
- Nessuna modifica alle tavole approvate o al canale stabile.

## [2.0.0-rc.1] — Candidata al rilascio (staging)

- ZIP riproducibile, SHA-256 e verifica contenuto; nessuno script di sviluppo nella nuova installazione.
- Migrazione 1.29.0: rimozione dei soli script di test precedentemente installati, conservazione di file personalizzati e stili.
- Canale aggiornamento stabile distinto e inizialmente vuoto; prerelease GitHub con due suite CI obbligatorie, senza pubblicazione stabile o deploy.

## [1.29.0] — Retrocompatibilità, accessibilità e sicurezza

- Escape contestuale, ripristino del focus, menu mobile utilizzabile senza JavaScript, controlli tradotti IT/EN e skip link visibile anche con header sticky.
- Ridotto il lavoro del posizionatore mega menu: nessun listener se non ci sono pannelli; resize/scroll accorpati per frame.
- Validazione uniforme degli URL immagini, fallback nativo del catalogo corsi per utenti non autorizzati, ACL esplicita dei campi recensione e nomi accessibili dei link Leggi tutto.
- Test automatici browser e Joomla reale; registro di regressione e collaudo manuale aggiornati.

## [1.28.0] — Configurazione editoriale e consolidamento

- Proporzioni 16:10, 16:9 o 1:1 per le immagini editoriali, overlay classico o più marcato e separatori configurabili; default invariati.
- Corretto il fallback dei corsi non accessibili al layout nativo Joomla, validazione URL copertina e finestre di pubblicazione dei metadati articolo.
- Evitato il doppio calcolo SEO degli articoli Magazine; riconoscimento homepage basato sulla route reale anche in Standard.
- Budget degli asset più stringenti, test configurazione e guide amministratore/redattore.

## [1.27.0] — SEO, mobile e prestazioni

- Open Graph e Twitter Card tramite API documento Joomla; precedenza immagine articolo, poi menu e fallback, rispetto dei metadati esistenti.
- Eliminata la query SQL dell'immagine articolo; URL validati e canonical esistente conservato oppure fallback con soli parametri di tracking rimossi.
- Font di sistema predefiniti nel Magazine, opzione Manrope e Standard invariato in modalità automatica. Nessuna nuova libreria.
- Reflow a 320 px, controlli touch, menu mobile scorrevole, contenuti multimediali e tabelle contenuti localmente; test e budget asset.

## [1.26.0] — Integrazioni editoriali Magazine

- CSS di contenuto TinyMCE con classi selezionabili per sommario, domanda e risposta; disponibile anche nella variante `.min.css` richiesta da Joomla.
- Riuso delle Extensions 1.0.0 per newsletter, CTA, associazioni Atlas e sei modelli HTML: nessun modulo o plugin duplicato.
- Collaudo nativo con il pacchetto Extensions fissato al commit stabile: wrapper e sidebar vuoti, fallback manuale, tre CTA e file CSS dell'editor.
- Riconciliazione matrice e registro collaudi, senza dichiarare concluso il collaudo visuale manuale.

## [1.25.0] — Carosello e mosaico Magazine

- Due layout alternativi nativi `magazine-carousel` e `magazine-mosaic`, con selezione e filtri Joomla invariati.
- Carosello a scorrimento manuale, pulsanti accessibili, nessun autoplay e fallback utilizzabile senza JavaScript; mosaico con prima scheda a tutta larghezza.
- Test di rendering, fallback e browser su desktop, tablet e mobile, chiaro e scuro.

## [1.24.0] — Archivio, autore e recensioni

- Layout Magazine per archivio nativo Joomla (articoli archiviati), ricerca, filtri mensili/annuali e paginazione.
- Layout autore basato sui contatti Joomla collegati a utenti: biografia pubblica, ritratto, ruolo e articoli recenti filtrati dal modello nativo. Nessuna esposizione dei dati privati del profilo.
- Scheda spettacolo con dodici campi personalizzati facoltativi, data della recita distinta dalla pubblicazione e rispetto dei livelli di accesso.
- Il piano di collaudo Markdown nel repository diventa il registro ufficiale.

## [1.23.0] — Pagine editoriali Magazine

- Layout alternativi nativi per categorie griglia, elenco e dossier; articolo, approfondimento, intervista e recensione. Riuso del rendering Joomla, metadati, plugin, paginazione e ACL.
- Tempo di lettura Unicode configurabile (200 parole/minuto), solo per articoli accessibili. Tipografia editoriale e classi dei modelli TinyMCE.
- Gli override dei corsi e la modalità Standard sono conservati. Scheda spettacolo dedicata, archivio e pagina autore restano da completare.

## [1.22.0] — Layout moduli Magazine

- Dodici layout alternativi per mod_articles: copertina overlay, notizie laterali, griglia, ultimi articoli, elenco, ritratti, dossier, numerato, temi e tre presentazioni di contenuti correlati selezionati con filtri nativi.
- Nomi con trattino per compatibilità con Joomla 6. Fallback core su Standard, raggruppamenti e solo titoli.
- Test di rendering e responsive, senza modificare i moduli ordinari.

## [1.21.2] — Correzioni Magazine

- Riutilizzati CSS e controller nativi del template per menu laterali e moduli login nelle colonne Magazine.
- Ripristinato il contrasto di voci attive, sottomenu e controlli dell’header chiaro/scuro anche dopo il wrapping JavaScript.
- Uniformati i titoli del menu superiore; mantenuti i box Atlas/CTA senza doppia cornice.
- Test browser con markup dei moduli Joomla, stati attivi e sottomenu, login e modalità responsive.

## [1.21.1] — Anteprima Magazine

- L'installer crea automaticamente uno stile nativo Joomla separato per Magazine, mantenendo gli stili Standard e quelli Magazine già esistenti.
- Nessuna modifica al default del sito, alle assegnazioni menu o ai parametri salvati; le nuove installazioni e gli aggiornamenti ripetuti non duplicano gli stili.
- Collaudo nativo di installazione e aggiornamento Joomla 6.

## [1.21.0] — Anteprima di sviluppo Magazine 2.0.0

- Modalità Standard/Magazine a livello di stile Joomla, Standard predefinita.
- Homepage Magazine indipendente dalla home istituzionale, fascia editoriale configurabile e griglia 8+4 con posizioni dedicate.
- CSS Magazine condizionale; sidebar del magazine determinata dal contenuto effettivamente renderizzato.
- Nessuna modifica agli override di articoli/corsi/categorie esistenti. Accettazione visiva definitiva e nuovi layout moduli nella milestone 2.0.0.

## [1.20.3] — 2026-11-25

- Rimossa la cornice generica dei moduli solo quando contiene un box editoriale Opera+ CTA o Atlas.
- Esclusi i pulsanti editoriali e i crediti Atlas dal colore forzato dei link della sidebar.
- Invariato il chrome degli altri moduli Joomla.
- Aggiunto collaudo browser del chrome e generazione automatica dello ZIP; rimosse dal manifest due immagini di anteprima non presenti nel repository.

## [1.20.2] — 2026-10-29

- Corretta la formattazione degli alert di sistema Joomla (successo, errore, avviso e informazione).
- Eliminati i riquadri annidati e migliorato il pulsante di chiusura senza modificare il componente Joomla.

## [1.20.1] — 2026-10-29

- Corretto il pacchetto installabile includendo la cartella `tools/` dichiarata nel manifest.
- Verificata la corrispondenza tra file e cartelle indicati nel manifest e contenuto dello ZIP.

## [1.20.0] — 2026-10-29

- Impostata a **48 px** la dimensione massima predefinita dell'H1 del contenuto, indipendente dall'H1 dell'hero.
- Riorganizzata la scheda **Documentazione** del backend per funzionalità, eliminando le note di versione.
- Riscritta `GUIDA_STILI.md` come guida operativa alle classi CSS pubbliche e alle impostazioni Joomla.

## [1.19.0] — 2026-10-29

- Riorganizzato il README e separato il changelog dalla documentazione d'uso.
- Migliorati gli identificativi DOM e rimossi assegnamenti ridondanti nel JavaScript dei menu laterali.
- Aggiunto uno script di controllo statico per manifest, traduzioni, asset e pacchetto.
- Privilegiate modifiche conservative per ridurre il rischio di regressioni grafiche.

## [1.18.1] — 2026-10-28

- Corretto l'accesso al database Joomla per recuperare l'immagine Open Graph degli articoli.
- Gestite le eccezioni del recupero metadati senza bloccare la visualizzazione delle pagine.

## [1.18.0] — 2026-10-28

- Aggiunto nella scheda **Branding** il campo favicon.
- Aggiunta l'immagine Open Graph predefinita, come fallback rispetto alle immagini specifiche di pagine e articoli.

## [1.17.0]

- Riunita la documentazione del backend in un'unica scheda **Documentazione**.
- Rinominate le schede delle impostazioni in **Titoli** e **Navigazione**.
- Aggiornati descrizione, autore e sito dell'autore del template.
- Aggiunti i file PNG per l'anteprima del template nell'amministrazione.

## [1.16.0]

- Ripristinata la stringa lingua `TPL_OPERAPLUS_DISCOVER_COURSE` per il collegamento al dettaglio del corso.
- Documentate le costanti di traduzione personalizzabili.
- Ridotta la spaziatura superiore delle pagine interne.

## [1.15.0]

- Corretti i contrasti dell'hero, delle superfici chiare e del modulo Login.
- Mostrate le copertine introduttive dei corsi senza ritaglio.
- Compattate ulteriormente le voci dei sottomenu laterali.
- Utilizzata temporaneamente la stringa nativa Joomla per il collegamento al corso; scelta rivista nella 1.16.0.

## [1.14.0]

- Rimossa l'etichetta editoriale fissa dal dettaglio del corso: il nome della categoria è ricavato da Joomla.
- Portate le etichette dell'interfaccia nei file lingua.
- Riviste proporzioni e spaziature del catalogo corsi.
- Uniformato l'allineamento delle voci della navigazione orizzontale.

## [1.13.0]

- Introdotto l'override del singolo articolo per la categoria con alias `corsi`, utilizzando titolo, autore alias, immagini e tag Joomla.
- Corretto il contrasto dei moduli nelle sidebar con tema chiaro.
- Rivisto il posizionamento del mega menu.
- Raffinati gli stili del pre-footer e dei componenti Joomla.

## [1.12.0]

- Adottato il tema chiaro come impostazione predefinita del contenuto.
- Aggiornati pre-footer, form e spaziature delle pagine interne.
- Ritoccato il posizionamento del mega menu.

## [1.11.0]

- Introdotta la variante cromatica chiara assegnabile tramite gli stili Joomla.
- Creato l'override del blog di categoria `corsi` con copertina, titolo, autore, tag e collegamento.
- Stilizzati breadcrumb, messaggi Joomla e modulo pre-footer.
- Migliorato il contenimento del mega menu nella finestra.

## [1.10.0]

- Compattati i menu delle sidebar e i livelli annidati.
- Aggiunti margini di sicurezza nel posizionamento del mega menu.
- Aggiornata la guida del backend e avviato il versionamento dei sorgenti su GitHub.

## [1.9.0]

- Sostituito il simbolo di espansione del menu orizzontale con un chevron.
- Ridotte le distanze verticali nei menu laterali.
- Migliorata la navigazione con livelli espandibili e ramo attivo.
- Rimossi i controlli decorativi superflui accanto alla password nel modulo Login.

## [1.8.0]

- Migliorata la gestione del campo password nel modulo Login.
- Introdotti controlli separati per espandere i livelli del menu laterale, mantenendo navigabili i collegamenti padre.

## [1.7.0]

- Corretto il posizionamento del mega menu.
- Migliorata la leggibilità del modulo Login e della checkbox “Remember me”.
- Riorganizzata la guida operativa nel backend.

## [1.6.0]

- Aggiunti dropdown multilivello e mega menu a colonne.
- Migliorata la tipografia dei corsi e aggiornata la guida del backend.

## [1.5.0]

- Migliorati menu orizzontale, Login e pulsanti.
- Resa configurabile la dimensione dei titoli H1–H5 e dell'hero.
- Introdotta la guida agli stili nelle impostazioni del template.

## [1.4.0]

- Adottato il contenitore Joomla `html5` per i moduli delle sidebar.
- Uniformati gli stili dei moduli nativi Menu, Login, Ricerca e delle form.
- Aggiunti componenti editoriali riutilizzabili per corsi, lezioni e contenuti.
- Confermato che il template non crea automaticamente articoli o moduli Joomla.
