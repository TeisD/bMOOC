@extends('master_simple')
@section('content')
<div class="row">
       <div class="columns large-4 large-push-4 medium-10 medium-push-1">
            <h2>Sign in</h2>

            <p>Using bMOOC for the first time? <a href="/register" class="emphasis">Create an account</a></p>

            @if (count($errors))
                @foreach($errors->all() as $error)
                    <small class="error">{{ $error }}</small>
                @endforeach
            @endif

            {!! Form::open(array('data-abide', 'url'=>'/login','method'=>'POST')) !!}
            <div>
                <label>Email:
                    <input type="email" required name="email" value="{{ old('email') }}">
                </label>
                <small class="error">Please enter a valid e-mail address.</small>
            </div>

            <div>
                <label>Password:
                    <input type="password" required name="password" id="password">
                </label>
                <small class="error">Please enter your password.</small>
            </div>

                <label>Remember me:
                    <input type="checkbox" class="clean" name="remember">
                </label>

                <br />

            <button type="submit" class="full purple">Sign in</button>
            {!! Form::close() !!}

            <p><small>Problems signing in? <a href="#" class="emphasis" data-reveal-id='feedback'>Send us a message</a></small></p>
    </div>
</div>

@endsection
