function StoriesAndDetails(){
  this.__init.call(this);
}
StoriesAndDetails.prototype = {
  __init: function(){
    this.options();
    this.__initEvents();
  },
  options: function() {
    this.user_img_path = 'images/Users/';
  },
  appendComment: function(item) {
    var line = this.generateCommentLine(item);
    $('.comments-content > .comment').append(line);
  },
  gallarySlick: function(){
    var galleryTop = new Swiper('.gallery-top-pic', {
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
  },
  generateCommentLine: function(item) {
    var mydate = new Date(item.created_at);
      var date = mydate.toDateString();
      var str = (String(mydate).split(" ")[4]);
      var index = str.lastIndexOf(":");
      var time = str.substring(0, index);
      date = date.replace(String(mydate).split(" ")[0], getMonthWeekdays(String(mydate).split(" ")[0])+',');
      date = date.replace(String(mydate).split(" ")[1], getMonthWeekdays(String(mydate).split(" ")[1]));
      var ava_src = _url('images/Users/'+item.avatar);
      var comment = `
      <div class="row-fluid comment-row" data-id="`+item.id+`">
        <div class="col-1">
          <div class="comment_avatar">
            <img  src="`+ava_src+`" alt="img">
          </div>
        </div>
        <div class="col-11">
          <b class="name">`+item.user_name+`</b>
          <p class="comment_text">`+item.description+`</p>
        </div>
      </div>
      <div class="row-fluid postinfobot-comment">
        <div class="col-1"></div>
        <div class="col-11 likeblock-comment" data-comment-id="`+item.id+`">
          <i class="fa fa-thumbs-o-up up btn-like-dislike" data-val="1" data-toggle="tooltip" data-placement="bottom" title="like"> `+item.likes_count+`</i>
          <i class="fa fa-thumbs-o-down down btn-like-dislike" data-val="-1" data-toggle="tooltip" data-placement="bottom" title="dislike"> `+item.dislikes_count+`</i>
          <i class="fa fa-reply reply_icon" data-count="1"  data-toggle="tooltip" data-placement="bottom" title="reply"></i>
          <i class="fa fa-clock-o"></i> <span class="comment_text">`+date+` @ `+time+`</span>
        </div>
      </div>
      <div class="stories_hr"><hr></div>`;
    return comment;
  },
  generateComments: function(data) {
    var comments = '';
    comments += `
      </div>
        <div class="row">
          <div class='col-12'>
            <h3 class="center commentHeader">Մեկնաբանություններ</h3>
          </div>
        </div>
      </div>`;

    comments += `<div class="col-12 comment">
        <div class="row-fluid">
          <div class="col-12"></div>
        </div>`;
    for(item of data){
      comments += this.generateCommentLine(item);
    }

    comments += `</div>
      <div class="row add-comment">
        <div class="col-1">
          <div class="comment_avatar">
          <img  src="`+_url(this.user_img_path+UserDataClass.img())+`" alt="img">
          </div>
        </div>
        <div class="col-11 textarea">
          <textarea id="add_comment" placeholder="Ավելացնել մեկնաբանություն..."></textarea>
          <button class="btn-add-comment">Ավելացնել</button>
        </div>
      </div>`;
    
    $('.comments-content').show();

    $('.content > .comments-content').html(comments);
  },
  storyDetails: function(id){
    var that = this;
    that.gallarySlick();
    
    $.ajax({
      url: _url('stories/getCommentsById'),
      type: 'GET', 
      data: {
        story_id : id
      }, 
      success: data => {
        that.generateComments(data);
        that.startChat(true);
        $('.comment_avatar').addClass('d-none');
      }
    });
  },
  addPagination: function(data) {
    data = JSON.parse(data);
    if(data.total > data.per_page){
      var pagination_settings = {
        items: data.total,
        itemsOnPage: data.per_page,
        currentPage: data.current_page,
        cssStyle: 'light-theme',
        hrefTextPrefix: '?page=',
      };
console.log('aaaaaaaaaaaa',pagination_settings,$("#pagination").pagination(pagination_settings))
    //  $("#pagination").show().pagination(pagination_settings);
    }else{
      $("#pagination").remove();
    }
  },
  addLikeOrDislike: function(like_or_dislike, comment_id) {
    var that = this;
    $.post(_url('stories/likes'), {
      _token: _token,
      like_or_dislike: like_or_dislike,
      comment_id: comment_id
    }, data => {
      that.changeCommentLikesCounts(comment_id, JSON.parse(data));
    });
  },
  changeCommentLikesCounts: function(comment_id, data) {
    var likes_block = $('.likeblock-comment[data-comment-id="'+comment_id+'"]');
    likes_block.find('i.btn-like-dislike[data-val="-1"]').html(' '+data.dislikes_count);
    likes_block.find('i.btn-like-dislike[data-val="1"]').html(' '+data.likes_count);
  },
  addComment: function(req_data) {
    var that = this;
    $.post(_url('stories/addComment'), req_data, data => {
      if(!data.error){
        that.getChatData(false);
      }
    });
  },
  startChat: function() {
    var that = this;

    setTimeout(() => {
      that.getChatData();
    }, 3000);
  },
  getChatData: function(recursion = true) {
    var that = this;
    var story_id = $('.story-details').attr('data-story-id');
    var comment_id = ~~$('.comment-row').last().attr('data-id');
    var req_data = {
      story_id: story_id,
      comment_id: comment_id
    };
    $.get(_url('stories/getCommentsStartedWith'), req_data, data => {
      if(data.story_id == story_id){
        for(item of data.data){
          that.appendComment(item);
        }
      }
      if(recursion){
        that.startChat();
      }
    });
  },
  __initEvents: function() {
    var that = this;
    $(".share, .sub-menu ul").hide();
    $(".rcom").hide();
    /*share close*/
    $(document).off('click', '.share_button').on('click', '.share_button', function() {
      $(".share").hide();
    });
    $(".sub-menu a").click(function () {
      $(this).parent(".sub-menu").children("ul").slideToggle("200");
      $(this).find("i.fa").toggleClass("fa-angle-up fa-angle-down");
    });
    $(document).off('click', '.news_reply').on('click', '.news_reply', function() {
      $(document).scrollTop($('.comment').position().top);
      $("#add_comment").focus();
    });
    /*/////////////////add comment////////////*/
    $(document).off('click', '.btn-add-comment').on('click', '.btn-add-comment', function() {
      if(UserDataClass.id() != 0)
      {
        var comment = $('#add_comment').val();
        var story_id = $('.story-details').attr('data-story-id');
        if(comment.length > 0){
          var data = {
            message: comment,
            story_id: story_id,
            _token: _token
          };
          that.addComment(data);
          $('#add_comment').val('');
        }
      }else{
        $('#signInModalLong').modal('show');
        return;
      }
    });
    $(document).off('click', '.btn-like-dislike').on('click', '.btn-like-dislike', function(){
      var like_or_dislike = $(this).attr('data-val');
      var comment_id = $(this).closest('.likeblock-comment').attr('data-comment-id');
      that.addLikeOrDislike(like_or_dislike, comment_id);
    });
  }
};
let StoriesAndDetailsClass = new StoriesAndDetails();