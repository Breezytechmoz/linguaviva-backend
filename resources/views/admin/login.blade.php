<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - LínguaViva Moz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 h-screen flex">
    
    <!-- LADO ESQUERDO (Marca) -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-green-900 via-green-800 to-green-600 flex-col justify-center items-center p-12 text-white relative overflow-hidden">
        <!-- Círculos decorativos -->
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 text-center">
            <!-- Coloca o teu logo aqui -->
            <img src="{{ asset('images/logo.png') }}" alt="LínguaViva Logo" class="w-32 h-32 mx-auto mb-6 drop-shadow-2xl rounded-2xl object-cover">
            <h1 class="text-4xl font-bold mb-4 tracking-tight">LínguaViva <span class="text-green-300">Moz</span></h1>
            <p class="text-green-100 text-lg max-w-md mx-auto leading-relaxed">
                A plataforma de intercâmbio linguístico que conecta Moçambique ao mundo. Gere a tua comunidade num só lugar.
            </p>
        </div>
    </div>

    <!-- LADO DIREITO (Formulário) -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md">
            
            <!-- Logo para Mobile -->
            <div class="lg:hidden text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 mx-auto mb-4 rounded-xl shadow-md">
                <h2 class="text-2xl font-bold text-slate-800">LínguaViva Moz</h2>
            </div>

            <div class="mb-10">
                <h2 class="text-3xl font-bold text-slate-800 mb-2">Bem-vindo de volta</h2>
                <p class="text-slate-500">Insere as tuas credenciais para aceder ao painel administrativo.</p>
            </div>
            
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-6 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">E-mail Administrativo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-slate-400"></i>
                        </div>
                        <input type="email" name="email" required 
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all outline-none text-slate-700" 
                            placeholder="admin@linguaviva.com">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Palavra-passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400"></i>
                        </div>
                        <input type="password" name="password" required 
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all outline-none text-slate-700" 
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white font-bold py-3.5 px-4 rounded-xl hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all shadow-lg shadow-green-600/30 flex justify-center items-center gap-2">
                    Entrar no Painel <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
            
            <p class="text-center text-slate-400 text-sm mt-10">
                &copy; {{ date('Y') }} LínguaViva Moz. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>