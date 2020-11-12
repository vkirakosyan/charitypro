
    <footer>
        <hr>

        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3">
                     <a href="/" class="d-block logo-content">
                            <p data-second="Pro" class="mb-0">Charity </p>
                            <span>Charity Project</span>
                        </a>
                </div>
                   <div class="col-12 col-sm-6 col-md-4 col-lg-5">
                        <div class="whoWeAre">
                            <h2 class="footHeaders">Who we are</h2>
                            <ul type="none">
                                <li><a class="" href="{{URL::to('about_us')}}">Մեր Մասին</a></li>
                                <li><a href="{{URL::to('video')}}">Մեր հոլովակները</a></li>
                            </ul>  
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                        <div class="whatWeDo">
                            <h2 class="footHeaders">What we do</h2>
                            <ul type="none">
                              <li><a href="{{url('upcoming_events')}}">Իրադարձություն</a></li>
                              <li><a href="{{URL::to('donations')}}">Նվիրաբերություն</a></li>
                            </ul>  
                        </div>
                    </div>
                      <div>
                        <span class="privaceTxt">© 2017 - <span class="yearChange"></span> | <a href="{{url('termsconditions')}}">Գաղտնիության քաղաքականություն</a></span>
        </div>
                </div>
            </div>
        </div>
    </footer>  
    <span class="lnr lnr-chevron-up jumpTo"></span>
    <script src="{{URL('js/user_data.js')}}"></script>
    <script type="text/javascript">
        let UserDataClass = new UserData('{!! json_encode($user_data) !!}');
        var _token = '{{csrf_token()}}';
        var user_id = {!! $userId !!};
        var base_url = '{{url('')}}';

        @if(Request::route()->uri() == '/')
            var youtube_ids = JSON.parse('{!! json_encode($youtube_links) !!}')
        @endif
    </script>
    <script async src="https://static.addtoany.com/menu/page.js"></script>
    <script type="text/javascript" src="{{URL('js/global_functions.js')}}"></script>
    
    <script src="{{URL('js/header.js')}}"></script>
    <script src="{{URL('js/offeredServices.js')}}"></script>
    <script src="{{URL('js/intlTelInput.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    @if(stripos(Request::route()->uri(), 'upcoming_events') !== false)
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAoxO7xzcP7R1W1fGxp2ysJ-DXpDH1wLhk&libraries=places" async defer></script>
    @endif
    <!-- Global site tag (gtag.js) - Google Analytics --> 
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-117514224-1"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'UA-117514224-1'); </script>
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/js/swiper.min.js"></script>
    <script src="{{URL('js/initTelInput.js')}}"></script>
    <script src="{{URL('js/utils.js')}}"></script>
    <script src="{{URL('js/masked-input.js')}}"></script>
    <script src="{{URL('js/job_new.js')}}"></script>
    <script src="{{URL('js/script.js')}}"></script>
    <script src="{{URL('js/jquery.simplePagination.js')}}"></script>
    @yield('script')
  </body>
</html>



