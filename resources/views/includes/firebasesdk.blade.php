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
  const analytics = getAnalytics(app);
</script>