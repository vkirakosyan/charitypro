@include('layouts/header')
    <main id="aboutWrap">
        <div class="container">
            <div class="row">
                <div class="col-12 ">
                    <div class="wayPath">
                        <p class="mb-0 ">
                            <a href="{{url('')}}">Գլխավոր</a>
                            <span>Ֆոտոշարք</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row galleryContent pt-4">
                <div class="loader-div">
                    <i class="loader fa fa-circle-o-notch fa-spin fa-5x img_loader" aria-hidden="true"></i>
                </div>
                <div class="col-12">
                    <div class="swiper-container gallerySwiper">
                        <div class="swiper-wrapper">
                  @foreach ($images as $image) 
                  <?php
                    $img=json_decode($image->images)[0];
                  ?>
                          <div class="swiper-slide">
                                <a href='{{URL("images_more/$image->id")}}'>
                                    <img src='{{URL("images/Images/$img")}}' class="img-fluid gallery_images">
                                    <span>{{$image->title}}</span>
                                </a>
                            </div>
                    @endforeach
                        </div>
                        <div class="swiper-button-next next_large swiper-button-white"></div>
                        <div class="swiper-button-prev prev_large swiper-button-white"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                ?>
            @foreach($items as $item)
                <div class="commonStoriesBlock story-in-images">
                    <?php
                    $img= json_decode($item->images)[0];
                    ?>
                    <img src='{{URL("images/Stories/$img")}}' class="img-fluid" alt="" width="100%">
                    <div class="dateSee">
                        <p>
                            <img src="{{URL('images/Users', $item->user_img)}}" class="user-image ">
                             {{$item->user_name}}
                        </p>
                        <p>
                             @php
                                $date  = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at);
                                $month = \App\Enum\DateHelper::get($date->format('n'))->monthName()->short;
                            @endphp
                                <span class="lnr lnr-calendar-full"></span>
                                {{$date->format('d')}} {{$month}} {{$date->format('Y')}}  
                        </p>
                        <p>
                            <span class="lnr lnr-bubble"></span>
                            <a href="javascript:void(0)" class="storiesComment" data-comment="showComment1">{{$item->comments_counts}} Մեկնաբանություն</a>
                        </p>

                    </div>
                    <div class="data-animate">
                        <h4 class="storiesItemName"><a href="{{URL('stories/details', $item->id)}}">   {{$item->title}}</a></h4>
                        <p class="descriptionStories">
                            {{ str_limit($item->description, $limit = 150, $end = '...') }}
                        </p>
                    </div>

                </div>
            @endforeach
        </div>
        </div>
    </main>
@include('layouts/footer')
    <script>
            var largeSlider = new Swiper('.gallerySwiper', {
                paginationClickable: true,
                nextButton: '.next_large',
                prevButton: '.prev_large',
                spaceBetween: 15,
                autoHeight: true,
                slidesPerView: 4,
                breakpoints: {
                    991: {
                        slidesPerView: 3
                    },
                    767: {
                        slidesPerView: 2
                    },
                    576: {
                        slidesPerView: 1
                    }
                },
                'onInit': function(){
                    setTimeout(()=>{
                        $(".loader-div").hide();
                    }, 200)
                }


            });
        </script>