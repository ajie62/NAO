$(function() {
    var $editProfileImgInput = $('#edit_profil_image_file');
    var $profilePicture = $('#profile-picture');

    $profilePicture.parent('figure').find('> *').click(function() {
        $editProfileImgInput.click();
    });

    $editProfileImgInput.on('change', function(e) {
        var isFileUploaded = e.target.files.length > 0;
        var $chosenPicture = $('.js-chosen-picture');
        var fileName = (isFileUploaded) ? e.target.files[0].name : null;
        $chosenPicture.text(fileName);
    });

    var $pwdChangeForm = $('.pwd-change-form');
    var $pwdChangeLink = $('#js-pwd-change');
    $pwdChangeLink.on('click', function(e) {
        e.preventDefault();
        $pwdChangeForm.show(500);
    });

    var $pwdChangeCancel = $('#js-cancel-pwd-change');
    $pwdChangeCancel.on('click', function(e) {
        e.preventDefault();
        $pwdChangeForm.hide(500);
    });
});