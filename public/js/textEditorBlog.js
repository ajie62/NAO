$(function() { $('textarea').froalaEditor({
    height: 500,
    maxHeight: 500,
    width: '90%',
    placeholderText: "Votre texte ...",
    pluginsEnabled: ['colors', 'emoticons', 'lists', 'link', 'quotes', 'charCounter', 'paragraphStyle', 'paragraphFormat', 'align', 'fontSize']
}) });

var $form = $('input#article_form_image_file');
var $img = $('#img');

$img.click(function () {
    $form.click();
});
// $('#article_form_published').prop('checked', true);
$('#draft').on('click', function () {
    $('#article_form_published').prop('checked',false);
});
$('#publish').on('click', function () {
    $('#article_form_published').prop('checked',true);
});
