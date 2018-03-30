$(function() { $('textarea').froalaEditor() });

var $form = $('input#article_form_image_file');
var $img = $('#img');

$img.click(function () {
    $form.click();
});