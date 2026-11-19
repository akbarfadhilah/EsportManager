<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Esports Manager')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav>
        <div class="container">
            <div class="logo">Esports Wiki</div>
            <ul>
                <li><a href="{{ route('players.index') }}" class="nav-link {{ request()->routeIs('players.*') ? 'active' : '' }}">Personnel</a></li>
                <li><a href="{{ route('teams.index') }}" class="nav-link {{ request()->routeIs('teams.*') ? 'active' : '' }}">Teams</a></li>
                <li><a href="{{ route('regions.index') }}" class="nav-link {{ request()->routeIs('regions.*') ? 'active' : '' }}">Regions</a></li>
                <li><a href="{{ route('heroes.index') }}" class="nav-link {{ request()->routeIs('heroes.*') ? 'active' : '' }}">Heroes</a></li>
                <li><a href="{{ route('tournaments.index') }}" class="nav-link {{ request()->routeIs('tournaments.*') || request()->routeIs('matches.*') || request()->routeIs('leaderboard.*') ? 'active' : '' }}">Tournaments</a></li>
            </ul>
        </div>
    </nav>

    <div class="main-container">
        @if(session('success'))
            <div class="alert alert-success">
                <strong>✓</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <strong>✗</strong> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <strong>✗</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>