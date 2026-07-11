<?php

/**
 * Collection-level Postman scripts: paced requests, reliable auth, rate-limit recovery.
 *
 * @return array<int, array{listen: string, script: array{type: string, exec: string[]}}>
 */
function postmanCollectionAuthEvents(): array
{
    $sharedHelpers = <<<'JS'
const ONE = {
    publicPaths: ['/login', '/register', '/activate', '/resend-code', '/password/', '/app-intro', '/terms'],
    globalPublic: [
        '/api/settings', '/api/is-production', '/api/main', '/api/countries', '/api/cities',
        '/api/contact', '/api/who-is-us', '/api/about', '/api/privacy', '/api/socials', '/api/export-pdf'
    ],
    gapMs() {
        const v = parseInt(pm.collectionVariables.get('one_request_gap_ms') || '3000', 10);
        return Number.isFinite(v) && v >= 1000 ? v : 3000;
    },
    loginGapMs() {
        const v = parseInt(pm.collectionVariables.get('one_login_gap_ms') || '8000', 10);
        return Number.isFinite(v) && v >= 2000 ? v : 8000;
    },
    applyPostmanHeaders() {
        pm.request.headers.upsert({ key: 'X-ONE-Postman', value: '1' });
    },
    apiHeaders(extra) {
        return Object.assign({ Accept: 'application/json', lang: 'ar', 'X-ONE-Postman': '1' }, extra || {});
    },
    maxRetries() {
        const v = parseInt(pm.collectionVariables.get('one_max_retries') || '3', 10);
        return Number.isFinite(v) && v >= 1 ? Math.min(v, 5) : 3;
    },
    tokenVar(role) { return role === 'client' ? 'client_token' : 'provider_token'; },
    loginAtVar(role) { return role === 'client' ? 'client_login_at' : 'provider_login_at'; },
    lastReqVar() { return 'one_last_request_at'; },
    lastLoginVar(role) { return role === 'client' ? 'one_last_client_login_at' : 'one_last_provider_login_at'; },
    lockVar(role) { return role === 'client' ? 'client_login_lock' : 'provider_login_lock'; },
    isPublic(url) {
        return this.publicPaths.some((p) => url.includes(p))
            || this.globalPublic.some((p) => url.includes(p));
    },
    roleFromUrl(url) {
        if (url.includes('/api/provider/')) return 'provider';
        if (url.includes('/api/client/')) return 'client';
        return null;
    },
    isValidToken(token) {
        const t = String(token || '').trim();
        return t.length > 30 && !t.includes('{{') && !t.includes('}}');
    },
    isRateLimited(json) {
        if (!json || json.key !== 'fail') return false;
        return String(json.msg || '').toLowerCase().includes('too many request');
    },
    isUnauthenticated(json) {
        return json && json.key === 'unauthenticated';
    },
    sleep(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
    },
    retryAfterMs(res) {
        if (!res || !res.headers) return 65000;
        const h = res.headers;
        const raw = h.get ? (h.get('Retry-After') || h.get('retry-after')) : (h['Retry-After'] || h['retry-after']);
        const sec = parseInt(raw, 10);
        return Number.isFinite(sec) && sec > 0 ? (sec * 1000) + 2000 : 65000;
    },
    async paceBeforeRequest(isLogin) {
        const gap = isLogin ? this.loginGapMs() : this.gapMs();
        const lastKey = isLogin ? this.lastReqVar() + '_login' : this.lastReqVar();
        const last = parseInt(pm.collectionVariables.get(lastKey) || '0', 10);
        const wait = gap - (Date.now() - last);
        if (wait > 0) {
            console.log('[ONE] Pacing ' + Math.round(wait / 1000) + 's before ' + (isLogin ? 'login' : 'request') + '…');
            await this.sleep(wait);
        }
        pm.collectionVariables.set(lastKey, String(Date.now()));
    },
    getToken(role) {
        return String(pm.collectionVariables.get(this.tokenVar(role)) || '').trim();
    },
    applyAuthHeader(token) {
        if (!this.isValidToken(token)) return;
        pm.request.headers.upsert({ key: 'Authorization', value: 'Bearer ' + token });
    },
    collectRequestHeaders(token) {
        const headerObj = this.apiHeaders({ Authorization: 'Bearer ' + token });
        pm.request.headers.each((h) => {
            const k = (h.key || '').toLowerCase();
            if (k === 'authorization' || k === 'accept' || k === 'lang') return;
            headerObj[h.key] = pm.variables.replaceIn(h.value);
        });
        return headerObj;
    },
    loginPayload(role) {
        const phone = role === 'client' ? '512345678' : '512345679';
        return {
            country_code: '966',
            phone,
            password: 'Password123',
            device_type: 'android',
            device_id: role === 'client' ? 'postman-device-1' : 'postman-device-provider',
            mac_address: role === 'client' ? '00:11:22:33:44:55' : '00:11:22:33:44:56',
            preferred_locale: 'ar'
        };
    },
    async sendLogin(role) {
        await this.paceBeforeRequest(true);
        const baseUrl = pm.collectionVariables.get('base_url');
        return pm.sendRequest({
            url: baseUrl + '/api/' + role + '/login',
            method: 'POST',
            header: this.apiHeaders({ 'Content-Type': 'application/json' }),
            body: {
                mode: 'raw',
                raw: JSON.stringify(this.loginPayload(role))
            }
        });
    },
    async login(role, force) {
        const varName = this.tokenVar(role);
        let existing = this.getToken(role);

        if (!force && this.isValidToken(existing)) {
            this.applyAuthHeader(existing);
            return existing;
        }

        const lockVar = this.lockVar(role);
        let attempts = 0;
        while (pm.collectionVariables.get(lockVar) === '1' && attempts < 30) {
            await this.sleep(500);
            attempts++;
            existing = this.getToken(role);
            if (this.isValidToken(existing)) {
                this.applyAuthHeader(existing);
                return existing;
            }
        }

        pm.collectionVariables.set(lockVar, '1');
        let res = await this.sendLogin(role);
        let json = res.json();
        let tries = 0;

        while (this.isRateLimited(json) && tries < this.maxRetries()) {
            const waitMs = this.retryAfterMs(res);
            console.warn('[ONE] Login rate-limited, waiting ' + Math.round(waitMs / 1000) + 's…');
            await this.sleep(waitMs);
            res = await this.sendLogin(role);
            json = res.json();
            tries++;
        }

        pm.collectionVariables.unset(lockVar);

        const token = json && json.data && json.data.token ? json.data.token : '';
        if (this.isValidToken(token)) {
            pm.collectionVariables.set(varName, token);
            pm.collectionVariables.set(this.loginAtVar(role), String(Date.now()));
            this.applyAuthHeader(token);
            console.log('[ONE] Login OK → ' + varName);
            return token;
        }

        console.warn('[ONE] Login failed for ' + role, json);
        return '';
    },
    async ensureAuth(role) {
        let token = this.getToken(role);
        if (!this.isValidToken(token)) {
            token = await this.login(role, true);
        } else {
            this.applyAuthHeader(token);
        }
        return token;
    },
    async sendCurrentRequest(token) {
        await this.paceBeforeRequest(false);
        const retryBody = pm.request.body && pm.request.body.mode ? pm.request.body : undefined;
        return pm.sendRequest({
            url: pm.variables.replaceIn(pm.request.url.toString()),
            method: pm.request.method,
            header: this.collectRequestHeaders(token),
            body: retryBody
        });
    },
    async retryOnce(role, token, label, firstJson, firstRes) {
        if (this.isUnauthenticated(firstJson)) {
            console.warn('[ONE] ' + label + ' — refreshing token…');
            pm.collectionVariables.unset(this.tokenVar(role));
            token = await this.login(role, true);
            if (!this.isValidToken(token)) {
                return { json: firstJson, token };
            }
            const res = await this.sendCurrentRequest(token);
            return { json: res.json(), token };
        }

        if (this.isRateLimited(firstJson)) {
            const waitMs = this.retryAfterMs(firstRes);
            console.warn('[ONE] ' + label + ' rate-limited — wait ' + Math.round(waitMs / 1000) + 's, one retry…');
            await this.sleep(waitMs);
            token = this.getToken(role);
            const res = await this.sendCurrentRequest(this.isValidToken(token) ? token : '');
            return { json: res.json(), token };
        }

        return { json: firstJson, token };
    }
};
JS;

    $prerequest = $sharedHelpers . <<<'JS'

const url = pm.request.url.toString();

ONE.applyPostmanHeaders();
await ONE.paceBeforeRequest(url.includes('/login'));

if (ONE.isPublic(url)) {
    // no auth
} else {
    const role = ONE.roleFromUrl(url);
    if (role) {
        await ONE.ensureAuth(role);
    }
}
JS;

    $testRetry = $sharedHelpers . <<<'JS'

(async () => {
    let json;
    try {
        json = pm.response.json();
    } catch (e) {
        return;
    }

    const url = pm.request.url.toString();
    if (ONE.isPublic(url) || url.includes('/login')) return;

    const role = ONE.roleFromUrl(url);
    if (!role) return;

    if (!ONE.isRateLimited(json) && !ONE.isUnauthenticated(json)) {
        return;
    }

    let token = ONE.getToken(role);
    const { json: retryJson } = await ONE.retryOnce(role, token, pm.info.requestName, json, pm.response);

    if (ONE.isRateLimited(retryJson) || ONE.isUnauthenticated(retryJson)) {
        pm.test('Request succeeded after auto-recovery', function () {
            pm.expect.fail('Still ' + retryJson.key + ': ' + retryJson.msg + ' — wait 1 min or run Auth → Login');
        });
        return;
    }

    console.log('[ONE] Recovered:', retryJson.key, retryJson.msg);
    pm.test('Request succeeded (auto auth / pace recovery)', function () {
        pm.expect(retryJson.key).to.eql('success');
    });
})();
JS;

    $toExec = static function (string $script): array {
        return array_values(array_filter(
            explode("\n", str_replace("\r\n", "\n", $script)),
            static fn ($line) => $line !== null
        ));
    };

    return [
        [
            'listen' => 'prerequest',
            'script' => [
                'type' => 'text/javascript',
                'exec' => $toExec($prerequest),
            ],
        ],
        [
            'listen' => 'test',
            'script' => [
                'type' => 'text/javascript',
                'exec' => $toExec($testRetry),
            ],
        ],
    ];
}

