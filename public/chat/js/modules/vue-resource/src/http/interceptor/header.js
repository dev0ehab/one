/**
 * Header Interceptor.
 */

import Http from '../index.js';
import {assign, each, toLower} from '../../util.js';

export default function (request) {

    const headers = assign({}, Http.headers.common,
        !request.crossOrigin ? Http.headers.custom : {},
        Http.headers[toLower(request.method)]
    );

    each(headers, (value, name) => {
        if (!request.headers.has(name)) {
            request.headers.set(name, value);
        }
    });

}
