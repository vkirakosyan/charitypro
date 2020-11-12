<div class="form-group{{ $errors->has('title') ? ' has-error' : ''}}">
    <label for="title" class="col-md-4 control-label">Title: </label>
    <div class="col-md-6">
        <input type="text" name="title" id="title" class="form-control" value="{{isset($events->title) ? $events->title : ''}}" required />
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('description') ? ' has-error' : ''}}">
    <label for="description" class="col-md-4 control-label">Description: </label>
    <div class="col-md-6">
        <textarea name="description" id="description" class="form-control" required >{{isset($events->description) ? $events->description : ''}}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('cities') ? ' has-error' : ''}}">
    <label for="cities" class="col-md-4 control-label">Cities: </label>
    <div class="col-md-6">
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
    <label for="details_location" class="col-md-4 control-label">Location: </label>
    <div class="col-md-6">
        <input type="text" name="details_location" id="details_location" class="form-control" value="{{isset($events->details_location) ? $events->details_location : ''}}" required />
        {!! $errors->first('details_location', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('time') ? ' has-error' : ''}}">
    <label for="time" class="col-md-4 control-label">Time: </label>
    <div class="col-md-6">
        <input type="datetime-local" name="time" class="form-control" value="{{isset($events->time) ? str_replace(' ', 'T', $events->time) : ''}}" required >
        {!! $errors->first('time', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('img') ? ' has-error' : ''}}">
    <label for="img" class="col-md-4 control-label">Image: </label>
    <div class="col-md-6">
        @if(isset($events->img))
            <img src='{{URL("images/Events/$events->img")}}' style="width: 200px" class="uploaded-img">
            <input type="button" class="delete-img" value="Delete">
            <input type="hidden" name="image_uploaded" value="{{$events->img}}" class="uploaded-inp">
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
