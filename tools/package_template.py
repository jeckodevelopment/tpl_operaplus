"""Build the manifest-declared Joomla template files; accepts an optional source root."""
import os, sys
from pathlib import Path
os.chdir(Path(sys.argv[1]).resolve() if len(sys.argv)>1 else Path(__file__).resolve().parents[1])
from pathlib import Path
from zipfile import ZipFile, ZIP_DEFLATED
import xml.etree.ElementTree as ET
root=ET.parse('templateDetails.xml').getroot()
files=set()
for node in root.findall('./files/*'):
    p=Path(node.text)
    files.update([p] if p.is_file() else [x for x in p.rglob('*') if x.is_file()])
for node in root.findall('./media/*'):
    p=Path(root.find('media').attrib['folder']) / node.text
    files.update([p] if p.is_file() else [x for x in p.rglob('*') if x.is_file()])
for node in root.findall('./languages/language'):
    files.add(Path(root.find('languages').attrib['folder']) / node.text)
Path('dist').mkdir(exist_ok=True)
with ZipFile('dist/tpl_operaplus-'+root.findtext('version')+'.zip','w',ZIP_DEFLATED) as z:
    for p in sorted(files): z.write(p,str(p))
