(function($) {	
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.profile-pic').attr('src', e.target.result);
            }
    
            reader.readAsDataURL(input.files[0]);
        }
    }
   
    $(".file-upload").on('change', function(){
        readURL(this);
    });
    
    $(".upload-button").on('click', function() {
       $(".file-upload").click();
    });

    $("input[name=password]").password({
        minimumLength: 8
    }).on('password.score', function(e, score) {
        $('form.update-profile-form input[name=password]').prop('score', score);
    });

    $('form.update-profile-form input[name$=password]').on("blur", function() {
        if ($('form.update-profile-form input[name=password]').prop('score') > 50 && $('form.update-profile-form input[name=password]').val() == $('form.update-profile-form input[name=confirm_password]').val())
            $('form.update-profile-form button[type=submit]').removeAttr('disabled');
        else
            $('form.update-profile-form button[type=submit]').attr('disabled', true);
    })
})(jQuery);