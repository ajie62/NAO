var map, marker, observations;
var center = { lat: 46.53924000000001, lng: 2.4301890000000412 };
var obsMap = document.getElementById('js-search-map');
var markers = [];
var speciesObj = null;
var $inputContainer = $('#input-container');

$(function() {
    const SEARCH_URL = $inputContainer.data('search-url');
    const GET_SPECIES_URL = $inputContainer.data('get-url');

    var $searchInput = $('#input');
    var $matchDiv = $('#js-search-match');
    var $speciesContainer = $('#js-search-match > #search-species-results');
    var listOfSpecies = [];

    $.getJSON(GET_SPECIES_URL, function(response) {
        $('.loader').hide(0);

        for (var i=0; i<response.count; ++i) {
            var species = createAndHydrateSpecies(response, i);
            listOfSpecies.push(species);
        }

        response.items.forEach(function(species) {
            $speciesContainer.append('<li style="cursor: pointer;" class="species visible" data-species-id="'+species.id+'" data-value="'+ $.trim(species.name.toLowerCase()) +'">'+ $.trim(species.name.toLowerCase()) +'</li>');
        });
    });

    $searchInput.on('input', function() {
        $matchDiv.hide(0);
        var input = $(this).val() || false;

        if (input) {
            if (input.length > 0) {
                $speciesContainer.find('li').each(function(){
                    var species = $(this).data('value');

                    if (species.toLowerCase() === input.toLowerCase() || species.toUpperCase() === input.toUpperCase()) {
                        $(this).trigger('click');
                        $(this).removeClass('visible').addClass('hidden');
                    } else {
                        if(species.startsWith(input))
                            $(this).addClass('visible').removeClass('hidden');
                        else
                            $(this).removeClass('visible').addClass('hidden');
                    }
                });
            }

            ($speciesContainer.find('.visible').length === 0) ? $matchDiv.hide(0) : $matchDiv.show(0);
        }
    });

    $(document).on('click', '.species', function(){
        var speciesId = $(this).data('species-id');

        if (!!speciesId) {
            $searchInput.val($(this).text());
            $matchDiv.hide(0);
            speciesObj = findSpeciesObjectFromTheListWithId(listOfSpecies, speciesId);

            if (markers.length > 0) deleteMarkers();

            $.ajax({type: "POST", url: SEARCH_URL, data: { id: speciesId }, dataType: 'json', timeout: 3000,
                success: function(response) {
                    if (response) {
                        observations = response;
                        for (var i=0; i<observations.length; ++i) {
                            var location = { lat: observations[i].latitude, lng: observations[i].longitude };
                            addMarker(location, observations[i], speciesObj);
                        }
                    }
                }
            });
        }
    });
});

var Species = {
    id: 0,
    name: '',
    family: '',
    order: ''
};

function createAndHydrateSpecies(ajaxResponse, iterator) {
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
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        animation: google.maps.Animation.DROP
    });

    marker.addListener('click', function() {
        createInfoWindow(marker, observation, species)
    });

    markers.push(marker);
}

function setMapOnAll(map) {
    for (var i=0; i<markers.length; ++i)
        markers[i].setMap(map);
}

function clearMarkers() {
    setMapOnAll(null);
}

function deleteMarkers() {
    clearMarkers();
    markers = [];
}

// Creates all info windows for the markers
function createInfoWindow(marker, observation, species) {
    var content = '<div style="padding:0; margin:0;">' +
        '<img src="images/' + observation.image.id + '.' + observation.image.url +'" />' +
        '<h3>' + species.name + '</h3>' +
        '<p>Publié par ' + observation.userFirstname + ' ' + observation.userLastname +
        ' le ' + observation.observedAt +
        '</div>';

    var infoWindow = new google.maps.InfoWindow({ content: content });
    infoWindow.open(map, marker);
}

function findSpeciesObjectFromTheListWithId(listOfSpecies, speciesId) {
    return listOfSpecies.find(function (element) {
        if (element.id == speciesId) return element;
    });
}