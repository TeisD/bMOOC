@extends('master_simple')
@section('content')
<div class="row">
       <div class="columns large-4 large-push-4 medium-10 medium-push-1">
       <h2>Create an account</h2>
{!! Form::open(array('data-abide', 'url'=>'/register','method'=>'POST')) !!}

<div>
    <label>
        Name:
        <input type="text" required name="name" value="{{ old('name') }}">
    </label>
    <small class="error">Please enter your name.</small>
    @if ($errors->has('name'))
        <div class="laravel_error">
            {{ $errors->first('name') }}
        </div>
    @endif
</div>
<div>
    <label>
        Email:
        <input type="email" required name="email" value="{{ old('email') }}">
    </label>
    <small class="error">Please enter a valid e-mail address.</small>
    @if ($errors->has('email'))
        <div class="laravel_error">
            {{ $errors->first('email') }}
        </div>
    @endif
</div>
<div>
    <label>
        Password:
        <input type="password" minlength="6" id="pwd" required name="password" pattern="^(.){6,}$">
    </label>
    <small class="error">Please enter a password (at least 6 characters).</small>
    @if ($errors->has('password'))
        <div class="laravel_error">
            {{ $errors->first('password') }}
        </div>
    @endif
</div>
<div>
    <label>
        Confirm Password:
        <input type="password" required data-equalto="pwd" name="password_confirmation">
    </label>
    <small class="error">The passwords do not match.</small>
    @if ($errors->has('password_confirmation'))
        <div class="laravel_error">
           {{ $errors->first('password_confirmation') }}
        </div>
    @endif
</div>
           <button type="submit" class="full purple">Create account</button>

{!! Form::close() !!}

<p><small>Problems creating an account? <a href="#" class="emphasis" data-reveal-id='feedback'>Send us a message</a></small></p>

    </div>
</div>

@endsection
