<!DOCTYPE html>
<html lang="en">
  	<head>
      <title>@yield('pageTitle', 'CharityPro')</title>
    	<meta charset="utf-8">
      <meta http-equiv="Cache-control" content="public">
      <meta name="robots" content="index, follow">
      <meta name="keywords" content="charpro, charitypro, forum, about us, home, stories, story, job, donations, donation, upcoming events, upcoming event, our services, our service, Մեր Մասին, Աշխատանք, Պատմություններ, Ֆորում, Նվիրաբերություն, Իրադարձություններ, մեկտեղ համատեղ, բարեգործություն, օգնություն">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      @if(isset($og))
        @foreach($og as $ogKey => $ogItem)
          <meta property="og:{{$ogKey}}" content="{{$ogItem}}">
        @endforeach
      @endif
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/css/swiper.min.css">
    <link rel="stylesheet" href="{{URL('css/initTelInput.css')}}">   
<link rel="stylesheet" type="text/css" href="{{URL('css/header.css')}}">
    <link rel="stylesheet" href="{{URL('css/style.css')}}">
    <link rel="stylesheet" href="{{URL('css/jquery-ui.css')}}"> 
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="{{URL('js/jquery.imgzoom.js')}}"></script>
       <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
  	</head>
  	<body>
      <header>
        <div class="d-block d-md-none homeTop">
            <span class="lnr lnr-menu clickMobileMenu"></span>
            Գլխավոր
        </div>
   
             <div class="container">
            <div class="top-panel">
                @if($userId)
                <div class="loginWrap text-right">
                    <div class="dropdown">
                        <img src="{{URL('images/Users', $user_data->img)}}" class="user-avatar dropdown-toggle" data-toggle="dropdown" style="width: 30px">
                        <ul class="dropdown-menu">
                        @if($isAdmin)
                            <li><i class="fa fa-user-circle" aria-hidden="true"></i> <a href="{{URL::to('/admin')}}">Ադմին</a></li>
                        @endif
                            <li><i class="fa fa-user-circle" aria-hidden="true"></i> <a href="{{URL::to('/account')}}">Իմ էջը</a></li>
                            <li>
                                <i class="fa fa-sign-out" aria-hidden="true"></i>
                                <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Ելք</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </div>
                    </div>
                    @else
       

                     <div class="loginWrap text-right">
                        <span class="lnr lnr-user loginIcon" ></span>
                        <div class="loginForm">
                            <h3 class="headerForm text-left">Մուտք:</h3>
                            <form name="" method="POST" action="{{ route('login') }}" id="loginForma" class="d-flex align-items-start flex-column">
                                {{ csrf_field() }}
                                <input type="text" name="email" placeholder="էլ֊հասցե">
                             @if ($errors->has('email'))
                                 <span class="help-block">
                                 <strong>{{ $errors->first('email') }}</strong>
                             </span>
                             @endif
                                <input type="password" name="password" placeholder="Գաղտնաբառ">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                                <a href="{{ route('password.request') }}">Մոռացե՞լ եք գաղտնաբառը</a>
                                <button type="submit" class="loginButton">Մուտք</button>
                                <a href="javascript:void(0)" class="registerFunc">Գրանցվել</a>
                            </form>
                        </div>
                        <div class="registerForm">
                            <h3 class="headerForm text-left">Գրանցում:</h3>
                            <form name="" method="POST" action="{{ route('register') }}" id="registerForma" class="d-flex align-items-start flex-column">
                                {{ csrf_field() }}
                                <input type="text" name="name" placeholder="Անուն" value="{{ old('name') }}" required>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                                <input type="email" name="email" placeholder="էլ֊հասցե" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                                <div class="genderBlock">
                                    <span>Սեռը</span>
                                    <p class="mb-0">
                                        <label for="male">
                                            <input type="radio" class="notSelect" id="male" name="gender" value="M"> :Արական
                                        </label>
                                        <label for="female">
                                            <input type="radio" class="notSelect" id="female" name="gender" value="F"> :Իգական
                                        </label>
                                    </p>
                                    @if ($errors->has('gender'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </span>
                                    @endif
                                </div>
                                <input type="password" name="password" placeholder="Գաղտնաբառ" required>
                                <input type="password" name="password_confirmation" placeholder="Կրկնել գաղտնաբառը" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                                <label for="agree" class="agreeClass">
                                    <input type="checkbox" id="agree" class="notSelect" name="termsconditions" required>
                                    <a href="{{url('termsconditions')}}">Համաձայն եմ գաղտնիության քաղաքականությանը</a>
                                </label>
                                <button type="submit" class="loginButton ">Գրանցվել</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <nav>
            <div class="container">
                <div class="row">          
                    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-center headerFixed">
                        <a href="/" class="d-block logo-content">
                            <p data-second="Pro" class="mb-0">Charity </p>
                            <span>charity project</span>
                        </a>
                        @php
                          $activeTab = Request::route()->uri()
                        @endphp
                        <ul class="navBar" type="none">
                            
                            <li class="@if($activeTab == 'forum') active-link @endif"><a  href="{{URL::to('forum')}}">Ֆորում</a></li>
                            <li class="@if($activeTab == 'job') active-link @endif" ><a  href="{{URL::to('job')}}">Աշխատանք</a></li>
                            <li class="@if(stripos($activeTab, 'stories')  !== false) active-link @endif" ><a href="{{URL::to('stories')}}">Պատմություններ</a></li>

<!--                             <li class="@if(stripos($activeTab, 'upcoming_events') !== false) active-link @endif"><a href="{{URL::to('upcoming_events')}}">Իրադ</a></li> -->
                            <li class="@if((stripos($activeTab, 'gallery') !== false) || (stripos($activeTab, 'images') !==false))active-link @endif"><a href="{{URL::to('gallery')}}">Ֆոտոշարք</a></li>
                        </ul>
                        <div class="donateBlock">
                            <a href="javascript:void(0)" class="lastButton logined">Նվիրիր <img src="{{URL('img/donation.svg')}}"></a>
                            <div class="addDonateWrap">
                                <form method="POST" action="{{ URL::to('/donations/create') }}" class="addWorkForm" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <input type="text" placeholder="Նվիրաբերություն" name="name" >
                                    <textarea rows="4" placeholder="Նկարագրություն" name="description"></textarea>
                                    <select class="filterCitys" name="cat_id" id='don-cat'>
                                    </select>
                                    <div class="telBlock d-flex align-items-center form-control">
                                        <input type="tel" class="filter_input new-don input-phone job-phone" name="phone">
                                    </div>
                                    <span class="valid-msg-job d-none">✓ Հեռախոսահամարը ճիշտ է լրացված</span>
                                    <span class="error-msg-job d-none">✗ Հեռախոսահամարը սխալ է լրացված</span>
                                    <label class="file-label">
                                        <input class="file-input" type="file" name="images[]" accept="image/x-png,image/gif,image/jpeg" multiple>
                                        <span class="file-cta">
                                            <span class="file-icon">
                                                <span class="lnr lnr-upload"></span>
                                            </span>
                                            <span class="file-label">
                                            Ներբեռնել նկարներ...
                                            </span>
                                        </span>
                                    </label>
                                    @if($userId)
                                    <input type="submit" class="addWork" value="Ավելացնել">
                                    @else
                                    <input type="button" class="addWork" value="Ավելացնել"  data-toggle="modal" data-target="#signInModalLong">
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </nav>
    </header>
		<div class="vertical_buttons">
          <ul>
            <li><a href="https://www.facebook.com/char.pro.378?hc_ref=ARSgrOsAep8HRJKv5qk-1p6FbzjxqYuX0fXp5JV_z6yVcUC_YSFbmYRqYHElWsA2xU0" target="_blank"><i class="fa fa-facebook"></i></a></li>
            <li><a href="https://twitter.com/CharityPro2018" target="_blank"><i class="fa fa-twitter"></i></a></li>
            <li><a href="https://www.linkedin.com/in/charity-pro-930ba315a/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
            <li><a href="https://www.youtube.com/channel/UC8yl8Px5OC2Lr-LxI2h9BqA" target="_blank"><i class="fa fa-youtube"></i></a></li>
          </ul>
        <div class="slide_icons">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
            <i class="fa fa-angle-left d-none" aria-hidden="true"></i>
        </div>
		</div>
    <input type="hidden" id="user-id" value="{{ $userId }}">                        
      <div id="signInModalLong" class="fullscreen_bg modal fade @if ($errors->has('email')) open-modal @endif" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true"/>
        <div id="regContainer" class="container modal-dialog" role="document">
            <div class="modal-content signInModalLong">
                <div class="panel panel-login">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 log-reg-btn">
                                <a href="javascript:void(0)" class="active" id="login-form-link">Մուտք</a>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 log-reg-btn">
                                <a href="javascript:void(0)" id="register-form-link">Գրանցվել</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                          <form id="login-form" class="form-horizontal" method="POST" action="{{ route('login') }}">
                              {{ csrf_field() }}

                              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                                  <div class="col-12">
                                      <input type="email" class="modal-email" placeholder="Էլ֊փոստ" name="email" value="{{ old('email') }}" required autofocus>

                                      @if ($errors->has('email'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('email') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>

                              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                  <div class="col-12">
                                      <input type="password" class="modal-password" placeholder="Գաղտնաբառ" name="password" required>

                                      @if ($errors->has('password'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('password') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>

                              <div class="form-group">
                                  <div class="col-12">
                                    <div class="row">
                                      <div class="col-12 col-sm-6">
                                        <div class="checkbox">
                                          <label style="font-size: 13px;">
                                              <input type="checkbox" name="remember"  {{ old('remember') ? 'checked' : '' }}> Հիշել ինձ
                                          </label>
                                        </div>
                                      </div>
                                      <div class="col-12 col-sm-6">
                                        <a class="btn btn-link" href="{{ route('password.request') }}">Մոռացե՞լ եք գաղտնաբառը</a>
                                      </div>
                                    </div>
                                  </div>
                              </div>

                              <div class=" form-group">
                                <div class="row">
                                  <div class="col-3"></div>
                                  <div class="col-6">
                                      <button type="submit" class="btn btn-login">Մուտք</button>
                                  </div>
                                </div>
                              </div>
                          </form>
                          <form class="form-horizontal" method="POST" action="{{ route('register') }}" id="register-form" style="display: none;">
                              {{ csrf_field() }}

                              <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                 
                                  <div class="col-12">
                                      <input id="name" type="text" placeholder="Անուն" class="modal-input" name="name" value="{{ old('name') }}" required autofocus>

                                      @if ($errors->has('name'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('name') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>

                              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                                  <div class="col-12">
                                      <input type="email" placeholder="Էլ֊փոստ" class="modal-input" name="email" value="{{ old('email') }}" required>

                                      @if ($errors->has('email'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('email') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>

                              <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                  <label for="gender" class="col-md-12 control-label">Սեռը</label>

                                  <div class="col-12">
                                      <input id="male1" type="radio" name="gender" value="M"> <label class="control-label">Արական</label>
                                      <input id="female1" type="radio" name="gender" value="F"> <label class="control-label">Իգական</label>

                                      @if ($errors->has('gender'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('gender') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>

                              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                  

                                  <div class="col-12">
                                      <input type="password" placeholder="Գաղտնաբառ" class="modal-input" name="password" required>

                                      @if ($errors->has('password'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('password') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>

                              <div class="form-group">
                             

                                  <div class="col-12">
                                      <input id="password-confirm" type="password" placeholder="Հաստատել գաղտնաբառը" class="modal-input" name="password_confirmation" required>
                                  </div>
                              </div>
                               <div class="col-12">
                                  <div class="radio">
                                      <label><input type="radio" name="termsconditions" required><a href="{{URL('termsconditions')}}" class=" btn-link terms-conditions-link"><b>Համաձայն եմ գաղտնիության քաղաքականությանը</b></a></label>
                                  </div>
                              </div>
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-3"></div>
                                  <div class="col-6">
                                      <button type="submit" class="btn btn-login" >Գրանցվել</button>
                                  </div>
                                </div>
                              </div>
                          </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>