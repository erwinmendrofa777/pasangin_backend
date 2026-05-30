// Scripts for firebase and firebase messaging
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: "AIzaSyB-2VsVBZ1ayN4Qj2Z1bvGSjlzeVasNu8A",
  projectId: "pasangin-c8050",
  messagingSenderId: "1016256565116",
  appId: "1:1016256565116:web:574dc80f84ac3dd2d05ef9"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
  console.log('[sw.js] Background Message received: ', payload);
  
  // Ambil data dari payload.notification (jika ada) atau payload.data
  const title = payload.notification?.title || payload.data?.title || 'Notifikasi Baru';
  const body = payload.notification?.body || payload.data?.message || 'Anda mendapatkan pesan baru.';
  
  const notificationOptions = {
    body: body,
    icon: '/favicon.ico', // Gunakan favicon standar atau icon yang pasti ada
    badge: '/favicon.ico',
    data: payload.data
  };

  return self.registration.showNotification(title, notificationOptions);
});
