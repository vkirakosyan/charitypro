@include('layouts/header')

<main>
 <form method="POST" action="{{url('account/update')}}" class="form-horizontal" enctype="multipart/form-data">
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
                    <div class="rightAside">
                        <h4>Կարգավորումներ</h4>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
                            <label for="name" class="control-label">Name: </label>
                            <div>
                                <input type="text" name="name" id="name1" class="form-control" value="{{isset($user->name) ? $user->name : ''}}" required />
                                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
                            <label for="email" class="control-label">Email: </label>
                            <div>
                                <input type="email" name="email" id="email" class="form-control" value="{{isset($user->email) ? $user->email : ''}}" required />
                                {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : ''}}">
                            <label for="email" class="control-label">Gender: </label>
                            <div>
                                <input type="radio" name="gender" id="male" value="M" @if(isset($user->gender) && $user->gender === 'M') {{'checked'}} @endif /> Male
                                {!! $errors->first('gender', '<p class="help-block">:message</p>') !!}
                                <input type="radio" name="gender" id="female" value="F" @if(isset($user->gender) && $user->gender === 'F') {{'checked'}} @endif /> Female
                                {!! $errors->first('gender', '<p class="help-block">:message</p>') !!}
                            </div>
                            
                        </div>

                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            Փոխել գաղտնաբառը
                        </button>
                        
                        <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : ''}}">
                            <label for="password" class="control-label">Password: </label>
                            <div>
                                <input type="password" name="password", class="form-control"/>
                                {!! $errors->first('password', '<p class="help-block">Գաղտնաբառը պետք է լինի առնվազն 6 նիշ</p>') !!}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : ''}}">
                            <label for="confirm_password" class="control-label">Confirm Password: </label>
                            <div>
                                <input type="password" name="confirm_password", class="form-control"  />
                                {!! $errors->first('confirm_password', '<p class="help-block">Գաղտնաբառի կրկնության սխալ</p>') !!}
                            </div>
                        </div>
                        </div>
                        </div>

                        <div class="form-group{{ $errors->has('img') ? ' has-error' : ''}}">
                            <label for="img" class="control-label">Image: </label>
                            <div class="mb-3">
                                @if(isset($user->img))
                                    <img src='{{URL("images/Users/$user->img")}}' style="width: 200px" class="uploaded-img">
                                    <input type="hidden" name="image_uploaded" value="{{$user->img}}" class="uploaded-inp">
                                @endif
                                
                                <div class="gallery"></div>
                                {!! $errors->first('img', '<p class="help-block">:message</p>') !!}
                            </div>
                            <label class="file-label">
                                <input class="file-input gallery-photo-add" name="img" type="file">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <i class="fa fa-upload"></i>
                                    </span>
                                    <span class="file-label">
                                        choses Image...
                                    </span>
                                </span>
                            </label>
                            <input type="submit" value="update" class="btn_warning" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

   
    <span class="lnr lnr-chevron-up jumpTo"></span>
                            {{ csrf_field() }}
                        </form>
</main>

 @include('layouts/footer')