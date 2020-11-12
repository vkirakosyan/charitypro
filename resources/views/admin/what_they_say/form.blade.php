<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    <label for="name" class="col-md-4 control-label">Name: </label>
    <div class="col-md-6">
        <input type="text" name="name" id="name" class="form-control" value="{{isset($whattheysay->name) ? $whattheysay->name : ''}}" required />
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('profession') ? ' has-error' : ''}}">
    <label for="profession" class="col-md-4 control-label">Profession: </label>
    <div class="col-md-6">
        <input type="text" name="profession" class="form-control" value="{{isset($whattheysay->profession) ? $whattheysay->profession : ''}}" required />
        {!! $errors->first('profession', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('description') ? ' has-error' : ''}}">
    <label for="description" class="col-md-4 control-label">Description: </label>
    <div class="col-md-6">
        <textarea name="description" id="description" class="form-control">{{isset($whattheysay->description) ? $whattheysay->description : ''}}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('img') ? ' has-error' : ''}}">
    <label for="img" class="col-md-4 control-label">Image: </label>
    <div class="col-md-6">
        @if(isset($whattheysay->img))
            <img src='{{url("images/WhatTheySay/$whattheysay->img")}}' style="width: 200px" class="uploaded-img">
            <input type="button" class="delete-img" value="Delete">
            <input type="hidden" name="image_uploaded" value="{{$whattheysay->img}}" class="uploaded-inp">
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
