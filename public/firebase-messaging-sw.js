// importScripts('https://www.gstatic.com/firebasejs/4.9.1/firebase.js');

importScripts("https://www.gstatic.com/firebasejs/7.6.1/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/7.6.1/firebase-messaging.js");
importScripts("https://www.gstatic.com/firebasejs/7.6.1/firebase-analytics.js");
importScripts("https://www.gstatic.com/firebasejs/7.6.1/firebase-auth.js");
importScripts("https://www.gstatic.com/firebasejs/7.6.1/firebase-firestore.js");

// Initialize Firebase
firebase.initializeApp({
    apiKey: "AIzaSyCS0Cg7xknz62D8gkvw5rHU-JZaHHDrEl4",
    authDomain: "sharikat-awan-almahaliya.firebaseapp.com",
    projectId: "sharikat-awan-almahaliya",
    storageBucket: "sharikat-awan-almahaliya.firebasestorage.app",
    messagingSenderId: "874808736383",
    appId: "1:874808736383:web:d6eac212bbb489141d9d4a",
    measurementId: "G-FY8NDVH2SH"
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    const notificationTitle = payload.data.title;
    const notificationOptions = {
        title: payload.data.title,
        body: payload.data.body_ar,
    };

    return self.registration.showNotification(notificationTitle,
        notificationOptions);
});
