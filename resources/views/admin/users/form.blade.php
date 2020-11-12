<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    <label for="name" class="col-md-4 control-label">Name: </label>
    <div class="col-md-6">
        <input type="text" name="name" id="name" class="form-control" value="{{isset($user->name) ? $user->name : ''}}" required />
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
    <label for="email" class="col-md-4 control-label">Email: </label>
    <div class="col-md-6">
        <input type="email" name="email" id="email" class="form-control" value="{{isset($user->email) ? $user->email : ''}}" required />
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('gender') ? ' has-error' : ''}}">
    <label for="email" class="col-md-4 control-label">Gender: </label>
    <div class="col-md-6">
        <input type="radio" name="gender" id="male" value="M" @if(isset($user->gender) && $user->gender === 'M') {{'checked'}} @endif /> Male
        {!! $errors->first('gender', '<p class="help-block">:message</p>') !!}
        <input type="radio" name="gender" id="female" value="F" @if(isset($user->gender) && $user->gender === 'F') {{'checked'}} @endif /> Female
        {!! $errors->first('gender', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('password') ? ' has-error' : ''}}">
    <label for="password" class="col-md-4 control-label">Password: </label>
    <div class="col-md-6">
        <input type="password" name="password", class="form-control" {{isset($user->id) ? '' : 'required'}} />
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('img') ? ' has-error' : ''}}">
    <label for="img" class="col-md-4 control-label">Image: </label>
    <div class="col-md-6">
        @if(isset($user->img))
            <img src='{{URL("images/Users/$user->img")}}' style="width: 200px" class="uploaded-img">
            <input type="button" class="delete-img" value="Delete">
            <input type="hidden" name="image_uploaded" value="{{$user->img}}" class="uploaded-inp">
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
