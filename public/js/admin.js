$(function() {
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    if(!input.hasAttribute('multiple')){
                        document.querySelector(placeToInsertImagePreview).innerHTML = '';
                    }
                    $($.parseHTML('<img>')).attr('src', event.target.result).width(200).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }

        }

    };

    $('.gallery-photo-add').on('change', function() {
        // imagesPreview(this, 'div.gallery');
        $('.update_buttons .btn_warning').attr('disabled', false);
    });

    $(document).off('click', '.delete-img').on('click', '.delete-img', function() {
        $(this).next('.uploaded-inp').remove();
        $(this).prev('.uploaded-img').parent('.imageWrapDelete').remove();
        $(this).remove(); 
        if($('.editImages').children().length == 0 && $('.gallery-photo-add').val() == ""){
            $('.update_buttons .btn_warning').attr('disabled', true);
        }
    });
});