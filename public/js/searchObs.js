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
        scrollwheel: false
    });
}

var markers = [];

$(function() {
    var $inputContainer = $('#input-container');
    const GET_URL = $inputContainer.attr('data-get-url');
    const SEARCH_URL = $inputContainer.attr('data-search-url');
    var $input = $('#input');
    var $results = $('#results');
    var arrayOfSpecies = [];
    var observations;

    // When user types a letter in input
    $input.on('keyup', function() {
        var userInput = $(this).val();

        if (userInput.length >= 1) {
            $.ajax({type: "POST", url: GET_URL, data: { input: userInput }, dataType: 'json', timeout: 3000,
                success: function(response) {
                    var listOfChoices = [];
                    var listOfSpecies = [];
                    if (response) {
                        for (var i = 0; i < response.count; i++) {
                            var species = Object.create(Species);
                            species.id = response.items[i].id;
                            species.name = response.items[i].name;
                            species.family = response.items[i].family;
                            species.order = response.items[i].order;
                            listOfSpecies.push(species);
                            listOfChoices.push('<li id="'+ species.id +'">' + species.name + '</li>');
                        }

                        arrayOfSpecies = listOfSpecies;

                        var speciesList = '<ul id="speciesList">'+ listOfChoices.join('') +'</ul>';
                        $results.html(speciesList);
                    } else {
                        $results.text('');
                    }
                }
            });
        } else if (userInput.length === 0) {
            $results.text('');
        }
    });

    $(document).on('click', '#speciesList li', function() {
        var speciesId = $(this).attr('id') || null;
        var speciesObj = null;
        deleteMarkers();

        if(!!speciesId) {
            $input.val($(this).text());
            $results.text('');
            speciesObj = arrayOfSpecies.find(function (element) {
                if (element.id == speciesId)
                    return element;
            });
        }

        $.ajax({type: "POST", url: SEARCH_URL, data: { id: speciesId }, dataType: 'json', timeout: 3000,
            success: function(response) {
                if (response) {
                    observations = response;
                    // Markers
                    for (var i = 0; i < observations.length; i++) {
                        var location = { lat: observations[i].latitude, lng: observations[i].longitude };
                        addMarker(location, observations[i], speciesObj);
                    }
                }
            }
        });

        $results.text('');
    });
});

var Species = {
    id: 0,
    name: '',
    family: '',
    order: ''
};

// Adds a marker to the map and push to the array
function addMarker(location, observation, species) {
    var marker = new google.maps.Marker({
        position: location,
        map: map
    });
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

function createInfoWindow(marker, observation, species) {
    var image = '<img src=" images/'+ observation.image.id + '.' + observation.image.url + '" alt="'+ observation.image.alt +'" style="height: 200px; width: auto;" />';
    var speciesFamily = species.family;
    var speciesOrder = species.order;

    var entireContent = '<div>' +
        '<figure>' + image + '</figure>' + speciesFamily +
        speciesOrder + '</div>';
    var infoWindow = new google.maps.InfoWindow({ content: entireContent });
    infoWindow.open(map, marker);
}