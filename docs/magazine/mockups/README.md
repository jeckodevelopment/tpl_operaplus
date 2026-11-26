# Riferimenti grafici Magazine — archivio ufficiale

Baseline tecnica **2.0.0-rc.1**. Riferimento #2. Le dieci immagini originali del 24 novembre 2026 e i tredici estratti sono conservati senza alterazioni in `originals/` e `extracts/`. Integrità verificata tramite SHA-256 in CI e confronto dei blob Git con l'archivio di origine. Gli estratti sono ritagli delle tavole, non nuove proposte.

Le decisioni scritte approvate prevalgono sulle bozze: asse 8+4, notizie laterali compatte, immagini recensioni uniformi, fondo del primo opzionale, box Atlas e Newsletter approvati. Nessuna di queste immagini è inclusa nello ZIP del template o pubblicata come contenuto editoriale.

## Galleria di revisione

[Apri l'indice delle 15 tavole](boards/index.html) (HTML offline; per anteprime PNG usare l'artifact `magazine-design-boards` della CI). Le 8 nuove tavole completano le aree parziali o mancanti: sono proposte distinte dagli originali, in attesa di approvazione.

## Tavole UX

| ID | Tavola | Riferimento | Stato formalizzazione |
|---|---|---|---|
| UX-01 | Homepage | [PNG](extracts/UX-01-homepage.png) | Approvata 26/11/2026; staging RC9 OK |
| UX-02 | Categoria elenco | [PNG](extracts/UX-02-categoria-elenco.png) | Approvata 26/11/2026; staging RC9 OK |
| UX-03 | Categoria griglia | [PNG ](extracts/UX-03-categoria-griglia.png) · [HTML](boards/UX-03.html) | Approvata 26/11/2026; staging RC9 OK |
| UX-04 | Articolo | [PNG](extracts/UX-04-articolo.png) | Approvata 26/11/2026; staging RC10 OK, ricollaudo mirato RC11 |
| UX-05 | Longform | [PNG ](extracts/UX-05-longform.png) · [HTML](boards/UX-05.html) | Approvata 26/11/2026; staging RC10 OK, ricollaudo mirato RC11 |
| UX-06 | Recensione | [PNG](extracts/UX-06-recensione.png) | Approvata 26/11/2026; staging RC10 OK, ricollaudo mirato RC11 |
| UX-07 | Intervista | [PNG](extracts/UX-07-intervista.png) | Approvata 26/11/2026; staging RC10 OK, ricollaudo mirato RC11 |
| UX-08 | Dossier | [PNG ](extracts/UX-08-dossier.png) · [HTML](boards/UX-08.html) | Approvata 26/11/2026; staging RC9 OK |
| UX-09 | Archivio | [PNG ](extracts/UX-09-archivio.png) · [HTML](boards/UX-09.html) | Approvata 26/11/2026; staging RC10 KO, ricollaudo mirato RC11 |
| UX-10 | Autore | [PNG ](extracts/UX-10-autore.png) · [HTML](boards/UX-10.html) | Approvata 26/11/2026; staging RC10 OK, ricollaudo mirato RC11 |
| UX-11 | Homepage mobile | [PNG ](extracts/UX-11-homepage-mobile.png) · [HTML](boards/UX-11.html) | Proposta completa, da approvare |
| UX-12 | Articolo mobile | [HTML](boards/UX-12.html) | Proposta completa, da approvare |
| UX-13 | Dark | [HTML](boards/UX-13.html) | Proposta completa, da approvare |
| UX-14 | Box Atlas | [PNG](extracts/UX-14-atlas.png) | Approvata e archiviata |
| UX-15 | Box CTA | [PNG](extracts/UX-15-cta.png) | Approvata e archiviata |

## Originali integrali

- [La Traviata: ieri e oggi](originals/R01-articolo.png)
- [Eleganza operistica: Magazine Opera+](originals/R02-home.png)
- [Intervista a Daniele Gatti su Opera+](originals/R03-intervista.png)
- [Recensioni d’Opera tra Palchi e Teatri](originals/R04-recensioni.png)
- [Presentazione editoriale del Magazine Opera+](originals/R05-presentazione.png)
- [Opera+ Magazine: Storie d’opera e musica lirica](originals/R06-home-completa.png)
- [Mockup editoriale Opera+ in italiano](originals/R07-tavola-risposte.png)
- [Doppia pagina Opera+: Magazine e Recensioni](originals/R08-home-recensioni.png)
- [Tavola editoriale del magazine Opera+](originals/R09-tavola-estesa.png)
- [Mockup editoriali Opera+ in stile burgundy e crema](originals/R10-consolidamento.png)

## Verifica

[Specifiche e criteri](SPECIFICHE.md) · [Manifest con SHA-256, sorgenti e ritagli](manifest.json) · [Provenienza originali](originals-manifest.json).

13 riferimenti originali archiviati; 8 tavole HTML di completamento predisposte, comprese UX-12 e UX-13 precedentemente mancanti. Tutti i 15 criteri hanno ora un riferimento visivo. 12 riferimenti UX approvati (01–10/14/15); 3 da approvare. Confronto staging OK per UX-01/02/03/08; REG-04 generale ancora aperto. Il collaudo visuale sul sito rimane separato. Nessun nuovo rilascio o deployment.
