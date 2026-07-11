<?php

$key = '';
$lineNum = 0;
foreach (file(dirname(__DIR__) . '/.env') as $num => $line) {
    $trimmed = trim($line);
    if (str_starts_with($trimmed, 'POSTMAN_API_KEY=')) {
        $lineNum = $num + 1;
        $key = trim(substr($trimmed, strlen('POSTMAN_API_KEY=')), " \t\"'");
        break;
    }
}

if ($key === '') {
    echo "POSTMAN_API_KEY: missing in .env\n";
    exit(1);
}

echo "Found on .env line: {$lineNum}\n";
echo 'Length: ' . strlen($key) . " (typical valid key: 50–70+)\n";
echo 'Starts with PMAK: ' . (str_starts_with($key, 'PMAK') ? 'yes' : 'no') . "\n";
echo 'Contains spaces: ' . (str_contains($key, ' ') ? 'yes — remove spaces' : 'no') . "\n";
echo 'Contains newline chars: ' . (preg_match('/[\r\n]/', $key) ? 'yes' : 'no') . "\n";

if (strlen($key) < 45) {
    echo "WARNING: Key looks too short — copy the FULL key from Postman settings.\n";
}

$ch = curl_init('https://api.getpostman.com/me');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['X-Api-Key: ' . $key],
    CURLOPT_TIMEOUT        => 30,
]);
$body = curl_exec($ch);
$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "GET /me HTTP {$code}\n";
if ($code === 200) {
    $json = json_decode($body, true);
    echo 'OK — user: ' . ($json['user']['username'] ?? 'authenticated') . "\n";
    exit(0);
}

echo "Auth failed. Regenerate at https://postman.postman.co/settings/me/api-keys\n";
exit(1);
