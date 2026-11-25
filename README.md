# Opera+ — Template per Joomla 6

Template responsive per il sito di **Opera+**, progetto di divulgazione dell’opera lirica. Sviluppato da [Jecko Development](https://jeckodevelopment.com).

**Versione:** 2.0.0-rc.3 (Release Candidate, solo staging) · **Piattaforma:** Joomla 6 · **Ambiente di riferimento:** staging.

## Funzionalità

- Branding configurabile, favicon e immagine Open Graph di fallback.
- Layout editoriale chiaro/scuro, hero e moduli Joomla nelle posizioni del template.
- Navigazione multilivello e mega menu con gestione da tastiera.
- Override nativi `com_content` per il catalogo e i dettagli dei corsi (categoria con alias `corsi`).
- Stringhe lingua traducibili attraverso le sostituzioni lingua di Joomla.
- Documentazione di moduli e classi direttamente nelle impostazioni del template.

## Installazione e configurazione

1. In Joomla vai in **Sistema → Installa → Estensioni** e installa il file ZIP della release.
2. Configura separatamente gli stili nativi Standard e Magazine; il tipo non è modificabile da un selettore. Le opzioni editoriali appaiono in Aspetto solo nello stile Magazine. Schede comuni: **Branding**, **Aspetto**, **Titoli**, **Navigazione** e **Documentazione**.
3. Pubblica i moduli nelle posizioni previste (`menu`, `hero`, `sidebar-left`, `sidebar-right`, `pre-footer`, ecc.).
4. Per il catalogo corsi usa una voce di menu **Blog di categoria** collegata alla categoria Joomla con alias `corsi`.
5. Per ogni corso usa un articolo Joomla: titolo, autore alias (compositore), immagine introduttiva, tag e testo.

Prima di pubblicare in produzione, verifica sempre la release nell'ambiente Joomla di prova.

## Struttura

| Percorso | Scopo |
|---|---|
| `templateDetails.xml` | Manifest, moduli e parametri dello stile |
| `index.php` | Shell HTML, asset e metadati |
| `html/com_content/` | Override degli articoli e del blog di categoria |
| `media/css/template.css` | Stili del tema |
| `media/js/template.js` | Comportamenti della navigazione |
| `language/` | Stringhe traducibili it-IT ed en-GB |
| `GUIDA_STILI.md` | Posizioni, classi, opzioni e stringhe di lingua |
| `CHANGELOG.md` | Cronologia delle versioni |
| `tools/quality_check.py` | Controlli statici della release |

## Principi tecnici

- **Joomla-first:** preferire API, componenti, moduli, traduzioni, Web Asset Manager e override di Joomla ai meccanismi personalizzati.
- **Sicurezza:** validare input, fare escaping in uscita in base al contesto, usare query API Joomla e non modificare file del core.
- **Prestazioni:** evitare query e asset superflui; preservare ordine della cascata CSS e verificare i cambiamenti di navigazione sui dispositivi reali.
- **Accessibilità:** contrasti adeguati, menu utilizzabili con tastiera, testi alternativi alle immagini.
- **Release:** numeri di versione coerenti, changelog aggiornato, ZIP installabile e verifica su staging.

## Documentazione

- [Guida agli stili](GUIDA_STILI.md)
- [Changelog](CHANGELOG.md)

## Autore e licenza

**Jecko Development** — https://jeckodevelopment.com. Il manifest dichiara GNU GPL v2 o successiva; le eventuali risorse grafiche hanno licenze proprie.
Per il Magazine, consulta il [piano](docs/magazine/PROJECT_PLAN.md), la [matrice](docs/magazine/REQUIREMENTS.csv) e il [registro di collaudo](docs/magazine/COLLAUDO.md). Le integrazioni editoriali richiedono il [pacchetto Opera+ Extensions 1.0.0](https://github.com/jeckodevelopment/operaplus-extensions/releases/tag/v1.0.0).

Guide operative Magazine: [amministratore](docs/magazine/ADMIN_GUIDE.md) · [redattore](docs/magazine/EDITOR_GUIDE.md).
Release Candidate: [note e checklist di rilascio](docs/releases/2.0.0-rc.2.md). Gli aggiornamenti automatici seguono esclusivamente il canale stabile; nessun aggiornamento RC automatico. Scaricare lo ZIP della prerelease e verificare SHA-256 prima dell'installazione su staging.
