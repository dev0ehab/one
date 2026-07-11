<?php

/**
 * Merge Postman item IDs from cloud snapshot into local collection by name.
 * Usage: php scripts/sync-postman-ids.php <cloud-full.json> <local-collection.json> [output.json]
 */

if ($argc < 3) {
    fwrite(STDERR, "Usage: php sync-postman-ids.php <cloud.json> <local.json> [output.json]\n");
    exit(1);
}

$cloudRaw = file_get_contents($argv[1]);
$cloud = json_decode($cloudRaw, true);
$local = json_decode(file_get_contents($argv[2]), true);

if (!$local) {
    fwrite(STDERR, 'Invalid local JSON: ' . json_last_error_msg() . "\n");
    exit(1);
}

if (!$cloud) {
    // Strip invalid UTF-8 from cloud export then retry
    $cloudRaw = iconv('UTF-8', 'UTF-8//IGNORE', $cloudRaw);
    $cloud = json_decode($cloudRaw, true);
}

if (!$cloud) {
    fwrite(STDERR, 'Invalid cloud JSON: ' . json_last_error_msg() . "\n");
    exit(1);
}

$cloudItems = $cloud['collection']['item'] ?? $cloud['item'] ?? [];

function walkAssignIds(array &$targetItems, array $sourceItems): void
{
    $sourceByName = [];
    foreach ($sourceItems as $src) {
        $sourceByName[$src['name'] ?? ''] = $src;
    }

    foreach ($targetItems as &$item) {
        $name = $item['name'] ?? '';

        if (!isset($sourceByName[$name])) {
            continue;
        }

        $src = $sourceByName[$name];

        if (!empty($src['id'])) {
            $item['id'] = $src['id'];
        }
        if (!empty($src['uid'])) {
            $item['uid'] = $src['uid'];
        }

        if (isset($item['item'], $src['item']) && is_array($item['item']) && is_array($src['item'])) {
            walkAssignIds($item['item'], $src['item']);
        }
    }
}

walkAssignIds($local['item'], $cloudItems);

$cloudInfo = $cloud['collection']['info'] ?? $cloud['info'] ?? [];
$local['info']['_postman_id'] = $cloudInfo['_postman_id'] ?? $local['info']['_postman_id'] ?? 'e39f2c1c-6eea-4f44-8d11-d928212207ff';
$local['info']['uid'] = $cloudInfo['uid'] ?? '54926212-e39f2c1c-6eea-4f44-8d11-d928212207ff';
$local['info']['schema'] = 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json';

$out = $argv[3] ?? 'php://stdout';
$json = json_encode($local, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

if ($out === 'php://stdout') {
    echo $json;
} else {
    file_put_contents($out, $json);
    echo strlen($json);
}
