var map, marker;
var center = { lat: 46.53924000000001, lng: 2.4301890000000412 };
var obsMap = document.getElementById('js-search-map');

/** Initiate a map with Google Maps API. Used as callback in view */
function searchMap() {
    map = new google.maps.Map(obsMap, {
        center: center,
        zoom: 6,
        streetViewControl: false,
        mapTypeControl: false,
        gestureHandling: 'cooperative',
        scrollwheel: false,
        styles: [
            {
                "stylers": [
                    {
                        "hue": "#baf4c4"
                    },
                    {
                        "saturation": 10
                    }
                ]
            },
            {
                "featureType": "water",
                "stylers": [
                    {
                        "color": "#effefd"
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            }
        ]
    });
}