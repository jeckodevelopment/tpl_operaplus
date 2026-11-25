#!/usr/bin/env python3
"""Basic static gate for the Opera+ Joomla template release; no runtime guarantees."""
from pathlib import Path
import json, re, sys, xml.etree.ElementTree as ET
root = Path(__file__).resolve().parents[1]
manifest = ET.parse(root / 'templateDetails.xml').getroot()
version = manifest.findtext('version')
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
assert (root / 'CHANGELOG.md').is_file()
print(f'Opera+ {version}: manifest, assets, language keys and changelog OK')
# Joomla may request the .min.css form even for custom editor CSS.
editor = (root / 'media/css/editor.css').read_text()
assert editor == (root / 'media/css/editor.min.css').read_text(), 'Editor CSS variants differ'
for cls in ('op-editorial-lead', 'op-interview-question', 'op-interview-answer'):
    assert 'p.' + cls in editor, f'Missing importable TinyMCE format: {cls}'
    assert '.' + cls in (root / 'media/css/magazine.css').read_text(), f'Missing frontend style: {cls}'
