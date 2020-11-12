@include('layouts/header')
 <main>
      <div class="container">
          <div class="row">
              <div class="col-12">
                  <div class="wayPath">
                      <p class="mb-0 ">
                          <a href="{{url('')}}">Գլխավոր</a>
                          <span>Աշխատանք</span>
                      </p>
                  </div> 
              </div>
          </div>

     <div class="row" style="display: flex;">
         <div class="backgraund_content filter col-12 col-md-4 col-lg-3 ">
                 <div class="workSection">
                     <h5 class="filter_title">Փնտրել Աշխատանք</h5>
                   
                     <div class="form-group workForm">
                         <div class="col-md-12">
                             <input type="text" class="filter_input title job-filter" placeholder="Աշխատանք">
                         </div>
                    
                  
                         <div class="col-md-12">
                             <select class="filter_input categories job-filter"></select>
                         
                     </div>
                     
                         <div class="col-md-12">
                             <select class="filter_input cities job-filter"></select>
                         
                     </div>
                     <button class="filter_search btn-job-search searchWork">
                         Որոնել
                     </button>
                   </div>
                 
                 @if($userId)    
                 <div class="col-12 col-sm-6 col-md-6 col-lg-12">
                 @else
                 <div class="col-12 col-sm-6 col-md-6 col-lg-12 not-loggedin" data-toggle="modal" data-target="#signInModalLong">
                
                 @endif
                </div>
                     <button class="add_job_title accordion addWorkButton">Ավելացնել աշխատանք</button>
                    
                     <div class="add-job addWorkWrap">

                      <div class="form-group addWorkForm">
                             <input type="text" class="filter_input new-job job-title" placeholder="Աշխատանք" name="title">
                         
                         
                             <textarea rows="4" name="description" id="description" class="filter_input add_job_textarea new-job job-description" placeholder="Նկարագրություն" required ></textarea>
                         <div class="telBlock d-flex align-items-center ">
                                 <input name="phone" type="tel" class="filter_input new-don input-phone job-phone2 telBlock d-flex align-items-center">
                                </div>
                                  <span class="valid-msg-job2 d-none">✓ Հեռախոսահամարը ճիշտ է լրացված</span>
                                <span class="error-msg-job2 d-none">✗ Հեռախոսահամարը սխալ է լրացված</span>
                                 <span id="valid-msg-job" class="d-none">✓ Հեռախոսահամարը ճիշտ է լրացված</span>
                                 <span id="error-msg-job" class="d-none">✗ Հեռախոսահամարը սխալ է լրացված</span>
                             
                         
                     
                             <input type="email" name="email" id="email" class="filter_input new-job job-email" placeholder="Էլ֊հասցե" required />
                         
                        
                             <select name="cat_id" class="filter_input categories new-job"></select>
                        
                        
                             <select name="city_id" class="filter_input cities new-job"></select>
                              <label class="file-label">
                                  <input class="file-input gallery-photo-add new-job job-img filter_input" name="img"  type="file" id="file-upload">
                                  <span class="file-cta">
                                      <span class="file-icon">
                                          <span class="lnr lnr-upload"></span>
                                      </span>
                                      <span class="file-label">
                                      Ներբեռնել նկար...
                                      </span>
                                  </span>
                              </label>
                         {{ csrf_field() }}
                         <button class="add_job_search btn-create-job addWork">
                             Ավելացնել
                         </button>
                     </div>
                 </div>
             </div>
         </div> 
         <div class="col-12 col-md-8 col-lg-9 job_categories content">    
                 <div class="row content-row worksCategories"> 
             </div>
         </div> 
         <div class="modal fade modalJobView" id="job_details" role="dialog">
             <div class="modal-dialog modal-lg job-modal">
                 <div class="modal-content">
                
                     <div class="modal-body job_details">
                      
                         <div class="row first ">
                             <div class="col-lg-5 col-md-12 popup_images "></div>
                             <div class="col-lg-7 col-md-12">
                                 <h5 class="popup_job_name"></h5>
                                   <p class='job_popup_city'>
                            <span class="lnr lnr-map-marker "></span>
                           
                                   </p>
                                
                                 <p class="job_popup_description"></p>
                             </div>
                         </div>
                         <hr>
                         <!-- <div class="row"> -->
                          <div class="contactInfo">
                          <h5>Կոնտակտային տվյալներ </h5>
                           <ul type="none">
                               <li>  <a class="job_popup_number"></a> </li>
                                <li> <a class="job_popup_email"></a></li>
                                <li> <a class="job_popup_username"></a></li>
                               
                            </ul>
                         <button type="button" class="closeModal" data-dismiss="modal">Փակել</button>
                         </div>
                     </div> 
                 </div>
        </div>
      </div>
    </div>
  </div>
<div class="modal fade" id="not-loggedin" role="dialog">
              <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5></h5>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
 
                      <div class="modal-body">
                          <div class="row">
                              <div class="col-12">
                                  <p class="">Ավելին տեսնելու համար մուտք գործեք համակարգ։</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>  
          </div>
     </div>
  </main>
   <script type="text/javascript">
@if(isset($pagination))
    JobsClass.initPagination('{!! json_encode($pagination) !!}');
@endif
</script>
@include('layouts/footer')
