<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - LínguaViva Moz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .nav-item { transition: all 0.2s ease-in-out; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-screen flex overflow-hidden text-slate-700" x-data="adminNotifications()" x-init="init()">

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
                    <a href="{{ route('admin.applications') }}" class="nav-item flex items-center px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.applications') ? 'bg-green-500/10 text-green-400 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i class="fa-solid fa-user-tie w-6 text-center"></i>
                <span class="ml-2">Candidaturas</span>
            </a>
        
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
        <header class="bg-white/90 backdrop-blur-md h-[72px] shadow-sm flex items-center justify-between px-8 sticky top-0 z-50 border-b border-slate-200">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-bold text-slate-800">@yield('title', 'Visão Geral')</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Barra de Pesquisa -->
                <div class="hidden md:flex items-center bg-slate-100 rounded-full px-4 py-2 w-64 border border-transparent focus-within:border-green-500 focus-within:bg-white transition-all">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Pesquisar no painel..." class="bg-transparent border-none outline-none text-sm ml-2 w-full text-slate-700 placeholder-slate-400">
                </div>
                
                <!-- SINO DE NOTIFICAÇÕES COM DROPDOWN -->
                <div class="relative" @click.away="open = false">
                    <button @click="open = !open" class="relative text-slate-400 hover:text-slate-600 transition-colors mt-1">
                        <i class="fa-regular fa-bell text-xl"></i>
                        <span x-show="unreadTotal > 0" x-text="unreadTotal" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white animate-pulse" x-cloak></span>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" x-transition.opacity x-cloak class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">
                        <div class="bg-slate-50 px-4 py-3 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-bold text-slate-800 text-sm">Notificações</h3>
                            <span class="text-xs font-bold text-slate-500" x-text="unreadTotal + ' não lidas'"></span>
                        </div>
                        
                        <!-- Categorias Rápidas -->
                        <div class="grid grid-cols-4 gap-1 p-2 bg-white border-b border-slate-100">
                            <div class="text-center p-2 rounded-lg hover:bg-slate-50 cursor-pointer">
                                <i class="fa-solid fa-users text-blue-500 mb-1"></i>
                                <span class="block text-xs font-bold text-slate-700" x-text="categories.user"></span>
                                <span class="block text-[10px] text-slate-400">Users</span>
                            </div>
                            <div class="text-center p-2 rounded-lg hover:bg-slate-50 cursor-pointer">
                                <i class="fa-solid fa-credit-card text-orange-500 mb-1"></i>
                                <span class="block text-xs font-bold text-slate-700" x-text="categories.payment"></span>
                                <span class="block text-[10px] text-slate-400">Pagam.</span>
                            </div>
                            <div class="text-center p-2 rounded-lg hover:bg-slate-50 cursor-pointer">
                                <i class="fa-solid fa-shield-halved text-red-500 mb-1"></i>
                                <span class="block text-xs font-bold text-slate-700" x-text="categories.report"></span>
                                <span class="block text-[10px] text-slate-400">Denún.</span>
                            </div>
                            <div class="text-center p-2 rounded-lg hover:bg-slate-50 cursor-pointer">
                                <i class="fa-solid fa-user-tie text-indigo-500 mb-1"></i>
                                <span class="block text-xs font-bold text-slate-700" x-text="categories.translator"></span>
                                <span class="block text-[10px] text-slate-400">Trad.</span>
                            </div>
                        </div>

                        <!-- Lista de Notificações -->
                        <div class="max-h-64 overflow-y-auto">
                            <template x-for="notif in notifications" :key="notif.id">
                                <div @click="markRead(notif)" class="p-3 border-b border-slate-50 hover:bg-slate-50 cursor-pointer flex gap-3 transition-colors" :class="notif.is_read ? 'opacity-60' : ''">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0"
                                         :class="{
                                            'bg-blue-100 text-blue-600': notif.type === 'user',
                                            'bg-orange-100 text-orange-600': notif.type === 'payment',
                                            'bg-red-100 text-red-600': notif.type === 'report',
                                            'bg-indigo-100 text-indigo-600': notif.type === 'translator'
                                         }">
                                         <i class="fa-solid" :class="{
                                            'fa-user': notif.type === 'user',
                                            'fa-money-bill': notif.type === 'payment',
                                            'fa-triangle-exclamation': notif.type === 'report',
                                            'fa-user-tie': notif.type === 'translator'
                                         }"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800" x-text="notif.title"></p>
                                        <p class="text-xs text-slate-500 mt-0.5" x-text="notif.message"></p>
                                        <p class="text-[10px] text-slate-400 mt-1" x-text="new Date(notif.created_at).toLocaleTimeString()"></p>
                                    </div>
                                </div>
                            </template>
                            <div x-show="notifications.length === 0" class="p-6 text-center text-slate-400 text-sm">
                                Sem notificações.
                            </div>
                        </div>
                    </div>
                </div>

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

    <!-- SCRIPT DE LÓGICA DO SINO -->
    <script>
        // Colocamos um pequeno bip em Base64 para garantir que toca sem precisarmos de ficheiros externos
        const notifSound = new Audio('data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU'+'123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890');

        function adminNotifications() {
            return {
                open: false,
                notifications: [],
                unreadTotal: 0,
                categories: { user: 0, payment: 0, report: 0, translator: 0 },
                previousCount: -1, // Para saber quando tocar o som

                init() {
                    this.fetchNotifications();
                    // Atualiza a cada 10 segundos
                    setInterval(() => this.fetchNotifications(), 10000); 
                },

                fetchNotifications() {
                    fetch('/admin/notifications', {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Se a contagem total subiu, toca o som
                        if (this.previousCount !== -1 && data.unreadTotal > this.previousCount) {
                            this.playSound();
                        }
                        
                        this.notifications = data.notifications;
                        this.unreadTotal = data.unreadTotal;
                        this.categories = data.categories;
                        this.previousCount = data.unreadTotal;
                    })
                    .catch(err => console.error("Erro nas notificações:", err));
                },

                markRead(notif) {
                    if (notif.is_read) return; // Se já estiver lida, não faz nada

                    fetch(`/admin/notifications/${notif.id}/read`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    })
                    .then(() => {
                        // Atualiza o estado local imediatamente
                        notif.is_read = true;
                        this.unreadTotal--;
                        this.categories[notif.type]--;
                        this.previousCount = this.unreadTotal;
                        
                        // Navega se tiver URL
                        if (notif.url) {
                            window.location.href = notif.url;
                        }
                    });
                },

                playSound() {
                    notifSound.volume = 0.5;
                    notifSound.play().catch(e => console.log("O browser bloqueou o som até haver interação."));
                }
            }
        }
    </script>
</body>
</html>