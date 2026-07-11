/**
 * JSONP Interceptor.
 */

import jsonpClient from '../client/jsonp.js';

export default function (request) {

    if (request.method == 'JSONP') {
        request.client = jsonpClient;
    }

}
