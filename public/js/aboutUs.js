function AboutUs(){
  this.__init.call(this);
}
AboutUs.prototype = {
  __init: function(){
    this.__initEvents();
  },

  gallaryAddClass: function() {
    var that = this;
    $('.img-wrapper img').each(function() {
      that.loadImage($(this).attr('src'));
    });
  },

  loadImage: function(src) {
    var image = new Image();
    image.src = src;
    image.onload = function() {
      if (image.width > image.height) {
        $('.img-wrapper img[src="'+image.src+'"]').addClass('landscape');
      }
    };
  },

  gallarySlick: function(count){
    $('.wts-profiles').slick({
      dots: true,
      infinite: true,
      speed: 300,
      slidesToShow: count,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: count,
            slidesToScroll: 1,
            infinite: true,
            dots: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: count-1,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: count-2,
            slidesToScroll: 1
          }
        }
      ]
    });
  },
  __initEvents: function(){
    var that = this;
    var width = $( document ).width();
    $(document).ready(function(){
      const header = $('h1').first().text();
      $('head').append('<meta property="og:title">');
      $('[property="og:title"]').attr('content', header);  
    });

    $( window ).resize(function() {
      var width = $( document ).width();
      var slidesToShow=3;
      if(width < 977 && width > 561){
        slidesToShow=2;
      }else if (width < 561) {
        slidesToShow=1;
      }
    });

    $.ajax({ 
      url: 'whatTheySay',
      type: "get", 
      // dataType: "json", 
      success: function(data){
        var whatTheySayDiv="";
        var count = data.length;
        var col = 4;

        if( count < 3 ) {
          if ( count == 1 ){ 
            col = 12; 
          }else if( count == 2 ){ 
            col = 6; 
          }
        }
        
        for(item of data){
          var img_url = _url('images/WhatTheySay/'+item.img);
          whatTheySayDiv += `<div class="col-`+col+`">
            <div class="img-wrapper">
              <img src="`+img_url+`">
            </div>
            <h3 class="name">`+item.name+`</h3>
            <h5 class="profession">`+item.profession+`</h5>
            <p class="description">`+item.description+`</p>
        </div>`;
        }
        $(".wts-profiles").append(whatTheySayDiv);
        count <= 3 ? count : count = 3;

        that.gallarySlick(count);
        that.gallaryAddClass();
        
      },

      error:function(error){ 
        console.log(error.responseText); 
      } 
    });
  }
};
let AboutUsClass = new AboutUs();