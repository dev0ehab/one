// import VueChatScroll from './modules/vue-chat-scroll/src/vue-chat-scroll.js';
// import VueResource from './modules/vue-resource/src/index.js';

// Load timeago.js from CDN or use a simple fallback
// We'll create a simple timeago implementation
window.timeago = {
  format: function(date, locale) {
    const now = new Date();
    const diff = now - new Date(date);
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);
    
    if (locale === 'ar') {
      if (minutes < 1) return 'الآن';
      if (minutes < 60) return `منذ ${minutes} دقيقة`;
      if (hours < 24) return `منذ ${hours} ساعة`;
      return `منذ ${days} يوم`;
    } else {
      if (minutes < 1) return 'now';
      if (minutes < 60) return `${minutes} minutes ago`;
      if (hours < 24) return `${hours} hours ago`;
      return `${days} days ago`;
    }
  },
  register: function() {
    // No-op for compatibility
  }
};

// vue resource
Vue.use(VueResource);
Vue.http.headers.common["X-CSRF-TOKEN"] = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// chat scroll
// Vue.use(VueChatScroll)

///////////////////// socket /////////////////////////
var NODE_HOST = $("meta[name=NODE_HOST]").attr("content");
var APP_URL = $("meta[name=APP_URL]").attr("content");
var NODE_PORT = $("meta[name=NODE_PORT]").attr("content");
var NODE_MODE = $("meta[name=NODE_MODE]").attr("content");
var USER_ID = Number($("meta[name=user_id]").attr("content"));
var USER_TYPE = $("meta[name=user_type]").attr("content");
var USER_NAME = $("meta[name=user_name]").attr("content");
var USER_AVATAR = $("meta[name=user_avatar]").attr("content");
var LANG = $("meta[name=lang]").attr("content");
var ROOM_ID = $("meta[name=room_id]").attr("content");
window.USER_ID = USER_ID;
window.USER_TYPE = USER_TYPE;
window.USER_NAME = USER_NAME;
window.USER_AVATAR = USER_AVATAR;
window.LANG = LANG;
window.ROOM_ID = ROOM_ID;

var url = NODE_MODE == 'test' ? NODE_HOST : APP_URL;
url += ':' + NODE_PORT;
console.log(url)
Vue.use(new VueSocketIO({
    debug: false,
    connection: url,
    options: {
        query: {
            sender_id: USER_ID || 1,
            sender_type: USER_TYPE || 'user',
            sender_name: USER_NAME || 'User',
            avatar: USER_AVATAR || 'default.png',
            room_id: ROOM_ID || 1,
        }
    } //Optional options
}));


// components

Vue.component('chat-component', httpVueLoader('/chat/js/components/ChatComponent.vue'));

// const files = require.context("./", true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split("/").pop().split(".")[0], httpVueLoader(files(key).default)));

const app = new Vue({
    el: "#app",
    mounted() {
        // console.log('mounted');

    },
    sockets: {
        connect: function () {
            // console.log('socket connected')
        }
    },

});
