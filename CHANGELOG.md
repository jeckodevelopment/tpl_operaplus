# Changelog — Opera+ per Joomla 6

Cronologia delle modifiche al template, dalla versione più recente alla meno recente.

Le date sono riportate solo quando documentate con certezza. Per installazione e utilizzo consulta il [README](README.md) e la [guida agli stili](GUIDA_STILI.md).

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
