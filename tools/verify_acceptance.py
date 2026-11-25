"""Validate traceability without pretending manual acceptance."""
from pathlib import Path
import csv, re
root = Path(__file__).resolve().parents[1] / "docs/magazine"
def read(name):
    with (root / name).open(encoding="utf-8", newline="") as f:
        return list(csv.DictReader(f))
req = read("REQUIREMENTS.csv")
results = read("ESITI_COLLAUDO.csv")
cases = re.findall(r"^### ([A-Z][A-Z0-9]*-[A-Z0-9]+(?:/\d+)?) —", (root / "COLLAUDO.md").read_text(encoding="utf-8"), re.M)
assert len(cases) == len(set(cases))
assert len(req) == 60 == len({r["id"] for r in req})
assert set(cases) == {i for r in req for i in r["casi_collaudo"].split(";")}
keys = [(r["caso_id"], int(r["tentativo"])) for r in results]
assert len(keys) == len(set(keys))
assert {r["caso_id"] for r in results} == set(cases)
assert {r["sessione"] for r in results} == set(map(str, range(1,10)))
latest = {}
for r in results:
    assert r["stato"] in {"non_eseguito","OK","KO","NA","bloccato"}
    assert r["approvazione_grafica"] in {"","da_approvare","approvata","da_correggere"}
    if r["stato"] != "non_eseguito":
        assert all(r[k] for k in ("data","ambiente_alias","versione_template","commit","evidenza")), r["caso_id"]
    if r["stato"] == "NA":
        assert r["motivazione_na"] and r["note"]
    if r["stato"] == "KO":
        assert r["issue_pr"]
    i = r["caso_id"]
    if i not in latest or int(r["tentativo"]) > int(latest[i]["tentativo"]):
        latest[i] = r
for r in req:
    assert r["casi_collaudo"]
    if r["stato_collaudo"] == "superato":
        assert all(latest[i]["stato"] in {"OK","NA"} for i in r["casi_collaudo"].split(";")), r["id"]
        if r["id"].startswith("UX-"):
            assert latest[r["id"]]["approvazione_grafica"] == "approvata"
print(f"Acceptance traceability: {len(req)} requirements, {len(cases)} procedures, 9 sessions; no orphan IDs.")
