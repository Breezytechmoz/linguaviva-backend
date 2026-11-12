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
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        /* Micro-animação para os links da sidebar */
        .nav-item { transition: all 0.2s ease-in-out; }
    </style>
</head>
<body class="h-screen flex overflow-hidden text-slate-700">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-2xl hidden md:flex z-20">
        <div class="h-20 flex items-center px-6 border-b border-slate-800 gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-9 h-9 rounded-xl object-cover shadow-sm bg-white p-0.5">
            <h1 class="text-lg font-bold tracking-tight text-white">LínguaViva <span class="text-green-400">Admin</span></h1>
        </div>
        
        <nav class="flex-1 py-6 px-3 space-y-1">
            <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Menu Principal</p>
            <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-green-500/10 text-green-400 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i class="fa-solid fa-chart-pie w-6 text-center"></i>
                <span class="ml-2">Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}" class="nav-item flex items-center px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.users') ? 'bg-green-500/10 text-green-400 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i class="fa-solid fa-users w-6 text-center"></i>
                <span class="ml-2">Utilizadores</span>
            </a>
            <a href="{{ route('admin.payments') }}" class="nav-item flex items-center px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.payments') ? 'bg-green-500/10 text-green-400 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i class="fa-solid fa-credit-card w-6 text-center"></i>
                <span class="ml-2">Pagamentos</span>
            </a>
            <a href="{{ route('admin.moderation') }}" class="nav-item flex items-center px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.moderation') ? 'bg-green-500/10 text-green-400 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i class="fa-solid fa-shield-halved w-6 text-center"></i>
                <span class="ml-2">Moderação</span>
            </a>
        </nav>
        
        <div class="p-4 border-t border-slate-800">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-red-600/90 text-slate-300 hover:text-white py-2.5 rounded-xl transition-all border border-slate-700 hover:border-red-500 font-medium text-sm">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Terminar Sessão
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto relative">
        <!-- TOP BAR -->
        <header class="bg-white/90 backdrop-blur-md h-[72px] shadow-sm flex items-center justify-between px-8 sticky top-0 z-10 border-b border-slate-200">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-bold text-slate-800">@yield('title', 'Visão Geral')</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Barra de Pesquisa Falsa (Visual) -->
                <div class="hidden md:flex items-center bg-slate-100 rounded-full px-4 py-2 w-64 border border-transparent focus-within:border-green-500 focus-within:bg-white transition-all">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Pesquisar no painel..." class="bg-transparent border-none outline-none text-sm ml-2 w-full text-slate-700 placeholder-slate-400">
                </div>
                
                <!-- Ícone de Notificações -->
                <button class="relative text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                </button>

                <div class="h-8 w-px bg-slate-200"></div>

                <!-- Perfil -->
                <div class="flex items-center gap-3 cursor-pointer">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-green-600 font-bold uppercase tracking-wider">Administrador</p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-tr from-green-600 to-green-400 rounded-full flex items-center justify-center text-white font-bold shadow-md ring-2 ring-white">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8 max-w-[1400px] mx-auto w-full">
            @yield('content')
        </div>
    </main>
</body>
</html>