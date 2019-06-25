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


{{--<v-card>--}}
    {{--<v-toolbar dark color="primary">--}}
        {{--<v-toolbar-title>Test</v-toolbar-title>--}}
    {{--</v-toolbar>--}}
    {{--<v-spacer></v-spacer>--}}
    {{--<v-card-text>--}}
        {{--<v-form method="POST" action="{{ route('login') }}">--}}
            {{--@csrf--}}
            {{--<v-text-field prepend-icon="person" name="email" value="{{ old('email') }}" label="Login" type="text"></v-text-field>--}}
            {{--@if ($errors->has('email'))--}}
                {{--<span class="invalid-feedback" role="alert">--}}
                    {{--<strong>{{ $errors->first('email') }}</strong>--}}
                {{--</span>--}}
            {{--@endif--}}
            {{--<v-text-field id="password" prepend-icon="lock" name="password" label="Password" type="password"></v-text-field>--}}
            {{--@if ($errors->has('password'))--}}
                {{--<span class="invalid-feedback" role="alert">--}}
                    {{--<strong>{{ $errors->first('password') }}</strong>--}}
                {{--</span>--}}
            {{--@endif--}}


            {{--<v-card-actions>--}}
                {{--<v-spacer></v-spacer>--}}
                {{--<v-btn color="primary" onclick="this.form.submit();">ログイン</v-btn>--}}
                {{--<v-btn onclick="location.href='{{ route('password.request') }}'">パスワードを忘れた方はこちら</v-btn>--}}
            {{--</v-card-actions>--}}
        {{--</v-form>--}}
    {{--</v-card-text>--}}
{{--</v-card>--}}

@endsection
