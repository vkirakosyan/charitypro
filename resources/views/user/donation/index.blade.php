@include('layouts/header')
<main>
   <div class="container pt-4">
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
                            <li>
                                <!-- <a href="javascript:void(0)"> -->
                                 <a href="{{url('job/myjob')}}">
                                    <img class="img-fluid" src="{{URL('img/profileIcon3.png')}}">
                                </a>
                            </li>
                        </ul>  
                    </div>
                    <div class="rightAside donateContainer">
                        <h4>Նվիրաբերություն</h4>
                        <div class="profile-content-1">
                     
                            <div class="description-profile">
                                <ul type="none">
                                @foreach($donation as $donation)
                                    <li class="badgeDonate">
                                        <span class="donateName">
                                            {{$donation['name']}}
                                        </span>
                                        <div class="parentCol">
                                            <?php
											$img=json_decode($donation['images'])[0];
                                            ?>
                                            <img src='{{url("images/Donations/$img")}}'>
                                            <div class="donateDesc">
                                                <span class="donatePhone">
                                                    <span class="lnr lnr-phone-handset"></span>
                                                    {{$donation['phone']}}
                                                </span>
                                                <p>{{ strlen($donation['description']) > 150 ? substr($donation['description'],0, 150) . '...' : $donation['description'] }}</p>
                                            </div>
                                            <span class="threeBtns">
                                                <a href='{{url("donation/delete/$donation[id]")}}'><span class="lnr lnr-trash"></span></a>
                                                <a href='{{url("donation/edit/$donation[id]")}}'><span class="lnr lnr-sync"></span></a>
                                                <a href='{{url("donation/show/$donation[id]")}}'><span class="lnr lnr-eye"></span></a>
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
    </main>
@include('layouts/footer')
