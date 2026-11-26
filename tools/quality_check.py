#!/usr/bin/env python3
"""Basic static gate for the Opera+ Joomla template release; no runtime guarantees."""
from pathlib import Path
from datetime import date
import json, re, sys, xml.etree.ElementTree as ET
root = Path(__file__).resolve().parents[1]
manifest = ET.parse(root / 'templateDetails.xml').getroot()
version = manifest.findtext('version')
release_date = (root / 'RELEASE_DATE').read_text().strip()
assert date.fromisoformat(release_date).isoformat() == release_date
assert manifest.findtext('creationDate') == release_date
assert manifest.find("./files/folder[.='language']") is not None, 'Local catalog fallback must be installed'
for name in ('template_preview.png', 'template_thumbnail.png'):
    image = root / 'media/images' / name
    assert image.read_bytes().startswith(b'\x89PNG\r\n\x1a\n'), name
assert manifest.find(".//field[@name='presentationMode']").get('type') == 'opstylemode'
assert manifest.find(".//fieldset[@name='magazine']") is None
assert manifest.find(".//fieldset[@name='magazineContent']") is None
for node in manifest.findall('.//field'):
    if node.get('name', '').startswith('magazine'):
        assert node.get('showon') == 'presentationMode:magazine', node.get('name')
assets = json.loads((root / 'joomla.asset.json').read_text())
assert assets['version'] == version, 'Manifest and Web Asset version differ'
for f in manifest.findall('./files/filename'):
    assert (root / f.text).is_file(), f'Missing manifest file: {f.text}'
for f in manifest.findall('./files/folder'):
    assert (root / f.text).is_dir(), f'Missing manifest folder: {f.text}'
for lang in ('it-IT', 'en-GB'):
    p = root / 'language' / lang / 'tpl_operaplus.ini'
    entries = [line.split('=', 1)[0] for line in p.read_text().splitlines()
               if line.strip() and not line.lstrip().startswith((';','#')) and '=' in line]
    assert len(entries) == len(set(entries)), f'Duplicate translation keys: {p}'
    assert 'TPL_OPERAPLUS_XML_DESCRIPTION' in entries, f'Missing style description: {p}'
assert (root / 'CHANGELOG.md').is_file()
print(f'Opera+ {version}: manifest, assets, language keys and changelog OK')
# Joomla may request the .min.css form even for custom editor CSS.
editor = (root / 'media/css/editor.css').read_text()
assert editor == (root / 'media/css/editor.min.css').read_text(), 'Editor CSS variants differ'
for cls in ('op-editorial-lead', 'op-interview-question', 'op-interview-answer'):
    assert 'p.' + cls in editor, f'Missing importable TinyMCE format: {cls}'
    assert '.' + cls in (root / 'media/css/magazine.css').read_text(), f'Missing frontend style: {cls}'

# Uncompressed runtime asset budgets; exclude third-party Joomla assets.
# RC5/RC6: allow at most 4 KB for scoped category metadata and native navigation CSS.
for path, limit in {"media/css/template.css": 78000, "media/css/magazine.css": 37000, "media/js/template.js": 12000}.items():
    size = (root / path).stat().st_size
    assert size <= limit, f"Runtime asset budget exceeded: {path}: {size} > {limit}"
    print(f"{path}: {size}/{limit} bytes")
assert "DatabaseInterface" not in (root / "index.php").read_text(), "SEO must not query content again"

for lang in ('it-IT', 'en-GB'):
    text = (root / 'language' / lang / 'tpl_operaplus.ini').read_text()
    for node in [n for n in manifest.findall(".//field") if n.get("name", "").startswith("magazine")]:
        for key in [node.get('label'), node.get('description')] + [o.text for o in node.findall('option')]:
            if key and key.startswith('TPL_OPERAPLUS_'):
                assert re.search(r'^' + re.escape(key) + r'=', text, re.M), f'Missing {lang}: {key}'


# All administrator copy must resolve in both site language catalogs.
admin_keys = set()
for node in manifest.findall('./config//fieldset') + manifest.findall('./config//field'):
    for attr in ('label', 'description', 'hint'):
        key = node.get(attr)
        if key:
            assert re.fullmatch(r'(?:TPL_OPERAPLUS_[A-Z0-9_]+|J[A-Z0-9_]+)', key), (node.get('name'), attr, key)
            admin_keys.add(key)
    for option in node.findall('option'):
        key = option.text or ''
        if re.fullmatch(r'\d+(?: px|:\d+)', key):
            continue
        assert re.fullmatch(r'(?:TPL_OPERAPLUS_[A-Z0-9_]+|J[A-Z0-9_]+)', key), key
        admin_keys.add(key)
for lang in ('it-IT', 'en-GB'):
    lang_text = (root / 'language' / lang / 'tpl_operaplus.ini').read_text()
    for key in admin_keys:
        if key.startswith('TPL_OPERAPLUS_'):
            assert re.search(r'^' + re.escape(key) + r'=".+"$', lang_text, re.M), (lang, key)

# Template-owned security boundaries: preserve native rendering and validate dynamic media.
assert "opSeoUrl($images->image_intro" in (root / "html/com_content/category/blog.php").read_text()
assert "getAuthorisedViewLevels()" in (root / "html/com_content/article/_magazine.php").read_text()
assert "opSeoUrl(" in (root / "html/mod_articles/_magazine.php").read_text()

# A stable-only feed must never advertise a pre-release.
assert all(n.text != "tools" for n in manifest.findall("./files/*"))
feed = ET.parse(root / "updates/stable.xml").getroot()
assert feed.tag == "updates"
assert all(u.findtext("tags/tag") == "stable" and not re.search(r"(?i)(alpha|beta|rc)", u.findtext("version") or "") for u in feed.findall("update"))
assert manifest.findtext("./updateservers/server") == "https://raw.githubusercontent.com/jeckodevelopment/tpl_operaplus/main/updates/stable.xml"

# Acceptance traceability (not manual acceptance).
import runpy
runpy.run_path(str(root / 'tools/verify_acceptance.py'))
