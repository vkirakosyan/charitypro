
function Donations(){
  this.__init.call(this);
}
Donations.prototype = {
  __init: function(){
    OfferedServicesClass.lastTwo();
    this.__initEvents();
/*    this.__addDonate();*/
  },
  addPagination: function(total, per_page, current_page, filters) {
    if(total > per_page){
      var pref = '?page=';
      var pagination_settings = {
        items: total,
        itemsOnPage: per_page,
        currentPage: current_page,
        cssStyle: 'light-theme',
        hrefTextPrefix: pref
      };
      $('.pagination-content').removeClass('hidden');
      $('#pagination').pagination(pagination_settings);
    }else{
      $('.pagination-content').remove();
    }
  },
 /*   __addDonate:()=>{
        document.querySelector('.addWorkButton').addEventListener("click", (e)=>{
            document.querySelector('.addWorkWrap').style.display = (document.querySelector('.addWorkWrap').style.display) ==  "block"?"none":"block"
        })
    },*/
  initPagination: function(data) {
    data = JSON.parse(data);
    this.addPagination(data.total, data.per_page, data.current_page);
  },
  initData: function(data){
    this.donations = data.data;
  },
  addNewDonetion: function(i){
    var that = this;
    var post_data = new FormData();
    var new_name = $('.form_donation_name').val();
    var new_description = $('.form_donation_description').val();
    var new_cat_id = $('.form-donation-categories');
    var new_images = $('.donation-gallery-photo-add');
    const telInput = $('.input-phone');
    const tel = telInput.intlTelInput("getNumber");
    post_data.append('_token', $('[name="_token"]').val());
    post_data.append('name', new_name);
    post_data.append('description', new_description);
    post_data.append('cat_id', new_cat_id.val());
    post_data.append('phone', tel);
    var images = new_images[0].files;
    for(index in images){
      post_data.append('images[]', new_images[0].files[index]);
    }
    $.ajax({
      type: 'POST',
      url: 'donations/create',
      contentType: false,
      processData: false,
      data: post_data
    }).done(data => {
      if(typeof data.errors != 'undefined'){
        $('.help-block').html('');
          $('.form-group').removeClass('has-error');
        for(key in data.errors){
          $('[name="'+key+'"]').after('<p class="help-block">'+data.errors[key]+'</p>')
          $('[name="'+key+'"]').parents('.form-group').addClass('has-error');
        }
      } else {
        new_cat_id.find('option').removeAttr('selected').prop('selected', false);
        new_images.wrap('<form>').closest('form').get(0).reset();
        new_images.unwrap();
        $('.gallery').html('');
        $('.donations-posts').prepend(`<div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>✓</strong> Ձեր նվիրատվությունը հաջողությամբ ավելացվել է։
        </div>`);
        setTimeout(()=>{
          history.go(0);
        }, 1000);
      }
    });
  },
  __initEvents: function(){
    var that = this;
    $('.add-don').hide();
    const telInput = $('.input-phone');
    telInput.intlTelInput({
      utilsScript: 'js/utils.js',
      preferredCountries: ['am', 'ru', 'us']
    });
    const errorMsg = $("#error-msg");
    const validMsg = $("#valid-msg");
    const telReset = () => {
      telInput.removeClass("has-error");
      errorMsg.addClass("d-none");
      validMsg.addClass("d-none");
    };
    // on blur: validate
    telInput.blur(() => {
      telReset();
      if ($.trim(telInput.val())) {
        if (telInput.intlTelInput("isValidNumber")) {
          validMsg.removeClass("d-none");
          $('.add_don_search').attr('disabled', false);
        } else {
          telInput.addClass("has-error");
          errorMsg.removeClass("d-none");
          $('.add_don_search').attr('disabled', true);
        }
      }
    });
    // on keyup / change flag: reset
    telInput.on("keyup change", telReset);
    $(document).off('click', '.accordion').on('click', '.accordion', function() {
      if($('.not-loggedin').length == 1) {
        $('#signInModalLong').modal('show'); 
      }
      else {
        $('.add-don').slideToggle('slow');
      }
    });
    var cur_img;
    $(document).off('click', '.slick-slide img').on('click', '.slick-slide img', function() {
      cur_img = $(this).attr('src');
      $('.simple-gallery--preview img').attr('src' , cur_img);
      $('.slick-slide').css('outline', 'none');
    });
    $(document).off('click', '.donations-post').on('click', '.donations-post', function() {
      var donation_id = $(this).attr('data-donation-id');
      var donation_info = that.donations.find(d => { return d.id == donation_id; });
      var parsed_images = JSON.parse(donation_info.images);
      var donation_name = donation_info.name;
      var created = new Date(donation_info.created_at);
      created = created.toDateString();
      created = created.replace(String(created).split(" ")[0], getMonthWeekdays(String(created).split(" ")[0])+',');
      created = created.replace(String(created).split(" ")[1], getMonthWeekdays(String(created).split(" ")[1]));
      var user_id = donation_info.user_id;
      var donation_description = donation_info.description;
      var images = '<div class="donations-slider">';
      const swiper_imgs = [];
      parsed_images.forEach(img => swiper_imgs.push(`<div class="swiper-slide" style="background-image:url(/images/Donations/${img})"></div>`));
      var donation_current = `
            <div class="swiper-wrapper">
                ${swiper_imgs}
            </div>
            <div class="swiper-button-next swiper-button-blue"></div>
            <div class="swiper-button-prev swiper-button-blue"></div>`;
      images += `<div class="swiper-container gallery-thumbs">
           <div class="swiper-wrapper">`
      swiper_imgs.forEach(img => images += img);
      images += `</div>
        </div>`;
        console.log
      $(".img-preview").html(donation_current);
      $(".modal-created").html(created);
      $(".modal-title").html(donation_name);
      $('.slider').html(images);
      $('#donationItem').modal('show');
      $(".donation_popup_user").html(donation_info.user_name);
      if(donation_info.phone != null){
        $('.donation_popup_number').html(donation_info.phone);
      }
      $('.donation_popup_email').html(donation_info.user_email);
      $(".modal-desc").html(donation_description)
    });
    $('#donationItem').on('shown.bs.modal', e => {
      var galleryTop = new Swiper('.gallery-top', {
        spaceBetween: 10,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {
          // when window width is <= 320px
          320: {
            slidesPerView: 1,
            spaceBetween: 10
          },
          // when window width is <= 480px
          480: {
            slidesPerView: 2,
            spaceBetween: 20
          },
          // when window width is <= 640px
          640: {
            slidesPerView: 3,
            spaceBetween: 30
          }
        }
      });
      var galleryThumbs = new Swiper('.gallery-thumbs', {
        spaceBetween: 10,
        centeredSlides: true,
        slidesPerView: 'auto',
        touchRatio: 0.2,
        slideToClickedSlide: true,
        breakpoints: {
          // when window width is <= 320px
          320: {
            slidesPerView: 1,
            spaceBetween: 10
          },
          // when window width is <= 480px
          480: {
            slidesPerView: 2,
            spaceBetween: 20
          },
          // when window width is <= 640px
          640: {
            slidesPerView: 3,
            spaceBetween: 30
        }
      }
      });
      galleryTop.controller.control = galleryThumbs;
      galleryThumbs.controller.control = galleryTop;
    });
    $('.sub-menu ul').hide();
    $(".sub-menu a").click(function () {
      $(this).parent(".sub-menu").children("ul").slideToggle("200");
      $(this).find("i.fa").toggleClass("fa-angle-up fa-angle-down");
    });
    $(document).off('click', '.form-donation-button').on('click', '.form-donation-button', function() {
      that.addNewDonetion();
    });
  }
};

let DonationsClass = new Donations();