<h2>Sign in</h2>

<p>Using bMOOC for the first time? <a href="auth/register" class="emphasis" data-reveal-id="signup" data-reveal-ajax="true">Create an account</a></p>

@if (count($errors))
    @foreach($errors->all() as $error)
        <small class="error">{{ $error }}</small>
    @endforeach
@endif

{!! Form::open(array('data-abide', 'url'=>'/auth/login','method'=>'POST')) !!}
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
        <input type="checkbox" name="remember">
    </label>

<button type="submit" class="full purple">Sign in</button>
{!! Form::close() !!}

<p><small>Problems signing in? <a href="#" class="emphasis" data-reveal-id='feedback'>Send us a message</a></small></p>

<a class="close-reveal-modal close" aria-label="Close">&#215;</a>
