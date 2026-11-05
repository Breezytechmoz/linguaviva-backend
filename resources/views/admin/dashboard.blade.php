<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - LínguaViva Moz</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome (Ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans text-slate-800 h-screen flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-xl hidden md:flex">
        <div class="h-16 flex items-center justify-center border-b border-slate-800">
            <i class="fa-solid fa-language text-2xl text-green-500 mr-2"></i>
            <h1 class="text-xl font-bold tracking-wider">LínguaViva</h1>
        </div>
        <nav class="flex-1 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 bg-slate-800 border-l-4 border-green-500 text-white">
                <i class="fa-solid fa-chart-pie w-6"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <!-- Adicionar links fictícios para o design parecer completo -->
            <a href="#" class="flex items-center px-6 py-3 text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-users w-6"></i>
                <span class="font-medium">Utilizadores</span>
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-credit-card w-6"></i>
                <span class="font-medium">Pagamentos</span>
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-shield-halved w-6"></i>
                <span class="font-medium">Moderação</span>
            </a>
        </nav>
        <div class="p-4 border-t border-slate-800">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-red-600 text-white py-2 rounded-lg transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Terminar Sessão
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        
        <!-- TOP BAR -->
        <header class="bg-white h-16 shadow-sm flex items-center justify-between px-8 sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-semibold text-slate-800">Visão Geral</h2>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">Administrador</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold border border-green-200">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="p-8 space-y-8 max-w-7xl mx-auto w-full">

            <!-- STATS CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Total de Utilizadores</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $totalUsers }}</p>
                    </div>
                </div>
                <!-- Total Messages -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Total de Mensagens</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $totalMessages }}</p>
                    </div>
                </div>
                <!-- Payments Pending -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Pagamentos Pendentes</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $pagamentosPendentes->count() }}</p>
                    </div>
                </div>
                <!-- Reports Pending -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Denúncias</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $denuncias->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- DENÚNCIAS -->
            @if($denuncias->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
                    <i class="fa-solid fa-shield-halved text-red-500 text-lg"></i>
                    <h3 class="text-lg font-bold text-slate-800">Denúncias de Utilizadores</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Quem Denunciou</th>
                                <th class="px-6 py-4 font-semibold">Denunciado</th>
                                <th class="px-6 py-4 font-semibold">Motivo</th>
                                <th class="px-6 py-4 font-semibold">Detalhes</th>
                                <th class="px-6 py-4 font-semibold">Anexo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($denuncias as $denuncia)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-700">{{ $denuncia->reporter->name }}</td>
                                <td class="px-6 py-4 font-bold text-red-600">{{ $denuncia->reported->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">{{ $denuncia->reason }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 max-w-xs truncate">{{ $denuncia->details ?? 'Sem detalhes' }}</td>
                                <td class="px-6 py-4">
                                    @if($denuncia->attachment)
                                        <a href="{{ $denuncia->attachment }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                            <i class="fa-solid fa-image"></i> Ver Prova
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- PAGAMENTOS PENDENTES -->
            @if($pagamentosPendentes->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
                    <i class="fa-solid fa-money-check-dollar text-orange-500 text-lg"></i>
                    <h3 class="text-lg font-bold text-slate-800">Pedidos de Pagamento (Premium)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Utilizador</th>
                                <th class="px-6 py-4 font-semibold">Método</th>
                                <th class="px-6 py-4 font-semibold">Código Transação</th>
                                <th class="px-6 py-4 font-semibold text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($pagamentosPendentes as $pagamento)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-700">{{ $pagamento->user->name }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-700">{{ strtoupper($pagamento->method) }}</td>
                                <td class="px-6 py-4 font-mono text-slate-600 bg-slate-50 rounded">{{ $pagamento->transaction_reference }}</td>
                                <td class="px-6 py-4 flex justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.handle-payment', $pagamento->id) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition-colors flex items-center gap-2">
                                            <i class="fa-solid fa-check"></i> Aprovar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.handle-payment', $pagamento->id) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg text-xs font-bold transition-colors flex items-center gap-2">
                                            <i class="fa-solid fa-xmark"></i> Rejeitar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

                    <!-- Secção de Candidaturas a Tradutor -->
        @if($candidaturas->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
                <i class="fa-solid fa-user-tie text-blue-500 text-lg"></i>
                <h3 class="text-lg font-bold text-slate-800">Candidaturas a Tradutor</h3>
            </div>
            <table class="w-full text-left">
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($candidaturas as $cand)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold">{{ $cand->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $cand->language_requested }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $cand->experience ?? 'Sem experiência descrita' }}</td>
                                            <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold">{{ $cand->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $cand->language_requested }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $cand->experience ?? 'Sem experiência' }}</td>
                        <!-- NOVA COLUNA DO VÍDEO -->
                        <td class="px-6 py-4">
                            @if($cand->video_path)
                                <a href="{{ $cand->video_path }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                    <i class="fa-solid fa-play"></i> Ver Vídeo
                                </a>
                            @else
                                <span class="text-slate-400">Sem vídeo</span>
                            @endif
                        </td>
                        <!-- ... botões de acção ... -->
                        <td class="px-6 py-4 text-right flex gap-2 justify-end">
                            <form method="POST" action="{{ route('admin.approve-translator', [$cand->id, 'approve']) }}">
                                @csrf
                                <button class="bg-green-500 text-white px-3 py-1 rounded text-xs font-bold hover:bg-green-600">Aprovar</button>
                            </form>
                            <form method="POST" action="{{ route('admin.approve-translator', [$cand->id, 'reject']) }}">
                                @csrf
                                <button class="bg-slate-200 text-slate-700 px-3 py-1 rounded text-xs font-bold hover:bg-slate-300">Rejeitar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Secção de Traduções Pendentes -->
        @if($traducoesPendentes->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
                <i class="fa-solid fa-language text-amber-500 text-lg"></i>
                <h3 class="text-lg font-bold text-slate-800">Traduções Pendentes (0.50 MT / Palavra)</h3>
            </div>
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Tradutor</th>
                        <th class="px-6 py-4 font-semibold">Língua</th>
                        <th class="px-6 py-4 font-semibold">Português</th>
                        <th class="px-6 py-4 font-semibold">Tradução</th>
                        <th class="px-6 py-4 font-semibold text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($traducoesPendentes as $trad)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium">{{ $trad->translator->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $trad->language->name }}</td>
                        <td class="px-6 py-4">{{ $trad->source_word }}</td>
                        <td class="px-6 py-4 font-bold text-green-700">{{ $trad->translated_word }}</td>
                        <td class="px-6 py-4 text-right flex gap-2 justify-end">
                            <form method="POST" action="{{ route('admin.handle-translation', [$trad->id, 'approve']) }}">
                                @csrf
                                <button class="bg-green-500 text-white px-3 py-1 rounded text-xs font-bold hover:bg-green-600">Aprovar e Pagar</button>
                            </form>
                            <form method="POST" action="{{ route('admin.handle-translation', [$trad->id, 'reject']) }}">
                                @csrf
                                <button class="bg-slate-200 text-slate-700 px-3 py-1 rounded text-xs font-bold hover:bg-slate-300">Rejeitar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

            <!-- GESTÃO DE UTILIZADORES -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
                    <i class="fa-solid fa-users-gear text-slate-500 text-lg"></i>
                    <h3 class="text-lg font-bold text-slate-800">Gestão de Utilizadores</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Utilizador</th>
                                <th class="px-6 py-4 font-semibold">Data de Registo</th>
                                <th class="px-6 py-4 font-semibold">Estado</th>
                                <th class="px-6 py-4 font-semibold text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($users as $user)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    @if($user->is_admin)
                                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-crown mr-1"></i> Admin</span>
                                    @elseif($user->is_suspended)
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-ban mr-1"></i> Suspenso</span>
                                    @else
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-check mr-1"></i> Ativo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if(!$user->is_admin)
                                        <form method="POST" action="{{ route('admin.toggle-suspend', $user->id) }}">
                                            @csrf
                                            <button type="submit" class="text-sm font-semibold {{ $user->is_suspended ? 'text-green-600 hover:text-green-800' : 'text-red-500 hover:text-red-700' }} transition-colors">
                                                {{ $user->is_suspended ? 'Reativar Conta' : 'Suspender Conta' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</body>
</html>