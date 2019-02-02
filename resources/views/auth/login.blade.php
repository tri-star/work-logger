@extends('layouts.single-column')

@section('page-title')
[ログイン]
@stop

@section('content')

<v-card>
    <v-toolbar dark color="primary">
        <v-toolbar-title>Test</v-toolbar-title>
    </v-toolbar>
    <v-spacer></v-spacer>
    <v-card-text>
        <v-form method="POST" action="{{ route('login') }}">
            @csrf
            <v-text-field prepend-icon="person" name="email" value="{{ old('email') }}" label="Login" type="text"></v-text-field>
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            <v-text-field id="password" prepend-icon="lock" name="password" label="Password" type="password"></v-text-field>
            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif

            <v-checkbox name="remember" label="ID、パスワードを保存" value="1"></v-checkbox>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" onclick="this.form.submit();">ログイン</v-btn>
                <v-btn onclick="location.href='{{ route('password.request') }}'">パスワードを忘れた方はこちら</v-btn>
            </v-card-actions>
        </v-form>
    </v-card-text>
</v-card>

@endsection
