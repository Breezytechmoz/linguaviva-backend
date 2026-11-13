<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tornar-se Tradutor - LínguaViva Moz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        
        /* Estilo para o Toggle Switch da Entrevista */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #2E7D32;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #2E7D32;
        }
    </style>
</head>
<body class="min-h-screen pb-12">

    <!-- Navbar Simples -->
    <nav class="bg-slate-900 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-5xl mx-auto flex justify-between items-center px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-language text-green-400"></i>
                </div>
                <h1 class="text-lg font-bold tracking-tight">LínguaViva Moz</h1>
            </div>
            <a href="{{ url('/') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors flex items-center gap-2 bg-slate-800 px-4 py-2 rounded-lg">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto mt-10 px-4">
        
        <!-- Cabeçalho -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-slate-800 mb-3">Torna-te Tradutor Oficial</h1>
            <p class="text-slate-500 text-lg max-w-xl mx-auto">Ganha <strong>0.50 MT</strong> por cada palavra aprovada e ajuda a conectar Moçambique ao mundo.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-5 rounded-r-xl mb-8 flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-xl mt-0.5"></i> 
                <div>
                    <p class="font-bold">Sucesso!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($jaCandidatou)
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-12 text-center">
                <div class="w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-hourglass-half text-4xl text-amber-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Candidatura em Análise</h3>
                <p class="text-slate-500">Já tens uma candidatura pendente. A nossa equipa está a rever o teu vídeo e documentos. Entraremos em contacto em breve!</p>
            </div>
        @else
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl mb-6 shadow-sm">
                    <p class="font-bold mb-1">Por favor, corrige os seguintes erros:</p>
                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('translator.apply-submit') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                                <!-- SECÇÃO 1: LÍNGUAS -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">1</div>
                        <h2 class="text-lg font-bold text-slate-800">Língua a que te candidatas</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-500 mb-4">Escolhe a língua para a qual te queres candidatar. Só podes candidatar-te a uma língua de cada vez.</p>
                        
                        @if ($errors->has('language_id'))
                            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm font-medium">
                                {{ $errors->first('language_id') }}
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($languages as $lang)
                                @php
                                    $jaTraduz = in_array((int)$lang->id, array_map('intval', $myLanguages));
                                @endphp
                                
                                <label class="relative flex items-center gap-3 p-3 border rounded-xl transition-all 
                                    {{ $jaTraduz ? 'border-slate-200 bg-slate-100 opacity-60 cursor-not-allowed' : 'border-slate-200 cursor-pointer hover:border-green-500 hover:bg-green-50 group has-[:checked]:border-green-500 has-[:checked]:bg-green-50 has-[:checked]:ring-1 has-[:checked]:ring-green-500' }}">
                                    
                                    <input type="radio" name="language_id" value="{{ $lang->id }}" 
                                        class="w-4 h-4 text-green-600 focus:ring-green-500 border-slate-300"
                                        {{ $jaTraduz ? 'disabled' : '' }} required>
                                    
                                    <span class="text-sm font-semibold {{ $jaTraduz ? 'text-slate-500' : 'text-slate-700 group-hover:text-green-700' }}">
                                        {{ $lang->name }}
                                    </span>

                                    @if($jaTraduz)
                                        <span class="absolute top-2 right-2 text-[10px] font-bold text-slate-500 bg-slate-200 px-2 py-0.5 rounded-full flex items-center gap-1">
                                            <i class="fa-solid fa-lock"></i> Já traduzes
                                        </span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- SECÇÃO 2: DOCUMENTOS E VÍDEO -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">2</div>
                        <h2 class="text-lg font-bold text-slate-800">Documentos e Apresentação</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        
                        <!-- Vídeo -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Vídeo de Apresentação <span class="text-red-500">*</span></label>
                            <p class="text-xs text-slate-500 mb-2">Grava um vídeo (máx. 20MB) a falar a língua que te candidatas.</p>
                            <input type="file" name="video" accept="video/*" required class="w-full border border-slate-300 rounded-xl p-2.5 focus:ring-2 focus:ring-green-500 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- CV -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Currículo (Opcional)</label>
                                <input type="file" name="cv" accept=".pdf,.doc,.docx" class="w-full border border-slate-300 rounded-xl p-2.5 focus:ring-2 focus:ring-green-500 outline-none text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all">
                            </div>

                            <!-- Carta -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Carta de Apresentação (Opcional)</label>
                                <input type="file" name="cover_letter" accept=".pdf,.doc,.docx" class="w-full border border-slate-300 rounded-xl p-2.5 focus:ring-2 focus:ring-green-500 outline-none text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all">
                            </div>
                        </div>

                        <!-- Experiência -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Experiência (Opcional)</label>
                            <textarea name="experience" rows="3" class="w-full border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500 outline-none text-sm" placeholder="Fala-nos um pouco sobre a tua experiência com tradução ou línguas..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- SECÇÃO 3: ENTREVISTA -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-video text-green-600"></i> Entrevista Online
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">Requeres uma breve entrevista de 10 minutos para validarmos a tua fluência oral?</p>
                        </div>
                        <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="wants_interview" id="wants_interview" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer z-10 transition-all duration-300"/>
                            <label for="wants_interview" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-300 cursor-pointer transition-colors duration-300"></label>
                        </div>
                    </div>
                </div>

                <!-- BOTÃO SUBMETER -->
                <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl hover:bg-black hover:shadow-lg transition-all flex items-center justify-center gap-3 text-lg group">
                    Enviar Candidatura 
                    <i class="fa-solid fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                </button>
            </form>
        @endif
    </div>
</body>
</html>