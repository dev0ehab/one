/**
 * Install plugin.
 */

import Url from './url/index.js';
import Http from './http/index.js';
import Promise from './promise.js';
import Resource from './resource.js';
import Util, {options} from './util.js';

function plugin(Vue) {

    if (plugin.installed) {
        return;
    }

    Util(Vue);

    Vue.url = Url;
    Vue.http = Http;
    Vue.resource = Resource;
    Vue.Promise = Promise;

    Object.defineProperties(Vue.prototype, {

        $url: {
            get() {
                return options(Vue.url, this, this.$options.url);
            }
        },

        $http: {
            get() {
                return options(Vue.http, this, this.$options.http);
            }
        },

        $resource: {
            get() {
                return Vue.resource.bind(this);
            }
        },

        $promise: {
            get() {
                return (executor) => new Vue.Promise(executor, this);
            }
        }

    });
}

if (typeof window !== 'undefined' && window.Vue && !window.Vue.resource) {
    window.Vue.use(plugin);
}

export default plugin;
