@section('pageTitle', $pageTitle)
@include('layouts/header')
    @if(isset($donateId))
<main class="{{$donateId}}">
    @else 
    <main>
    @endif
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="wayPath gallMore">
                        <p class="mb-0 ">
                            <a href="/">Գլխավոր</a>
                            <span>Նվիրաբերություն</span>
                        </p>
                    </div> 
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="workSection">
                        <h5>Կատեգորիաներ</h5>
                        <div class="categDonate">
                            @foreach($categories as $category)
                            <a  href="{{URL::to('donations/category', $category->id)}}">{{$category->name}}</a>
                            @endforeach
                        </div>
                        @if($userId)
                            <button class="addWorkButton">Նվիրաբերիր</button>
                        @else
                             <button  data-toggle="modal" data-target="#signInModalLong" class="addWorkButton">Նվիրաբերիր</button>
                        @endif
                        <div class="addWorkWrap">
                            <form action="{{URL('donations/create')}}" class="addWorkForm" method="post"  enctype="multipart/form-data">
                             {{ csrf_field() }}
                                <input type="text" placeholder="Նվիրաբերություն" name="name" >
                                <textarea rows="4" placeholder="Նկարագրություն" name="description"></textarea>
                                <select name="cat_id" class="filterCitys">
                                    @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <div class="telBlock d-flex align-items-center form-control">
                                    <input type="tel" name="phone" class="filter_input new-don input-phone job-phone">
                                
                                </div>
                                <span  class="valid-msg-job d-none">✓ Հեռախոսահամարը ճիշտ է լրացված</span>
                                <span  class="error-msg-job d-none">✗ Հեռախոսահամարը սխալ է լրացված</span>
                                <label class="file-label">
                                    <input class="file-input" type="file" accept="image/x-png,image/gif,image/jpeg" name="images[]" multiple>
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <span class="lnr lnr-upload"></span>
                                        </span>
                                        <span class="file-label">
                                        choses Image...
                                        </span>
                                    </span>
                                </label>
                                <input type="submit" class="addWork" value="Ավելացնել">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="d-block">
                        <a href="/" class="comeBack"> <span class="lnr lnr-arrow-left"></span> Հետ</a>
                    </div>
                    <div class="row">
                    @foreach($donations as $donation)
                        <div class="col-12 col-md-6 col-lg-4 donateBadge donations-post" data-donation-id="{{$donation->id}}">
                            <a href="javascript:void(0)" class="d-block moreDonate" images="{{$donation->images}}" >
                                <div class="donateBadgeImage" style="background-image: url({{URL::to('images/Donations', json_decode($donation->images)[0])}});"></div>
                                <div class="desc_href">
                                    <h4>{{$donation->name}}</h4>
                                    <p>{{$donation->description}}</p>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div> 
        </div>
    </main>
    <div class="modalJobView">
            <div class="commonInfoDonate">
                <div class="imgBlockModalDonate d-flex flex-column flex-md-row align-items-start">
                    <div class=" swiperSliderDoante">
                        <div class="swiper-container charitySlider">
                            <div class="swiper-wrapper big-slide">
                           
                           </div>
                    
                            <!-- Add Arrows -->
                            <div class="swiper-button-next next_large swiper-button-white"></div>
                            <div class="swiper-button-prev prev_large swiper-button-white"></div>
                        </div> 
                    <div class="swiper-container charitySliderThumb">
                        <div class="swiper-wrapper small-slide" >
                        </div>
                    </div>
                    </div>
                    <div class="descriptionModalDonate">
                        <h5 class="modal-title"></h5>
                        <p class="modal-desc">
                             
                        </p>   
                    </div>
                </div>
                <div class="contactInfoDonate">
                    <h5>Կոնտակտային տվյալներ</h5>
                    <ul type="none">
                        <li>
                            <span class="lnr lnr-phone-handset"></span>
                            <p class="mb-0">
                                <a href="tel:37477291892" class="phone donation_popup_number"></a>
                            </p>   
                        </li>
                        <li>
                            <span class="lnr lnr-envelope"></span>
                            <a href="emailto:info@tanger.am" class="donation_popup_email"></a>
                        </li>  
                        <li>
                            <span class="lnr lnr-user"> <a  class="donation_popup_user"></a></span>
                        </li>
                    </ul>
                    <button class="closeModalDonate">Փակել</button>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div id="pagination" class="pagination-sm col-12 pageinationGallery d-flex justify-content-center"></div>
            </div>
        </div>

@include('layouts/footer')
    <script src="{{URL('js/donations.js')}}"></script>
  <script src="{{URL('js/donate.js')}}"></script>
<script type="text/javascript"> 


 @if(isset($pagination))
     DonationsClass.initPagination('{!! json_encode($pagination) !!}');
 @endif
    DonationsClass.initData({!! json_encode($donations, JSON_PRETTY_PRINT) !!});
 </script>
<script type="text/javascript">
        function image_zoom(){
            $('.imgBox').imgZoom({
                boxWidth: 400,
                boxHeight: 400,
                marginLeft: 5,
                origin: 'data-origin'
            });
        }
      if(window.innerWidth >= 991){
            image_zoom()
       }
      
    </script>