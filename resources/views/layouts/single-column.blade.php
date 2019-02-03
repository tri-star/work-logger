<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Work Logger</title>

    <!-- Scripts -->
    <script src="{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ mix('js/vendor.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}" defer></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

</head>
<body id="{{-- TODO: ここにページ毎のID --}}">
<div id="app">
    <v-app>
        <v-toolbar app></v-toolbar>
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-content-center justify-center>
                    <v-flex xs12 md4>
                        @yield('content')
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
        <v-footer app></v-footer>
    </v-app>
</div>

</body>
</html>
