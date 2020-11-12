<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
	<label for="name" class="col-md-4 control-label">Name: </label>
    <div class="col-md-6">
    	<input type="text" name="name" id="name" class="form-control" value="{{isset($story_categories->name) ? $story_categories->name : ''}}" required />
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input type="submit" value="{{isset($submitButtonText) ? $submitButtonText : 'Create'}}" class="btn btn-primary" />
    </div>
</div>
