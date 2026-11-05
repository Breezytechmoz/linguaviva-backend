<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Tradutor - LínguaViva Moz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-slate-900 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto flex justify-between items-center px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-language text-xl text-green-400"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold leading-tight">Portal do Tradutor</h1>
                    <p class="text-xs text-slate-400">LínguaViva Moz</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden sm:block text-right">
                    <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400">Tradutor Oficial</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-10 h-10 rounded-lg bg-slate-800 hover:bg-red-500 transition-colors flex items-center justify-center text-slate-300 hover:text-white">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto p-4 md:p-6 space-y-6">
        
        <!-- Mensagens de Feedback -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-lg"></i> 
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i> 
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Cards de Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Carteira -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 text-slate-500 mb-2">
                        <i class="fa-solid fa-wallet"></i>
                        <p class="text-sm font-medium">Saldo Disponível</p>
                    </div>
                    <h2 class="text-4xl font-bold text-slate-800">{{ number_format($wallet->balance, 2) }} <span class="text-lg text-slate-500">MT</span></h2>
                </div>
                <form method="POST" action="{{ route('translator.withdraw') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-money-bill-transfer"></i> Pedir Saque
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-2">Mínimo para saque: 250 MT</p>
                </form>
            </div>
            
            <!-- Total Ganho -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-2 text-slate-500 mb-2">
                    <i class="fa-solid fa-chart-line"></i>
                    <p class="text-sm font-medium">Total Ganho</p>
                </div>
                <h2 class="text-4xl font-bold text-slate-800">{{ number_format($wallet->total_earned, 2) }} <span class="text-lg text-slate-500">MT</span></h2>
                <div class="mt-4 bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <p class="text-xs text-slate-500 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-500"></i>
                        Pagamento de <strong>0.50 MT</strong> por cada palavra aprovada.
                    </p>
                </div>
            </div>

                        <!-- Candidatar a Nova Língua -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 text-slate-500 mb-2">
                        <i class="fa-solid fa-plus-circle"></i>
                        <p class="text-sm font-medium">Nova Língua</p>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Queres traduzir mais alguma língua? Envia uma nova candidatura com um vídeo.</p>
                </div>
                <button onclick="document.getElementById('modal-apply').classList.remove('hidden')" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-file-video"></i> Enviar Candidatura
                </button>
            </div>

            <!-- Modal de Candidatura (Oculto por defeito) -->
            <div id="modal-apply" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl p-6 w-full max-w-md">
                    <h3 class="text-xl font-bold mb-4">Candidatar a Nova Língua</h3>
                    <form action="{{ route('translator.apply-new') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold mb-2">Língua</label>
                            <select name="language_id" class="w-full border rounded-xl p-2" required>
                                @foreach($allLanguages as $lang)
                                    <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-bold mb-2">Vídeo (Mínimo 1 minuto a ler um texto)</label>
                            <input type="file" name="video" accept="video/*" class="w-full border rounded-xl p-2" required>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="document.getElementById('modal-apply').classList.add('hidden')" class="px-4 py-2 bg-slate-200 rounded-lg">Cancelar</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-gradient-to-br from-green-600 to-green-800 p-6 rounded-2xl shadow-md text-white flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-3">
                    <i class="fa-solid fa-award text-3xl text-amber-300"></i>
                </div>
                <h3 class="text-xl font-bold">Tradutor Oficial</h3>
                <p class="text-sm text-green-100 mt-1">Obrigado por ajudares a preservar e expandir as línguas de Moçambique.</p>
            </div>
        </div>

        <!-- Formulário de Submissão -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
                <i class="fa-solid fa-plus-circle text-green-600"></i>
                <h2 class="text-lg font-bold text-slate-800">Submeter Nova Palavra</h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('translator.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1.5">Língua</label>
                        <select name="language_id" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all">
                            @foreach($languages as $lang)
                                <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1.5">Palavra (Português)</label>
                        <input type="text" name="source_word" required class="w-full border border-slate-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all" placeholder="Ex: Olá">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1.5">Tradução</label>
                        <input type="text" name="translated_word" required class="w-full border border-slate-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all" placeholder="Ex: Salama">
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Submeter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Histórico -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-slate-500"></i>
                <h2 class="text-lg font-bold text-slate-800">As Minhas Submissões</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Língua</th>
                            <th class="px-6 py-3 font-semibold">Original (PT)</th>
                            <th class="px-6 py-3 font-semibold">Tradução</th>
                            <th class="px-6 py-3 font-semibold">Estado</th>
                            <th class="px-6 py-3 font-semibold">Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($translations as $translation)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-700">{{ $translation->language->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $translation->source_word }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $translation->translated_word }}</td>
                            <td class="px-6 py-4">
                                @if($translation->status == 'approved')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i> Aprovado
                                    </span>
                                @elseif($translation->status == 'rejected')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1">
                                        <i class="fa-solid fa-xmark"></i> Rejeitado
                                    </span>
                                @else
                                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1">
                                        <i class="fa-solid fa-clock"></i> Pendente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $translation->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-2xl mb-2 block"></i>
                                Ainda não submeteste nenhuma tradução.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>