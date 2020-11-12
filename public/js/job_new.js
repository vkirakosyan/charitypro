function Jobs() {
  this.__init.call(this);
}

Jobs.prototype = {
  __init: function () {
    this.__initOptions();
    this.setData();
    this.__initEvents();
  },
  __initOptions: function () {
    this.img_folder = 'images/Jobs/';
  },
  setData: function () {
    var cat_id = ~~getURLParameter('cat_id');
    var city_id = ~~getURLParameter('city_id');
    var title = getURLParameter('title');
    var page = ~~getURLParameter('page');
    var set_cats = true;
    var filters = {};

    if (cat_id) {
      filters.cat_id = cat_id;
    }

    if (city_id) {
      filters.city_id = city_id;
    }

    if (title) {
      filters.title = title;
    }

    if (Object.keys(filters).length > 0) {
      var set_cats = false;
      $(".content_all").hide();

      this.getByFilter(filters, page);
    } else {
      $(".content_current").hide();
    }
    const header = $('h1').first().text();
    $('head').append(`<meta property="og:title" content=${header}>`);
    this.getCategoriesAndSities(set_cats, filters);
    this.setTitle(title);
  },
  setTitle: function (title) {
    if (title) {
      $('.job-filter.title').val(unescape(title));
    }
  },
  getCategoriesAndSities: function (set_cats, filters) {
    var that = this;
    $.get('jobs/categoriesAndSities', data => {
      that.data_categories = data.categories;
      that.setCategories(data.categories, set_cats, filters.cat_id);
      that.setCities(data.cities, filters.city_id);
      $('.job-spinner').detach();
    });
  },
  setCities: function (cities, selcted_city_id) {
    if (typeof cities != 'undefined') {
      var cities_html = '<option value="">Ընտրել Քաղաքը</option>';
      for (item of cities) {
        let selected = (selcted_city_id == item.id) ? 'selected' : '';
        cities_html += '<option value="' + item.id + '" ' + selected + '>' + item.name + '</option>';
      }
      $('.cities').html(cities_html);
      $('.cities.new-job').find('option').removeAttr('selected');
    }
  },
  setCategories: function (categories, set_cats, selcted_cat_id) {
    var that = this;
    if (typeof categories != 'undefined') {
      var categories_html = '';
      var category_names = '<option value="">Կատեգորիա</option>';
      categories.forEach((item, i) => {
        i++;
        // if(i==3){
        //   categories_html += `<div class="content3">`;
        // }else if(i==6){
        //   categories_html += `</div>`;
        // }

        if (set_cats) {
          let img_url = _url(that.img_folder + item.img);
          categories_html += `
            <div data-id="`+ item.id + `" class="job_image div_images col-12 col-sm-6 col-md-6 col-lg-4 wrapDest">
            <div class='categWrap d-block'>
              <img class="jobs-link img-fluid" src="`+ img_url + `" alt="">
           
               <span class="lnr lnr-eye"></span>
               
              </div>
 <span>`+ item.name + `</span>
            </div>`;
        }





        let selected = (selcted_cat_id == item.id) ? 'selected' : '';

        category_names += '<option value="' + item.id + '" ' + selected + '>' + item.name + '</option>';
        i++;
      })
      that.categories_html = categories_html;

      $(".categories").html(category_names);
      $('.categories.new-job').find('option').removeAttr('selected');
      if (set_cats) {
        $('.loader').detach();
        $(".content-row").html(categories_html);
      }
    }


  },
  getByFilter: function (filters, page_n) {
    var that = this;
    var ajax_data = {};
    ajax_data.filters = JSON.stringify(filters);
    if (page_n) {
      ajax_data.page = page_n;
    }
    $.ajax({
      url: 'jobs/getByFilter',
      type: "get",
      data: ajax_data,
      dataType: "json",
      success: jobs => {
        if (jobs.total == 0) {
          that.addJobs(0);
        } else {
          that.addJobs(jobs.data);
          that.addPagination(jobs.total, jobs.per_page, jobs.current_page, filters);
        }
      }
    });
  },

  addPagination: function (total, per_page, current_page, filters) {
    var url = location.pathname + location.hash
    var pagination_content = $('.pagination-content');
    pagination_content.find('#pagination').remove();
    var pagination_html = '<ul id="pagination" class="pagination-sm"></ul>';
    var url_hash = '';
    if (total > per_page) {
      pagination_content.html(pagination_html);
      var pagination_settings = {
        items: total,
        itemsOnPage: per_page,
        currentPage: current_page,
        cssStyle: 'light-theme',
        //hrefTextPrefix: 'job#cat_id=' + filters.cat_id + '&page='
        hrefTextPrefix: url + '&page='
      };

      $('#pagination').pagination(pagination_settings);
    }
  },
  addJobs: function (jobs) {
    var that = this;
    that.jobs = jobs;

    //   var job= '<a href="{{url('job')}}" class="comeBack"> <span class="lnr lnr-arrow-left"></span> Back</a>';
    var job = '<div class="data-jobs-content"><div class="d-block"><span class="back comeBack"><span class="lnr lnr-arrow-left"></span> Հետ</span></div>';
    if (jobs == 0) {
      job += '<h2 class="no_jobs">Որոնմանը համապատասխան աշխատանքներ չկան</h2>';
    } else {
      jobs.forEach((item, i) => {
        let img_url = _url(that.img_folder + item.img);
        job += `
              <div class="row backgraund_content job_row jobsCategories d-flex flex-column flex-sm-row align-items-start">
                <div class=" imgWrapJob">
                  <img src="`+ img_url + `" class="job-img img-fluid">
                </div>
                <div class="jobDescriptionWrap">

                  <h5 class="job_name" data-id="`+ i + `"data-toggle="modal" data-target="#job_details">` + item.title + `</h5>
                  <div class='description'><p class="job_description">`+ item.description + `</p>
               
                  <button data-id="`+ i + `"  class="more_job" id='jobMoreShow' type="button" name="button" data-toggle="modal" data-target="#job_details">
                     Ավելին
                  </button>
                  </div>
                </div>
              </div>`
      });
      job += "</div><div class='row'><div id='pagination' class='pagination-content pagination-sm col-12 pageinationGallery d-flex justify-content-center' ></div>";
    }
    $(".content").html(job);
  },
  __initEvents: function () {
    var that = this;

    $('.add-job').hide();
    // $(document).ready(function () {
      $("#phone").mask("(999) 999-9999");
      $('.content').append('<i class="loader job-spinner fa fa-circle-o-notch fa-spin fa-5x" aria-hidden="true"></i>');
      // $('.loader').hide()
    // });


    const telInput = $('.job-phone');


    telInput.intlTelInput({
      utilsScript: 'js/utils.js',
      preferredCountries: ['am', 'ru', 'us']
    });


    const errorMsg = $(".error-msg-job");
    const validMsg = $(".valid-msg-job");

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
          $('.add_job_search').attr('disabled', false);
        } else {
          telInput.addClass("has-error");
          errorMsg.removeClass("d-none");
          $('.add_job_search').attr('disabled', true);
        }
      }
    });


    // on keyup / change flag: reset
    telInput.on("keyup change", telReset);


    // plugin using secondth time
    const telInput2 = $('.job-phone2');
    const errorMsg2 = $(".error-msg-job2");
    const validMsg2 = $(".valid-msg-job2");
    const telReset2 = () => {
      telInput2.removeClass("has-error");
      errorMsg2.addClass("d-none");
      validMsg2.addClass("d-none");
    };

    telInput2.intlTelInput({
      utilsScript: 'js/utils.js',
      preferredCountries: ['am', 'ru', 'us']
    });
    telInput2.blur(() => {
      telReset();
      if ($.trim(telInput2.val())) {
        if (telInput2.intlTelInput("isValidNumber")) {
          validMsg2.removeClass("d-none");
          $('.add_job_search').attr('disabled', false);
        } else {
          telInput2.addClass("has-error");
          errorMsg2.removeClass("d-none");
          $('.add_job_search').attr('disabled', true);
        }
      }
    });
    telInput2.on("keyup change", telReset2);
    // plugin using secondth time 

    $(document).off('click', '.accordion').on('click', '.accordion', function () {

      if ($('.not-loggedin').length == 1) {
        $('#signInModalLong').modal('show');
      }
      else {
        $('.add-job').slideToggle(1000);
      }
    });

    $(document).off('click', '.back').on('click', '.back', function () {
      window.location.hash = '';
      $('.content').html('<div><div class="row content-row"></div></div>')
      that.getCategoriesAndSities(true, {});
      that.setTitle(getURLParameter('title'));
    });

    $(document).off('click', '.div_images').on('click', '.div_images', function () {
      var cat_id = this.getAttribute("data-id");
      $('.categories.job-filter option').removeAttr('selected');
      $('.categories.job-filter option[value="' + cat_id + '"]').attr({ selected: 'selected' });
      // $(".content_all").hide();
      window.location.hash = 'cat_id=' + cat_id;
      var filters = {
        cat_id: cat_id
      };
      that.getByFilter(filters);
    });

    $(document).off('click', '.more_job, .job_name').on('click', '.more_job, .job_name', function () {

      var job_id = this.getAttribute("data-id");
      var img_url = _url(that.img_folder + that.jobs[job_id]['img']);

      if ($('[property="og:title"]').length > 0) {
        $('[property="og:title"]').attr('content', that.jobs[job_id]['title']);
      }
      else {
        $('head').append(`<meta property="og:title" content=${that.jobs[job_id]['title']}>`);
      }

      if ($('[property="og:image"]').attr('content')) {
        $('[property="og:image"]').attr('content', that.jobs[job_id]['img']);
      }
      else {
        $('head').append(`<meta property="og:image" content=${that.jobs[job_id]['img']}>`);
      }

      $(".popup_images").html(`<img src="` + img_url + `" class='img-fluid'>`);
      $(".popup_job_name").html(that.jobs[job_id]['title']);
      $(".job_popup_city").html('<span class="lnr lnr-map-marker"></span>' + that.jobs[job_id]['city_name']);
      $(".job_popup_description").html(that.jobs[job_id]['description']);
      $(".job_popup_number").html('<span class="lnr lnr-phone-handset"></span> ' + that.jobs[job_id]['number']);
      $(".job_popup_email").html('<span class="lnr lnr-envelope"></span> ' + that.jobs[job_id]['email']);
      $(".job_popup_username").html('<span class="lnr lnr-user"></span> ' + that.jobs[job_id]['username']);
    });

    $(document).off('click', '.page-link').on('click', '.page-link', function (e) {
      e.preventDefault();
      window.location.hash = this.hash;
      var href = $(this).attr('href');
      var page_n = ~~getURLParameter('page');
      var cat_id = ~~getURLParameter('cat_id');
      var city_id = ~~getURLParameter('city_id');
      var title = getURLParameter('title');
      var filters = {};
      if (cat_id) {
        filters.cat_id = cat_id;
      }
      if (city_id) {
        filters.city_id = city_id;
      }
      if (title && title.length > 0) {
        filters.title = title;
      }

      that.getByFilter(filters, page_n);
    });

    $(document).off('click', '.btn-job-search').on('click', '.btn-job-search', function () {
      var cat_id = ~~$('.job-filter.categories').val();
      var city_id = ~~$('.job-filter.cities').val();
      var title = $('.job-filter.title').val();
      var url_hash = '';
      var filters = {};
      if (!cat_id && !city_id && title.length == 0) {
        return;
      } else {
        if (cat_id) {
          filters.cat_id = cat_id;
          url_hash += 'cat_id=' + cat_id;
        }
        if (city_id) {
          filters.city_id = city_id;
          url_hash += url_hash.length > 0 ? '&' : '';
          url_hash += 'city_id=' + city_id;
        }
        if (title.length > 0) {
          filters.title = title;
          url_hash += url_hash.length > 0 ? '&' : '';
          url_hash += 'title=' + escape(title);
        }
      }
      window.location.hash = url_hash;
      that.getByFilter(filters);
    });

    $(document).off('.btn-create-job').on('click', '.btn-create-job', function () {
      var create_data = new FormData();
      var new_title = $('.new-job.job-title');
      var new_description = $('.new-job.job-description');
      var new_email = $('.new-job.job-email');
      var new_cat_id = $('.new-job.categories');
      var new_city_id = $('.new-job.cities');
      var new_img = $('.new-job.job-img');
      const telInput = $('.job-phone2');
      const tel = telInput.intlTelInput("getNumber");
      create_data.append('_token', $('[name="_token"]').val());
      create_data.append('title', new_title.val());
      create_data.append('description', new_description.val());
      create_data.append('number', tel);
      create_data.append('email', new_email.val());
      create_data.append('cat_id', new_cat_id.val());
      create_data.append('city_id', new_city_id.val());
      create_data.append('img', new_img[0].files[0]);

      $.ajax({
        type: 'POST',
        url: 'jobs/create',
        contentType: false,
        processData: false,
        data: create_data
      }).done(data => {
        if (typeof data.errors != 'undefined') {
          $('.help-block').html('');
          $('.form-group').removeClass('has-error');
          for (key in data.errors) {
            $('[name="' + key + '"]').after('<p class="help-block">' + data.errors[key] + '</p>')
            $('[name="' + key + '"]').parents('.form-group').addClass('has-error');
          }

        } else {
          new_title.val('');
          new_description.val('');
          telInput.val('');
          new_email.val('');
          new_img.val('');
          new_cat_id.find('option').removeAttr('selected').prop('selected', false);
          new_city_id.find('option').removeAttr('selected').prop('selected', false);
          that.setData();
          $(document).scrollTop(0);
          $('.content').prepend(`<div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <strong>✓</strong> Աշխատանքը հաջողությամբ ավելացվել է։
                  </div>`);
        }
      });
    });

    $(window).on('popstate', () => {
      if (window.location.hash.length == 0) {
        that.getCategoriesAndSities(true, {});
        that.setTitle(getURLParameter('title'));
      }
      else {
        that.setData();
      }
    });

  }
};

    $(document).ready(function(){
      let JobsNewClass = new Jobs();
    })
