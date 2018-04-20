$(function() {
    var $currentRoute = $('#js-current-route').data('route');

    window.addEventListener("load", function(){
        window.cookieconsent.initialise({
            "palette": {
                "popup": {
                    "background": "#efefef",
                    "text": "#404040"
                },
                "button": {
                    "background": "#8ec760",
                    "text": "#ffffff"
                }
            },
            "theme": "edgeless",
            "content": {
                "message": "En poursuivant votre navigation sur ce site, vous acceptez l’utilisation de cookies pour vous assurer la meilleure expérience.",
                "dismiss": "J'ai compris !",
                "href": $currentRoute
            }
        })});
});