/**
 * CORS Interceptor.
 */

import Url from '../../url/index.js';
import xdrClient from '../client/xdr.js';
import {inBrowser} from '../../util.js';

const SUPPORTS_CORS = inBrowser && 'withCredentials' in new XMLHttpRequest();

export default function (request) {

    if (inBrowser) {

        const orgUrl = Url.parse(location.href);
        const reqUrl = Url.parse(request.getUrl());

        if (reqUrl.protocol !== orgUrl.protocol || reqUrl.host !== orgUrl.host) {

            request.crossOrigin = true;
            request.emulateHTTP = false;

            if (!SUPPORTS_CORS) {
                request.client = xdrClient;
            }
        }
    }

}
