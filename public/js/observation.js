var errorDiv = document.getElementById('error');

// Geolocation variables
var map, marker;
var center = { lat: 46.53924000000001, lng: 2.4301890000000412 };
var obsMap = document.getElementById('map');
var obsLng = document.getElementById('observation_longitude');
var obsLat = document.getElementById('observation_latitude');
var obsLngEmpty = obsLng.value.length === 0;
var obsLatEmpty = obsLat.value.length === 0;

// Form variables
var deathCause = document.getElementById('observation_deathCause').parentNode;
var flightDirection = document.getElementById('observation_flightDirection').parentNode;
var deceasedCheckbox = document.getElementById('observation_deceased');
var styleDeath = deathCause.style;
var styleFlightDir = flightDirection.style;

/** Initiate a map with Google Maps API. Used as callback in view */
function initMap() {
    map = new google.maps.Map(obsMap, { center: center, zoom: 15, mapTypeId: google.maps.MapTypeId.HYBRID });

    // Only if the user creates an observation
    if (obsLatEmpty && obsLngEmpty) {
        startWatch();
    } else {
        var latLng = new google.maps.LatLng(obsLat.value, obsLng.value);
        map.setCenter(latLng);
        placeMarker(latLng);
    }

    // Place a unique marker on the map & add coordinates into inputs
    google.maps.event.addListener(map, 'click', function(e) {
        placeMarker(e.latLng);
        if (obsLng !== null) { obsLng.value = e.latLng.lng(); }
        if (obsLat !== null) { obsLat.value = e.latLng.lat(); }
    });
}

function placeMarker(location) {
    marker ? marker.setPosition(location) : marker = new google.maps.Marker({
        position: location, map: map, animation: google.maps.Animation.DROP
    });
}

function startWatch() {
    var positionOptions = { timeout: 10000, enableHighAccuracy: true, maximumAge: 10000, frequency: 3000 };
    if (window.navigator.geolocation)
        navigator.geolocation.watchPosition(handleData, handleError, positionOptions);
}

function handleData(geoData) {
    if (errorDiv.innerHTML.length > 0)
        errorDiv.innerHTML = '';
    var userPosition = { lat: geoData.coords.latitude, lng: geoData.coords.longitude };
    map.setCenter(userPosition);
}

function handleError(error) {
    switch (error.code) {
        case 1:
            errorDiv.innerHTML = '<p>Vous n\'avez pas donné la permission d\'utiliser la géolocalisation.</p>';
            break;
        case 2:
            errorDiv.innerHTML = '<p>Impossible de déterminer votre position. Veuillez réessayer s\'il vous plaît.</p>';
            break;
        case 3:
            errorDiv.innerHTML = '<p>La géolocalisation prend plus de temps que prévu...</p>';
            break;
        case 4:
            errorDiv.innerHTML = '<p>Une erreur inconnue est survenue.</p>';
            break;
    }
}

// Handling form input display
styleDeath.display = 'none';
deceasedCheckbox.addEventListener('change', function() {
    styleDeath.display = (styleDeath.display === 'none') ? 'block' : 'none';
    styleFlightDir.display = (styleFlightDir.display === 'none') ? 'block' : 'none';
});