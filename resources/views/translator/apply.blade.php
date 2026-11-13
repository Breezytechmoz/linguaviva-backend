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
    </style>
</head>
<body class="min-h-screen">

    <!-- Navbar Simples -->
    <nav class="bg-slate-900 text-white shadow-md">
        <div class="max-w-4xl mx-auto flex justify-between items-center px-6 py-4">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-language text-xl text-green-400"></i>
                <h1 class="text-lg font-bold">LínguaViva Moz</h1>
            </div>
            <a href="{{ url('/') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto mt-10 p-6">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-br from-green-600 to-green-800 p-8 text-white text-center">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <i class="fa-solid fa-microphone-lines text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold mb-2">Torna-te Tradutor Oficial</h1>
                <p class="text-green-100">Ganha <strong>0.50 MT</strong> por cada palavra aprovada e ajuda a preservar as línguas de Moçambique.</p>
            </div>

            <div class="p-8">
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-xl mb-6 flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-lg"></i> 
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if($jaCandidatou)
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 p-6 rounded-2xl text-center">
                        <i class="fa-solid fa-hourglass-half text-4xl mb-3 text-amber-500"></i>
                        <h3 class="text-xl font-bold mb-2">Candidatura em Análise</h3>
                        <p class="text-sm">Já tens uma candidatura pendente. Por favor, aguarda que o administrador analise o teu vídeo.</p>
                    </div>
                @else
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl mb-6 text-sm">
                            <ul class="list-disc ml-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('translator.apply-submit') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3">1. Que línguas dominas para traduzir? (Seleciona pelo menos uma)</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($languages as $lang)
                                    <label class="flex items-center gap-2 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                        <input type="checkbox" name="language_ids[]" value="{{ $lang->id }}" class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                                        <span class="text-sm font-medium text-slate-700">{{ $lang->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">2. Vídeo de Apresentação (Máximo 20MB)</label>
                            <p class="text-xs text-slate-500 mb-3">Grava um vídeo de 1 a 2 minutos a apresentar-te na língua que te candidatas.</p>
                            <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo" required class="w-full border border-slate-300 rounded-xl p-2.5 focus:ring-2 focus:ring-green-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">3. Fala-nos da tua experiência (Opcional)</label>
                            <textarea name="experience" rows="4" class="w-full border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500 outline-none" placeholder="Ex: Sou falante nativo e já traduzi documentos para..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:bg-black transition-all shadow-md flex items-center justify-center gap-2">
                            Enviar Candidatura <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</body>
</html>