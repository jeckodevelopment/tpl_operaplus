# Piano di collaudo cumulativo — Magazine
Revisione 01, 25 novembre 2026. Baseline Extensions 1.0.0, template 1.22.0; 1.23.0 dopo integrazione PR #15. Collaudi manuali rinviati dall'utente, senza bloccare i lotti di sviluppo. Non dichiarare superata l'accettazione grafica sulla base della CI. Documento di lavoro dettagliato consegnato separatamente in formato Word.

## Prerequisiti
Ambiente di staging, backup, Joomla 6 e PHP compatibile; utente ospite e registrato, amministratore, almeno due categorie editoriali, corsi, articoli pubblici/riservati/non pubblicati, immagini autorizzate, articolo senza immagine. Usare OK/KO/N.A. motivato/Bloccato, data, versione, browser, URL e screenshot. Non alterare la produzione.

## Casi da registrare
| ID | Verifica e configurazione | Risultato atteso |
|---|---|---|
| SET-01 | Installare ZIP come aggiornamento, pulire cache | Dati e configurazioni conservati |
| SET-02 | Sistema → Stili template sito: Standard e Magazine | Due stili nativi, default invariato; già confermato in parte |
| SET-03 | Voce menu landing, fascia immagine/titolo/sottotitolo | Masthead solo sulla landing |
| SET-04 | Seguire link articolo con Itemid ereditato, categoria e corsi | Route corretta; Standard invariato |
| SET-05 | Ripetere installazione e confrontare ID/assegnazioni | Nessun duplicato |
| HOME-01 | Articoli, magazine-cover, posizione magazine-cover, 1, immagine e titolo collegato, modulo senza titolo | Copertina overlay leggibile |
| HOME-02 | Articoli, magazine-sidebar, magazine-news, 3, immagini/metadati | Tre notizie compatte |
| HOME-03 | Articoli, magazine-grid, magazine-featured, 3, colonne 2–4 | Schede uniformi |
| HOME-04 | Coppie di posizioni featured/reviews/interviews/dossier/insights/latest e -aside | Asse 8+4 comune |
| HOME-05 | Disattivare moduli; mostra componente sì/no | Fallback nativo, righe vuote assenti |
| MOD-01 | magazine-list in reviews, magazine-portraits in interviews | Immagini uniformi e ritratti verticali |
| MOD-02 | magazine-dossier, magazine-latest | Filtri, numero e ordinamento nativi |
| MOD-03 | magazine-ranked, magazine-topics | Numerazione/elenco testuale |
| MOD-04 | magazine-related-sidebar in magazine-sidebar | Miniatura, titolo, categoria, data |
| MOD-05 | magazine-related-horizontal in main-bottom e magazine-related-compact | Griglia ed elenco testuale; nessun algoritmo automatico |
| MOD-06 | Immagini mancanti, disattivare titolo/link/metadati/estratto | Opzioni e fallback rispettati |
| MOD-07 | Filtri, categorie vuote, ACL e stato pubblicazione | Comportamento Joomla nativo |
| MOD-08 | Standard, raggruppamento, solo titoli | Fallback core |
| MOD-09 | 1200/768/375 px, titoli lunghi | Nessun overflow |
| MOD-10 | Temi, tastiera, foto chiare | Contrasto e focus |
| CAT-01 | magazine-grid, colonne e leading | Griglia e metadati |
| CAT-02 | magazine-list, fondo prima voce sì/no | Immagini identiche |
| CAT-03 | magazine-dossier e tag | Raccolta nativa |
| CAT-04 | Paginazione, figli, vuoto | Navigazione nativa |
| CAT-05 | Permessi, Standard e corsi | ACL e baseline |
| ART-01 | magazine-article, titolo/immagine/metadata/tag | Titoli corretti, intro non duplicato |
| ART-02 | Tempo lettura 200/400 parole/min e off | Unicode, ceil, nessun tempo per testo riservato |
| ART-03 | magazine-longform, TOC e pagebreak | Lettura estesa e indice nativo |
| ART-04 | magazine-interview, modello TinyMCE D/R | Domande/risposte e citazioni leggibili |
| ART-05 | magazine-review con campi Joomla pubblicati | Pannello campi; scheda spettacolo definitiva futura |
| INT-01 | Sei modelli TinyMCE, annullamento, salvataggio | Già confermato; regressione |
| INT-02 | atlas-entities multiplo, atlas-primary singolo, entrambi facoltativi | Già confermato; regressione |
| INT-03 | Atlas contestuale in magazine-sidebar, principale e vuoto | Entità/link e sidebar assente se vuota |
| INT-04 | Rigoletto/Verdi PD, CC0/CC BY/SA | Crediti secondo licenza; Verdi confermato |
| INT-05 | CTA chiara/scura/orizzontale/Atlas | Grafica approvata, niente doppia cornice |
| REG-01 | Menu attivo, sottomenu, login e mobile | Confermato 1.21.2; regressione |
| REG-02 | Sidebar vuota, contenuti riservati | Piena larghezza, ACL |
| REG-03 | SEO, plugin, edit, titoli e link | Nessuna regressione |
| REG-04 | Confronto con mockup originali UX-01–08, UX-11–15 | Accettazione visiva reale |

CAT/ART da eseguire dopo merge PR #15. Mockup definitivi da versionare (issue #2). Archivio, autore, carousel, mosaico, scheda spettacolo strutturata, editor CSS, correlazione automatica e audit finale 2.0.0 restano da completare; aggiungere casi nei lotti successivi.
