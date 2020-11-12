<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
	<label for="name" class="col-md-4 control-label">Name: </label>
    <div class="col-md-6">
    	<input type="text" name="name" id="name" class="form-control" value="{{isset($jobs_categories->name) ? $jobs_categories->name : ''}}" required />
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('img') ? ' has-error' : ''}}">
    <label for="img" class="col-md-4 control-label">Image: </label>
    <div class="col-md-6">
        @if(isset($jobs_categories->img))
            <img src='{{url("images/Jobs/$jobs_categories->img")}}' style="width: 200px" class="uploaded-img">
            <input type="button" class="delete-img" value="Delete">
            <input type="hidden" name="image_uploaded" value="{{$jobs_categories->img}}" class="uploaded-inp">
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
