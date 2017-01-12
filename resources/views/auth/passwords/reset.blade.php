@extends('master_simple')

<!-- Main Content -->
@section('content')
<div class="row">
       <div class="columns large-4 large-push-4 medium-10 medium-push-1">
           <h2>Reset password</h2>

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}">

                        @if ($errors->has('email'))
                            <div class="laravel_error">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-4 control-label">Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password">

                        @if ($errors->has('password'))
                            <div class="laravel_error">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                        @if ($errors->has('password_confirmation'))
                            <div class="laravel_error">
                                {{ $errors->first('password_confirmation') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-refresh"></i> Reset Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
