@include('layouts/header')
<main><div class="container pt-4">
        <div class="row">
            <div class="col">
                <div class="user-content">
                    <div class="leftAside">
                        <ul type="none">
                            <li>
                                <a href="{{url('account')}}">
                                    <img class="img-fluid" src="{{URL('img/profileIcon5.png')}}">
                                </a>
                            </li>
                            <li>
                                <a href="{{url('events')}}">
                                    <img class="img-fluid" src="{{URL('img/profileIcon1.png')}}">
                                </a>
                            </li>
                            <li class="activeThumb">
                                <a href="{{url('donation/donation')}}">
                                    <img class="img-fluid" src="{{URL('img/profileIcon2.png')}}">
                                </a>
                            </li>
                            <li  >
                                 <a href="{{url('job/myjob')}}" >
                                    <img class="img-fluid" src="{{URL('img/profileIcon3.png')}}">
                                </a>
                            </li>
                        </ul>  
                    </div>
                    <div class="rightAside donateMore">
                        <h4>{{ $donations->name }}</h4>
                        <div class="donateWrap">
                            <div class=" swiperSliderDoante">
                                <div class="swiper-container big_donate_slide">
                                    <div class="swiper-wrapper ">
                                    <?php
                                        $img=json_decode($donations->images);
                                    ?>
                                    @for($i=0 ; $i<count($img); $i++)
                                        <div class="swiper-slide">
                                            <img src='{{url("images/Donations/$img[$i]")}}'>
                                        </div>    
                                    @endfor 
                                    
                                </div>
                                @if(count($img) != 1)
                                    <!-- Add Arrows -->
                                    <div class="swiper-button-next next_large swiper-button-white"></div>
                                    <div class="swiper-button-prev prev_large swiper-button-white"></div>
                                @endif
                                </div>
                                @if(count($img) != 1) 
                                <div class="swiper-container small_donate_slide">
                                    <div class="swiper-wrapper " >
                                        @for($i=0 ; $i<count($img); $i++)
                                            <div class="swiper-slide">
                                                <img data-slide-to='{{$i}}' class="swipe-slide-to" src='{{url("images/Donations/$img[$i]")}}'>
                                            </div>    
                                        @endfor 
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="decDonateWrap">
                                <p>{{ $donations->description }}</p>
                                <a href="tel:{{ $donations->phone }}"> <span class="lnr lnr-phone"></span>{{ $donations->phone }}</a>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
    <span class="lnr lnr-chevron-up jumpTo"></span>
    </main>
@include('layouts/footer')
<script>
    var largeSlider = new Swiper('.big_donate_slide', {
            paginationClickable: true,
            slidesPerView: 1,
            nextButton: '.next_large',
            prevButton: '.prev_large',
            spaceBetween: 15,
            autoHeight: true,
            

        });
        var thumbSlider = new Swiper('.small_donate_slide', {
            paginationClickable: true,
            spaceBetween: 10,
            slidesPerView: 3,
            autoHeight: true,
            breakpoints: {
                991: {
                    slidesPerView: 2
                },
                767: {
                    slidesPerView: 3
                }
            }
        });
        document.querySelectorAll('.swipe-slide-to').forEach(element => {
            element
            .addEventListener('click', (e)=> {
                var index = e.target.getAttribute('data-slide-to');
                largeSlider.slideTo(index, 500);
            });
        })
</script>