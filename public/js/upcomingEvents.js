
  



function UpcomingEvents(){
  this.__init.call(this);
}

UpcomingEvents.prototype = {
  __init: function(){
    this.__initOptions();
    this.__initEvents();
    this.__datePicker();
  },
  __initOptions: function() {
    this.users_img_path = 'images/Users/';
  },
     __datePicker:()=>{
        // Datepicker
        jQuery(document).ready(function ($) {
            var getField = function (id) {
                var el = $('#' + id + '-select');
                return el.length ? el : null;
            };

            var pickerSetup = function (id, date) {
                var el = getField(id);
                if (el) {
                    var checkin = id === 'checkin';
                    el.datepicker({
                    /*    altField: el.get(0).form[id],*/
                        altFormat: 'mm-yy-dd',
                        dateFormat: 'mm/dd/yy',
                        onSelect: function () {
                            if (checkin && getField('checkout') !== null) {
                                var constraint = new Date(el.datepicker('getDate'));
                                constraint.setDate(constraint.getDate() + 1);
                                getField('checkout').datepicker("option", 'minDate', constraint);
                            }
                        },
                        numberOfMonths: 1,
                        mandatory: true,
                        firstDay: 1,
                        minDate: checkin ? 0 : 1,
                        maxDate: '+2y'
                        //changeMonth: true,
                        //changeYear: true,
                        //showOtherMonths: true,
                        //selectOtherMonths: true
                    });
                    el.datepicker("setDate", date);
                }
            };
            pickerSetup("checkin", "+0");
            pickerSetup("checkout", "+1");
        });
        $(function () {
            $("#checkin-select").datepicker();
            $("#checkin-select2").datepicker();
        });

    // Datepicker
    },
  addGuestsGoings: function(going_users) {
    var goings = '';
    if(going_users != null){
      for(user of going_users){
        goings += '<li><img src="'+_url(this.users_img_path+user.f2)+'">'+user.f1+'</li>';
      }
      $('.guests .coming-list').html(goings);
    }else{
      $('.guests .coming-list').html('');
    }
  },
  addGuestsInterested: function(interested_users) {
    var interested = '';
    if(interested_users != null){
      for(user of interested_users){
        interested += '<li><img src="'+_url(this.users_img_path+user.f2)+'">'+user.f1+'</li>';
      }
      $('.guests .interested-list').html(interested);
    }else{
      $('.guests .interested-list').html('');
    }
  },
  addPagination: function(total, per_page, current_page, filters) {
    if(total > per_page){
      var pref = window.location.search.length > 0 ? window.location.search : '?page=';

      if (pref != '?page=') {
        pref = pref.slice(0, pref.indexOf('&page='))+'&page=';
      }

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
  initPagination: function(data) {
    data = JSON.parse(data);
    this.addPagination(data.total, data.per_page, data.current_page);
  },
  getDate: function(date) {
    var d = new Date(date);
    var month = Number(d.getMonth())+1;
    var day = d.getDate();
    var month = String(month).length == 1 ? '0'+month : month;
    var day = String(day).length == 1 ? '0'+day : day;
    formated_date = d.getFullYear()+'-'+month+'-'+day;
    return formated_date;
  },
  addToGoing: function(item_id, plus_minus) {
    var that = this;
    $.post(_url('upcoming_events/addGoing'), {
      event_id: item_id,
      add_or_cancle: plus_minus,
      _token: _token
    }, data => {
      var data_obj = JSON.parse(data);
      $('.circle-add-go').find('.count').text(data_obj.count_goings);
      that.addGuestsGoings(JSON.parse(data_obj.going_users));
    });
  },
  addToInterested: function(item_id, plus_minus) {
    var that = this;
    $.post(_url('upcoming_events/addInterested'), {
      event_id: item_id,
      add_or_cancle: plus_minus,
      _token: _token
    }, data => {

      var data_obj = JSON.parse(data);
      $('.circle-add-interested').find('.count').text(data_obj.count_interested);
      that.addGuestsInterested(JSON.parse(data_obj.interested_users));
    });
  },
  createEvent: function(data) {
    $.ajax({
        type: 'POST',
        url: _url('upcomingEvents/create'),
        contentType: false,
        processData: false,
        data: data
      }).done(data => {
        if(typeof data.errors != 'undefined'){
          $('.help-block').html('');
          $('.form-group').removeClass('has-error');
          for(key in data.errors){
            $('[name="'+key+'"]').after('<p class="help-block">'+data.errors[key]+'</p>')
            $('[name="'+key+'"]').parents('.form-group').addClass('has-error');
          }
        }else{
          history.go(0);
        }
      });
  },
  disableKeyboardOnMobile: function(media){
    if(media.matches){
      $("#datetimepicker").attr('readonly', true);
      $("#checkin-select").attr('readonly', true);
      $("#checkout-select").attr('readonly', true);
    }
    else
    {
      $("#datetimepicker").attr('readonly', false);
      $("#checkin-select").attr('readonly', false);
      $("#checkout-select").attr('readonly', false);
    }
  },
  __initMap: function(address) {
    var map;
    var infowindow;
    req_data = {
      key: 'AIzaSyAoxO7xzcP7R1W1fGxp2ysJ-DXpDH1wLhk',
      address: address.replace(/ /g, '+'),
    };

    $.get('https://maps.googleapis.com/maps/api/geocode/json', req_data, data => {
      if(data.results.length > 0){
        var pyrmont = data.results[0].geometry.location;
        map = new google.maps.Map(document.getElementById('map'), {
          center: pyrmont,
          zoom: 15
        });

        new google.maps.Marker({
          position: pyrmont,
          map: map,
          title: address
        });

        $('#locationModal').modal('show');
      }
    });
  },
  __initEvents: function() {
    var that = this;

    const media = window.matchMedia("(max-width: 575px)");

    this.disableKeyboardOnMobile(media);

    media.addListener(this.disableKeyboardOnMobile);

    $('.add-event').hide();

    $('[data-toggle="tooltip"]').tooltip();

/*    $('#datepickerFrom').datetimepicker({
      timepicker: false,
      format:'Y/m/d'
    });
    $('#datepickerTo').datetimepicker({
      timepicker: false,
      format:'Y/m/d'
    });

    $('#datetimepicker').datetimepicker();*/

    $('ul.tabs li').click(function(){
      var tab_id = $(this).attr('data-tab');

      $('ul.tabs li').removeClass('current');
      $('.tab-content').removeClass('current');

      $(this).addClass('current');
      $("#"+tab_id).addClass('current');
    });
    
    $(document).off('click', '.accordion').on('click', '.accordion', function() {
      if($('.not-loggedin').length == 1) {
        $('#signInModalLong').modal('show'); 
      }
      else {
        $('.add-event').slideToggle('slow');
      }
    });

    $(document).off('click', '.btn-event-search').on('click', '.btn-event-search', function(){
      var name    = $('.event-filter.title').val().trim();
      var city_id = ~~$('.filter-input.cities').val();
      var from_date = $('#checkin-select').val();
      var to_date = $('#checkout-select').val();
      var origin = window.location.origin+'/upcoming_events';
      var url = '';
      if(name.length > 0){
        url += 'title='+name;
      }
      if(city_id){
        url += url.length > 0 ? '&' : '';
        url += 'city_id='+city_id;
      }
      if(from_date){
        url += url.length > 0 ? '&' : '';
        url += 'from_date='+that.getDate(from_date);
      }
      if(to_date){
        url += url.length > 0 ? '&' : '';
        url += 'to_date='+that.getDate(to_date);
      }
      window.location.href = (url != '') ? origin+'?'+url : origin;
    });

    $(document).off('click', '.circle-add-go').on('click', '.circle-add-go', function(){
      if(user_id){
        var goes_content = $(this);
        var plus_minus = (~~goes_content.attr('data-user-goes')) * (-1);
        var item_id = goes_content.closest('.current-item').attr('data-item-id');
        goes_content.attr('data-user-goes', plus_minus);
        if(plus_minus === 1){
          goes_content.find('i').removeClass('fa-plus').addClass('fa-minus');
        }else{
          goes_content.find('i').removeClass('fa-minus').addClass('fa-plus');
        }
        that.addToGoing(item_id, plus_minus);
      }else{
        $('#signInModalLong').modal('show');
        return;
      }
    });
    $(document).off('click', '.circle-add-interested').on('click', '.circle-add-interested', function(){
      if(user_id)
      {
        var goes_content = $(this);
        var plus_minus = (~~goes_content.attr('data-user-interested')) * (-1);
        var item_id = goes_content.closest('.current-item').attr('data-item-id');
        goes_content.attr('data-user-interested', plus_minus);
        if(plus_minus === 1){
          goes_content.find('i').removeClass('fa-plus').addClass('fa-minus');
        }else{
          goes_content.find('i').removeClass('fa-minus').addClass('fa-plus');
        }
        that.addToInterested(item_id, plus_minus);
      }
      else
      {
        $('#signInModalLong').modal('show');
        return;
      }
    });

    $(document).off('click', '.btn-create-event').on('click', '.btn-create-event', function(){
      var data = new FormData();
     /* var title = $('.new-event.event-title').val();*/
      var title = $('.new-event').val();
      var description = $('.new-event.event-description').val();
      var time = $('.new-event.event-time').val();
      var address = $('.new-event.event-details').val();
      var city_id = $('.cities.new-event').val();
      var img = $('.new-event.event-img')[0].files[0];

      data.append('_token', $('[name="_token"]').val());
      data.append('title', title);
      data.append('description', description);
      data.append('time', time);
      data.append('details_location', address);
      data.append('city_id', city_id);
      data.append('img', img);
      that.createEvent(data);
    });

    $(document).off('click', '.show-location').on('click', '.show-location', function(){
      address = this.getAttribute('data-address');
      that.__initMap(address);
    });
  }
};

let UpcomingEventsClass = new UpcomingEvents();