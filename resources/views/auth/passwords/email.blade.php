@extends('master_simple')

<!-- Main Content -->
@section('content')
<div class="row">
       <div class="columns large-4 large-push-4 medium-10 medium-push-1">
           <h2>Reset password</h2>

            @if (session('status'))
                <small class="error success">
                    {{ session('status') }}
                </small>
            @endif

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email">E-Mail Address
                       <input id="email" type="email" required class="form-control" name="email" value="{{ old('email') }}">
                    </label>

                        @if ($errors->has('email'))
                            <div class="laravel_error">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
                        </button>
                    </div>
            </form>
        </div>
    </div>
@endsection
