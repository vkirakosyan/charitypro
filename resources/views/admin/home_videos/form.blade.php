<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    <label for="name" class="col-md-4 control-label">Name: </label>
    <div class="col-md-6">
        <input type="text" name="youtube_id" id="youtubeId" class="form-control" value="{{isset($video) ? $video : ''}}" required />
        {!! $errors->first('youtube_id', '<p class="help-block">:message</p>') !!}
        @if(isset($video) and $video and count($errors) == 0)
            <br>
            <img src="http://img.youtube.com/vi/{{$video}}/default.jpg" />
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input type="submit" value="{{isset($submitButtonText) ? $submitButtonText : 'Create'}}" class="btn btn-primary" />
    </div>
</div>
