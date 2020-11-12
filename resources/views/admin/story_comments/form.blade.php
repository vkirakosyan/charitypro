<div class="form-group{{ $errors->has('story_id') ? ' has-error' : ''}}">
    <label for="story_id" class="col-md-4 control-label">story Id: </label>
    <div class="col-md-6">
        <input type="text" name="story_id" id="story_id" class="form-control" value="{{isset($story_comments->story_id) ? $story_comments->story_id : ''}}" required />
        {!! $errors->first('forum_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('description') ? ' has-error' : ''}}">
    <label for="description" class="col-md-4 control-label">Description: </label>
    <div class="col-md-6">
        <textarea name="description" id="description" class="form-control" required >{{isset($story_comments->description) ? $story_comments->description : ''}}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input type="submit" value="{{isset($submitButtonText) ? $submitButtonText : 'Create'}}" class="btn btn-primary" />
    </div>
</div>
