<?php

/**
 * Patch Postman collection with auto-login prerequest + unauthenticated retry.
 *
 * Usage: php scripts/patch-postman-auth.php [collection.json]
 */

declare(strict_types=1);

require_once __DIR__ . '/postman-auth-events.php';

$path = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

mergePostmanCollectionAuth($collection);

file_put_contents(
    $path,
    json_encode($collection, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

echo "Patched auth scripts on: {$path}\n";
