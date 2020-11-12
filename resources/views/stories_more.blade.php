@section('pageTitle', $pageTitle)
@include('layouts/header')
<main>
    <div class="container">
        <div class="row">
            <div class="col-12 d-block">
                <a href="{{url('stories')}}" class="comeBack"> <span class="lnr lnr-arrow-left"></span> Հետ</a>
            </div>
            <div class="col-12">
                <div class="bgWhiteCharity">    
                    <div class="row">
                        <div class="col-12 col-md-5 mb-4">
                            <div class="swiper-container charitySlider">
                                <div class="swiper-wrapper">

                                    @foreach(json_decode($item->images) as $image)
                                    <div class="swiper-slide">
                                        <div class="large_slide_item imgBox">
                                            <img class="img-fluid" data-origin="{{URL('images/Stories', $image)}}" src="{{URL('images/Stories', $image)}}">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <!-- Add Arrows -->
                                <div class="swiper-button-next next_large swiper-button-white"></div>
                                <div class="swiper-button-prev prev_large swiper-button-white"></div>
                            </div>
                            <div class="swiper-container charitySliderThumb">
                                <div class="swiper-wrapper">
                                @foreach(json_decode($item->images) as $image)
                                    <div class="swiper-slide">
                                        <div class="thumb_slide_item">
                                            <img class="swipe-slide-to img-fluid" src="{{URL('images/Stories', $image)}}" data-slide-to="{{$loop->index}}">
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-7 position-relative mb-4">
                            <div class="charityDescripition pb-5">
                                <h5>{{$item->title}}</h5>
                                <span class="d-block mt-3 mb-3">
                                    <img src=" {{ asset('img/logo.png') }}" alt="" class=" mr-2" style="max-width:25px">

                                    Charity Pro
                                </span>
                                <p>
                                    {{$item->description}}
                                    <ul type="none" class="telHelp">
                                </p>
                                <div class="datePublication text-muted d-flex flex-column-reverse flex-lg-row  justify-content-between" >
                                    <a href="javascript:void(0)" onclick="showComment()"> Մեկնաբանություններ <span class="lnr lnr-arrow-down"></span></a>
                                    <p>
                                        <span class="lnr lnr-calendar-full"></span>
                                         @php
                                            $date     = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at);
                                            $date =  (new \Carbon\Carbon($date))->format('Y-m-d H:i');
                                            $month    = \App\Enum\DateHelper::get((new \Carbon\Carbon($date))->format('n'))->monthName()->short;
                                            $weekName = \App\Enum\DateHelper::get((new \Carbon\Carbon($date))->format('w'))->weekDay()->short;
                                          @endphp
                                        {{$weekName}} ,  {{$month}} {{$date}}
                                    </p>  
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-10 offset-md-1  border-top commentNone">
                            <h4 class="pt-3">Մեկնաբանություններ</h4>
                            <div class="commentsCharity">
                                
                                @foreach($comments as $comment)
                                <div class="userBlock d-flex align-items-start">
                                    <img src="{{URL('images/Users', $comment->avatar)}}" style="max-width:35px;" alt="">
                                    <div class="commentUser">
                                        <h6>{{$comment->user_name}}</h6>
                                        <p>{{$comment->description}}</p>
                                    </div>
                                    </div>
                                @endforeach
                                
                                
                            </div>
                            <form action="{{URL('stories/addComment')}}" id="addCommentCharity" method="post">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden"  value="{{$item->id}}" name='story_id'>
                                <textarea name="message" class="form-control mt-4" rows="4" placeholder="Ավելացնել մեկնաբանություն..."></textarea>
                                @if($userId)
                                <input type="submit" class="addCharityComment" value="Ավելացնել">
                                @else
                                <input type="button" class="addCharityComment" data-toggle="modal" data-target="#signInModalLong" value="Ավելացնել">
                                @endif
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('layouts/footer')
    <script src="{{URL('js/stories-more.js')}}"></script>
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