<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Tradutor - LínguaViva Moz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 h-screen flex">

    <!-- LADO ESQUERDO (Marca Tradutor) -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-600 flex-col justify-center items-center p-12 text-white relative overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-black opacity-20 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 text-center">
            <div class="w-24 h-24 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6 backdrop-blur-sm border border-white/20 shadow-2xl">
                <i class="fa-solid fa-language text-5xl text-blue-300"></i>
            </div>
            <h1 class="text-4xl font-bold mb-4 tracking-tight">Portal do <span class="text-blue-300">Tradutor</span></h1>
            <p class="text-blue-100 text-lg max-w-md mx-auto leading-relaxed">
                Acede à tua carteira, submete novas traduções e ajuda a preservar as línguas de Moçambique.
            </p>
        </div>
    </div>

    <!-- LADO DIREITO (Formulário) -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md">
            
            <div class="lg:hidden text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-language text-3xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Portal do Tradutor</h2>
            </div>

            <div class="mb-10">
                <h2 class="text-3xl font-bold text-slate-800 mb-2">Bem-vindo de volta</h2>
                <p class="text-slate-500">Insere as tuas credenciais para aceder ao teu portal.</p>
            </div>
            
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-6 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">E-mail de Tradutor</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-slate-400"></i>
                        </div>
                        <input type="email" name="email" required 
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none text-slate-700" 
                            placeholder="tradutor@linguaviva.com">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Palavra-passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400"></i>
                        </div>
                        <input type="password" name="password" required 
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none text-slate-700" 
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3.5 px-4 rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-lg shadow-blue-600/30 flex justify-center items-center gap-2">
                    Entrar no Portal <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
            
            <p class="text-center text-slate-400 text-sm mt-10">
                &copy; {{ date('Y') }} LínguaViva Moz. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>