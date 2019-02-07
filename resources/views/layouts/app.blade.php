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
        <v-toolbar app dark color="primary">
            <v-toolbar-title>Work Logger</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon><v-icon>account_box</v-icon></v-btn>
        </v-toolbar>
        <v-navigation-drawer app :mini-variant="mini">
            <v-list>
                <v-list-tile v-if="mini" @click="mini = !mini">
                    <v-list-tile-action>
                        <v-icon>chevron_right</v-icon>
                    </v-list-tile-action>
                </v-list-tile>
                <v-list-tile  v-if="!mini" @click="mini = !mini">
                    <v-list-tile-action>
                        <v-icon>chevron_left</v-icon>
                    </v-list-tile-action>
                </v-list-tile>

            </v-list>
            <v-list>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-icon>event_note</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>タスク一覧</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>

        </v-navigation-drawer>
        <v-content>
            <v-container fluid>
                @yield('content')
            </v-container>
        </v-content>
        <v-footer app></v-footer>
    </v-app>

</div>
</body>
</html>
