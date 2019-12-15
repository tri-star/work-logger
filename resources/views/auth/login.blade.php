@extends('layouts.guest-home')

@section('page-title')
[ログイン]
@stop

@section('content')

        <div class="logo"></div>
            <form action="{{ route('login')  }}" method="POST" class="frame">
                @csrf

                <div class="row">
                    <div class="col-header">
                        <label>LOGIN ID: </label>
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
                        <label>PASSWORD: </label>
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
                        <label for="remember"><input type="checkbox" name="remember" id="remember" value="1"/>ID、パスワードを保存</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <button class="button">LOGIN</button>
                    </div>
                </div>

            </form>
        </div>

@endsection
