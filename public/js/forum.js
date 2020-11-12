//*
function Forum(){
  this.__init.call(this);
};

Forum.prototype = {
  __init: function(){
    OfferedServicesClass.lastTwo();
    this.__initOptions();
    this.__initData();
    this.__initEvents();
  },
  __initOptions: function() {
    this.user_img_path = 'images/Users/';
  },
  __initData: function() {
    var forum_id = ~~getURLParameter('forum_id');
    this.getMembersCount();

    if(forum_id){
      this.getItemsCount();
      this.getForumDetails(forum_id);
      this.addToViews(forum_id);
    }else{
      var page_n = ~~getURLParameter('page');
      this.getByFilter(page_n);
    }
    const header = $('h1').first().text();
    $('head').append(`<meta property="og:title" content=${header}>`);
  },
  getItemsCount: function() {
    var that = this;
    $.get(_url('forums/getItemsCount'), data => {
      that.addItemsCount(data.items_count);
    });
  },
  generateCommentLine: function(item) {
    var mydate = new Date(item.created_at);
    var date = mydate.toDateString();
    var str = (String(mydate).split(" ")[4]);
    var index = str.lastIndexOf(":");
    var time = str.substring(0, index);
    date = date.replace(String(mydate).split(" ")[0], getMonthWeekdays(String(mydate).split(" ")[0])+',')
    date = date.replace(String(mydate).split(" ")[1], getMonthWeekdays(String(mydate).split(" ")[1]))
    var ava_src = _url('images/Users/'+item.avatar);
/*    var comment = `<div class="subCommentsUser comment-row" data-id="`+item.id+`">
        <div class="col-2">
          <div class="comment_avatar">
            <img  src="`+ava_src+`" alt="img" style="max-width:50px;border-radius:50%">
          </div>
        </div>
        <div class="col-10">
          <b class="name">`+item.user_name+`</b>
          <p class="comment_text">`+item.comment+`</p>
        </div>
    
    <div class="row postinfobot_comment">
      <div class="col-0 col-sm-2"></div>
      <div class="col-12 col-sm-8 likeblock-comment" data-comment-id="`+item.id+`">
        <i class="fa fa-thumbs-o-up up btn-like-dislike" data-val="1" data-toggle="tooltip" data-placement="bottom" title="like"> `+item.likes_count+`</i>
        <i class="lnr lnr-thumbs-down down btn-like-dislike" data-val="-1" data-toggle="tooltip" data-placement="bottom" title="dislike"> `+item.dislikes_count+`</i>
        <i class="fa fa-clock-o"></i> <span class="comment_text">`+date+` @ `+time+`</span>
    
    </div>`;*/
    var comment = `<div class="subCommentsUser comment-row comment_avatar" data-id="`+item.id+`">
            <img  src="`+ava_src+`" alt="img" style="max-width:50px;border-radius:50%">
       <div class="subUserComments">
          <b class="name">`+item.user_name+`</b>
          <p class="comment_text">`+item.comment+`</p> 
    <div class="bottomLikes postinfobot_comment">
  <div class=" likeblock-comment bottomLikes" data-comment-id="`+item.id+`">
    <p class="likePut">
    <i class="lnr lnr-thumbs-up up btn-like-dislike" data-val="1" data-toggle="tooltip" data-placement="bottom" title="like"> `+item.likes_count+`</i>
        </p>        
        <p class="dislikePut">
        <i class="lnr lnr-thumbs-down down btn-like-dislike" data-val="-1" data-toggle="tooltip" data-placement="bottom" title="dislike"> `+item.dislikes_count+`</i>     
    </p>
    <p>
      <span class="lnr lnr-history">`+date+` @ `+time+`</span>
    </p>
  </div>
      </div>                                         
      </div>  
      </div>                                      
                                               
                                            


    `;
    return comment;
  },
  getForumDetails: function(forum_id) {
    var that = this;/*
    $('.content-all').html('<i class="alert-loader fa fa-circle-o-notch fa-spin fa-5x" aria-hidden="true"></i>');*/
    $.get(_url('forums/getCommentsByForumId'), {
      forum_id: forum_id
    }, data => {
      var comments = '';
      var mydate = new Date(data.forum_data.created_at);
      var date = mydate.toDateString();
      var str = (String(mydate).split(" ")[4]);
      date = date.replace(String(mydate).split(" ")[0], getMonthWeekdays(String(mydate).split(" ")[0])+',')
      date = date.replace(String(mydate).split(" ")[1], getMonthWeekdays(String(mydate).split(" ")[1]))
      var index = str.lastIndexOf(":");
      var time = str.substring(0, index);
      var ava_src = _url(that.user_img_path+data.forum_data.avatar);
      $('[property="og:title"]').attr('content', data.forum_data.title);
      var current_post = `<div class="forum-success alert alert-success d-none alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>✓</strong> Ձեր թեման հաջողությամբ ավելացվել է։
                    </div>
                    <div class="forum-failure alert alert-danger d-none">
                        <strong>✗</strong> Ձեր թեման պետք է ունենա Վերնագիր և Նկարագրություն։ Նկարագրությունը պետք է լինի 10 տառից ավելի երկար։<a data-toggle="modal" class="alert-link" href="#newForumModal">Ուղղել</a>։
                    </div><div class="d-block border-top mt-4">
      <span class="back comeBack" ><span class="lnr lnr-arrow-left"></span> Հետ</span>
      </div>
        <div class=" currentPostHeader forum-details commentsWrap" data-forum-id="`+forum_id+`">
            <h4>`+data.forum_data.title+`</h4>
        <div class=" current-post-details allCommentsWrap">
          <div class="writeUser">
          <div class="userSection">
          <p >
            <img class='avatar' src='`+ava_src+`' alt='img' style="max-width:50px;border-radius:50%">
            <span>`+data.forum_data.user_name+`</span>
            </p>
            <span class="d-block">`+date+`, `+time+`</span>
          </div>
          <div class="col-9 currentPostDescription userComment">
            <p>
              `+data.forum_data.description+`
            </p>
       
         
        
          
        
        <div class="backgraund_content comments-content  posterUser">
          <div class=" comment">
           `;

      if(data.comments){
        for(item of data.comments){
          comments += that.generateCommentLine(item);
        }
      }

      current_post += comments;
      const shouldDisplayPic = UserDataClass.id() != 0;
        // <div class="col-1">
        //         <div class="comment_avatar">
         //           <img ${shouldDisplayPic ? `style="display: block"` : `style="display: none"`} src="`+_url(that.user_img_path+UserDataClass.img())+`" alt="img">
          //        </div>
         //       </div>
       var userId=$("#user-id").val();
         if(userId){
            var button = "<button class='btn-add-comment addSubComment'>Ավելացնել</button>"
                  }
           else{
              var button ="<button class='addSubComment' data-toggle='modal' data-target='#signInModalLong'>Ավելացնել</button>"; 
                  }
      current_post += `</div>
        <div class="addSubCommentForm add-comment">
             
                <div class=" textarea">
                  <textarea id="add_comment" name="" rows="3" placeholder="Ավելացնել մեկնաբանություն..." class="form-control"></textarea>` + button +
                
                `</div>
              </div>
          </div>
      </div>`;

      $('.content_all').html(current_post);
      
      that.startChat();
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
    var forum_id = ~~getURLParameter('forum_id');
    var comment_id = ~~$('.comment-row').last().attr('data-id');
    var req_data = {
      forum_id: forum_id,
      comment_id: comment_id
    };
    $.get(_url('forums/getCommentsStartedWith'), req_data, data => {
      if(data.forum_id == ~~getURLParameter('forum_id')){
        for(item of data.data){
          that.appendComment(item);
        }
      }
      if(recursion){
        that.startChat();
      }
    });
  },
  addComment: function(req_data) {
    var that = this;
    $.post(_url('forums/addComment'), req_data, data => {
      if(data.status){
        that.getChatData(false);
        $('.btn-add-comment').attr('disabled', false);
      }
    });
  },
  getMembersCount: function() {
    $.get(_url('forums/getMembersCount'), data => {
      $('.participants > b').html(data.members_count);
       
    });
  },
  addItemsCount: function(count) {
    $('.themes > b').html(count);
  },
  getByFilter: function(page_n) {
    var that = this;
    var ajax_data = {};
    if(page_n){
      ajax_data.page = page_n;
    }
    $.get(_url('forums/getByFilter'), ajax_data, data => {
      that.addItemsCount(data.data.length > 0 ? data.data[0].count_items : 0);
      var posts = '';
      for(item of data.data){
        posts += that.generateForumRow(item);
      }
      $('.content').html(posts);
     
      that.addPagination(data);
    });
  },
  generateForumRow: function(item) {
    var mydate = new Date(item.created_at);
    var date = mydate.toDateString();
    var str = (String(mydate).split(" ")[4]);
    var index = str.lastIndexOf(":");
    var time = str.substring(0, index);
    date = date.replace(String(mydate).split(" ")[0], getMonthWeekdays(String(mydate).split(" ")[0])+',')
    date = date.replace(String(mydate).split(" ")[1], getMonthWeekdays(String(mydate).split(" ")[1]))
    var img_src = _url(this.user_img_path+item.avatar);
    var row =`<div class="trchild">
        <p data-item-id="`+item.id+`" class="post_link"><a>`+item.title+`</a></p>
        <p><span class="lnr lnr-pencil"></span> `+item.comments_count+`</p>  
        <p><span class="lnr lnr-eye"></span>`+item.views_count+`</p>
       <p><a href="#"><img src="`+img_src+`" style="max-width:30px;" alt=""> `+item.user_name+`</a></p>
    </div>`;
    return row;
     
  },/*
  */
  addToViews: function(forum_id) {
    if (forum_id) {
      $.post(_url('forums/addToViews'), {
        forum_id: forum_id,
        _token: _token
      }, data => {

      });
    }
  },
  createForum: function(title, desc) {
    const that = this;
    $('#newForumModal').modal('toggle');
    $('.alert-box').append('<i class="alert-loader fa fa-circle-o-notch fa-spin fa-5x" aria-hidden="true"></i>');
    $.post(_url('forums/create'), {
      title: title,
      description: desc,
      _token: _token
    }, data => {
      if(typeof data.errors == 'undefined'){
        that.__initData();
        $('.alert-loader').hide();
        $('.topic-link').attr('href',_url(`forum#forum_id=${data.id}`));
        $('.forum-success').removeClass('d-none');
        $('.forum-failure').addClass('d-none');
      }else{
        if(title.length < 2)
        {
          $('.forum-failure').html('<strong>✗</strong> Վերնագիրը պետք է բաղկացած լինի առնվազն 2 տառից։<a data-toggle="modal" class="alert-link" href="#newForumModal">Ուղղել</a>։');
        }

        if(title.length == 0)
        {
          $('.forum-failure').html('<strong>✗</strong> Թեման պետք է ունենա Վերնագիր։<a data-toggle="modal" class="alert-link" href="#newForumModal">Ուղղել</a>։');
        }

        if(desc.length < 10)
        {
          $('.forum-failure').html('<strong>✗</strong> Նկարագրությունը պետք է բաղկացած լինի առնվազն 10 տառից։<a data-toggle="modal" class="alert-link" href="#newForumModal">Ուղղել</a>։');
        }

        if(desc.length == 0)
        {
          $('.forum-failure').html('<strong>✗</strong> Թեման պետք է ունենա Նկարագրություն։<a data-toggle="modal" class="alert-link" href="#newForumModal">Ուղղել</a>։');
        }

        if(title.length == 0 && desc.length == 0)
        {
          $('.forum-failure').html('<strong>✗</strong> Թեման պետք է ունենա Վերնագիր և Նկարագրություն։<a data-toggle="modal" class="alert-link" href="#newForumModal">Ուղղել</a>։');
        }
        $('.alert-loader').hide();
        $('.forum-failure').removeClass('d-none');
        $('.forum-success').addClass('d-none');
      }
    });
  },
  addLikeOrDislike: function(like_or_dislike, comment_id) {
    var that = this;
    $.post('forums/likes', {
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
  appendComment: function(item) {
    var line = this.generateCommentLine(item);
    $('.comments-content > .comment').append(line);
  },
  addPagination: function(data, filters) {
    if(data.total > 15){
      var pagination_settings = {
        items: data.total,
        itemsOnPage: data.per_page,
        currentPage: data.current_page,
        cssStyle: 'light-theme'
      };
      var url_hash = '';
      if(typeof filters != 'undefined' && Object.keys(filters).length > 0){
        if(filters.cat_id){
          url_hash += '#cat_id='+filters.cat_id;
        }
        url_hash += '&page=';
      }else{
        url_hash += '#page=';
      }

      pagination_settings.hrefTextPrefix = url_hash;
      $('#pagination').pagination(pagination_settings);
    }
  },
  restoreForum(){
    
    return `
                    <div class="forum-success alert alert-success d-none alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>✓</strong> Ձեր թեման հաջողությամբ ավելացվել է։
                    </div>
                    <div class="forum-failure alert alert-danger d-none">
                        <strong>✗</strong> Ձեր թեման պետք է ունենա Վերնագիր և Նկարագրություն։ Նկարագրությունը պետք է լինի 10 տառից ավելի երկար։<a data-toggle="modal" class="alert-link" href="#newForumModal">Ուղղել</a>։
                    </div>
                </div>
                <div class="d-block border-top mt-4">
                      <span class="back comeBack" style="display: none;"><span class="lnr lnr-arrow-left"></span> Հետ</span>
                      </div>
                <div class="alert-box">
                  <div class="tableForum mt-4">
                        <div class="tableTh trchild">
                            <p>Թեմաներ</p>
                            <p>Գրառումներ</p>
                            <p>Դիտումներ</p>
                            <p>Գրառող</p>
                        </div>
                        </div>
                         
               
                <div class="tableTbody content"></div>
                <div class="row">
                    <div id="pagination" class="pagination-sm col-12 pageinationGallery d-flex justify-content-center"></div>
                </div>
             `;
  },
  __initEvents: function() {
    var that = this;
/*    $('.content').html('<i class="loader fa fa-circle-o-notch fa-spin fa-5x" aria-hidden="true"></i>');
*/    $(document).ready(function(){
      $(".rcom").hide();
      $('.sub-menu ul').hide();
     
      //////////see more//////////
      var descMinHeight = 47;
      var desc = $('.desc');
      var descWrapper = $('.desc-wrapper');

      // show more button if desc too long
      if (desc.height() > descWrapper.height()) {
        $('.more-info').show();
      }

      // When clicking more/less button
      $('.more-info').click(function() {
        
        var this_content='.content'+$(this).data("id");
        var fullHeight = $('.desc').height();

        if ($(this).hasClass('expand')) {
          // contract
          $(this_content).animate({'height': descMinHeight}, 'slow');
        }
        else {
          // expand
          $(this_content).css({'height': descMinHeight, 'max-height': 'none'}).animate({'height': fullHeight}, 'slow');
        }

        $(this).toggleClass('expand');
        return false;
      });
      //////////see more end//////////
    });

    $(document).off('click', '.page-link').on('click', '.page-link', function(e){
      e.preventDefault();
      window.location.hash = this.hash;
      var page_n = ~~getURLParameter('page');
      that.getByFilter(page_n);
    });

    $(document).off('click', '.post_link').on('click', '.post_link', function(){
      $(".back").css("display" , "block");
      var forum_id = ~~this.getAttribute("data-item-id");
      that.addToViews(forum_id);
      window.location.hash = 'forum_id='+forum_id;
      
      $('.content').html('<i class="job-spinner fa fa-circle-o-notch fa-spin fa-5x" aria-hidden="true"></i>');
      that.getForumDetails(forum_id);
    });

    $(document).off('click', '.btn-post').on('click', '.btn-post', function(){
      var title = $('#title').val();
      var desc = $('#desc').val();
      that.createForum(title, desc);
    });

    $(document).off('click', '.back').on('click', '.back', function() {
      window.location.hash = '';
      $('.content_all').html(that.restoreForum());
      that.__initData();
    });

    if(window.location.hash != ""){
      $(".back").css("display" , "block");
    }

    $(document).off('click', '.btn-like-dislike').on('click', '.btn-like-dislike', function(){
      if(UserDataClass.id() != 0)
      {
        var like_or_dislike = $(this).attr('data-val');
        var comment_id = $(this).closest('.likeblock-comment').attr('data-comment-id');
        that.addLikeOrDislike(like_or_dislike, comment_id);
      }
      else
      {
        $('#signInModalLong').modal('show');
      }
    });

    $(document).off('click', '.btn-add-comment').on('click', '.btn-add-comment', function() {
      if(UserDataClass.id() != 0)
      {
        var comment = $('#add_comment').val();
        var forum_id = $('.forum-details').attr('data-forum-id');
        if(comment.length > 0 && user_id){
          var data = {
            message: comment,
            forum_id: forum_id,
            _token: _token
          };
          $('.btn-add-comment').attr('disabled',true)
          that.addComment(data);
          $('#add_comment').val('');
        }
      }
      else
      {
        $('#signInModalLong').modal('show'); 
      }
    });

    $(window).on('popstate', () => {
      
      if(window.location.hash.length == 0)
      {
        $('.content_all').html(that.restoreForum());
      that.__initData();
      }
      else
      {
        that.__initData();
      }
    });
  }
};

let ForumClass = new Forum();


// */

/*
function Forum(){
    this.__functions.call(this);
}
Forum.prototype = {
    __functions:function(){
        this.__addForum();
        this.__closeForumForm();
        this.__getItemsCount();
    },
    __addForum:()=>{
        document.querySelector('#addForumClick').addEventListener('click', ()=>{
            document.body.classList.add("afterForm");
            document.querySelector('.addForumForm').setAttribute('style', 'visibility:visible; transform:scale(1); opacity:1');
            document.onclick= (e)=>{
                if(
                    !e.target.closest(".addForumForm") && !e.target.closest(".closeFormForum") && document.querySelector('.addForumForm') 
                    && !e.target.closest("#addForumClick")
                    || e.target.closest(".closeFormForum") || e.target.closest(".closeButton") 
                ){
                    document.body.classList.remove("afterForm");
                    document.querySelector('.addForumForm').setAttribute('style', 'visibility:hidden; transform:scale(0); opacity:0')
                }
            }
        })
       
    },
    __closeForumForm:()=>{
        document.querySelector('.closeButton').addEventListener('click', (e)=>{
            e.preventDefault()
        })
    },
    __getItemsCount: function() {
    var that = this;
    $.get(_url('forums/getItemsCount'), data => {
      that.__addItemsCount(data.items_count);
    });
  },
  __addItemsCount: function(count) {
    $('.themes b').html(count);
  },
    
}
let ForumClass = new Forum();*/