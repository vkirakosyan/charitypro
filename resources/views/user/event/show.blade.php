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
                            <li class="activeThumb">
                                <a href="{{url('events')}}">
                                    <img class="img-fluid" src="{{URL('img/profileIcon1.png')}}">
                                </a>
                            </li>
                            <li>
                                <a href="{{url('donation/donation')}}">
                                    <img class="img-fluid" src="{{URL('img/profileIcon2.png')}}">
                                </a>
                            </li>
                            <li>
                                 <a href="{{url('job/myjob')}}">
                                    <img class="img-fluid" src="{{URL('img/profileIcon3.png')}}">
                                </a>
                            </li>
                        </ul>  
                    </div>
                    <div class="rightAside">
                        <div class="profile-content-1">
                            <h4 class="eventsTitle">{{ $events['title'] }}</h4>
                            <p><span class="lnr lnr-map-marker"></span> {{$city[0]['name']}}</p>
                            <div class="description-profile-more">
                                <img src='{{URL("images/Events/$events[img]")}}'>
                                <div class="desc_more_events">
                                    <p>{{ $events['description']}}</p>
                                    <span class="calendDate"><span class="lnr lnr-calendar-full"></span> {{substr( $events['time'] , 0,10)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span class="lnr lnr-chevron-up jumpTo"></span>
</main>
@include('layouts/footer')