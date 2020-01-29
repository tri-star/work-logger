@extends('layouts.guest-home')

@section('page-title')
[ログイン]
@stop

@section('content')

<h1>WORK LOGGER</h1>

<form action="{{ route('login')  }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-header">
            <label>Login ID: </label>
        </div>
        <div class="col">
            <input type="text" class="text-box" name="email" id="email">
            @if ($errors->has('email'))
                <p class="invalid-feedback" role="alert">
                    <span>{{ $errors->first('email') }}</span>
                </p>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-header">
            <label>Password: </label>
        </div>
        <div class="col">
            <input type="password" class="text-box" name="password" id="password">
            @if ($errors->has('password'))
            <p class="invalid-feedback" role="alert">
                <span>{{ $errors->first('password') }}</span>
            </p>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label for="remember"><input type="checkbox" name="remember" id="remember" value="1"/>ログイン状態を保持</label>
        </div>
    </div>
    <div class="row">
        <div class="col col-login-button">
            <button class="button-lg">Login</button>
        </div>
    </div>

</form>

@endsection
