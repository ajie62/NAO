var map, marker;
var center = { lat: 46.53924000000001, lng: 2.4301890000000412 };

// Initiate a map thanks to Google Maps API. Used as callback in view
function initMap() {
    map = new google.maps.Map(document.getElementById('map'), { center: center, zoom: 8 });
    startWatch();

    // latitude & longitude inputs
    var obsLongitude = document.getElementById('observation_longitude');
    var obsLatitude = document.getElementById('observation_latitude');

    // place a unique marker on the map & add coordinates into inputs
    google.maps.event.addListener(map, 'click', function(event) {
        placeMarker(event.latLng);
        if (obsLongitude !== null) { obsLongitude.value = event.latLng.lng(); }
        if (obsLatitude !== null) { obsLatitude.value = event.latLng.lat(); }
    });
}

function placeMarker(location) {
    marker ? marker.setPosition(location) : marker = new google.maps.Marker({
        position: location, map: map, animation: google.maps.Animation.DROP
    });
}

// Geolocation
function startWatch() {
    var positionOptions = { timeout: 10000, enableHighAccuracy: true, maximumAge: 10000 };
    if (window.navigator.geolocation)
        navigator.geolocation.watchPosition(handleData, handleError, positionOptions);
}

function handleData(geoData) {
    var userPosition = { lat: geoData.coords.latitude, lng: geoData.coords.longitude };
    map.setCenter(userPosition);
}

function handleError(error) {
    document.getElementById('error').innerHTML = error.message;
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