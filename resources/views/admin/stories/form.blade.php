<div class="form-group{{ $errors->has('title') ? ' has-error' : ''}}">
    <label for="title" class="col-md-4 control-label">Title: </label>
    <div class="col-md-6">
        <input type="text" name="title" id="title" class="form-control" value="{{isset($stories->title) ? $stories->title : ''}}" required />
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('description') ? ' has-error' : ''}}">
    <label for="description" class="col-md-4 control-label">Description: </label>
    <div class="col-md-6">
        <textarea name="description" id="description" class="form-control" required >{{isset($stories->description) ? $stories->description : ''}}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('categories') ? ' has-error' : ''}}">
    <label for="categories" class="col-md-4 control-label">Categories: </label>
    <div class="col-md-6">
        <select name="cat_id" class="form-control">
            @foreach($categories as $value)
                @if(isset($stories->cat_id) and $value->id == $stories->cat_id)
                    <option value="{{$value->id}}" selected >{{$value->name}}</option>
                @else
                    <option value="{{$value->id}}">{{$value->name}}</option>
                @endif
            @endforeach    
        </select>
        {!! $errors->first('categories', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('youtube_video') ? ' has-error' : ''}}">
    <label for="name" class="col-md-4 control-label">Youtube video id: </label>
    <div class="col-md-6">
        <input type="text" name="youtube_id" id="youtubeId" class="form-control" value="{{isset($stories->youtube_id) ? $stories->youtube_id : ''}}" />
        {!! $errors->first('youtube_id', '<p class="help-block">:message</p>') !!}
        @if(isset($stories->youtube_id) and $stories->youtube_id and count($errors) == 0)
            <br>
            <img src="http://img.youtube.com/vi/{{$stories->youtube_id}}/default.jpg" />
        @endif
    </div>
</div>
<div class="form-group{{ $errors->has('images') ? ' has-error' : ''}}">
    <label for="images" class="col-md-4 control-label">Image: </label>
    <div class="col-md-6">
        @if(isset($stories->images))
            @foreach(json_decode($stories->images) as $value)
                @if($value != null)
                    <img src='{{URL("images/Stories/$value")}}' style="width: 200px" class="uploaded-img">
                    <input type="button" class="delete-img" value="Delete">
                    <input type="hidden" name="image_uploaded[]" value="{{$value}}" class="uploaded-inp">
                @endif
            @endforeach   
        @endif
        <input type="file" multiple class="gallery-photo-add" name="images[]">
        <div class="gallery"></div>
        {!! $errors->first('images', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('is_success_story') ? ' has-error' : ''}}">
    <label for="is_blocked" class="col-md-4 control-label">Is Success Story: </label>
    <div class="col-md-6">
        <input type="checkbox" name="is_success_story" id="is_success_story" {{(isset($donations->is_success_story) and $donations->is_success_story) ? 'checked' : ''}} />
        {!! $errors->first('is_success_story', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input type="submit" value="{{isset($submitButtonText) ? $submitButtonText : 'Create'}}" class="btn btn-primary" />
    </div>
</div>
