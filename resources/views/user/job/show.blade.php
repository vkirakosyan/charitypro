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
                            <li>
                                <a href="{{url('donation/donation')}}">
                                    <img class="img-fluid" src="{{URL('img/profileIcon2.png')}}">
                                </a>
                            </li>
                            <li  class="activeThumb">
                                 <a href="{{url('job/myjob')}}" >
                                    <img class="img-fluid" src="{{URL('img/profileIcon3.png')}}">
                                </a>
                            </li>
                        </ul>  
                    </div>
                    <div class="rightAside jobContainerMore">
                        <h4>{{ $jobs->title }}</h4>
                        <div class="jobMore">
                            <img src='{{URL("images/Jobs/$jobs->img")}}'>
                            <div class="descMoreJob">
                                <p>
                                    <span class="d-block">
                                        <span class="lnr lnr-envelope"></span> <a href="mailto:{{ $jobs->email }}">{{ $jobs->email }}</a>
                                    </span>
                                    <span class="d-block">
                                        <span class="lnr lnr-phone-handset"></span> <a href="tel:{{ $jobs->number }}">{{ $jobs->number }}</a>
                                    </span>
                                </p>
                                <p>{{ $jobs->description}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <span class="lnr lnr-chevron-up jumpTo"></span>
</main>
@include('layouts/footer')