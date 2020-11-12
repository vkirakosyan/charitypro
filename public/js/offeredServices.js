function OfferedServices(){
  this.__init.call(this);
}

OfferedServices.prototype = {
  __init: function(){
    this.__initOPtions();
    this.__initData();
    this.__initEvents();
  },
  __initOPtions: function() {
    this.service_img_path = 'images/SuggestedServices/';
  },
  lastTwo: function() {
    var that = this;
    $.get(_url('our_services/lastTwo'), function(data){
      var service_div="";

      for(item of data){
        let service_url = _url('our_service/'+item.id);
        let img_url = _url(that.service_img_path+item.img);
        service_div += `<div class="carousel-item">
            <img class="d-block img-fluid" src="`+img_url+`" alt="First slide">
            <div class="carousel-caption d-md-block">
            <p class="service-p" id="`+item.id+`"><a href="`+service_url+`">`+item.description+`</p>
            <a href="`+service_url+`" class="service-more" role="button">Ավելին</a>
        </div>
        </div>`;
      }

      $(".services-slider").append(service_div)
      $('.services-slider .carousel-item').eq(0).addClass('active');
    });
  },
  __initData: function() {
    if(window.location.href.indexOf('our_service') > -1){
      if(typeof window.service_id !== 'undefined' && ~~service_id != 0){
        this.getItemById(service_id);
      }else{
        var page_n = ~~getURLParameter('page');
        this.getAll(page_n);
      }
    }
  },
  getItemById: function(item_id, page_n='') {
    var that = this;
    var currentService = '';
    $.get(_url('our_service/getById'), {
      id : item_id
    }, data => {
      if(typeof data.id != 'undefined'){
        currentService += `<div class="row each-service">
          <div class="col-12">
            <button class="back" data-page="`+page_n+`"><i class="fa fa-chevron-left" aria-hidden="true"></i> Հետ</button>
          </div>
          <div class="post_img col-12 col-sm-12 col-md-3 col-lg-3 img">
            <img src="`+_url(that.service_img_path+data.img)+`" alt="">
          </div>
          <div class="col-12 col-sm-12 col-md-8 col-lg-9 serv-details">
            <h2>`+data.title+`</h2>
            <p>`+data.description+`</p>
          </div>
        </div>`;

        $('.serv-post-page').html(currentService);
        $('.each-service').addClass('border-naone');
      }
    });
  },
  getAll: function(page_n) {
    var that = this;
    var allServices = '';
    var req_data = {};
    if(page_n){
      req_data.page = page_n;
    }
    $.get(_url('our_service/getAll'), req_data, data => {
      for(item of data.data){
        let img_url = _url(that.service_img_path+item.img);
        allServices += `<div class="row each-service">
          <div class="post_img col-12 col-sm-12 col-md-3 col-lg-3 img">
             <img src="`+_url(that.service_img_path+item.img)+`" alt="">
          </div>
          <div class="col-12 col-sm-12 col-md-8 col-lg-9 serv-details">
            <h2 href="javascript:void(0)" data-item-id="`+item.id+`" class="service-h2"> `+item.title+`</h2>
            <p>`+item.description+`</p>
            <a href="javascript:void(0)" data-item-id="`+item.id+`" class="service-more" role="button">Ավելին</a>
          </div>
        </div>`;
      }

      $('.serv-post-page').html(allServices);
      $('.serv-post-page').append('<div class="row col-12 pagination-content"><ul id="pagination" class="pagination-sm"></ul></div>');
      if(data.total > data.per_page){
        var pagination_settings = {
          items: data.total,
          itemsOnPage: data.per_page,
          currentPage: data.current_page,
          cssStyle: 'light-theme',
          hrefTextPrefix: '#page='
        };
        $('#pagination').pagination(pagination_settings);
      }
    });
  },
  __initEvents: function(){
    if(window.location.href.indexOf('our_service') > -1){
      var that = this;
      $(document).off('click', '.page-link').on('click', ' .page-link', function(e){
        e.preventDefault();
        window.location.hash = this.hash;
        var page_n = ~~getURLParameter('page');
        that.getAll(page_n);
      });

      $(document).off('click', '.service-more').on('click', '.service-more', function(){
        var item_id = $(this).attr('data-item-id');
        var page_n = ~~getURLParameter('page');
        var page_n = page_n == 0 ? '' : page_n;
        history.pushState({},'', _url('our_service/'+item_id));
        that.getItemById(item_id, page_n);
      });
      $(document).off('click', '.service-h2').on('click', '.service-h2', function(){
        var item_id = $(this).attr('data-item-id');
        var page_n = ~~getURLParameter('page');
        var page_n = page_n == 0 ? '' : page_n;
        history.pushState({},'', _url('our_service/'+item_id));
        that.getItemById(item_id, page_n);
      });

      $(document).off('click', '.back').on('click', '.back', function(){
        var page_n = ~~$(this).attr('data-page');
        var url_part = _url('our_service');
        history.pushState({},'', url_part);
        if(page_n){
          location.hash = '#page='+page_n;
        }
        that.getAll(page_n);
      });
    }
  }
};

let OfferedServicesClass = new OfferedServices();