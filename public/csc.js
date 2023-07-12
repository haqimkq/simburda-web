$(document).ready(function(){


    // var host = window.location.href;    


   $("#country").change(function() {
        document.getElementById('city-field').style.display ='block !important';
        $.getJSON('http://127.0.0.1:8000/api' + "/city/" + $("#country option:selected").val(), function(data) {

            console.log(data);

            var temp = [];

            //CONVERT INTO ARRAY

            $.each(data, function(key, value) {

                temp.push({v:value, k: key});

            });

            //SORT THE ARRAY

            temp.sort(function(a,b){

                if(a.v > b.v){ return 1}

                if(a.v < b.v){ return -1}

                    return 0;

            });

            //APPEND INTO SELECT BOX
            $('#city').empty();

            $.each(temp, function(key, obj) {

                $('#city').append('<option value="' + obj.v+'">' + obj.v + '</option>');           

            });            

        });   

            

    }); 
});//end of document ready