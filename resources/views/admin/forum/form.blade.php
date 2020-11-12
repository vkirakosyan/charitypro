<div class="form-group{{ $errors->has('title') ? ' has-error' : ''}}">
    <label for="title" class="col-md-4 control-label">Title: </label>
    <div class="col-md-6">
        <input type="text" name="title" id="title" class="form-control" value="{{isset($posts->title) ? $posts->title : ''}}" required />
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('description') ? ' has-error' : ''}}">
    <label for="description" class="col-md-4 control-label">Description: </label>
    <div class="col-md-6">
        <textarea name="description" id="description" class="form-control" required >{{isset($posts->description) ? $posts->description : ''}}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input type="submit" value="{{isset($submitButtonText) ? $submitButtonText : 'Create'}}" class="btn btn-primary" />
    </div>
</div>