function postmanCollectionAuthVariables(): array
{
    return [
        ['key' => 'one_request_gap_ms', 'value' => '3000', 'description' => 'Min ms between API requests (default 3s)'],
        ['key' => 'one_login_gap_ms', 'value' => '8000', 'description' => 'Min ms between login calls (default 8s)'],
        ['key' => 'one_max_retries', 'value' => '1', 'description' => 'Recovery retries (keep 1 to avoid bursts)'],
    ];
}

function postmanCollectionAuthDescription(): string
{
    return "ONE App API — Client & Provider.\n\n"
        . "**Paced requests:** Waits 3s between calls (8s between logins). Header `X-ONE-Postman: 1` skips throttle on dev.\n"
        . "**Auto-auth:** Ensures a valid token before every protected request.\n"
        . "**Auto-recovery:** On `unauthenticated` or `too many request`, waits and retries.\n\n"
        . "Variables: `one_request_gap_ms`, `one_login_gap_ms`, `one_max_retries`\n\n"
        . "Demo: Client `966`/`512345678`, Provider `966`/`512345679`, password `Password123`";
}

function mergePostmanCollectionAuth(array &$collection): void
{
    $collection['event'] = postmanCollectionAuthEvents();
    $collection['info']['description'] = postmanCollectionAuthDescription();

    $existing = [];
    foreach ($collection['variable'] ?? [] as $v) {
        $existing[$v['key'] ?? ''] = true;
    }
    foreach (postmanCollectionAuthVariables() as $v) {
        $key = $v['key'];
        $updated = false;
        foreach ($collection['variable'] as &$cv) {
            if (($cv['key'] ?? '') === $key) {
                $cv['value'] = $v['value'];
                $cv['description'] = $v['description'] ?? ($cv['description'] ?? '');
                $updated = true;
                break;
            }
        }
        unset($cv);
        if (!$updated) {
            $collection['variable'][] = $v;
        }
    }
}
