$(document).ready(function () {
    $.getJSON("http://jsonip.com/?callback=?", function (data) {
        console.log('DATA : ');
        console.log(data);
        $.ajax({
            url : 'geoloc',
            type : 'POST',
            data : data,
            dataType : 'json', // On désire recevoir du HTML
            success : function(response){ // code_html contient le HTML renvoyé
                console.log('RESPONSE : ');
                console.log(response);
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: response
                });
                var marker = new google.maps.Marker({
                    position: response,
                    map: map
                });
            }
        });
    });

    //var APIkey = 'AIzaSyDgncS4rwdSr_OB9ak6sVLeGn9e6--vQks';
    function initMap() {}
});