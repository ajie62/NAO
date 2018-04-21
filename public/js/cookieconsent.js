$(function() {
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
            "theme": "classic",
            "content": {
                "header": "Cookies utilisés sur le site web",
                "message": "En poursuivant votre navigation sur ce site, vous acceptez l’utilisation de cookies.",
                "dismiss": "J'ai compris !",
                "link": "En savoir plus",
                "href": '/terms-of-use'
            }
        })});
});