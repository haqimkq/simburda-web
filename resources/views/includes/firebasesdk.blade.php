{{-- Put inside head --}}
<script type="module">
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.1/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.22.1/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "@env('FIREBASE_SERVER_KEY')",
    authDomain: "burda-contraco.firebaseapp.com",
    databaseURL: "https://burda-contraco-default-rtdb.firebaseio.com",
    projectId: "burda-contraco",
    storageBucket: "burda-contraco.appspot.com",
    messagingSenderId: "522699406443",
    appId: "1:522699406443:web:13e1437c0473a52530c52e",
    measurementId: "G-9S4HPEYXVM"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const messaging = getMessaging(app);

  function startFCM() {
      messaging
          .requestPermission()
          .then(function () {
              return messaging.getToken()
          })
          .then(function (response) {
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $.ajax({
                  url: '{{ route("store.token") }}',
                  type: 'POST',
                  data: {
                      token: response
                  },
                  dataType: 'JSON',
                  success: function (response) {
                      alert('Token stored.');
                  },
                  error: function (error) {
                      alert(error);
                  },
              });
          }).catch(function (error) {
              alert(error);
          });
    }
    messaging.onMessage(function (payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });
</script>