<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    <label for="name" class="col-md-4 control-label">Name: </label>
    <div class="col-md-6">
        <input type="text" name="name" id="name" class="form-control" value="{{isset($donations->name) ? $donations->name : ''}}" required />
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('description') ? ' has-error' : ''}}">
    <label for="description" class="col-md-4 control-label">Description: </label>
    <div class="col-md-6">
        <textarea name="description" id="description" class="form-control" required >{{isset($donations->description) ? $donations->description : ''}}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('categories') ? ' has-error' : ''}}">
    <label for="categories" class="col-md-4 control-label">Categories: </label>
    <div class="col-md-6">
        <select name="cat_id" class="form-control">
            @foreach($categories as $value)
                @if(isset($donations->cat_id) and $value->id == $donations->cat_id)
                    <option value="{{$value->id}}" selected >{{$value->name}}</option>
                @else
                    <option value="{{$value->id}}">{{$value->name}}</option>
                @endif
            @endforeach    
        </select>
        {!! $errors->first('categories', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('phone') ? ' has-error' : ''}}">
    <label for="phone" class="col-md-4 control-label">Phone: </label>
    <div class="col-md-6">
        <input name="phone" type="tel" class="form-control input-phone" value="{{isset($donations->phone) ? $donations->phone : ''}}">
        {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('is_blocked') ? ' has-error' : ''}}">
    <label for="is_blocked" class="col-md-4 control-label">Is blocked: </label>
    <div class="col-md-6">
        <input type="checkbox" name="is_blocked" id="is_blocked" {{(isset($donations->is_blocked) and $donations->is_blocked) ? 'checked' : ''}} />
        {!! $errors->first('is_blocked', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('images') ? ' has-error' : ''}}">
    <label for="images" class="col-md-4 control-label">Image: </label>
    <div class="col-md-6">
        @if(isset($donations->images))
            @foreach(json_decode($donations->images) as $value)
                @if($value != null)
                    <img src='{{URL("images/Donations/$value")}}' style="width: 200px" class="uploaded-img">
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
