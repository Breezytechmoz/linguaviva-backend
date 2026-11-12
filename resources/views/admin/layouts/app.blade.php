<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - LínguaViva Moz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 h-screen flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-2xl hidden md:flex z-20">
        <div class="h-20 flex items-center justify-center border-b border-slate-800 gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover">
            <h1 class="text-xl font-bold tracking-wider">LínguaViva</h1>
        </div>
        <nav class="flex-1 py-6 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3.5 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 border-l-4 border-green-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800 transition-colors' }}">
                <i class="fa-solid fa-chart-pie w-6 {{ request()->routeIs('admin.dashboard') ? 'text-green-400' : '' }}"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center px-6 py-3.5 {{ request()->routeIs('admin.users') ? 'bg-slate-800 border-l-4 border-green-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800 transition-colors' }}">
                <i class="fa-solid fa-users w-6 {{ request()->routeIs('admin.users') ? 'text-green-400' : '' }}"></i>
                <span class="font-medium">Utilizadores</span>
            </a>
            <a href="{{ route('admin.payments') }}" class="flex items-center px-6 py-3.5 {{ request()->routeIs('admin.payments') ? 'bg-slate-800 border-l-4 border-green-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800 transition-colors' }}">
                <i class="fa-solid fa-credit-card w-6 {{ request()->routeIs('admin.payments') ? 'text-green-400' : '' }}"></i>
                <span class="font-medium">Pagamentos</span>
            </a>
            <a href="{{ route('admin.moderation') }}" class="flex items-center px-6 py-3.5 {{ request()->routeIs('admin.moderation') ? 'bg-slate-800 border-l-4 border-green-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800 transition-colors' }}">
                <i class="fa-solid fa-shield-halved w-6 {{ request()->routeIs('admin.moderation') ? 'text-green-400' : '' }}"></i>
                <span class="font-medium">Moderação</span>
            </a>
        </nav>
        <div class="p-4 border-t border-slate-800">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-red-600 hover:border-red-600 text-slate-300 hover:text-white py-3 rounded-xl transition-all border border-slate-700">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Terminar Sessão
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto relative">
        <!-- TOP BAR -->
        <header class="bg-white/80 backdrop-blur-md h-20 shadow-sm flex items-center justify-between px-8 sticky top-0 z-10 border-b border-slate-200">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-bold text-slate-800">@yield('title', 'Visão Geral')</h2>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-green-600 font-semibold bg-green-50 px-2 py-0.5 rounded-full inline-block mt-1">Administrador</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="p-8 space-y-8 max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>
</body>
</html>