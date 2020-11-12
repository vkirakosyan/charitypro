function Home(){
  this.__init.call(this);
}
Home.prototype = {
    __init: function(){
      OfferedServicesClass.lastTwo();
      this.__initEvents();
    },
    __initEvents: function(){
      $('#login-form-link').click(function(e) {
      $("#login-form").delay(100).show(100);
      $("#register-form").hide(100);
      $('#register-form-link').removeClass('active');
      $(this).addClass('active');
      // e.preventDefault();
    });
      $('#register-form-link').click(function(e) {
      $("#register-form").delay(100).show(100);
      $("#login-form").hide(100);
      $('#login-form-link').removeClass('active');
      $(this).addClass('active');
      // e.preventDefault();
    });
      $( document ).ready(function() {
      	$('#myCarousel').carousel({
            interval: 5000
        });
        $('#carousel-text').html($('#slide-content-0').html());
        //Handles the carousel thumbnails
        $('[id^=carousel-selector-]').click( function(){
            var id_selector = $(this).attr("id");
            var id = id_selector.substr(id_selector.length -1);
            var id = parseInt(id);
            $('#myCarousel').carousel(id);
        });
        // When the carousel slides, auto update the text
        $('#myCarousel').on('slid', function (e) {
            var id = $('.item.active').data('slide-number');
            $('#carousel-text').html($('#slide-content-'+id).html());
        });
    });
  }
};
let HomeClass = new Home();

var youtubeapidfd = $.Deferred()

function onYouTubeIframeAPIReady(){
  youtubeapidfd.resolve()
}

var ddyoutubeGallery = (function($){
  
  var youtubethumbnailsurl = 'https://img.youtube.com/vi/VIDEO-ID/default.jpg'
  var youtubescreenshotsurl = 'https://img.youtube.com/vi/VIDEO-ID/mqdefault.jpg'
  var idevice = /ipad|iphone|ipod/i.test(navigator.userAgent)

  function ddyoutubeGallery(setting){
    var thisinst = this
    this.$slider = $('#' + setting.sliderid)
    this.$videowrapper = this.$slider.find('.videoWrapper:eq(0)')
    this.$nav = this.$slider.find('.videoNav:eq(0)')
    this.totalvids = setting.playlist.length
    this.currentvid = setting.selected || 0
    this.$navbelt
    this.setting = setting
    var tempdiv = $('<div />').appendTo( this.$videowrapper ) // temporary DIV container to be replaced with Youtube IFRAME
    var tag = document.createElement('script')
    tag.src = "https://www.youtube.com/iframe_api"
    var firstScriptTag = document.getElementsByTagName('script')[0]
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag)
    youtubeapidfd.then(function(){
      thisinst.player = new YT.Player(tempdiv.get(0), {
        playerVars: {
          controls: 1,
          playlist: setting.playlist.join(',')
        },
        events: {
          'onReady': function(e){
            thisinst.populatenav(setting.playlist)
            if (setting.autoplay && !idevice){
              thisinst.player.playVideoAt( thisinst.currentvid )
            }
          },
          'onStateChange': function(e){
            if (setting.autoplay && e.data == 5 && !idevice){ // if auto play video and playlist is cued and not iOS devices. See https://developers.google.com/youtube/iframe_api_reference for e.data details 
              thisinst.player.playVideoAt( thisinst.currentvid )
            }
            if (setting.autocycle && e.data == 0 && !idevice){ // if auto cycle && current video finished playing
              var nextvid = (thisinst.currentvid < thisinst.totalvids-1)? thisinst.currentvid + 1 : 0
              thisinst._scrolltothumb( nextvid )
              thisinst.player.playVideoAt( nextvid )
            }
          }
        }
      })
    })
  }

  ddyoutubeGallery.prototype = {


    _scrolltothumb(index){
      var indexnum = parseInt(index)
      var selectedvid = (indexnum < 0)? 0 : (indexnum > this.totalvids-1)? this.totalvids-1 : indexnum
      var $imgs = this.$nav.find('img')
      var $targetimg = $imgs.eq(selectedvid)
      var rightpos = $targetimg.position().left
      if (selectedvid <= this.currentvid){ // if clicking on thumbnail to the left of current selected thumbnail
        var navwidth = this.$nav.width()
        var imgwidth = $targetimg.width()
        var imagemargin = parseInt($targetimg.css('marginRight')) + parseInt($targetimg.parent().css('marginRight')) 
        rightpos -= navwidth - imgwidth - imagemargin
      }
      this.$navbelt.animate({scrollLeft: rightpos}, 400)
      $imgs.eq(this.currentvid).removeClass('selected').end()
      .eq(selectedvid).addClass('selected')
      this.currentvid = selectedvid
    },

    populatenav(playlist){
      var thisinst = this
      var navhtml = '<ul>\n'
      for (var i=0; i<playlist.length; i++){
        navhtml += '<li><img src="' + youtubethumbnailsurl.replace('VIDEO-ID', playlist[i]) + '" data-videoindex="' + i +'"/></li>\n'
      }
      navhtml += '</ul>\n'
      this.$nav.empty().html( navhtml )
      this.$navbelt = this.$nav.find('ul:eq(0)')
      this.$nav.off('.selectvideos').on('click.selectvideos', function(e){
      if (e.target.tagName == 'IMG'){
        var $target = $(e.target)
        var selectedvid = parseInt($target.data('videoindex'))
        thisinst._scrolltothumb( selectedvid )
        if (typeof thisinst.player != "undefined"){
          if (idevice){
            thisinst.player.cueVideoById( thisinst.setting.playlist[ selectedvid ] )
          }
          else{
            thisinst.player.playVideoAt( selectedvid )
          }
        }
        this.currentvid = parseInt($target.data('videoindex'))
      }
      })
      this._scrolltothumb( this.currentvid )
    }
  }

  return ddyoutubeGallery

})(jQuery);

var myvideogallery = new ddyoutubeGallery({
  sliderid: 'videojukebox',
  selected: 0, // default selected video within playlist (0=1st, 1=2nd etc)
  autoplay: 0, // 0 to disable auto play, 1 to enable
  autocycle: 1, // 0 to disable auto cycle, 1 to auto cycle and play each video automatically
  playlist: youtube_ids
})
