/**
 * HTTP Request.
 */

import Url from '../url/index.js';
import Headers from './headers.js';
import Response from './response.js';
import {assign, toUpper} from '../util.js';

export default class Request {

    constructor(options) {

        this.body = null;
        this.params = {};

        assign(this, options, {
            method: toUpper(options.method || 'GET')
        });

        if (!(this.headers instanceof Headers)) {
            this.headers = new Headers(this.headers);
        }
    }

    getUrl() {
        return Url(this);
    }

    getBody() {
        return this.body;
    }

    respondWith(body, options) {
        return new Response(body, assign(options || {}, {url: this.getUrl()}));
    }

}
