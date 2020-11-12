function Header(){
  this.__init.call(this);
}
Header.prototype = {
    __init: function(){
        this.__initEvents();
    },
    __initEvents: function(){
      $(document).ready(function(){
        if($('#signInModalLong').hasClass('open-modal')){
          $('#signInModalLong').modal('show');
        }
      });

      if( window.location.href == _url('password/reset') || window.location.href == _url('login') || window.location.href == _url('register')){
        $(".a2a_kit.a2a_kit_size_32.a2a_floating_style.a2a_vertical_style.vertical_buttons").css("display" , "none");
      }

      $(".signin").parent().css("marginTop", "15px");
      $('#login-form-link').click(function(e) {
        $("#login-form").show(300);
        $("#register-form").hide(300);
        $('#register-form-link').removeClass('active');
        $(this).addClass('active');
        // e.preventDefault();
      });
      $('#register-form-link').click(function(e) {
        $("#register-form").show(300);
        $("#login-form").hide(300);
        $('#login-form-link').removeClass('active');
        $(this).addClass('active');
        // e.preventDefault();
      });
      $(".nav-item").click(function(){
          $(this).addClass("active");
          $(this).siblings().removeClass("active");
      });
      $(document).off('click','.slide_icons').on('click','.slide_icons',function(){
        let left_icon=$(".slide_icons .fa-angle-left");
        if(left_icon.hasClass('d-none')){
          $('.vertical_buttons').animate({left: '-60px'}, "slow");
          $(".slide_icons .fa-angle-left").removeClass('d-none');
          $(".slide_icons .fa-angle-right").addClass('d-none');
        }else{
          $('.vertical_buttons').animate({left: '0px'}, "slow");
          $(".slide_icons .fa-angle-left").addClass('d-none');
          $(".slide_icons .fa-angle-right").removeClass('d-none');
        }
      });

      var imagesPreview = function(input, placeToInsertImagePreview) {
        if (input.files) {
          var filesAmount = input.files.length;
          for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function(event) {
              $($.parseHTML('<img>')).attr('src', event.target.result).width(200).appendTo(placeToInsertImagePreview);
            }

            reader.readAsDataURL(input.files[i]);
          }
        }
      };
 $(document).off('change','.gallery-photo-add').on('change','.gallery-photo-add',function(){
      // $('.gallery-photo-add').on('change', function() {
        imagesPreview(this, 'div.gallery');
      });
    }

};
let HeaderClass = new Header();
