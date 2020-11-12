function Account(){
  this.__init.call(this);
}
Account.prototype = {
  __init: function(){
    // debugger;
    this.setData();
    this.__initEvents();
    this.profileInfo();
  },
  setData: function(){
    var that = this
    var user_id = $('.job_account').attr("data-id");
    var filters = {
        user_id: user_id
    };
    if(window.location.hash == ""){
      window.location.hash = 'job';
    }

    var hash = window.location.hash;
    var job_id = ~~getURLParameter('job_id');
    var donation_id = ~~getURLParameter('donation_id');
    var event_id = ~~getURLParameter('event_id');
    var page = ~~getURLParameter('page');
    if(hash.indexOf('#job') > -1 && hash.indexOf('_') == -1) {
      that.getJobsByUserId(filters);
    }else if(hash == '#new_job') {
      if (that.jobs) {
        that.crateJob();
      } else {
        that.getJobsByUserId(filters);
        setTimeout(function(){ that.crateJob(); }, 0);
      }
    }else if(job_id) {
      that.getJob(job_id);
    }else if(hash.indexOf('#donations') > -1) {
        that.getDonationsByUserId(filters);
    }else if(hash == '#new_donation') {
      if (that.donations) {
        that.crateDonation();
      } else {
        that.getDonationsByUserId(filters);
        setTimeout(function(){ that.crateDonation(); }, 0);
      }
    }else if(donation_id) {
      that.getDonation(donation_id);
    }else if(hash.indexOf('#upcoming_events') > -1) {
        that.getEventsByUserId(filters);
    }else if(hash == '#new_event') {
      if (that.events) {
        that.crateEvent();
      } else {
        that.getEventsByUserId(filters);
        setTimeout(function(){ that.crateEvent(); }, 0);
      }
    }else if(event_id) {
      that.getEvent(event_id);
    }else if(hash.indexOf('#profile_edit') > -1) {
      that.editProfile();
    }

    if(hash.indexOf('#job') > -1) {
      $(".donations_account, .upcoming_events_account").parent().removeClass("active");
      $('.job_account').parent().addClass("active");
    }else if(hash.indexOf('#donations') > -1) {
        $(".job_account, .upcoming_events_account").parent().removeClass("active");
        $('.donations_account').parent().addClass("active");
    }else if(hash.indexOf('#upcoming_events') > -1) {
        $(".job_account, .donations_account").parent().removeClass("active");
        $('.upcoming_events_account').parent().addClass("active");
    }
  },
  imgPreview: function(input, placeToInsertImagePreview) {
    if (input.files) {
      var filesAmount = input.files.length;
      for (i = 0; i < filesAmount; i++) {
        var reader = new FileReader();
        reader.onload = function(event) {
          $(placeToInsertImagePreview).html($($.parseHTML('<img>')).attr('src', event.target.result).width(200));
        }
        reader.readAsDataURL(input.files[i]);
      }
    }
  },
  imagesPreview: function(input, placeToInsertImagePreview) {
    $(placeToInsertImagePreview).html('');
    if (input.files) {
      var filesAmount = input.files.length;
      for (i = 0; i < filesAmount; i++) {
        var reader = new FileReader();
        reader.onload = function(event) {
          $(placeToInsertImagePreview).append($($.parseHTML('<img>')).attr('src', event.target.result).width(200));
        }
        reader.readAsDataURL(input.files[i]);
      }
    }
  },

  getJobsByUserId: function(filters){
    var that = this;
    var ajax_data = {};
    var page_n = ~~getURLParameter('page');
    ajax_data.filters = JSON.stringify(filters);
    $('.account_jobs_content').html('<i class="loader fa fa-circle-o-notch fa-spin fa-5x" aria-hidden="true"></i>');
    if(page_n){
      ajax_data.page = page_n;
    }
    $.ajax({
      url: 'job/getJobsByUserId',
      type: "get",
      data: ajax_data,
      success: result => {
        that.jobs = result['jobs'].data;
        that.city = result['city'];
        that.categories = result['categories'];
        that.showJobs(that.jobs);
        that.addPagination(result['jobs'].total, result['jobs'].per_page, result['jobs'].current_page, filters);
      }
    });
  },
  getJob: function(id){
    var that = this;
    $.ajax({
      url: 'job/getJob',
      type: "get",
      data: {id: id},
      success: result => {
        that.city = result['city'];
        that.categories = result['categories'];
        that.editJob(result['job'][0]);
      }
    });
  },
  deleteJob: function(i){
    var that = this;
    var id = that.jobs[i].id;
    $.ajax({
      url: 'job/deleteJobsByUserId',
      type: "get",
      data: {id: id},
      success: jobs => {
        $('.jobs').eq(i).remove();
        that.jobs.splice(i, 1);
        setTimeout(function(){
          var items = $('.job_delete');
          for(var i = 0; i < items.length; i++) {
            $('.job_delete').eq(i).data("id", i);
            $('.job_edit').eq(i).data("id", i);
          }
        }, 0);
      }
    });
  },
  crateJob: function(i){
    var that = this;
    window.location.hash = 'new_job';
    var cities = that.city;
    var categories = that.categories;
    var form_job_cities = '';
    var form_job_categories = '';
    var form_job_button = `Ավելացնել`;
    categories.forEach((item, j) => {
      form_job_categories += `<option value="`+item['id']+`">`+item['name']+`</option>`;
    });
    cities.forEach((item, j) => {
      form_job_cities += `<option value="`+item['id']+`">`+item['name']+`</option>`;
    });
    $('.account_jobs_content').hide();
    $('.account_jobs_form').show();
    $('.form_job_title, .form_job_description, .form_job_number, .form_job_email').val('');
    $('.form_job_img').html('');
    $('.form_job_categories').append(form_job_categories);
    $('.form_job_cities').append(form_job_cities);
    $('.form_job_button').html(form_job_button);
    $('.form_job_button').removeClass('update_job');
    $('.form_job_button').addClass('add_job_button');
    $(".form_job_button" ).removeData( "id" );

    const telInput = $('.form_job_number');

    telInput.intlTelInput({
        utilsScript: 'js/utils.js',
        preferredCountries: ['am', 'ru', 'us']
    });

    const telReset = () => {
      telInput.parent().removeClass("has-error");
      telInput.parent().removeClass("has-success");
    };

    // on blur: validate
    telInput.blur(() => {
      telReset();
      if ($.trim(telInput.val())) {
        if (telInput.intlTelInput("isValidNumber")) {
          telInput.parent().addClass("has-success");
          $(".add_job_button").attr("disabled", false);
        } else {
          telInput.parent().addClass("has-error");
          $(".add_job_button").attr("disabled", true); 
        }
      }
    });

    // on keyup / change flag: reset
    telInput.on("keyup change", telReset);
  },
  addNewJob: function(i){
    var that = this;
    var post_data = new FormData();
    var new_title = $('.form_job_title').val();
    var new_description = $('.form_job_description').val();
    var new_email = $('.form_job_email').val();
    var new_cat_id = $('.form_job_categories');
    var new_city_id = $('.form_job_cities');
    var new_img = $('.job-gallery-photo-add');
    const telInput = $('.form_job_number');
    const tel = telInput.intlTelInput("getNumber");
    post_data.append('_token', $('[name="_token"]').val());
    post_data.append('title', new_title);
    post_data.append('description', new_description);
    post_data.append('number', tel);
    post_data.append('email', new_email);
    post_data.append('cat_id', new_cat_id.val());
    post_data.append('city_id', new_city_id.val());
    post_data.append('img', new_img[0].files[0]);

    $.ajax({
      type: 'POST',
      url: 'jobs/create',
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
        that.showJob(data.item);
        new_cat_id.find('option').removeAttr('selected').prop('selected', false);
        new_city_id.find('option').removeAttr('selected').prop('selected', false);
        new_img.wrap('<form>').closest('form').get(0).reset();
        new_img.unwrap();
        $('.gallery').html('');
      }
    });
  },
  editJob: function(job){
    var that = this;
    window.location.hash = 'job_id=' + job['id'];
    var cities = that.city;
    var categories = that.categories;
    var form_job_cities = '';
    var form_job_categories = '';
    var form_job_button = `Թարմացնել`;
    categories.forEach((item, j) => {
      if(item['id'] == job['cat_id']) {
          form_job_categories += `<option value="`+item['id']+`" selected >`+item['name']+`</option>`;
      }else {
          form_job_categories += `<option value="`+item['id']+`">`+item['name']+`</option>`;
      }
    });
    cities.forEach((item, j) => {
      if(item['id'] == job['city_id']) {
          form_job_cities += `<option value="`+item['id']+`" selected >`+item['name']+`</option>`;
      }else {
          form_job_cities += `<option value="`+item['id']+`">`+item['name']+`</option>`;
      }
    });
    $('.account_jobs_content, .profile-content, .account_donations_content').hide();
    $('.account_jobs_form').show();
    $('.form_job_title').val(job.title);
    $('.form_job_description').html(job.description);
    $('.form_job_number').val(job.number);
    $('.form_job_email').val(job.email);
    $('.form_job_categories').append(form_job_categories);
    $('.form_job_cities').append(form_job_cities);
    $('.form_job_button').html(form_job_button);
    $('.form_job_button').removeClass('add_job_button');
    $('.form_job_button').addClass('update_job');
    $('.update_job').data('id', job['id']);

    const telInput = $('.form_job_number');

    telInput.intlTelInput({
        utilsScript: 'js/utils.js',
        preferredCountries: ['am', 'ru', 'us']
    });

    const telReset = () => {
      telInput.parent().removeClass("has-error");
      telInput.parent().removeClass("has-success");
    };

    // on blur: validate
    telInput.blur(() => {
      telReset();
      if ($.trim(telInput.val())) {
        if (telInput.intlTelInput("isValidNumber")) {
          telInput.parent().addClass("has-success");
          $(".update_job").attr("disabled", false);
        } else {
          telInput.parent().addClass("has-error");
          $(".update_job").attr("disabled", true); 
        }
      }
    });

    // on keyup / change flag: reset
    telInput.on("keyup change", telReset);

    if(job.img) {
      var img = `
      <img src="`+_url('images/Jobs/'+job.img)+`" style="width: 200px" class="uploaded-img">
      <input type="button" class="delete-img" value="Ջնջել">
      <input type="hidden" name="image_uploaded" value="`+job.img+`" class="uploaded-inp">`;
      $('.form_job_img').html(img);
    }
  },
  updateJob: function(id){
    var that = this;
    var post_data = new FormData();
    var new_title = $('.form_job_title');
    var new_description = $('.form_job_description');
    var new_email = $('.form_job_email');
    var new_cat_id = $('.form_job_categories');
    var new_city_id = $('.form_job_cities');
    var new_img = $('.job-gallery-photo-add');
    const telInput = $('.form_job_number');
    const tel = telInput.intlTelInput("getNumber");
    post_data.append('title', new_title.val());
    post_data.append('description', new_description.val());
    post_data.append('number', tel);
    post_data.append('email', new_email.val());
    post_data.append('cat_id', new_cat_id.val());
    post_data.append('city_id', new_city_id.val());
    post_data.append('_token', $('[name="_token"]').val());
    post_data.append('id', id);
    if(new_img[0].files[0]){
      post_data.append('img', new_img[0].files[0]);
    }

    $.ajax({
      type: 'POST',
      url: 'job/updateJobAccount',
      contentType: false,
      processData: false,
      data: post_data
    }).done(data => {
      if(typeof data.errors != 'undefined') {
        $('.help-block').html('');
        $('.form-group').removeClass('has-error');
        for(key in data.errors){
          $('[name="'+key+'"]').after('<p class="help-block">'+data.errors[key]+'</p>')
          $('[name="'+key+'"]').parents('.form-group').addClass('has-error');
        }
      }else{
        $('.account_jobs_form').hide();
        $('.account_jobs_content').show();
        var user_id = $('.job_account').attr("data-id");
        var filters = {
          user_id: user_id
        };
        window.location.hash = 'job';
        that.getJobsByUserId(filters);
      }
    });
  },
  addPagination: function(total, per_page, current_page, filters) {
    var activeHref = $('.profile-usermenu>ul>.active>a').attr('href');
    var hrefTextPrefix = activeHref+'&page=';
    var pagination_content = $('.pagination-content[data-id="'+activeHref+'"]');
    pagination_content.find('#pagination').remove();
    var pagination_html = '<ul id="pagination" class="pagination-sm"></ul>';
    if(total > per_page){
      pagination_content.html(pagination_html);
      var pagination_settings = {
        items: total,
        itemsOnPage: per_page,
        currentPage: current_page,
        cssStyle: 'light-theme',
        hrefTextPrefix: hrefTextPrefix
      };

      pagination_content.find('#pagination').pagination(pagination_settings);
    }
  },
  showJobs: function(jobs){
    var job = `
    <div class="panel panel-default">
    <div class="panel-heading">Աշխատանք</div>
    <div class="panel-body">
        <button type="button" class="btn btn-sm add_new_job">Ավելացնել</button>
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>Վերնագիր</th><th>Նկարագրություն</th><th>Նկար</th><th>Հեռախոսահամար</th><th>Էլ․ Հասցե</th>
                    </tr>
                </thead>
                <tbody class="tbody_job">`;
    jobs.forEach((item, i) => {
    job += `
        <tr class="jobs">
        <td>`+ item.title +`</td>
        <th>`+ item.description +`</th>
        <th style="max-width: 150px; overflow: hidden;"><img src="images/Jobs/`+item.img+`" style="width: 100%;"></th>
        <th>`+ item.number +`</th>
        <th>`+ item.email +`</th>
        <td>
            <button class="btn btn-primary btn-sm job_edit" data-id="`+i+`"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Փոխել </button>
            <button class="btn btn-danger btn-sm job_delete" data-id="`+i+`"><i class="fa fa-trash-o" aria-hidden="true"></i> Ջնջել </button>
            </form>
        </td>
    </tr>`;
    });
        job += `</tbody>
            </table>
        </div>
    </div>
    </div>
    <div class='pagination-content' data-id='#job'></div>`;
    $('.account_jobs_form, .account_donations_form, .account_donations_content, .profile-content').hide();
    $('.account_jobs_content').show();
    $('.account_jobs_content').html(job);
  },
  showJob: function(item ){
    var that = this; 
    var count = $('.job_delete').length;
    that.jobs[count] = item;
    job = `
        <tr class="jobs">
        <td>`+ item.title +`</td>
        <th>`+ item.description +`</th>
        <th style="max-width: 150px; overflow: hidden;"><img src="images/Jobs/`+item.img+`" style="width: 100%;"></th>
        <th>`+ item.number +`</th>
        <th>`+ item.email +`</th>
        <td>
            <button class="btn btn-primary btn-sm job_edit" data-id="`+i+`"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Փոխել</button>
            <button class="btn btn-danger btn-sm job_delete" data-id="`+i+`"><i class="fa fa-trash-o" aria-hidden="true"></i> Ջնջել</button>
            </form>
        </td>
    </tr>`;
    var th_img = '.th_img'+count;
    $('.account_jobs_content').show();
    $('.tbody_job').append(job);
    window.location.hash = 'job';
    that.imgPreview(item.img, th_img);
    $('.account_jobs_form').hide();
  },

  getDonation: function(id){
    var that = this;
    $.ajax({
      url: 'donation/getDonation',
      type: "get",
      data: {id: id},
      success: result => {
        that.donations_categories = result['categories'];
        that.editDonation(result['donation'][0]);
      }
    });
  },
  getDonationsByUserId: function(filters){
    var that = this;
    var ajax_data = {};
    var page_n = ~~getURLParameter('page');
    ajax_data.filters = JSON.stringify(filters);
    $('.account_donations_content').html('<i class="loader fa fa-circle-o-notch fa-spin fa-5x" aria-hidden="true"></i>');
    if(page_n){
      ajax_data.page = page_n;
    }
    $.ajax({
      url: 'donations/getDonationsByUserId',
      type: "get",
      data: ajax_data,
      success: result => {
        that.donations = result['donations'].data;
        that.donations_categories = result['categories'];
        that.showDonations(that.donations);
        that.addPagination(result.donations.total, result.donations.per_page, result.donations.current_page, filters);
      }
    });
  },
  showDonations: function(donations){
    var donation = `
    <div class="panel panel-default">
    <div class="panel-heading"> Նվիրաբերություն </div>
    <div class="panel-body">
        <button type="button" class="btn btn-sm add_new_donation">Ավելացնել</button>
        <br/>
        <br/>
        <div class="table-responsive">
          <table class="table table-borderless">
            <thead>
              <tr>
                <th>Անուն</th><th>Նկարագրություն</th><th>Հեռախոսահամար</th><th>Նկար</th>
              </tr>
          </thead>
          <tbody class="tbody_donations">`;
    donations.forEach((item, i) => {
      var images = JSON.parse(item.images);
      var slider_part = `<div class="donations_slider">`;
      images.forEach((img_path, i) => {
        slider_part +=`<div class="donation_img" style="background-image: url(images/Donations/`+img_path+`);"></div>`;
      });
      slider_part += '</div>';
      donation += `
        <tr class="donations">
          <td>`+ item.name +`</td>
          <th style="max-width: 150px; overflow: hidden;">`+ item.description +`</th>
          <th style="max-width: 150px; overflow: hidden;">`+ item.phone +`</th>
          <th style="max-width: 150px; overflow: hidden;">`+slider_part+`</th>
          <td>
            <button class="btn btn-primary btn-sm donation_edit" data-id="`+i+`"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Փոխել </button>
            <button class="btn btn-danger btn-sm donation_delete" data-id="`+i+`"><i class="fa fa-trash-o" aria-hidden="true"></i> Ջնջել</button>
            </form>
          </td>
        </tr>`;
    });
        donation += `
          </tbody>
        </table>
      </div>
    </div>
    </div>
    <div class='pagination-content' data-id='#donations'></div>`;
    $('.account_jobs_form, .account_donations_form, .account_jobs_content, .profile-content').hide();
    $('.account_donations_content').show();
    $('.account_donations_content').html(donation);
    $('.donations_slider').slick();
  },
  showDonation: function(item){
    var that = this; 
    var count = $('.donation_delete').length;
    that.donations[count] = item;
    var images = JSON.parse(item['images']);
    var slider_part = `<div class="donations_slider">`;
    images.forEach((img_path, i) => {
      slider_part +=`<div class="donation_img" style="background-image: url(images/Donations/`+img_path+`);"></div>`;
    });
    slider_part += '</div>';
    donations = `
      <tr class="donations">
        <td>`+ item.name +`</td>
        <th style="max-width: 150px; overflow: hidden;">`+ item.description +`</th>
        <th style="max-width: 150px; overflow: hidden;">`+item.phone+`</th>
        <th style="max-width: 150px; overflow: hidden;">`+slider_part+`</th>
        <td>
          <button class="btn btn-primary btn-sm donation_edit" data-id="`+count+`"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Փոխել</button>
          <button class="btn btn-danger btn-sm donation_delete" data-id="`+count+`"><i class="fa fa-trash-o" aria-hidden="true"></i> Ջնջել</button>
          </form>
        </td>
      </tr>`;
    var th_img = '.th_img'+count;
    $('.account_donations_content').show();
    $('.tbody_donations').append(donations);
    window.location.hash = 'donations';
    $('.account_donations_form').hide();
    $('.donations_slider').eq(count).slick();
  },
  deleteDonation: function(i){
    var that = this;
    var id = that.donations[i].id;
    $.ajax({
      url: 'donation/deleteDonation',
      type: "get",
      data: {id: id},
      success: donations => {
        $('.donations').eq(i).remove();
        that.donations.splice(i, 1);
        setTimeout(function(){
          var items = $('.donation_delete');
          for(var i = 0; i < items.length; i++) {
            $('.donation_delete').eq(i).data("id", i);
            $('.donation_edit').eq(i).data("id", i);
          }
        }, 0);
      }
    });
  },
  crateDonation: function(i){
    var that = this;
    window.location.hash = 'new_donation';
    var categories = that.donations_categories;
    var form_donation_categories = '';
    var form_donation_button = `Ավելացնել`;
    categories.forEach((item, j) => {
      form_donation_categories += `<option value="`+item['id']+`">`+item['name']+`</option>`;
    });
    $('.account_donations_content').hide();
    $('.account_donations_form').show();
    $('.form_donation_name, .form_donation_description, .form_donation_phone').val('');
    $('.form_donation_img, .form_donation_images').html('');
    $('.form_donation_categories').append(form_donation_categories);
    $('.form_donation_button').html(form_donation_button);
    $('.form_donation_button').removeClass('update_donation');
    $('.form_donation_button').addClass('add_donation_button');
    $(".form_donation_button" ).removeData( "id" );

    const telInput = $('.form_donation_phone');

    telInput.intlTelInput({
        utilsScript: 'js/utils.js',
        preferredCountries: ['am', 'ru', 'us']
    });

    const telReset = () => {
      telInput.parent().removeClass("has-error");
      telInput.parent().removeClass("has-success");
    };

    // on blur: validate
    telInput.blur(() => {
      telReset();
      if ($.trim(telInput.val())) {
        if (telInput.intlTelInput("isValidNumber")) {
          telInput.parent().addClass("has-success");
          $(".add_donation_button").attr("disabled", false);
        } else {
          telInput.parent().addClass("has-error");
          $(".add_donation_button").attr("disabled", true); 
        }
      }
    });

    // on keyup / change flag: reset
    telInput.on("keyup change", telReset);
  },
  addNewDonetion: function(i){
    var that = this;
    var post_data = new FormData();
    var new_name = $('.form_donation_name').val();
    var new_description = $('.form_donation_description').val();
    var new_cat_id = $('.form_donation_categories');
    var new_images = $('.donation-gallery-photo-add');
    const telInput = $('.form_donation_phone');
    const tel = telInput.intlTelInput("getNumber");
    post_data.append('_token', $('[name="_token"]').val());
    post_data.append('name', new_name);
    post_data.append('description', new_description);
    post_data.append('phone', tel);
    post_data.append('cat_id', new_cat_id.val());
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
        for(key in data.errors){
          $('[name="'+key+'"]').after('<p class="help-block">'+data.errors[key]+'</p>')
          $('[name="'+key+'"]').parents('.form-group').addClass('has-error');
        }
      } else {
        that.showDonation(data.item);
        new_cat_id.find('option').removeAttr('selected').prop('selected', false);
        new_images.wrap('<form>').closest('form').get(0).reset();
        new_images.unwrap();
        $('.gallery').html('');
      }
    });
  },
  editDonation: function(donation){
    var that = this;
    window.location.hash = 'donation_id=' + donation['id'];
    var categories = that.donations_categories;
    var form_donation_categories = '';
    var form_donation_button = `Թարմացնել`;
    categories.forEach((item, j) => {
      if(item['id'] == donation['cat_id']) {
          form_donation_categories += `<option value="`+item['id']+`" selected >`+item['name']+`</option>`;
      }else {
          form_donation_categories += `<option value="`+item['id']+`">`+item['name']+`</option>`;
      }
    });
    $('.account_donations_content, .profile-content, .account_jobs_content').hide();
    $('.account_donations_form').show();
    $('.form_donation_name').val(donation.name);
    $('.form_donation_description').html(donation.description);
    $('.form_donation_categories').append(form_donation_categories);
    $('.form_donation_phone').val(donation.phone);
    $('.form_donation_button').html(form_donation_button);
    $('.form_donation_button').removeClass('add_donation_button');
    $('.form_donation_button').addClass('update_donation');
    $('.update_donation').data('id', donation['id']);


    const telInput = $('.form_donation_phone');

    telInput.intlTelInput({
        utilsScript: 'js/utils.js',
        preferredCountries: ['am', 'ru', 'us']
    });

    const telReset = () => {
      telInput.parent().removeClass("has-error");
      telInput.parent().removeClass("has-success");
    };

    // on blur: validate
    telInput.blur(() => {
      telReset();
      if ($.trim(telInput.val())) {
        if (telInput.intlTelInput("isValidNumber")) {
          telInput.parent().addClass("has-success");
          $(".update_donation").attr("disabled", false);
        } else {
          telInput.parent().addClass("has-error");
          $(".update_donation").attr("disabled", true); 
        }
      }
    });

    // on keyup / change flag: reset
    telInput.on("keyup change", telReset);

    if(donation.images) {
      var images = JSON.parse(donation.images);
      var img = '';
      images.forEach((img_path, i) => {
        img += `
        <img src="`+_url('images/Donations/'+img_path)+`" style="width: 200px" class="uploaded-img">
        <input type="button" class="delete-img" value="Ջնջել">
        <input type="hidden" name="img_uploaded" value="`+img_path+`" class="uploaded-img">`;
      });
      $('.form_donation_images').html(img);
    }
  },
  updateDonation: function(id){
    var that = this;
    var post_data = new FormData();
    var new_name = $('.form_donation_name');
    var new_description = $('.form_donation_description');
    var new_cat_id = $('.form_donation_categories');
    var new_images = $('.donation-gallery-photo-add');
    const telInput = $('.form_donation_phone');
    const tel = telInput.intlTelInput("getNumber");
    var images = [];
    post_data.append('name', new_name.val());
    post_data.append('description', new_description.val());
    post_data.append('cat_id', new_cat_id.val());
    post_data.append('phone', tel);
    post_data.append('_token', $('[name="_token"]').val());
    post_data.append('id', id);
    images = new_images[0].files;
    var uploaded_img = $('input.uploaded-img')
    for(var j = 0; j < uploaded_img.length; j++) {
      post_data.append('uploaded_images[]', uploaded_img.eq(j).val());
    }
    if(images.length != 0){
      for(index in images){
        post_data.append('images[]', new_images[0].files[index]);
      }
    }
    $.ajax({
      type: 'POST',
      url: 'donation/updateDonationAccount',
      contentType: false,
      processData: false,
      data: post_data
    }).done(data => {
      if(typeof data.errors != 'undefined') {
        $('.help-block').html('');
          $('.form-group').removeClass('has-error');
        for(key in data.errors){
          $('[name="'+key+'"]').after('<p class="help-block">'+data.errors[key]+'</p>')
          $('[name="'+key+'"]').parents('.form-group').addClass('has-error');
        }
      }else{
        $('.account_donations_form').hide();
        $('.account_donations_content').show();
        var user_id = $('.donations_account').attr("data-id");
        var filters = {
          user_id: user_id
        };
        window.location.hash = 'donations';
        that.getDonationsByUserId(filters);
        $('.form_donation_images').html('');
      }
    });
  },

  getEvent: function(id){
    var that = this;
    $.ajax({
      url: 'upcomingEvents/getEvent',
      type: "get",
      data: {id: id},
      success: result => {
        that.city = result['city'];
        that.editEvent(result['event'][0]);
      }
    });
  },
  getEventsByUserId: function(filters){
    var that = this;
    var ajax_data = {};
    var page_n = ~~getURLParameter('page');
    ajax_data.filters = JSON.stringify(filters);
    $('.account_events_content').html('<i class="loader fa fa-circle-o-notch fa-spin fa-5x" aria-hidden="true"></i>');
    if(page_n){
      ajax_data.page = page_n;
    }
    $.ajax({
      url: 'upcomingEvents/getEventsByUserId',
      type: "get",
      data: ajax_data,
      success: result => {
        that.events = result.events.data;
        that.city = result.city;
        that.showEvents(that.events);
        that.addPagination(result['events'].total, result['events'].per_page, result['events'].current_page, filters);
      }
    });
  },
  showEvents: function(events){
    var event = `
    <div class="panel panel-default">
    <div class="panel-heading">Իրադարձություններ</div>
    <div class="panel-body">
        <button type="button" class="btn btn-sm add_new_event">Ավելացնել</button>
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>Վերնագիր</th><th>Նկարագրություն</th><th>Քաղաք</th><th>Վայր</th><th>Ամսաթիվ</th><th>Նկար</th>
                    </tr>
                </thead>
                <tbody class="tbody_event">`;
    events.forEach((item, i) => {
    event += `
        <tr class="events">
        <td>`+ item.title +`</td>
        <th>`+ item.description +`</th>
        <th>`+ item.city_name +`</th>
        <th>`+ item.details_location +`</th>
        <th>`+ item.time +`</th>
        <th style="max-width: 150px; overflow: hidden;"><img src="images/Events/`+item.img+`" style="width: 100%;"></th>
        <td>
            <button class="btn btn-primary btn-sm event_edit" data-id="`+i+`"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Փոխել </button>
            <button class="btn btn-danger btn-sm event_delete" data-id="`+i+`"><i class="fa fa-trash-o" aria-hidden="true"></i> Ջնջել </button>
            </form>
        </td>
    </tr>`;
    });
        event += `</tbody>
            </table>
        </div>
    </div>
    </div>
    <div class='pagination-content' data-id='#upcoming_events'></div>`;
    $('.account_jobs_form, .account_donations_form, .account_events_form, .account_jobs_content, .account_donations_content, .profile-content').hide();
    $('.account_events_content').show();
    $('.account_events_content').html(event);
  },
  showEvent: function(item){
    var that = this; 
    var count = $('.event_delete').length;
    that.events[count] = item;
    event = `
        <tr class="events">
        <tr class="events">
        <td>`+ item.title +`</td>
        <th>`+ item.description +`</th>
        <th>`+ item.city_name +`</th>
        <th>`+ item.details_location +`</th>
        <th>`+ item.time +`</th>
        <th style="max-width: 150px; overflow: hidden;"><img src="images/Events/`+item.img+`" style="width: 100%;"></th>
        <td>
            <button class="btn btn-primary btn-sm event_edit" data-id="`+i+`"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Փոխել </button>
            <button class="btn btn-danger btn-sm event_delete" data-id="`+i+`"><i class="fa fa-trash-o" aria-hidden="true"></i> Ջնջել </button>
            </form>
        </td>
    </tr>`;
    var th_img = '.th_img'+count;
    $('.account_events_content').show();
    $('.tbody_event').append(event);
    window.location.hash = 'event';
    that.imgPreview(item.img, th_img);
    $('.account_events_form').hide();
  },
  crateEvent: function(i){
    var that = this;
    window.location.hash = 'new_event';
    var cities = that.city;
    var form_event_cities = '';
    var form_event_button = `Ավելացնել`;
    cities.forEach((item, j) => {
      form_event_cities += `<option value="`+item['id']+`">`+item['name']+`</option>`;
    });
    $('.account_events_content').hide();
    $('.account_events_form').show();
    $('.form_events_title, .form_events_description, .form_events_location, .form_events_date').val('');
    $('.form_event_img').html('');
    $('.form_event_cities').append(form_event_cities);
    $('.form_event_button').html(form_event_button);
    $('.form_event_button').removeClass('update_event');
    $('.form_event_button').addClass('add_event_button');
    $(".form_event_button" ).removeData( "id" );
  },
  addNewEvent: function(i){
    var that = this;
    var post_data = new FormData();
    var new_title = $('.form_events_title').val();
    var new_description = $('.form_events_description').val();
    var new_location = $('.form_events_location').val();
    var new_date = $('.form_events_date').val();
    var new_city_id = $('.form_event_cities');
    var new_img = $('.events-gallery-photo-add');
    post_data.append('_token', $('[name="_token"]').val());
    post_data.append('title', new_title);
    post_data.append('description', new_description);
    post_data.append('details_location', new_location);
    post_data.append('time', new_date);
    post_data.append('city_id', new_city_id.val());
    post_data.append('img', new_img[0].files[0]);
    $.ajax({
      type: 'POST',
      url: 'upcomingEvents/create',
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
        that.showEvent(data.item);
        new_city_id.find('option').removeAttr('selected').prop('selected', false);
        new_img.wrap('<form>').closest('form').get(0).reset();
        new_img.unwrap();
        $('.gallery').html('');
      }
    });
  },
  deleteEvent: function(i){
    var that = this;
    var id = that.events[i].id;

    $.ajax({
      url: 'upcomingEvents/deleteEventsByUserId',
      type: "get",
      data: {id: id},
      success: events => {
        $('.events').eq(i).remove();
        that.events.splice(i, 1);
        setTimeout(function(){
          var items = $('.event_delete');
          for(var i = 0; i < items.length; i++) {
            $('.event_delete').eq(i).data("id", i);
            $('.event_edit').eq(i).data("id", i);
          }
        }, 0);
      }
    });
  },
  editEvent: function(event){
    var that = this;
    window.location.hash = 'event_id=' + event['id'];
    var cities = that.city;
    var form_event_cities = '';
    var form_event_button = `Թարմացնել`;
    cities.forEach((item, j) => {
      if(item['id'] == event['city_id']) {
          form_event_cities += `<option value="`+item['id']+`" selected >`+item['name']+`</option>`;
      }else {
          form_event_cities += `<option value="`+item['id']+`">`+item['name']+`</option>`;
      }
    });
    $('.account_jobs_content, .profile-content, .account_donations_content, .account_events_content').hide();
    $('.account_events_form').show();
    $('.form_events_title').val(event.title);
    $('.form_events_description').html(event.description);
    $('.form_events_location').val(event.details_location);
    $('.form_events_date').val(event.time);
    $('.form_event_cities').append(form_event_cities);
    $('.form_event_button').html(form_event_button);
    $('.form_event_button').removeClass('add_event_button');
    $('.form_event_button').addClass('update_event');
    $('.update_event').data('id', event['id']);
    if(event.img) {
      var img = `
      <img src="`+_url('images/Events/'+event.img)+`" style="width: 200px" class="uploaded-img">
      <input type="button" class="delete-img" value="Ջնջել">
      <input type="hidden" name="image_uploaded" value="`+event.img+`" class="uploaded-inp">`;
      $('.form_event_img').html(img);
    }
  },
  updateEvent: function(id){
    var that = this;
    var post_data = new FormData();
    var new_title = $('.form_events_title').val();
    var new_description = $('.form_events_description').val();
    var new_location = $('.form_events_location').val();
    var new_date = $('.form_events_date').val();
    var new_city_id = $('.form_event_cities');
    var new_img = $('.events-gallery-photo-add');
    post_data.append('_token', $('[name="_token"]').val());
    post_data.append('title', new_title);
    post_data.append('description', new_description);
    post_data.append('details_location', new_location);
    post_data.append('time', new_date);
    post_data.append('city_id', new_city_id.val());
    post_data.append('id', id);
    if(new_img[0].files[0]){
      post_data.append('img', new_img[0].files[0]);
    }
    $.ajax({
      type: 'POST',
      url: 'upcomingEvents/updateEventAccount',
      contentType: false,
      processData: false,
      data: post_data
    }).done(data => {
      if(typeof data.errors != 'undefined') {
        $('.help-block').html('');
          $('.form-group').removeClass('has-error');
        for(key in data.errors){
          $('[name="'+key+'"]').after('<p class="help-block">'+data.errors[key]+'</p>')
          $('[name="'+key+'"]').parents('.form-group').addClass('has-error');
        }
      }else{
        $('.account_events_form').hide();
        $('.account_events_content').show();
        var user_id = $('.upcoming_events_account').attr("data-id");
        var filters = {
          user_id: user_id
        };
        window.location.hash = 'event';
        that.getEventsByUserId(filters);
      }
    });
  },

  profileInfo: function(){
    $('.profile-usertitle-name').html(UserDataClass.name());
    $('.profile-userpic>img').attr("src" , "/images/Users/"+UserDataClass.img());
  },
  editProfile: function(){
    var that = this;
    window.location.hash = 'profile_edit';
    $(".job_account, .donations_account, .upcoming_events_account").parent().removeClass("active");
    $('.account_jobs_form, .account_donations_form, .account_events_form, .account_donations_content, .account_jobs_content, .account_events_content, .profile-content').hide();
    $('.account_edit_profile_form').show();
    $('.account-change-password-form').show();
    $('.edit_profile_form_img').val('');
    $('.edit-profile-new-img').html('');
    $('.edit_profile_form_name').val(UserDataClass.name());
    $('.edit_profile_form_email').val(UserDataClass.email());
    $('.user-gender[value="'+UserDataClass.gender()+'"]').prop('checked', true);
    $('.edit-profile-old-img').html('<img src="'+_url('images/Users/'+UserDataClass.img())+'" />');

    if(UserDataClass.img()) {
      var img = `
      <img src="`+_url('images/Users/'+UserDataClass.img())+`" style="width: 200px" class="uploaded-img">
      <input type="button" class="delete-img" value="Ջնջել">
      <input type="hidden" name="image_uploaded" value="`+UserDataClass.img()+`" class="uploaded-inp">`;
      $('.edit-profile-old-img').html(img);
    }
  },
  updateProfile: function(){
    var that = this;
    var post_data = new FormData();
    var new_name = $('.edit_profile_form_name').val();
    var new_email= $('.edit_profile_form_email').val();
    var new_gender = $('.user-gender:checked').val();
    var new_img = $('.edit_profile_form_img');
    var new_pass = $('.edit_profile_form_password').val();
    var new_pass_rep = $('.edit_profile_form_password_confirm').val();
    post_data.append('_token', $('[name="_token"]').val());
    const image = $("#imageToCrop");
    const {width,height,x,y} = image.cropper('getData',true);
    post_data.append('name', new_name);
    post_data.append('email', new_email);
    post_data.append('gender', new_gender);
    post_data.append('deletedImg', deletedImg);
    post_data.append('crop-width', width);
    post_data.append('crop-height', height);
    post_data.append('crop-x', x);
    post_data.append('crop-y', y);
    if(new_img[0].files[0]){
      post_data.append('img', new_img[0].files[0]);
    }

    if(new_pass.trim().length > 0){
      post_data.append('password', new_pass.trim());
      post_data.append('confirm_password', new_pass_rep.trim());
    }

    $.ajax({
      type: 'POST',
      url: 'account/updateUserData',
      contentType: false,
      processData: false,
      data: post_data
    }).done(data => {
      if(typeof data.errors != 'undefined') {
        $('.help-block').html('');
          $('.form-group').removeClass('has-error');
        for(key in data.errors){
          $('[name="'+key+'"]').after('<p class="help-block">'+data.errors[key]+'</p>')
          $('[name="'+key+'"]').parents('.form-group').addClass('has-error');
        }
      }else{
        $('.profile-usertitle-name').html(data.user.name);
        if(data.user.img){
          $('.profile-userpic>img').attr("src" , "/images/Users/"+data.user.img);
        } else if(data.user.img == null){
          if(data.user.gender == "F"){
            $('.profile-userpic>img').attr("src" , "/images/Users/female.png");
          }else{
            $('.profile-userpic>img').attr("src" , "/images/Users/male.png");
          }
        }
        $('.account_edit_profile_form').hide();
        $('.account-change-password-form').hide();
        if (that.job){
          $('.account_jobs_content').show();
        }else{
          var user_id = $('.job_account').data("id");
          var filters = {
            user_id: user_id
          };
          that.getJobsByUserId(filters);
        }
      }
    });
  },

  updatePassword: function(){
    var that = this;
    var post_data = new FormData();
    var new_pass = $('.edit_profile_form_password').val();
    var new_pass_rep = $('.edit_profile_form_password_confirm').val();
    post_data.append('_token', $('[name="_token"]').val());

    if(new_pass.trim().length > 0){
      post_data.append('password', new_pass.trim());
      post_data.append('confirm_password', new_pass_rep.trim());
    }

    $.ajax({
      type: 'POST',
      url: 'account/updatePassword',
      contentType: false,
      processData: false,
      data: post_data,
    }).done(data => {
      if(typeof data.errors != 'undefined') {
        $('.help-block').html('');
          $('.form-group').removeClass('has-error');
        for(key in data.errors){
          $('[name="'+key+'"]').after('<p class="help-block">'+data.errors[key]+'</p>')
          $('[name="'+key+'"]').parents('.form-group').addClass('has-error');
        }
      }else{
        $('.profile-usertitle-name').html(data.user.name);
        $('.account_edit_profile_form').hide();
        $('.account-change-password-form').hide();
        if (that.job){
          $('.account_jobs_content').show();
        }else{
          var user_id = $('.job_account').data("id");
          var filters = {
            user_id: user_id
          };
          that.getJobsByUserId(filters);
        }
      }
    });
  },

  __initEvents: function(){
    var that = this;
    deletedImg = 0;
    $(document).off('click', '.job_account').on('click', '.job_account', function() {
      location.hash = '#job';
      $(".account-pages > li").removeClass("active");
      $(this).closest('li').addClass("active");

      if (that.jobs) {
        $('.account_jobs_form, .account_donations_form, .account_events_form, .account_edit_profile_form, .account-change-password-form, .account_donations_content, .account_events_content, .profile-content').hide();
        $('.account_jobs_content').show();
      } else {
        $('.account_edit_profile_form').hide();
        $('.account-change-password-form').hide();
        var user_id = $(this).data("id");
        var filters = {
          user_id: user_id
        };
        that.getJobsByUserId(filters);
      }
    });


    $(document).off('click', '.add_new_job').on('click', '.add_new_job', function() {
      that.crateJob();
    });

    $(document).off('click', '.add_job_button').on('click', '.add_job_button', function() {
      that.addNewJob();
    });

    $(document).off('click', '.job_delete').on('click', '.job_delete', function() {
      var i = $(this).data("id");
      var text = "Do you really want to delete this job?";

      if(confirm(text)){
        that.deleteJob(i);
      }
    });

    $('.job-gallery-photo-add').on('change', function() {
      that.imgPreview(this, 'div.gallery');
    });
    $('.donation-gallery-photo-add').on('change', function() {
      that.imagesPreview(this, 'div.gallery');
    });

    $(document).off('click', '.delete-img').on('click', '.delete-img', function() {
      deletedImg = 1;
      var text = "Do you raelly want to delete this image?";
      
      if(confirm(text)){
        $(this).next('.uploaded-img').remove();
        $(this).prev('.uploaded-img').remove();
        $(this).remove();
      }
    });
    $(document).off('click', '.job_edit').on('click', '.job_edit', function() {
      var i = $(this).data("id");
      var job = that.jobs[i];
      that.editJob(job);
    });
    $(document).off('click', '.update_job').on('click', '.update_job', function() {
      var id = $(this).data("id");
      that.updateJob(id);
    });


    $(document).off('click', '.donations_account').on('click', '.donations_account', function() {
      location.hash = '#donations';
      $(".account-pages > li").removeClass("active");
      $(this).closest('li').addClass("active");

      if (that.donations) {
        $('.account_jobs_form, .account_donations_form, .account_events_form, .account_edit_profile_form, .account-change-password-form, .account_jobs_content, .account_events_content, .profile-content').hide();
        $('.account_donations_content').show();
      } else {
        $('.account_edit_profile_form').hide();
        $('.account-change-password-form').hide();
        var id = $(this).data("id");
        var filters = {
              user_id: user_id
          };
        that.getDonationsByUserId(filters);
      }
    });
    $(document).off('click', '.donation_delete').on('click', '.donation_delete', function() {
      var i = $(this).data("id");
      var text = "Do you really want to delete this donation?";
      
      if(confirm(text)){
        that.deleteDonation(i);
      }
    });
    $(document).off('click', '.add_new_donation').on('click', '.add_new_donation', function() {
      that.crateDonation();
    });
    $(document).off('click', '.add_donation_button').on('click', '.add_donation_button', function() {
      that.addNewDonetion();
    });
    $(document).off('click', '.donation_edit').on('click', '.donation_edit', function() {
      var i = $(this).data("id");
      var donation = that.donations[i];
      that.editDonation(donation);
    });
    $(document).off('click', '.update_donation').on('click', '.update_donation', function() {
      var id = $(this).data("id");
      that.updateDonation(id);
    });

    
    $(document).off('click', '.upcoming_events_account').on('click', '.upcoming_events_account', function() {
      location.hash = '#upcoming_events';
      $(".account-pages > li").removeClass("active");
      $(this).closest('li').addClass("active");

      if (that.events) {
        $('.account_jobs_form, .account_donations_form, .account_events_form, .account_edit_profile_form, .account-change-password-form, .account_donations_content, .account_jobs_content, .profile-content').hide();
        $('.account_events_content').show();
      } else {
        $('.account_edit_profile_form').hide();
        $('.account-change-password-form').hide();
        var user_id = $(this).data("id");
        var filters = {
          user_id: user_id
        };
        that.getEventsByUserId(filters);
      }
    });

    $(document).off('click', '.add_new_event').on('click', '.add_new_event', function() {
      that.crateEvent();
    });

    $(document).off('click', '.add_event_button').on('click', '.add_event_button', function() {
      that.addNewEvent();
    });

    $(document).off('click', '.event_delete').on('click', '.event_delete', function() {
      var i = $(this).data("id");
      var text = "Do you really want to delete this event?";
      
      if(confirm(text)){
        that.deleteEvent(i);
      }
    });

    $('.events-gallery-photo-add').on('change', function() {
      that.imgPreview(this, 'div.gallery');
    });

    $(document).off('click', '.event_edit').on('click', '.event_edit', function() {
      var i = $(this).data("id");
      var event = that.events[i];
      that.editEvent(event);
    });
    $(document).off('click', '.update_event').on('click', '.update_event', function() {
      var id = $(this).data("id");
      that.updateEvent(id);
    });

    $(document).off('click', '.edit_profile_btn').on('click', '.edit_profile_btn', function() {
      that.editProfile();
      $('.account-change-password-form').removeClass('d-none');
    });
    $(document).off('click', '.edit_profile_form_confirm').on('click', '.edit_profile_form_confirm', function() {
      that.updateProfile();
    });

    $(document).off('click', '.change_password_confirm').on('click', '.change_password_confirm', function() {
      that.updatePassword();
    });

    $('.edit_profile_form_img').on('change', function(e) {
      $('.edit-profile-old-img').html('');
      that.imgPreview(this, 'div.edit-profile-new-img');
      const reader = new FileReader();
      reader.onload = (event) => {
        $('#imageToCrop').attr('src', event.target.result);
      }
      reader.readAsDataURL(e.target.files[0]);
      const image = $("#imageToCrop");
      setTimeout(() => image.cropper({
        viewMode: 1
      }), 350);
      const cropper = image.data('cropper');
      $("#imageCropperModal").show(500);
    });

    $('.cropper-close').on('click', function(){
      $('#imageCropperModal').hide(500);
    });

    $(document).off('click', '.page-link').on('click', '.page-link', function(e){
      e.preventDefault();
      window.location.hash = this.hash;
      var windowhash = this.hash;
      var user_id = $('.job_account').attr("data-id");
      var filters = {
        user_id: user_id
      };
      if(windowhash.startsWith('#job')){
        that.getJobsByUserId(filters);
      }else if(windowhash.startsWith('#donations')){
        that.getDonationsByUserId(filters);
      }else if(windowhash.startsWith('#upcoming_events')){
        that.getEventsByUserId(filters);
      }
    });
  }
};
let AccountClass = new Account();