var map, marker, observations;
var center = { lat: 46.53924000000001, lng: 2.4301890000000412 };
var obsMap = document.getElementById('js-search-map');
var markers = [];
var speciesObj = null;

$(function() {
    var $inputContainer = $('#input-container');
    const GET_URL = $inputContainer.attr('data-get-url');
    const SEARCH_URL = $inputContainer.attr('data-search-url');
    var $input = $('#input');
    var $results = $('#results');
    var listOfSpecies = [];

    // When user types a letter in input
    $input.on('keyup', function() {
        var userInput = $(this).val();

        if (userInput.length >= 1) {
            $.ajax({type: "POST", url: GET_URL, data: { input: userInput }, dataType: 'json', timeout: 3000,
                success: function(response) {
                    var listOfChoices = [];
                    if (response) {
                        for (var i = 0; i < response.count; i++) {
                            var species = createAndHydrateSpeciesWithAjax(response, i);
                            listOfSpecies.push(species);
                            listOfChoices.push('<li id="'+ species.id +'">' + species.name + '</li>');
                        }

                        var speciesList = '<ul id="speciesList">'+ listOfChoices.join('') +'</ul>';
                        $results.html(speciesList);
                    } else {
                        $results.empty();
                    }
                }
            });
        } else if (userInput.length === 0) {
            $results.empty();
        }
    });

    $(document).on('click', '#speciesList li', function() {
        var selectedSpeciesId = $(this).attr('id') || null;

        if (!!selectedSpeciesId) {
            $input.val($(this).text());
            $results.empty();
            speciesObj = listOfSpecies.find(function (element) {
                if (element.id == selectedSpeciesId;) return element;
            });
        }

        $.ajax({type: "POST", url: SEARCH_URL, data: { id: selectedSpeciesId }, dataType: 'json', timeout: 3000,
            success: function(response) {
                if (response) {
                    observations = response;

                    for (var i = 0; i < observations.length; i++) {
                        var location = { lat: observations[i].latitude, lng: observations[i].longitude };
                        addMarker(location, observations[i], speciesObj);
                    }
                }
            }
        });
        $results.empty();
    });
});

var Species = {
    id: 0,
    name: '',
    family: '',
    order: ''
};

function createAndHydrateSpeciesWithAjax(ajaxResponse, iterator) {
    var species = Object.create(Species);
    species.id = ajaxResponse.items[iterator].id;
    species.name = ajaxResponse.items[iterator].name;
    species.family = ajaxResponse.items[iterator].family;
    species.order = ajaxResponse.items[iterator].order;

    return species;
}

/** Initiate a map with Google Maps API. Used as callback in view */
function searchMap() {
    map = new google.maps.Map(obsMap, {
        center: center,
        zoom: 6,
        streetViewControl: false,
        mapTypeControl: false,
        gestureHandling: 'cooperative',
        scrollwheel: false
    });
}

// Adds a marker to the map and push to the array
function addMarker(location, observation, species) {
    var animation = google.maps.Animation.DROP;
    var marker = new google.maps.Marker({ position: location, map: map, animation: animation });

    marker.addListener('click', function() {
        createInfoWindow(marker, observation, species)
    });

    markers.push(marker);
}

// Sets the map on all markers in the array
function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}

// Removes the markers from the map, but keeps them in the array
function clearMarkers() {
    setMapOnAll(null);
}

// Shows any markers currently in the array
function showMarkers() {
    setMapOnAll(map);
}

// Deletes all markers in the array by removing references to them
function deleteMarkers() {
    clearMarkers();
    markers = [];
}

// Creates all info windows for the markers
function createInfoWindow(marker, observation, species) {
    var content = '<div><h3>' + species.name + '</h3><img src="images/'+ observation.image.id + '.' + observation.image.url +'" /></div>';
    var infoWindow = new google.maps.InfoWindow({ content: content });
    infoWindow.open(map, marker);
}