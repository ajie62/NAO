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
    map = new google.maps.Map(obsMap, {
        center: center,
        zoom: 15,
        streetViewControl: false,
        mapTypeControl: false,
        gestureHandling: 'cooperative',
        scrollwheel: false,
        zoomControl: true,
        zoomControlOptions: {
            position: google.maps.ControlPosition.LEFT_BOTTOM
        }
    });

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
        errorDiv.style.display = "none";
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
            errorDiv.style.display = "block";
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
    console.log($(this).val());
});

/** Autocomplete */
$(function() {
    var $especeInput = $('#observation_espece');
    var $hiddenInput = $('#observation_species');
    var $matchDiv = $('#js-match');
    const GET_SPECIES_URL = $('#get-species-url').data('url');
    var $speciesContainer = $('#js-match > #species');

    if ($('#observation_deceased').is(':checked')){
        styleDeath.display = 'block';
        styleFlightDir.display = 'none';
    } else {
        styleDeath.display = 'none';
        styleFlightDir.display = 'block';
    }

    $.getJSON(GET_SPECIES_URL, function(response) {
        $('.loader').hide(0);
        response.items.forEach(function(species) {
            $speciesContainer.append('<li style="cursor: pointer;" class="species visible" data-species-id="'+species.id+'" data-value="'+ $.trim(species.name.toLowerCase()) +'">'+ $.trim(species.name.toLowerCase()) +'</li>');
        });
    });

    // When the user types something in the input
    $especeInput.on('input', function() {
        $matchDiv.hide(0);
        $hiddenInput.val('');

        var input = $(this).val() || false;

        if (input) {
            if (input.length > 0) {
                $speciesContainer.find('li').each(function() {
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
        $especeInput.val($(this).text());
        $hiddenInput.val(speciesId);
        $matchDiv.hide(0);
    });

    $('.btn-js').on('click', function() {
        $('.upload-btn-wrapper input[type="file"]').click();
    });

    /* IMAGE */
    var $inputImage = $('#observation_image_file');

    $inputImage.on('change', function(e) {
        var isFileUploaded = e.target.files.length > 0;
        var $chosenPicture = $('.js-chosen-picture');
        var fileName = (isFileUploaded) ? e.target.files[0].name : null;
        $chosenPicture.text(fileName);
    });

    /** Handling addObs page change depending it's on mobile or desktop */
    var $window = $(window);

    function checkWidth() {
        var windowSize = $window.width();
        if (windowSize > 768) {
            $("#menu1").removeClass("tab-pane fade");
            $("#menu2").removeClass("tab-pane fade");
            $("#menu3").removeClass("tab-pane fade");
        } else {
            $("#menu1").addClass("tab-pane fade");
            $("#menu2").addClass("tab-pane fade");
            $("#menu3").addClass("tab-pane fade");
        }
    }

    checkWidth();
    $window.resize(checkWidth);
});