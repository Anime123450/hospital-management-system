<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="htmlRoot">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MediCare HMS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    {{-- Anti-FOUC: restore theme before any CSS renders --}}
    <script>
        (function() {
            const t = localStorage.getItem('hms_theme');
            if (t === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="hms-body">

<div class="hms-layout" id="hmsLayout">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="hms-sidebar">
        <div class="hms-logo">
            <div class="hms-logo-icon"><i class="fa-solid fa-hospital-user"></i></div>
            <div class="hms-logo-wrap">
                <div class="hms-logo-name">MediCare HMS</div>
                <div class="hms-logo-tagline">Hospital Management</div>
            </div>
        </div>

        <nav class="hms-nav">
            <div class="hms-nav-section">Main</div>

            <a href="{{ route('dashboard') }}"
               class="hms-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               data-label="Dashboard">
                <i class="fa-solid fa-gauge-high"></i>
                <span class="hms-nav-text">Dashboard</span>
            </a>

            <a href="{{ route('patients.index') }}"
               class="hms-nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}"
               data-label="Patients">
                <i class="fa-solid fa-users"></i>
                <span class="hms-nav-text">Patients</span>
            </a>

            <a href="{{ route('appointments.index') }}"
               class="hms-nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}"
               data-label="Appointments">
                <i class="fa-solid fa-calendar-days"></i>
                <span class="hms-nav-text">Appointments</span>
            </a>

            <a href="{{ route('doctors.index') }}"
               class="hms-nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}"
               data-label="Doctors">
                <i class="fa-solid fa-user-doctor"></i>
                <span class="hms-nav-text">Doctors</span>
            </a>

            <div class="hms-nav-section">Administration</div>

            <a href="{{ route('beds.index') }}"
               class="hms-nav-link {{ request()->routeIs('beds.*') ? 'active' : '' }}"
               data-label="Wards & Beds">
                <i class="fa-solid fa-bed"></i>
                <span class="hms-nav-text">Wards &amp; Beds</span>
            </a>

            <a href="{{ route('bills.index') }}"
               class="hms-nav-link {{ request()->routeIs('bills.*') ? 'active' : '' }}"
               data-label="Billing">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span class="hms-nav-text">Billing</span>
            </a>
        </nav>

        <div class="hms-sidebar-footer">
            <div class="hms-sidebar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            <div class="hms-sidebar-user-wrap">
                <div class="hms-sidebar-username">{{ Auth::user()->name }}</div>
                <div class="hms-sidebar-role">Administrator</div>
            </div>
        </div>
    </aside>

    {{-- ═══ MAIN ═══ --}}
    <div class="hms-main">

        {{-- Topbar --}}
        <header class="hms-topbar">
            <div class="hms-topbar-left">
                <button class="hms-icon-btn" onclick="toggleSidebar()" title="Toggle sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div style="font-size:13px;color:var(--text-muted);">
                    <i class="fa-regular fa-calendar" style="margin-right:5px"></i>
                    {{ now()->format('l, d F Y') }}
                </div>
            </div>
            <div class="hms-topbar-right">
                <button class="hms-icon-btn" id="themeToggle" onclick="toggleTheme()" title="Toggle dark mode">
                    <i id="themeIcon" class="fa-solid fa-moon"></i>
                </button>
                <div class="hms-icon-btn" style="position:relative" title="Notifications">
                    <i class="fa-solid fa-bell"></i>
                    <span class="hms-notif-dot"></span>
                </div>
                <div class="hms-topbar-avatar" title="{{ Auth::user()->name }}">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" class="hms-icon-btn" title="Log out">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </header>

        {{-- Optional page header (title + action button) --}}
        @isset($header)
            <div class="hms-page-header">
                {{ $header }}
            </div>
        @endisset

        {{-- Page content --}}
        <main class="hms-content">
            {{ $slot }}
        </main>
    </div>

</div>

@stack('scripts')

<script>
/* ── Sidebar collapse ── */
function toggleSidebar() {
    const el = document.getElementById('hmsLayout');
    el.classList.toggle('sidebar-mini');
    localStorage.setItem('hms_mini', el.classList.contains('sidebar-mini') ? '1' : '0');
}
(function() {
    if (localStorage.getItem('hms_mini') === '1') {
        const el = document.getElementById('hmsLayout');
        if (el) el.classList.add('sidebar-mini');
    }
})();

/* ── Dark mode toggle ── */
function toggleTheme() {
    const html = document.getElementById('htmlRoot');
    const icon = document.getElementById('themeIcon');
    const isDark = html.getAttribute('data-theme') === 'dark';

    if (isDark) {
        html.removeAttribute('data-theme');
        html.classList.remove('dark');
        icon.className = 'fa-solid fa-moon';
        localStorage.setItem('hms_theme', 'light');
    } else {
        html.setAttribute('data-theme', 'dark');
        html.classList.add('dark');
        icon.className = 'fa-solid fa-sun';
        localStorage.setItem('hms_theme', 'dark');
    }
}

/* ── Sync icon on load ── */
(function() {
    if (localStorage.getItem('hms_theme') === 'dark') {
        const icon = document.getElementById('themeIcon');
        if (icon) icon.className = 'fa-solid fa-sun';
    }
})();
</script>
</body>
</html>
