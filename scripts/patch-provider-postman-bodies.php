<?php

/**
 * Patch provider register + update profile bodies in Postman collections.
 * Usage: php scripts/patch-provider-postman-bodies.php [file1.json ...]
 */

$files = array_slice($argv, 1, 100);
if ($files === []) {
    $files = [
        dirname(__DIR__) . '/postman/One.postman_collection.json',
        dirname(__DIR__) . '/postman/One.postman_collection.synced.json',
        dirname(__DIR__) . '/postman/One.postman_collection.cloud-full.json',
    ];
}

function providerRegisterFormdata(): array
{
    return [
        ['key' => 'owner_name', 'value' => 'محمد أحمد', 'type' => 'text'],
        ['key' => 'trade_name', 'value' => 'مؤسسة النور للتجارة', 'type' => 'text'],
        ['key' => 'country_code', 'value' => '966', 'type' => 'text'],
        ['key' => 'phone', 'value' => '512345679', 'type' => 'text'],
        ['key' => 'password', 'value' => 'Password123', 'type' => 'text'],
        ['key' => 'password_confirmation', 'value' => 'Password123', 'type' => 'text'],
        ['key' => 'national_id', 'value' => '1234567890', 'type' => 'text'],
        ['key' => 'category_id', 'value' => '1', 'type' => 'text'],
        ['key' => 'commercial_register_num', 'value' => '1234567890123', 'type' => 'text'],
        ['key' => 'lat', 'value' => '24.7136', 'type' => 'text'],
        ['key' => 'lng', 'value' => '46.6753', 'type' => 'text'],
        ['key' => 'map_desc', 'value' => 'الرياض، المملكة العربية السعودية', 'type' => 'text'],
        ['key' => 'start_time', 'value' => '08:00', 'type' => 'text', 'description' => 'H:i — required'],
        ['key' => 'end_time', 'value' => '22:00', 'type' => 'text', 'description' => 'H:i — required, after start_time'],
        ['key' => 'accept_terms', 'value' => '1', 'type' => 'text'],
        ['key' => 'banners[]', 'type' => 'file', 'src' => [], 'description' => 'Required image (add more banners[] rows)'],
        ['key' => 'banners[]', 'type' => 'file', 'src' => [], 'description' => 'Second banner image'],
    ];
}

function providerUpdateProfileFormdata(): array
{
    return [
        ['key' => 'owner_name', 'value' => 'محمد أحمد', 'type' => 'text'],
        ['key' => 'trade_name', 'value' => 'مؤسسة النور للتجارة', 'type' => 'text'],
        ['key' => 'category_id', 'value' => '1', 'type' => 'text'],
        ['key' => 'commercial_register_num', 'value' => '1234567890123', 'type' => 'text'],
        ['key' => 'start_time', 'value' => '08:00', 'type' => 'text', 'description' => 'H:i — required'],
        ['key' => 'end_time', 'value' => '22:00', 'type' => 'text', 'description' => 'H:i — required, after start_time'],
        ['key' => 'banners[]', 'type' => 'file', 'src' => [], 'description' => 'Required image (add more banners[] rows)'],
        ['key' => 'banners[]', 'type' => 'file', 'src' => [], 'description' => 'Second banner image'],
    ];
}

function requestUrl(array $req): string
{
    $url = $req['url'] ?? '';
    if (is_string($url)) {
        return $url;
    }

    return $url['raw'] ?? '';
}

function applyFormdataBody(array &$req, array $formdata): void
{
    $headers = [];
    foreach ($req['header'] ?? [] as $h) {
        if (strtolower($h['key'] ?? '') === 'content-type') {
            continue;
        }
        $headers[] = $h;
    }
    $req['header'] = $headers;
    $req['body'] = ['mode' => 'formdata', 'formdata' => $formdata];
}

function appendBodyDocs(string $description, string $block): string
{
    if (str_contains($description, $block)) {
        return $description;
    }

    return rtrim($description) . "\n\n" . $block;
}

