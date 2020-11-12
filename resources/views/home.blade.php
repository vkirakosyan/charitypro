@include('layouts/header')
<?php
    use Carbon\Carbon;
?>
<main>
    <section id="oblation">
        <div class="container">
            <div class="left-oblation">
                <div class="data-animate">
                    <h1><a href="{{URL::to('donations')}}">Նվիրաբերություն</a></h1>
                    <div class="textOblation dottedText">
                        @if(isset($donations))
                    {{$donations->description}}
                    @endif
                    </div>
                </div>
                <a href="{{URL::to('donations')}}">
                    <span>Տեսնել ավելին</span>
                    <span class="lnr lnr-arrow-right"></span>
                </a>
            </div>
        </div>
       
    </section>
    <section class="mainTop">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="joinUs">
                        <h2 class="mb-4">Միացիր Մեզ</h2>
                        <div class="textJoin dottedText">
                                Բարեգործությունը միջոց է լուծելու կամ մեղմացնելու այս կամ այն խնդիրը: Խնդրի առկայության մասին հնարավոր է տեղեկանալ այլոց միջոցով, ովքեր կօգնեն բարձրաձայնելու այն:Ուստի, որոշեցինք ստեղծել այս հարթակը. կստեղծվի խումբ, կլինեն անհատներ, ովքեր կցանկանան աջակցել այդ հարցում: Այստեղ հոդվածներ կտեղադրվեն, բաց քննարկումներ կանցկացվեն, որոնց միջոցով կտեղեկանաք կարիքավորների խնդիրների մասին և հնարավորինս նպատակաուղղված և դիպուկ օգնություն կհասցնեք Ձեր աջակցությանն ակնկալողներին: Օգնելն ավելի հեշտ կլինի, եթե իմանաք թե ինչպես: Ցանկության դեպքում մարդ կարող է պաշտպան կանգնել բարուն կամ ստեղծել այն: Այս ամենի կիզակետում լինելու համար անհրաժեշտ է միանալ մեզ և համալրել մեր շարքերը:
                        </div>
                        @if(!$userId)
                   <a href="#" class="joinUsButton not-loggedin" data-toggle="modal" data-target="#signInModalLong">Միացիր <span class="lnr lnr-arrow-right"></span></a>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="events">
                        <div  class="data-animate">
                            <h2>Մեր հոլովակները</h2>
                            <div class="textEvents">
                            @if(count($youtube_links)>0)
                                <iframe width="100%" height="300" src="https://www.youtube.com/embed/{{$youtube_links[0]}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                             @else
                             
                             @endif
                            </div>
                        </div>
                        <a href="{{URL::to('video')}}" class="eventsButton">Դիտել հոլովակները<span class="lnr lnr-arrow-right"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="successedStory pt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-7">
                    <div class="success">
                        <h2>Հաջողված Պատմություն</h2>
                        <hr>
                        @if(isset($success_story))
                        <div class="successedText">
                            
                          {{ str_limit($success_story->description, $limit = 420, $end = '...') }}
                         
                        </div>
                        <a href='{{URL::to("stories/details/$success_story->id")}}' class="successedButton">Ավելին <span class="lnr lnr-arrow-right"></span></a>
                         @endif
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <div class="rightSection">
                        <h4>Առաջարկվող ծառայություններ</h4>
                        <hr>
                        <div class="offeredText">
                                    @if(isset($offered_services))
                                {{$offered_services->description}}
                                @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="eventsDate">
        <div class="container">
            <div class="row">
                <div class="col-12  col-md-7 col-lg-5 offset-lg-7 offset-md-5">
                    <div class="eventsCol">
                        <h3>Իրադարձություններ {{Carbon::today()->format('Y')}}</h3>
                        <ul type="none"  class="data-animate">
                        @foreach($last_event as $event)
                            <li>
                                <a href='{{url("upcoming_events/details/$event->id")}}'>
                                   @php
                                $date  = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->time);
                                $month = \App\Enum\DateHelper::get($date->format('n'))->monthName()->short;

                            @endphp
                              
                                    <span>{{$date->format('d')}}<br> {{$month}}</span>
                                    <p>{{$event->title}}</p>   
                                </a>
                            </li>

                        @endforeach 

                        </ul>
                        <a href="{{url('upcoming_events')}}"
 class="reportButton">Տեսնել ավելին<span class="lnr lnr-arrow-right"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="wordofGreatePeople">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                
                    <div class="wordPeople">
                        <div class="storiesHeader">
                            <h3 class="text-uppercase">Կարծիքներ մեր մասին</h3>
                        </div>  
                        <div class="swiper-container home_slide">
                            <div class="swiper-wrapper">
                               
                                @foreach($what_they_say as $about)
                                 <div class="swiper-slide">
                                    <div class="wordsText">
                                        {{$about->description}}
                                     </div>
                                    <div class="namePeople">
                                        <b class="d-block">{{$about->name}}</b>
                                        <b>({{$about->profession}})</b>   
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="buuts_swiper">
                                <div class="swiper-button-prev">
                                    <span class="lnr lnr-arrow-left"></span>
                                </div>
                                <div class="swiper-button-next">
                                    <span class="lnr lnr-arrow-right"></span>
                                </div>
                            </div>
            
                        </div>                
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="newsLetter">
                        <h2>Դարձիր կամավոր</h2>
                        <p>Բաժանորդագրվեք մեր վերջին նորությունները և թարմացումները ստանալու համար</p>
                             @if ($errors->has('contact_email')) 
                        <p class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                    <!-- <p class="valid-message"> --> {{ $error }} <!-- </p> -->
                             @endforeach
                                   </p>
                                   @elseif(Session::has('messages'))
           
                                   <p class="alert alert-success">
                                   <p class="valid-message">
                                   {{ Session::get('messages') }}
                                   </p>
                                   </p>
                                   @endif
                        <form method="POST" action="{{url('send')}}" >
                           <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <input type="email" name="contact_email" class="subscribeInput" placeholder="Լրացրեք Ձեր էլ․ հասցեն">
                            <textarea placeholder="Լրացրեք հաղորդագրություն" name='message' id="message" rows='4' class="subscribeInput"></textarea>
                            <button type="submit" class="suscribeButton subscrbtn">Բաժանորդագրվել<span class="lnr lnr-arrow-right"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@include('layouts/footer')
<script>
        var swiper_badge_sub = new Swiper('.home_slide', {
            slidesPerView: 1,
            speed: 500,
            // effect:'fade',
            spaceBetween: 0,
    //        simulateTouch:false,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            paginationClickable: true,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev'
        });

    </script>