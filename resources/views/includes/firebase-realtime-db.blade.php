{{-- Put inside body --}}
<script type="module">
    // import { initializeApp } from "firebase/app";
    // import { getDatabase, ref, onValue  } from "firebase/database";
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.1/firebase-app.js";
    import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/9.22.1/firebase-database.js";
    const firebaseConfig = {
        apiKey: "{{ env('FIREBASE_SERVER_KEY') }}",
        authDomain: "burda-contraco.firebaseapp.com",
        databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}",
        projectId: "burda-contraco",
        storageBucket: "burda-contraco.appspot.com",
        messagingSenderId: "522699406443",
        appId: "1:522699406443:web:13e1437c0473a52530c52e",
        measurementId: "G-9S4HPEYXVM"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const database  = getDatabase(app);

    // const logisticRef = ref(database, "logistic/{{ $deliveryorder->logistic_id }}");
    // // console.log(logisticRef)
    // onValue(logisticRef, (snapshot) => {
    //     const data = snapshot.val();
    //     console.log(data)
    // });
    @stack('script-rtdb')
</script>