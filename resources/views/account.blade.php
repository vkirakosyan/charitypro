@include('layouts/header')
<main>
    <div class="container pt-4">
        <div class="row">
            <div class="col">
                <div class="user-content">
                    <div class="leftAside">
                        <ul type="none">
                            <li class="activeThumb">
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
                            <li>
                                 <a href="{{url('job/myjob')}}">
                                    <img class="img-fluid" src="{{URL('img/profileIcon3.png')}}">
                                </a>
                            </li>
                        </ul>  
                    </div>
                    <div class="rightAside userAccount">
                        <h4>Կարգավորումներ</h4>
                        <div class="profile-content-1">
                            <div class="avatar-section">
                                <img src="{{URL('images/Users', $user_data->img)}}" alt="" class="img-fluid">
                                
                            </div>
                            <div class="description-profile">
                                <ul type="none" class="account_UL">
                                    <li>
                                        <b>Անուն</b>
                                        <span>{{$user_data->name}}</span>
                                    </li>
                                    <li>
                                        <b>Էլ֊հասցե</b>
                                        <span>{{$user_data->email}}</span>
                                    </li>
                                </ul>
                                   <a href="{{url('account/edit')}}" class="btn btn-success">Update</a>
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