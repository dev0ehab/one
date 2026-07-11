<?php

/** Export Postman cloud full export to local collection file (pretty JSON). */
$cloudPath = $argv[1] ?? '';
$outPath = $argv[2] ?? 'postman/One.postman_collection.json';

if ($cloudPath === '' || !is_file($cloudPath)) {
    fwrite(STDERR, "Usage: php export-cloud-to-local.php <cloud-full.json> [output.json]\n");
    exit(1);
}

$raw = file_get_contents($cloudPath);
$raw = iconv('UTF-8', 'UTF-8//IGNORE', $raw);
$data = json_decode($raw, true);
$collection = $data['collection'] ?? $data;

if (!$collection) {
    fwrite(STDERR, json_last_error_msg() . "\n");
    exit(1);
}

file_put_contents(
    $outPath,
    json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n"
);

echo "Wrote {$outPath}\n";
