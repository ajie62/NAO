$(function() { $('textarea').froalaEditor() });

// EDIT FORM

var $form = $('input#article_form_mainImage_file');
var $img = $('#img');

$img.click(function () {
    $form.click();
    // $form.on('change', function () {
    //     $(this).parents('form').submit();
    // });
});