<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Portal do Tradutor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="min-h-screen">

    <!-- Navbar -->
    <nav class="bg-slate-900 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-language text-xl text-blue-400"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold leading-tight">Portal do Tradutor</h1>
                    <p class="text-xs text-slate-400">LínguaViva Moz</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden sm:block text-right">
                    <p class="text-sm font-bold">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-blue-400 font-semibold uppercase tracking-wider">Tradutor Oficial</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-red-600 transition-colors flex items-center justify-center text-slate-300 hover:text-white">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6 space-y-8 mt-4">
        
        <!-- Mensagens de Feedback -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-xl flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-lg"></i> 
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i> 
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

                <!-- Mostrar erros de validação do Laravel -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-lg mt-0.5"></i> 
                <div>
                    <p class="font-bold">Ocorreu um erro:</p>
                    <ul class="list-disc ml-4 text-sm mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Carteira -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-center gap-2 text-slate-500 mb-2">
                        <i class="fa-solid fa-wallet text-blue-500"></i>
                        <p class="text-sm font-semibold">Saldo Disponível</p>
                    </div>
                    <h2 class="text-4xl font-extrabold text-slate-800">{{ number_format($wallet->balance, 2) }} <span class="text-lg text-slate-400 font-medium">MT</span></h2>
                </div>
                <form method="POST" action="{{ route('translator.withdraw') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-money-bill-transfer"></i> Pedir Saque
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-2">Mínimo para saque: 250 MT</p>
                </form>
            </div>
            
            <!-- Total Ganho -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-2 text-slate-500 mb-2">
                    <i class="fa-solid fa-chart-line text-green-500"></i>
                    <p class="text-sm font-semibold">Total Ganho</p>
                </div>
                <h2 class="text-4xl font-extrabold text-slate-800">{{ number_format($wallet->total_earned, 2) }} <span class="text-lg text-slate-400 font-medium">MT</span></h2>
                <div class="mt-6 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-500 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-500"></i>
                        Pagamento de <strong>0.50 MT</strong> por cada palavra aprovada.
                    </p>
                </div>
            </div>

            <!-- Nova Língua / Status -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-6 rounded-2xl shadow-md text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-[-20%] right-[-10%] w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                <div>
                    <div class="flex items-center gap-2 text-blue-100 mb-2 relative z-10">
                        <i class="fa-solid fa-award"></i>
                        <p class="text-sm font-semibold">Tradutor Oficial</p>
                    </div>
                    <p class="text-sm text-blue-50 mt-2 relative z-10">Queres traduzir mais alguma língua? Envia uma nova candidatura.</p>
                </div>
                <button onclick="document.getElementById('modal-apply').classList.remove('hidden')" class="mt-4 w-full bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 text-white py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 relative z-10">
                    <i class="fa-solid fa-file-video"></i> Candidatar Nova Língua
                </button>
            </div>
        </div>

        <!-- Formulário de Submissão -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex items-center gap-3">
                <i class="fa-solid fa-plus-circle text-green-600 text-lg"></i>
                <h2 class="text-lg font-bold text-slate-800">Submeter Nova Palavra</h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('translator.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Língua</label>
                        <select name="language_id" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all bg-white">
                            @foreach($languages as $lang)
                                <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Palavra (Português)</label>
                        <input type="text" name="source_word" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all" placeholder="Ex: Olá">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tradução</label>
                        <input type="text" name="translated_word" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none transition-all" placeholder="Ex: Salama">
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm shadow-green-600/20">
                            <i class="fa-solid fa-paper-plane"></i> Submeter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Histórico de Submissões -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex items-center gap-3">
                <i class="fa-solid fa-clock-rotate-left text-slate-500 text-lg"></i>
                <h2 class="text-lg font-bold text-slate-800">As Minhas Submissões</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50/80 text-slate-400 text-[11px] uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Língua</th>
                            <th class="px-6 py-4 font-semibold">Original (PT)</th>
                            <th class="px-6 py-4 font-semibold">Tradução</th>
                            <th class="px-6 py-4 font-semibold">Estado</th>
                            <th class="px-6 py-4 font-semibold text-right">Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($translations as $translation)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-700">{{ $translation->language->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $translation->source_word }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800 bg-green-50 rounded px-2 inline-block mt-2">{{ $translation->translated_word }}</td>
                            <td class="px-6 py-4">
                                @if($translation->status == 'approved')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-md text-xs font-bold inline-flex items-center gap-1 border border-green-200">
                                        <i class="fa-solid fa-check"></i> Aprovado
                                    </span>
                                @elseif($translation->status == 'rejected')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-md text-xs font-bold inline-flex items-center gap-1 border border-red-200">
                                        <i class="fa-solid fa-xmark"></i> Rejeitado
                                    </span>
                                @else
                                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-md text-xs font-bold inline-flex items-center gap-1 border border-amber-200">
                                        <i class="fa-regular fa-clock"></i> Pendente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-right">{{ $translation->created_at->format('d M, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-folder-open text-2xl"></i>
                                </div>
                                <p class="font-medium text-slate-600">Ainda não submeteste nenhuma tradução.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal de Candidatura -->
    <div id="modal-apply" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-slate-800">Candidatar a Nova Língua</h3>
                <button onclick="document.getElementById('modal-apply').classList.add('hidden')" class="text-slate-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="{{ route('translator.apply-new') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Língua</label>
                    <select name="language_id" class="w-full border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
                        @foreach($allLanguages as $lang)
                            <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Vídeo (Mínimo 1 minuto a ler um texto)</label>
                    <input type="file" name="video" accept="video/*" class="w-full border border-slate-300 rounded-xl p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-apply').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-sm shadow-blue-600/30">Enviar</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>