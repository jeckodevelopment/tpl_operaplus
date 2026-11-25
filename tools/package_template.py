"""Build reproducible Joomla ZIP from the manifest; accepts optional source root."""
import hashlib
import os
import sys
from pathlib import Path
from zipfile import ZipFile, ZipInfo, ZIP_DEFLATED
import xml.etree.ElementTree as ET

os.chdir(Path(sys.argv[1]).resolve() if len(sys.argv)>1 else Path(__file__).resolve().parents[1])
root = ET.parse('templateDetails.xml').getroot()
files = set()
def add(p):
    if not p.exists():
        raise FileNotFoundError(p)
    files.update([p] if p.is_file() else [x for x in p.rglob('*') if x.is_file()])
for node in root.findall('./files/*'):
    add(Path(node.text))
for node in root.findall('./media/*'):
    add(Path(root.find('media').attrib['folder']) / node.text)
for node in root.findall('./languages/language'):
    add(Path(root.find('languages').attrib['folder']) / node.text)
version = root.findtext('version')
Path('dist').mkdir(exist_ok=True)
package = Path('dist') / ('tpl_operaplus-' + version + '.zip')
with ZipFile(package, 'w', ZIP_DEFLATED, compresslevel=9) as z:
    for p in sorted(files):
        if p.is_symlink() or p.is_absolute() or '..' in p.parts:
            raise ValueError(f'Unsafe package path: {p}')
        entry = ZipInfo(p.as_posix(), (1980,1,1,0,0,0))
        entry.external_attr = 0o100644 << 16
        entry.compress_type = ZIP_DEFLATED
        z.writestr(entry, p.read_bytes(), compress_type=ZIP_DEFLATED, compresslevel=9)
with ZipFile(package) as z:
    assert z.testzip() is None
    assert set(z.namelist()) == {p.as_posix() for p in files}
    assert ET.fromstring(z.read('templateDetails.xml')).findtext('version') == version
    # Historical baseline packaging is supported by the lifecycle job; RC and stable
    # packages must never install test/development scripts in the document root.
    if version.startswith('2.'):
        assert not any(n.startswith(('tools/', '.github/', 'docs/', 'dist/')) for n in z.namelist())
digest = hashlib.sha256(package.read_bytes()).hexdigest()
(package.parent / (package.name + '.sha256')).write_text(f'{digest}  {package.name}\n')
print(f'{package}: {package.stat().st_size} bytes, sha256 {digest}')
