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
                    <div class="rightAside eventsCotainer">
                        <h4>Իրադարձություններ</h4>
                        <div class="profile-content-1">
                     
                            <div class="description-profile">
                                <ul type="none" class="events_UL">
                               @foreach($events as $event)
                                    <li class="cardEvents">
                                        <img src='{{URL("images/Events/$event[img]")}}'>
                                        <div class="descEvent">
                                            <h5>{{$event['title']}}</h5>
                                            <p class="eventsDescTXT">{{$event['description']}}</p>
                                            
                                            <span class="tree_buttons">
                                                <span class="eventDate d-block"><span class="lnr lnr-calendar-full"></span> {{substr( $event['time'] , 0,10)}}</span>
                                                <span>
                                                    <a href='{{url("events/delete/$event[id]")}}' class="btns btn_danger">Ջնջել</a>
                                                    <a href='{{url("events/edit/$event[id]")}}' class="btns btn_warning">Թարմանցնել</a>
                                                    <a href='{{url("events/show/$event[id]")}}' class="btns btn_success">Տեսնել</a>
                                                </span>    
                                            </span>
                                        </div>
									</li>
								@endforeach
                                </ul>
                            </div>
                        </div>
                        @if($count>1)
                            <div class="col-12 pageinationGallery d-flex justify-content-center">
                                <a @if(isset($_GET['page']) && $_GET['page'] != 1) href="?page={{$_GET['page']-1}}" @else class='disabledPage' @endif ><span class="lnr lnr-arrow-left"></span></a>
                            @for($i=0;$i < $count;$i++)
                                @if(isset($_GET['page']))
                                    @if(($i+1 == $_GET['page']))
                                        <a class='activePage' href="?page={{$i+1}}">{{$i+1}}</a>
                                        @else
                                            <a href="?page={{$i+1}}">{{$i+1}}</a>
                                        @endif

                                        @else
                                                @if(($i == 0))
                                                    <a class='activePage' href="?page={{$i+1}}">{{$i+1}}</a>
                                                @else
                                                    <a href="?page={{$i+1}}">{{$i+1}}</a>
                                        @endif


                                @endif
                                @endfor
                                <a @if(isset($_GET['page']) && ($_GET['page'] != $count)) href="?page={{$_GET['page']+1}}"
                                @elseif(!isset($_GET['page']))
                                href="?page=2"

                                @else class='disabledPage' @endif ><span class="lnr lnr-arrow-right"></span></a>

                            </div>
                            @endif
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
    <span class="lnr lnr-chevron-up jumpTo"></span>
    
@include('layouts/footer')