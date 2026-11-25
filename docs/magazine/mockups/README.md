# Riferimenti grafici Magazine — formalizzazione 25 novembre 2026

Riferimento #2, baseline tecnica 2.0.0-rc.1. Recuperati dalla conversazione di progettazione del 24 novembre **10 PNG originali** e predisposti 13 estratti separati, senza ridisegnare i mockup. Le immagini binarie sono state recuperate in un archivio esterno, **non ancora caricate nel repository**; l'inventario e le impronte sono versionati qui. Il connettore GitHub non accetta direttamente i file binari del workspace. Non dichiarare un file presente in Git prima di verificarlo.

## Regola di precedenza
Le decisioni scritte approvate prevalgono sui dettagli divergenti delle bozze (in particolare asse unico 8+4, tre notizie laterali compatte, immagini recensioni uniformi, fondo del primo opzionale, box Atlas e Newsletter). R08 è riferimento esplicito dei box approvati; R10 consolida quattro pagine, ma non prova l'approvazione individuale delle altre tavole. I mockup sono riferimenti di design, non fonti di dati editoriali o fotografie da pubblicare sul sito. Le fixture CI non sono mockup originali.

## Inventario e criteri
Consulta [SPECIFICHE.md](SPECIFICHE.md) per tutti i 15 criteri. Il file [originals-manifest.json](originals-manifest.json) registra filename, data UTC, risoluzione e SHA-256 degli originali recuperati. Destinazione dei PNG: `originals/`; estratti non alterati: `extracts/`. Conservare anche i compositi integrali. Nessuna immagine nel pacchetto installabile.

| Stato | Numero |
|---|---:|
| Riferimenti visivi recuperati (inclusi estratti parziali) | 13 |
| Tavole senza originale reperito | 2 (UX-12, UX-13) |
| Box con approvazione grafica esplicita | 2 (UX-14, UX-15) |
| Criteri con file binario verificato in Git | 0 |

Per chiudere UX-01–15: archiviare i PNG originali con verifica SHA-256; completare i riferimenti parziali e mancanti, registrare la singola approvazione; confronto con staging nel COLLAUDO (separato). Nessuna nuova versione o deploy per questa formalizzazione.
