# Opera+ — Template per Joomla 6

Template frontend di **Opera+**, il progetto di divulgazione dell'opera lirica disponibile su [opera.plus](https://opera.plus/). Lo sviluppo è curato da [Jecko Development](https://jeckodevelopment.com).

**Versione:** 1.18.1 · **Piattaforma:** Joomla 6 · **Stato:** sviluppo e verifica su ambiente di prova.

## Caratteristiche

- Grafica editoriale coordinata con opera.plus: bordeaux, avorio e antracite, tipografia Georgia e Manrope.
- Layout responsive, navigazione multilivello, sidebar e moduli Joomla nativi.
- Gestione tramite stili del template: branding, favicon, immagine Open Graph fallback, colori, titoli e navigazione.
- Override Joomla per blog della categoria `corsi` e dettaglio corso (immagini, autore alias, tag e titolo nativi).
- Stringhe traducibili Joomla, documentazione integrata nel backend e guida delle classi CSS.

## Installazione e configurazione

1. Genera o scarica il pacchetto installabile del template e installalo in **Sistema → Installa → Estensioni**.
2. Imposta Opera+ come stile predefinito oppure duplica lo stile e assegnalo alle voci di menu desiderate.
3. Configura logo, favicon e immagine Open Graph nella scheda **Branding**.
4. Assegna i moduli alle posizioni (ad esempio `menu`, `hero`, `sidebar-right`, `pre-footer`).
5. Per i corsi, crea una categoria con alias `corsi` e una voce di menu **Blog di categoria**. Usa i campi nativi degli articoli per immagini, autore alias e tag.

> Installazione e funzionamento vanno sempre verificati su Joomla 6 in staging prima della pubblicazione in produzione. Le immagini di anteprima del backend non influiscono sul frontend.

## Struttura del progetto

| Percorso | Funzione |
|---|---|
| `templateDetails.xml` | Manifest, posizioni e opzioni del template |
| `index.php` | Layout principale e metadati |
| `component.php`, `error.php`, `offline.php` | Layout specializzati |
| `media/css/`, `media/js/` | Stili e JavaScript |
| `html/com_content/` | Override dei layout nativi Joomla |
| `language/it-IT/`, `language/en-GB/` | Traduzioni |
| `GUIDA_STILI.md` | Uso di posizioni, classi e stringhe |
| `CHANGELOG.md` | Cronologia versioni |

## Standard di sviluppo

- **Joomla-first:** preferire API, layout, Web Asset Manager, configurazioni, moduli, stringhe lingua e override nativi prima di introdurre funzionalità proprietarie.
- **Qualità e sicurezza:** rispettare le convenzioni Joomla/PHP; validare dati in ingresso, eseguire escaping contestuale in uscita, usare API database/query parametrizzate e considerare autorizzazioni e token CSRF quando pertinenti.
- **Manutenibilità:** separare responsabilità, evitare testi fissi, duplicazioni CSS/JS e personalizzazioni dirette dei file core Joomla.
- **Prestazioni:** caricare soltanto asset necessari, contenere dipendenze e JavaScript, ottimizzare immagini e ridurre query ridondanti. La misurazione delle prestazioni va effettuata su pagine reali.
- **Accessibilità:** mantenere contrasto, focus da tastiera, semantica HTML e navigazione utilizzabile su dispositivi mobili.
- **Documentazione:** ogni nuova classe, opzione o stringa deve comparire nella guida backend e in `GUIDA_STILI.md`; ogni modifica deve essere riportata in `CHANGELOG.md`.
- **Release:** allineare versione nel manifest e negli asset, verificare sintassi PHP/JSON/XML e produrre ZIP installabile sincronizzato ai sorgenti GitHub. Testare in staging.

## Documentazione e cronologia

- [Guida agli stili e alle stringhe](GUIDA_STILI.md)
- [Changelog](CHANGELOG.md)

## Licenza e autore

Autore: **Jecko Development** — https://jeckodevelopment.com. Il manifest riporta **GNU GPL v2 o successiva**. Verificare separatamente diritti e licenze degli eventuali asset grafici incorporati.
