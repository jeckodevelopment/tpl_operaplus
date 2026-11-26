<?php
declare(strict_types=1);
// Run against the exact pinned Extensions checkout, without network access.
require __DIR__ . '/../.ci-extensions/lib_operaplus/src/Index.php';
use OperaPlus\Atlas\Index;
function expect(bool $ok, string $message): void {
    if (!$ok) { throw new RuntimeException($message); }
}
$types = ['opera','composer','theatre','festival','production'];
$entities = array_map(static fn ($type) => ['type'=>$type,'id'=>'ci-'.$type,'title'=>'CI '.$type,'url'=>'https://opera.plus/atlas/'.$type.'/ci/'], $types);
$raw = json_encode(['schema_version'=>1,'source_revision'=>str_repeat('a',40),'entities'=>$entities], JSON_THROW_ON_ERROR);
$index = Index::decode($raw);
$refs = array_map(static fn ($type) => $type.':ci-'.$type, $types);
expect(array_keys($index) === $refs, 'Five supported types must retain their references');
expect(count(Index::resolve($index, $refs, true)) === 5, 'All supported types resolve');
expect(!Index::validReference('season:ci-season'), 'Season must not be selectable');
$dir = sys_get_temp_dir().'/op-atlas-ci-'.bin2hex(random_bytes(8));
mkdir($dir);
$file = $dir.'/cache.json';
$cold = $dir.'/cold.json';
$unavailable = static fn () => throw new RuntimeException('Isolated offline fixture');
try {
    expect(Index::load($file, static fn () => $raw) === $index, 'Warm cache setup');
    touch($file,time()-7200);
    expect(Index::load($file,$unavailable) === $index, 'Offline refresh must retain last good cache');
    expect(file_get_contents($file) === $raw, 'Offline refresh must not overwrite cache');
    expect(Index::load($cold,$unavailable) === [], 'Offline without cache must degrade to empty');
    expect(!is_file($cold), 'Offline must not create invalid cache');
    expect(Index::resolve([], $refs, true) === [], 'Empty cache must not resolve entities');
    $calls=0;
    Index::load($cold, static function () use (&$calls,$raw) { ++$calls; return $raw; });
    expect($calls===0,'Cold-cache failure cooldown must suppress repeated fetch');
} finally {
    foreach (glob($dir.'/*') ?: [] as $entry) { unlink($entry); }
    rmdir($dir);
}
echo "Atlas isolated resilience: five entity types, offline warm/cold cache and cooldown passed\n";
