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
    <nav class="app-nav">
        <div class="container">
            <a href="{{ route('players.index') }}" class="brand">
                <img src="{{ asset('Image/logomobamania.png') }}" alt="Moba Mania Logo" class="brand-logo">
                <span class="brand-text">Moba Mania<span>Wiki</span><small>COMMAND CENTER</small></span>
            </a>
            <ul class="nav-menu">
                <li>
                    <a href="{{ route('players.index') }}" class="nav-link {{ request()->routeIs('players.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Personnel</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('teams.index') }}" class="nav-link {{ request()->routeIs('teams.*') ? 'active' : '' }}">
                        <i class="fas fa-shield-halved"></i>
                        <span>Teams</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('regions.index') }}" class="nav-link {{ request()->routeIs('regions.*') ? 'active' : '' }}">
                        <i class="fas fa-globe"></i>
                        <span>Regions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('heroes.index') }}" class="nav-link {{ request()->routeIs('heroes.*') ? 'active' : '' }}">
                        <i class="fas fa-sword"></i>
                        <span>Heroes</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tournaments.index') }}" class="nav-link {{ request()->routeIs('tournaments.*') || request()->routeIs('matches.*') || request()->routeIs('leaderboard.*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i>
                        <span>Tournaments</span>
                    </a>
                </li>
            </ul>
            <div class="nav-side">
                <span class="nav-live"><span class="live-dot"></span> System Online</span>
            </div>
        </div>
    </nav>

    <div class="main-container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <script>
        (() => {
            const nav = document.querySelector('nav.app-nav');
            if (!nav) return;

            const onScroll = () => {
                if (window.scrollY > 10) {
                    nav.classList.add('is-scrolled');
                } else {
                    nav.classList.remove('is-scrolled');
                }
            };

            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll(); // jalankan sekali saat load
        })();
    </script>
    
</body>
</html>