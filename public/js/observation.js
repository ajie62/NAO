var map, marker;
var center = { lat: 46.53924000000001, lng: 2.4301890000000412 };
var obsMap = document.getElementById('map');
var errorElmt = document.getElementById('error');

// latitude & longitude inputs
var obsLng = document.getElementById('observation_longitude');
var obsLat = document.getElementById('observation_latitude');

// Initiate a map thanks to Google Maps API. Used as callback in view
function initMap() {
    map = new google.maps.Map(obsMap, { center: center, zoom: 8 });

    if (obsLat.value.length === 0 && obsLng.value.length === 0) {
        startWatch();
    } else {
        var latLng = new google.maps.LatLng(obsLat.value, obsLng.value);
        map.setCenter(latLng);
        placeMarker(latLng);
    }

    // place a unique marker on the map & add coordinates into inputs
    google.maps.event.addListener(map, 'click', function(event) {
        placeMarker(event.latLng);
        if (obsLng !== null) { obsLng.value = event.latLng.lng(); }
        if (obsLat !== null) { obsLat.value = event.latLng.lat(); }
    });
}

function placeMarker(location) {
    marker ? marker.setPosition(location) : marker = new google.maps.Marker({
        position: location, map: map, animation: google.maps.Animation.DROP
    });
}

// Geolocation
function startWatch() {
    var positionOptions = { timeout: 10000, enableHighAccuracy: true, maximumAge: 10000, frequency: 3000 };
    if (window.navigator.geolocation)
        navigator.geolocation.watchPosition(handleData, handleError, positionOptions);
}

function handleData(geoData) {
    if (errorElmt.innerHTML.length > 0)
        errorElmt.innerHTML = '';

    var userPosition = { lat: geoData.coords.latitude, lng: geoData.coords.longitude };
    map.setCenter(userPosition);
}

function handleError(error) {
    switch (error.code) {
        case 1:
            errorElmt.innerHTML = '<p>Vous n\'avez pas donné la permission d\'utiliser la géolocalisation.</p>';
            break;
        case 2:
            errorElmt.innerHTML = '<p>Impossible de déterminer votre position. Veuillez réessayer s\'il vous plaît.</p>';
            break;
        case 3:
            errorElmt.innerHTML = '<p>La géolocalisation prend plus de temps que prévu...</p>';
            break;
    }
}

// Handle inputs depending on deceased checkbox
var deathCause = document.getElementById('observation_deathCause');
var deathCauseParent = deathCause.parentNode;
var flightDirection = document.getElementById('observation_flightDirection');
var flightDirectionParent = flightDirection.parentNode;
var deceasedCheckbox = document.getElementById('observation_deceased');

deathCauseParent.style.display = 'none';

// Click handler
deceasedCheckbox.addEventListener('change', function() {
    if (deathCauseParent.style.display === 'none') {
        deathCauseParent.style.display = 'block';
    } else {
        deathCauseParent.style.display = 'none';
        deathCause.selectedIndex = 0;
    }

    if (flightDirectionParent.style.display === 'none') {
        flightDirectionParent.style.display = 'block';
    } else {
        flightDirectionParent.style.display = 'none';
    }
});