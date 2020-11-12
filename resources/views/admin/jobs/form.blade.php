<div class="form-group{{ $errors->has('title') ? ' has-error' : ''}}">
    <label for="title" class="col-md-4 control-label">Title: </label>
    <div class="col-md-6">
        <input type="text" name="title" id="title" class="form-control" value="{{isset($jobs->title) ? $jobs->title : ''}}" required />
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('description') ? ' has-error' : ''}}">
    <label for="description" class="col-md-4 control-label">Description: </label>
    <div class="col-md-6">
        <textarea name="description" id="description" class="form-control" required >{{isset($jobs->description) ? $jobs->description : ''}}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('number') ? ' has-error' : ''}}">
    <label for="number" class="col-md-4 control-label">Number: </label>
    <div class="col-md-6">
        <input type="text" name="number" id="number" class="form-control" value="{{isset($jobs->number) ? $jobs->number : ''}}" required />
        {!! $errors->first('number', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
    <label for="email" class="col-md-4 control-label">Email: </label>
    <div class="col-md-6">
        <input type="email" name="email" id="email" class="form-control" value="{{isset($jobs->email) ? $jobs->email : ''}}" required />
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('categories') ? ' has-error' : ''}}">
    <label for="categories" class="col-md-4 control-label">Categories: </label>
    <div class="col-md-6">
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
    <label for="cities" class="col-md-4 control-label">Cities: </label>
    <div class="col-md-6">
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
    <label for="img" class="col-md-4 control-label">Image: </label>
    <div class="col-md-6">
        @if(isset($jobs->img))
            <img src='{{URL("/images/Jobs/$jobs->img")}}' style="width: 200px" class="uploaded-img">
            <input type="button" class="delete-img" value="Delete">
            <input type="hidden" name="image_uploaded" value="{{$jobs->img}}" class="uploaded-inp">
        @endif
        <input type="file" class="gallery-photo-add" name="img">
        <div class="gallery"></div>
        {!! $errors->first('img', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input type="submit" value="{{isset($submitButtonText) ? $submitButtonText : 'Create'}}" class="btn btn-primary" />
    </div>
</div>
