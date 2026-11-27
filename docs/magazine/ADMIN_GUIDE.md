# Guida amministratore — Magazine (1.28.0)

## Installazione e stili
1. Eseguire backup database e file, acquisire ZIP della versione dal workflow di qualità; installare come aggiornamento in staging, senza disinstallare il template. Installare il pacchetto Opera+ Extensions 1.0.0 separatamente, se non presente.
2. Sistema → Stili template sito: conservare Standard e Magazine (creato automaticamente). Non cambiare il default globale. Assegnare Magazine soltanto alle voci di menu editoriali e ai menu interni pertinenti. Configurare menu homepage Magazine, fascia, branding e tema. Usare un menu dedicato; un articolo con Itemid ereditato non deve essere trattato come homepage.
3. In Aspetto dello stile Magazine (senza selettore di modalità), impostare: rapporto immagini 16:10 / 16:9 / 1:1; overlay classico / forte; separatori sì/no; tempo lettura e velocità; tinta prima scheda elenco. I default mantengono la grafica approvata. Per metadati, colonne, quantità, selezione e ordinamento usare i parametri nativi dei moduli e del menu.
4. In Aspetto scegliere font automatici (Magazine senza richieste a Google, Standard invariato), sistema o Manrope. Assegnare il tema chiaro/scuro allo stile senza cambiare il tema istituzionale.

## Composizione homepage
| Posizione | Layout suggerito | Configurazione iniziale |
|---|---|---|
| magazine-cover | magazine-cover | 1 articolo, titolo modulo nascosto |
| magazine-news | magazine-sidebar | 3 articoli, miniature |
| magazine-featured | magazine-grid | 3 articoli |
| magazine-reviews | magazine-list | 3 articoli |
| magazine-interviews | magazine-portraits | 3 articoli |
| magazine-dossier | magazine-dossier | 2–3 articoli |
| magazine-insights | magazine-mosaic o magazine-carousel | numero a scelta |
| magazine-latest | magazine-latest | ordinamento data decrescente |

Le posizioni -aside abbinano moduli CTA, Articoli o Atlas. L'asse 8+4 resta comune alle righe; su mobile i contenuti si impilano. Le pagine interne usano magazine-sidebar e sidebar-right; main-bottom ospita correlati o newsletter orizzontale. I moduli Articoli usano selezioni, ACL, pubblicazione e lingua native. Non creare categorie fittizie; usare quelle editoriali reali.

## Permessi e integrazioni
- Configurare gruppi Joomla e livelli accesso. Non concedere permessi globali ai redattori solo per usare il layout.
- Installare le Extensions; abilitare plugin fields e editors-xtd. Creare atlas-entities multiplo, atlas-primary singolo facoltativo e i campi review-* documentati nella guida stili; auto-display disabilitato e accesso coerente. Per Atlas contestuale impostare hide_empty sì.
- CTA: varianti light, dark, horizontal; inserire link reale di iscrizione. Il modulo non raccoglie email.
- TinyMCE: consentire pulsanti CMS nel set, attivare CSS del template o URL HTTPS di media/templates/site/operaplus/css/editor.css. Non disattivare filtri Joomla. Vedi guida redattore.

## SEO, performance e collaudo
Impostare descrizioni menu/articoli e immagine OG predefinita (preferibilmente 1200×630, ottimizzata). La foto articolo prevale sui fallback; plugin SEO via API documento conservati. Impostare URL e canonical coerenti in Joomla/plugin SEO; il fallback template rimuove soltanto tracking noto, non normalizza tutte le route equivalenti. Usare immagini già ottimizzate, cache Joomla secondo compatibilità dei moduli e compressione HTTP del server. Il template non implementa una cache parallela.

Eseguire [COLLAUDO.md](COLLAUDO.md), con particolare attenzione a mobile 320–430 px, zoom, contrasto, navigation, login, ACL, Open Graph e Core Web Vitals. Registrare esiti ed evidenze, senza confondere CI con accettazione manuale. Il passaggio a produzione e il tag stabile restano subordinati al collaudo. In caso di problemi riattivare le assegnazioni Standard o ripristinare il backup verificato; non disinstallare il template attivo.

## Doppia navigazione Magazine

- Creare un menu Joomla **Ecosistema** con Home, Corsi (quando disponibile), Atlas e Osservatorio. Usare Alias voce di menu per le destinazioni Joomla canoniche e URL per le applicazioni autonome, nella stessa scheda.
- Pubblicare un modulo Menu nativo in `magazine-topnav`, titolo nascosto, solo primo livello, assegnato a tutte le pagine Magazine. Il menu editoriale continua a usare `menu`; assegnare il menu istituzionale esclusivamente alle pagine Standard. Impostare la voce homepage Magazine in Aspetto per il link del logo.
- Desktop: collegamenti compatti in alto a destra; mobile (fino a 900 px): un solo hamburger con gruppo editoriale seguito dal gruppo ecosistema. Nessun menu aggiuntivo per mobile. Se il modulo non produce contenuto la posizione scompare. Standard non renderizza la posizione.
