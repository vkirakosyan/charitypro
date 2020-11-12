@extends('layouts.app')

@section('content')
<div class="container" style="padding-top:8%;">
    <div class="row">
      <div class="col-0 col-md-3 col-lg-4"></div>
      <div class="col-12 col-md-6 col-lg-4">
            <div class="panel panel-default">
              <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-12 control-label">Էլ֊փոստ</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-12 control-label">Գաղտնաբառ</label>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Հիշել
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    Մուտք
                                </button>
                            </div>
                            <div class="col-md-12 text-center">
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Մոռացե՞լ եք գաղտտնաբառը
                                </a>
                            </div>
                        </div>
                        <hr>
                        <!-- <div class="form-group m-tp">
                            <div class="login_width text-center">
                                <a href="{{ url('/auth/twitter') }}" class="icon-button twitter"><i class="icon-twitter fa fa-twitter"></i><span></span></a>
                                <a href="{{ url('/auth/facebook') }}" class="icon-button facebook"><i class="icon-facebook fa fa-facebook"></i><span></span></a>
                                <a href="{{ url('/auth/google') }}" class="icon-button google-plus"><i class="icon-google-plus fa fa-google"></i><span></span></a>
                                <a href="{{ url('/auth/linkedin') }}" class="icon-button linkedin"><i class="icon-linkedin fa fa-linkedin"></i><span></span></a>
                            </div>
                        </div> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