function registerBodyDocs(): string
{
    return <<<'MD'
### Body (`multipart/form-data`)

| Field | Required | Notes |
|-------|----------|-------|
| `owner_name` | yes | string |
| `trade_name` | yes | string |
| `country_code` | yes | e.g. `966` |
| `phone` | yes | Saudi mobile, starts with `5` |
| `password` | yes | min 8, letters + numbers |
| `password_confirmation` | yes | must match `password` |
| `national_id` | yes | 10 digits |
| `category_id` | yes | exists in `categories` |
| `commercial_register_num` | yes | 10–16 digits |
| `lat` / `lng` / `map_desc` | yes | location |
| `start_time` | yes | `H:i` e.g. `08:00` |
| `end_time` | yes | `H:i`, after `start_time` |
| `banners[]` | yes | one or more image files (jpeg/png/jpg) |
| `accept_terms` | yes | `1` / `true` / `on` |

Do **not** set `Content-Type` manually — Postman adds the multipart boundary.
MD;
}

function updateProfileBodyDocs(): string
{
    return <<<'MD'
### Body (`multipart/form-data`)

| Field | Required | Notes |
|-------|----------|-------|
| `owner_name` | yes | |
| `trade_name` | yes | |
| `category_id` | yes | |
| `commercial_register_num` | yes | 10–16 digits |
| `start_time` | yes | `H:i` |
| `end_time` | yes | after `start_time` |
| `banners[]` | yes | one or more image files |

Requires `Authorization: Bearer {{provider_token}}`.
MD;
}

function formdataHasLegacyProviderProfile(array $req): bool
{
    foreach ($req['body']['formdata'] ?? [] as $row) {
        if (in_array($row['key'] ?? '', ['clinic_name[ar]', 'license_image', 'owner_image'], true)) {
            return true;
        }
    }

    return false;
}

function patchRequest(array &$req, int &$patched): void
{
    $url = requestUrl($req);
    $method = strtoupper($req['method'] ?? '');

    if (str_contains($url, '/api/provider/register') && $method === 'POST') {
        applyFormdataBody($req, providerRegisterFormdata());
        $req['description'] = appendBodyDocs($req['description'] ?? '', registerBodyDocs());
        $patched++;

        return;
    }

    $isUpdateProfile = str_contains($url, '/api/provider/update/profile')
        || (str_contains($url, 'provider') && str_contains($url, 'update/profile') && formdataHasLegacyProviderProfile($req));

    if ($isUpdateProfile && $method === 'POST') {
        applyFormdataBody($req, providerUpdateProfileFormdata());
        $req['description'] = appendBodyDocs($req['description'] ?? '', updateProfileBodyDocs());
        $patched++;
    }
}

function walk(array &$items, int &$patched): void
{
    foreach ($items as $index => &$item) {
        if (isset($item['item'])) {
            walk($item['item'], $patched);
            continue;
        }

        if (isset($item['request'])) {
            patchRequest($item['request'], $patched);
        }

        if (isset($item['response']) && is_array($item['response'])) {
            foreach ($item['response'] as $ri => $_ignored) {
                if (isset($item['response'][$ri]['originalRequest'])) {
                    patchRequest($item['response'][$ri]['originalRequest'], $patched);
                }
            }
        }

        if (($item['name'] ?? '') === 'Register' && str_contains($item['description'] ?? '', 'POST /api/provider/register')) {
            $item['description'] = appendBodyDocs($item['description'] ?? '', registerBodyDocs());
        }
        if (($item['name'] ?? '') === 'Update Profile' && str_contains($item['description'] ?? '', 'POST /api/provider/update/profile')) {
            $item['description'] = appendBodyDocs($item['description'] ?? '', updateProfileBodyDocs());
        }
    }
    unset($item);
}

foreach ($files as $path) {
    if (!is_file($path)) {
        fwrite(STDERR, "Skip missing: {$path}\n");
        continue;
    }

    $root = json_decode(file_get_contents($path), true);
    if (!$root) {
        fwrite(STDERR, "Invalid JSON: {$path}\n");
        exit(1);
    }

    $wrapped = isset($root['collection']) && is_array($root['collection']);
    $collection = $wrapped ? $root['collection'] : $root;

    if (!isset($collection['item']) || !is_array($collection['item'])) {
        fwrite(STDERR, "Skip (no item tree): {$path}\n");
        continue;
    }

    $patched = 0;
    walk($collection['item'], $patched);

    $output = $wrapped ? ['collection' => $collection] : $collection;
    file_put_contents(
        $path,
        json_encode($output, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
    );

    echo basename($path) . ": patched {$patched} request(s)\n";
}
