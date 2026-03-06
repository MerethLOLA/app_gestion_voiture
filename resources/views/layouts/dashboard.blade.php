<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="dashboard-shell">
    <div class="bg-grid"></div>
    <button type="button" class="sidebar-overlay" data-sidebar-close aria-label="Fermer le menu"></button>
    <aside class="sidebar" data-sidebar>
        <div>
            <div class="brand">
                <div class="brand-dot"></div>
                <div>
                    <p class="brand-title">AutoFlow</p>
                    <p class="brand-subtitle">Gestion de parc automobile</p>
                </div>
            </div>

            <nav class="menu">
                <p class="menu-label">Navigation</p>
                <a href="{{ route('dashboard') }}" class="menu-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    Tableau de bord
                </a>

                @foreach($modules as $slug => $module)
                    @php($moduleHref = $slug === 'employes' ? route('employes.index') : route('module.show', $slug))
                    @php($isModuleActive = $slug === 'employes' ? request()->routeIs('employes.*') : request()->is('gestion/' . $slug))
                    <a href="{{ $moduleHref }}" class="menu-link {{ $isModuleActive ? 'is-active' : '' }}">
                        {{ $module['title'] }}
                    </a>
                @endforeach
            </nav>
        </div>
        <div class="sidebar-foot">
            <p>Suivi en temps reel des ventes, clients et operations atelier.</p>
        </div>
    </aside>

    <main class="content">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="menu-toggle" data-sidebar-toggle aria-label="Ouvrir le menu">
                    Menu
                </button>
                <div>
                    <p class="muted">Plateforme de gestion</p>
                    <h1>@yield('page_title', 'Dashboard')</h1>
                </div>
            </div>
            <div class="topbar-right">
                <div class="status-chip">
                    <span class="pulse"></span>
                    Systeme actif
                </div>
                <div class="user-box">
                    <span>{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">Deconnexion</button>
                    </form>
                </div>
            </div>
        </header>

        @yield('content')
    </main>
</body>
</html>
