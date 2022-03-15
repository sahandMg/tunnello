// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: "AIzaSyB_TBEcGdBzO4enoT8DNeD34OV2Yc8UABs",
    authDomain: "tunnello-aef38.firebaseapp.com",
    projectId: "tunnello-aef38",
    storageBucket: "tunnello-aef38.appspot.com",
    messagingSenderId: "731744527215",
    appId: "1:731744527215:web:de0d2a5df091152d743e9a"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "./images/tunnello.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});