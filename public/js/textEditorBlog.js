$(function() { $('textarea').froalaEditor() });

var $form = $('input#article_form_mainImage_file');
var $img = $('#img');

$img.click(function () {
    $form.click();
});