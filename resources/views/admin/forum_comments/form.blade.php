<div class="form-group{{ $errors->has('forum_id') ? ' has-error' : ''}}">
    <label for="forum_id" class="col-md-4 control-label">post Id: </label>
    <div class="col-md-6">
        <input type="text" name="forum_id" id="forum_id" class="form-control" value="{{isset($forum_comments->forum_id) ? $forum_comments->forum_id : ''}}" required />
        {!! $errors->first('forum_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('comment') ? ' has-error' : ''}}">
    <label for="comment" class="col-md-4 control-label">Comment: </label>
    <div class="col-md-6">
        <textarea name="comment" id="comment" class="form-control" required >{{isset($forum_comments->comment) ? $forum_comments->comment : ''}}</textarea>
        {!! $errors->first('comment', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input type="submit" value="{{isset($submitButtonText) ? $submitButtonText : 'Create'}}" class="btn btn-primary" />
    </div>
</div>
