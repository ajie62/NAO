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
    var $speciesUnorderedListContainer = $('#js-search-match');
    var $speciesUnorderedList = $speciesUnorderedListContainer.find('ul');
    var listOfSpecies = [];

    $.getJSON(GET_SPECIES_URL, function(response) {
        for (var i = 0; i < response.count; ++i) {
            var species = createAndHydrateSpecies(response, i);
            listOfSpecies.push(species);
        }

        response.items.forEach(function(species) {
            $speciesUnorderedList.append(
                '<li style="cursor: pointer;" ' +
                    'class="species visible" ' +
                    'data-species-id="'+species.id+'" ' +
                    'data-value="'+ $.trim(species.name.toLowerCase()) +'">'+
                    $.trim(species.name.toLowerCase()) +
                '</li>');
        });
    });

    $searchInput.on('input', function() {
        $speciesUnorderedListContainer.hide(0);
        var input = $(this).val() || false;

        if (input) {
            if (input.length > 0) {
                $speciesUnorderedList.find('li').each(function(){
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
            ($speciesUnorderedList.find('.visible').length === 0) ? $speciesUnorderedListContainer.hide(0) : $speciesUnorderedListContainer.show(0);
        }
    });

    var speciesId = null;

    $(document).on('click', '.species', function(){

        speciesId = $(this).data('species-id');

        if (!!speciesId) {
            $searchInput.val($(this).text());
            $speciesUnorderedListContainer.hide(0);
            speciesObj = findSpeciesObjectFromTheListWithId(listOfSpecies, speciesId);
        }
    });

    $("#search-ok-button").on('click', function() {

        if (markers.length > 0) deleteMarkers();
        if(speciesId !== null){
            $.ajax({type: "POST", url: SEARCH_URL, data: { id: speciesId }, dataType: 'json', timeout: 3000,
                success: function(response) {
                    if (response.length === 0) {
                        $('.no-result').html('<p>Aucun résultat pour : <strong>' + $searchInput.val() + '</strong></p>');
                    } else {
                        $('.no-result').text('');
                        observations = response;
                        for (var i=0; i<observations.length; ++i) {
                            var location = { lat: observations[i].latitude, lng: observations[i].longitude };
                            addMarker(location, observations[i], speciesObj);
                        }
                        $('.search-species').fadeToggle();
                    }
                }
            });
        } else {
            $('.no-result').html('<p>Aucun résultat pour : <strong>' + $searchInput.val() + '</strong></p>');
            $speciesUnorderedListContainer.fadeOut();
        }

        speciesId = null;
    });

    $('#js-start-search').on('click', function(e) {
        e.preventDefault();
        $('.search-species').fadeToggle();
    });
});

var Species = {
    id: 0,
    name: '',
    family: '',
    order: ''
};

var infoWindow = null;

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
        scrollwheel: false,
        zoomControlOptions: {
            position: google.maps.ControlPosition.LEFT_BOTTOM
        }
    });

    google.maps.event.addListener(map, 'click', function() {
        if (infoWindow){
            infoWindow.close();
        }
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
        if (infoWindow){
            infoWindow.close();
        }
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
    var comment = observation.comment === null ? 'Aucun' : observation.comment;

    var isDead = observation.deceased === true;
    var flightDirection = '<li><strong>Direction du vol : </strong>'+ observation.flightDirection +'</li>';
    var deathCause = '<li><strong>Cause de la mort : </strong>' + observation.deathCause + '</li>';
    var correctContent = null;

    if (isDead) {
        correctContent = deathCause;
    } else {
        correctContent = flightDirection;
    }

    var content = '<div style="padding:0; margin:0;">' +
        '<img src="'+observation.image+'" />' +
        '<h3>' + species.name + '</h3>' +
        '<p>par ' + observation.userFirstname + ' ' + observation.userLastname +
        ' le ' + observation.observedAt +
        '</div>' +
        '<div>' +
        '<ul class="infoWindow-list">' +
        '<li><strong>Famille : </strong>'+ species.family +'</li>' +
        '<li><strong>Ordre : </strong>'+ species.order +'</li>' +
        '<li><strong>Sexe : </strong>'+ observation.sex +'</li>' +
        '<li><strong>Code Atlas : </strong>'+ observation.atlasCode +'</li>' +
        correctContent +
        '<li><strong>Commentaire : </strong>'+ comment + '</li>' +
        '</ul>' +
        '</div>'
    ;

    infoWindow = new google.maps.InfoWindow({ content: content });
    infoWindow.open(map, marker);
}

function findSpeciesObjectFromTheListWithId(listOfSpecies, speciesId) {
    return listOfSpecies.find(function (element) {
        if (element.id == speciesId) return element;
    });
}