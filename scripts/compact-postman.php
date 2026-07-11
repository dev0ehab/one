<?php

$path = $argv[1] ?? 'postman/One.postman_collection.merged.json';
$out = $argv[2] ?? 'postman/One.postman_collection.compact.json';

$data = json_decode(file_get_contents($path), true);
if (!$data) {
    fwrite(STDERR, json_last_error_msg() . "\n");
    exit(1);
}

file_put_contents($out, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo strlen(file_get_contents($out));
