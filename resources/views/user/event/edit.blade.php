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
                        <h4  class="eventsTitle">{{$events->title}}</h4>
                        <form method="POST" action="{{url('events/update', $events->id)}}" class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : ''}}">
                                <label for="title" class="control-label">Title: </label>
                                <div>
                                    <input type="text" name="title" id="title" class="form-control" value="{{isset($events->title) ? $events->title : ''}}" required />
                                    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : ''}}">
                                <label for="description" class="control-label">Description: </label>
                                <div>
                                    <textarea name="description" id="description" rows="5" class="form-control" required >{{isset($events->description) ? $events->description : ''}}</textarea>
                                    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('cities') ? ' has-error' : ''}}">
                                <label for="cities" class="control-label">Cities: </label>
                                <div>
                                    <select name="city_id" class="form-control">
                                        @foreach($cities as $value)
                                            @if(isset($events->city_id) and $value->id == $events->city_id)
                                                <option value="{{$value->id}}" selected >{{$value->name}}</option>
                                            @else
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endif
                                        @endforeach    
                                    </select>
                                    {!! $errors->first('cities', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('details_location') ? ' has-error' : ''}}">
                                <label for="details_location" class="control-label">Location: </label>
                                <div>
                                    <input type="text" name="details_location" id="details_location" class="form-control" value="{{isset($events->details_location) ? $events->details_location : ''}}" required />
                                    {!! $errors->first('details_location', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('time') ? ' has-error' : ''}}">
                                <label for="time" class="control-label">Time: </label>
                                <div>
                                    <input type="date" name="time" class="form-control" value="{{isset($events->time) ? substr($events->time,0,10) : ''}}" required >
                                    {!! $errors->first('time', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('img') ? ' has-error' : ''}}">
                                <label for="img" class="control-label">Image: </label>
                                <div>
                                    @if(isset($events->img))
                                        <img src='{{URL("images/Events/$events->img")}}' class="uploaded-img">
                                        <input type="button" class="delete-img" value="Delete">
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
                                        <input type="hidden" name="image_uploaded" value="{{$events->img}}" class="uploaded-inp">
                                    @endif
                                        <input type="submit" value="update" class="btn_warning" />
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
    <span class="lnr lnr-chevron-up jumpTo"></span>
 </main>
@include('layouts/footer')
  <script type="text/javascript" src="{{URL('/js/admin.js')}}"></script>


