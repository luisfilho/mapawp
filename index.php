<style>
    #map {
        height: 900px;
    }
    .controls {
      margin-top: 10px;
      border: 1px solid transparent;
      border-radius: 2px 0 0 2px;
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      height: 32px;
      outline: none;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    #input-localidade {
      background-color: #fff !important;
        font-size: 14px !important;
        padding: 20px !important;
        top: 112px !important;
        left: 60px !important;
        width: 400px !important;
        color: #000 !important;
      
    }
    #type-selector {
      color: #fff;
      background-color: #4d90fe;
      padding: 5px 11px 0px 11px;
    }

    #type-selector label {
      font-family: Roboto;
      font-size: 13px;
      font-weight: 300;
    }

    #target {
        width: 345px;
    }
    .txt-lojas{
         position: absolute;
        top: 120px;
        left: 60px;
        z-index: 99999;
        background: #000;
        padding: 10px;
        font-family: 'globerblack' !important;
        color: #ffc905;
}

</style>

<script>
    var map;
var infowindow;

$('#input-localidade').change(function() {
this.value;
})


function createMarker(place) {
coords = place.coord.split(',');
coords[0] = eval(coords[0]);
coords[1] = eval(coords[1]);
position = {lat: coords[0], lng: coords[1]};

if(position.lat && position.lng) {
var marker = new google.maps.Marker({
    map: map,
    icon: 'http://bebelu.com.br/wp-content/themes/bebelu16/images/maps.svg',
    position: position,
    local: place
});

google.maps.event.addListener(marker, 'click', function() {
    
    content  = "<b>localidade: </b>" + this.local.localidade;
    content  += "<br><b>Cidade: </b>" + this.local.cidade + "-" + this.local.uf;
    content  += "<br><b>Endereço: </b>" + this.local.endereco;
    //content  += "<br><b>Fone: </b>" + this.local.fone;
    //content  += "<br><b>E-mail: </b>" + this.local.mail;
    //content  += "<br><b>Código: </b>" + this.local.bebelu;

    infowindow.setContent(content);
    infowindow.open(map, this);
});	
}  
}

//-- funções para cálculos de distância

function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
var R = 6371; // Radius of the earth in km
var dLat = deg2rad(lat2-lat1);  // deg2rad below
var dLon = deg2rad(lon2-lon1); 
var a = 
Math.sin(dLat/2) * Math.sin(dLat/2) +
Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
Math.sin(dLon/2) * Math.sin(dLon/2)
; 
var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
var d = R * c; // Distance in km
return d;
}

function deg2rad(deg) {
return deg * (Math.PI/180)
}



function initMap() {

var pyrmont = {lat: -3.7701442, lng: -38.5458163};

map = new google.maps.Map(document.getElementById('map'), {
    center: pyrmont,
    zoom: 13
});

infowindow = new google.maps.InfoWindow();

//-- marca os pontos no mapa
for(i = 0; i < bebelus.length; i++) {
    createMarker(bebelus[i]);
}



// Create the search box and link it to the UI element.
var input = document.getElementById('input-localidade');
var searchBox = new google.maps.places.SearchBox(input);
map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

// Bias the SearchBox results towards current map's viewport.
map.addListener('bounds_changed', function() {
searchBox.setBounds(map.getBounds());
});

// [START region_getplaces]
// Listen for the event fired when the user selects a prediction and retrieve
// more details for that place.
searchBox.addListener('places_changed', function() {
var places = searchBox.getPlaces();

if (places.length == 0) {
  return;
}

position = {lat: places[0].geometry.location.lat(), lng: places[0].geometry.location.lng()};

distancia = null;
minimo    = null;
//busca bebelu mais próxima
for(i = 0; i < bebelus.length; i++) {
    coords = bebelus[i].coord.split(',');
    coords[0] = eval(coords[0]);
    coords[1] = eval(coords[1]);
    position2 = {lat: coords[0], lng: coords[1]};

    distancia2 = getDistanceFromLatLonInKm(position.lat,position.lng,position2.lat,position2.lng);
    if(distancia == null || distancia > distancia2) {
        minimo = position2;
        distancia = distancia2;
    }
}
map.setCenter(minimo);
map.setZoom(20);
});




}




</script>

<div id='entradas'>
    <h2 class="txt-lojas">TEM SEMPRE UMA BEBELU PERTINHO DE VOCÊ.</h2>
  <input id='input-localidade' class="controls" type='text' placeholder='Digite sua localidade (CEP ou endereço completo)'></input>
</div>
<div id="map"></div>
<script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSGMxlRdyoIAIDRxQjOKXOYiZHmlFIQrk&signed_in=true&libraries=places&callback=initMap" async defer></script>
