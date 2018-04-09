$(function() { $('textarea').froalaEditor({
    height: 500,
    maxHeight: 500,
    placeholderText: "Votre texte ...",
    pluginsEnabled: ['colors', 'emoticons', 'lists', 'link', 'quotes', 'charCounter', 'paragraphStyle', 'paragraphFormat', 'align', 'fontSize']
}) });

var $form = $('input#article_form_image_file');
var $img = $('#img');

$img.click(function () {
    $form.click();
});