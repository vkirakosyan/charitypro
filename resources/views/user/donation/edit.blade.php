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
                    <div class="rightAside donateEdit">
                        <h4>{{$donations->name}}</h4>
                        
                     <form method="POST" action="{{url('donation/update', $donations->id)}}" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
                            <label for="name" class="control-label">Name: </label>
                            <div>
                                <input type="text" name="name" id="name1" class="form-control" value="{{isset($donations->name) ? $donations->name : ''}}" required />
                                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : ''}}">
                                <label for="description" class="control-label">Description:</label>
                                <div>
                                    <textarea name="description" rows="5" id="description" class="form-control" required >{{isset($donations->description) ? $donations->description : ''}}</textarea>
                                    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('categories') ? ' has-error' : ''}}">
                                <label for="categories" class="control-label">Categories: </label>
                                <div>
                                    <select name="cat_id" class="form-control">
                                        @foreach($categories as $value)
                                            @if(isset($donations->cat_id) and $value->id == $donations->cat_id)
                                                <option value="{{$value->id}}" selected >{{$value->name}}</option>
                                            @else
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endif
                                        @endforeach    
                                    </select>
                                    {!! $errors->first('categories', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : ''}}">
                                <label for="phone" class="control-label">Phone: </label>
                                <div>
                                    <input name="phone" type="tel" class="form-control input-phone" value="{{isset($donations->phone) ? $donations->phone : ''}}">
                                    {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('images') ? ' has-error' : ''}}">
                                <label for="images" class="control-label">Image: </label>
                                <div class="editImages">
                                    @if(isset($donations->images))
                                        @foreach(json_decode($donations->images) as $value)
                                            @if($value != null)
                                                <div class="imageWrapDelete">
                                                    <img src='{{URL("images/Donations/$value")}}' class="uploaded-img">
                                                    <input type="button" class="delete-img" value="Delete">
                                                    <input type="hidden" name="image_uploaded[]" value="{{$value}}" class="uploaded-inp">
                                                </div>    
                                            @endif
                                        @endforeach   
                                    @endif
                                </div>
                                <div class="update_buttons">
                                    <label class="file-label">
                                        <input class="file-input gallery-photo-add" multiple name="images[]" type="file">
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
                                {!! $errors->first('images', '<p class="help-block">:message</p>') !!}
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
  <script type="text/javascript" src="{{URL('/js/admin.js')}}"></script>
