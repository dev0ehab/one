/**
 * Before Interceptor.
 */

import {isFunction} from '../../util.js';

export default function (request) {

    if (isFunction(request.before)) {
        request.before.call(this, request);
    }

}
