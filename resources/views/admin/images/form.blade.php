<div class="form-group{{ $errors->has('title') ? ' has-error' : ''}}">
    <label for="title" class="col-md-4 control-label">Title: </label>
    <div class="col-md-6">
        <input type="text" name="title" id="title" class="form-control" value="{{isset($images->title) ? $images->title : ''}}" required />
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('images') ? ' has-error' : ''}}">
    <label for="images" class="col-md-4 control-label">Image: </label>
    <div class="col-md-6">
        @if(isset($images->images))
            @foreach(json_decode($images->images) as $value)
                @if($value != null)
                    <img src='{{URL("images/Images/$value")}}' style="width: 200px" class="uploaded-img">
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
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input type="submit" value="{{isset($submitButtonText) ? $submitButtonText : 'Create'}}" class="btn btn-primary" />
    </div>
</div>
