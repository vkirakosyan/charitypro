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
                    <div class="rightAside editJob">
                        <h4>{{$jobs->title}}</h4>
                        <div class="profile-content-1">
                    
                            <form method="POST" action="{{url('job/update', $jobs->id)}}" class="form-horizontal" enctype="multipart/form-data">
                                <div class="form-group{{ $errors->has('title') ? ' has-error' : ''}}">
                                    <label for="title" class="control-label">Title: </label>
                                    <div class="">
                                        <input type="text" name="title" id="title" class="form-control" value="{{isset($jobs->title) ? $jobs->title : ''}}" required />
                                        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('description') ? ' has-error' : ''}}">
                                    <label for="description" class="control-label">Description: </label>
                                    <div class="">
                                        <textarea name="description" rows="5" id="description" class="form-control" required >{{isset($jobs->description) ? $jobs->description : ''}}</textarea>
                                        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('number') ? ' has-error' : ''}}">
                                    <label for="number" class="control-label">Number: </label>
                                    <div class="">
                                        <input type="text" name="number" id="number" class="form-control" value="{{isset($jobs->number) ? $jobs->number : ''}}" required />
                                        {!! $errors->first('number', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
                                    <label for="email" class="control-label">Email: </label>
                                    <div class="">
                                        <input type="email" name="email" id="email" class="form-control" value="{{isset($jobs->email) ? $jobs->email : ''}}" required />
                                        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('categories') ? ' has-error' : ''}}">
                                    <label for="categories" class="control-label">Categories: </label>
                                    <div class="">
                                        <select name="cat_id" class="form-control">
                                            @foreach($categories as $value)
                                                @if(isset($jobs->cat_id) and $value->id == $jobs->cat_id)
                                                    <option value="{{$value->id}}" selected >{{$value->name}}</option>
                                                @else
                                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endif
                                            @endforeach    
                                        </select>
                                        {!! $errors->first('categories', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('cities') ? ' has-error' : ''}}">
                                    <label for="cities" class="control-label">Cities: </label>
                                    <div class="">
                                        <select name="city_id" class="form-control">
                                            @foreach($cities as $value)
                                                @if(isset($jobs->city_id) and $value->id == $jobs->city_id)
                                                    <option value="{{$value->id}}" selected >{{$value->name}}</option>
                                                @else
                                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endif
                                            @endforeach    
                                        </select>
                                        {!! $errors->first('cities', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('img') ? ' has-error' : ''}}">
                                    <label for="img" class="control-label">Image: </label>
                                    <div class="">
                                        @if(isset($jobs->img))
                                            <img src='{{URL("/images/Jobs/$jobs->img")}}' style="width: 200px" class="uploaded-img">
                                <!-- <input type="button" class="delete-img" value="Delete"> -->
                                            <input type="hidden" name="image_uploaded" value="{{$jobs->img}}" class="uploaded-inp">
                                        @endif
                                        <div class="update_buttons">
                                            <label class="file-label">
                                                <input class="file-input gallery-photo-add"  name="img" type="file">
                                                <span class="file-cta">
                                                    <span class="file-icon">
                                                        <i class="fa fa-upload"></i>
                                                    </span>
                                                    <span class="file-label">
                                                        choses Image...
                                                    </span>
                                                </span>
                                            </label>
                                            <input type="submit" value="Update" class="btn_warning" />
                                        </div>
                                        <div class="gallery"></div>
                                        {!! $errors->first('img', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                {{csrf_field()}}
                            </form>
                        </div>
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
    <span class="lnr lnr-chevron-up jumpTo"></span>
</main>
@include('layouts/footer')
<!--   <script type="text/javascript" src="{{URL('/js/admin.js')}}"></script> -->
