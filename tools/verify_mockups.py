"""Verify archived original mockup bytes, inventory, and extract provenance."""
from pathlib import Path
import hashlib
import json

root = Path(__file__).resolve().parents[1] / "docs/magazine/mockups"
manifest = json.loads((root / "manifest.json").read_text())
provenance = json.loads((root / "originals-manifest.json").read_text())
originals = {e["file"]: e for e in manifest["originals"]}
entries = manifest["originals"] + manifest["extracts"]
assert len(entries) == len({e["file"] for e in entries}), "Duplicate manifest path"
for entry in entries:
    relative = Path(entry["file"])
    assert len(relative.parts) == 2 and relative.parts[0] in {"originals", "extracts"}
    assert relative.suffix == ".png" and ".." not in relative.parts
    data = (root / relative).read_bytes()
    assert data.startswith(b"\x89PNG\r\n\x1a\n"), entry["file"]
    assert hashlib.sha256(data).hexdigest() == entry["sha256"], entry["file"]
    assert len(data) == entry["size"], entry["file"]
    assert hashlib.sha1(b"blob " + str(len(data)).encode() + b"\0" + data).hexdigest() == entry["git_blob_sha"], entry["file"]
    if "source" in entry:
        assert entry["source"] in originals, entry["file"]
assert {p.relative_to(root).as_posix() for p in root.rglob("*.png")} == {e["file"] for e in entries}, "Untracked image inventory"
assert {e["file"]: e["sha256"] for e in provenance["originals"]} == {e["file"]: e["sha256"] for e in manifest["originals"]}
assert {e["id"] for e in manifest["extracts"]}.isdisjoint(manifest["missing"])
assert {e["id"] for e in manifest["extracts"]} | set(manifest["missing"]) == {f"UX-{i:02}" for i in range(1, 16)}
print(f"Mockup archive: {len(originals)} originals, {len(manifest['extracts'])} extracts; SHA-256 and Git blob identities verified.")
