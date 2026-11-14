@extends('admin.layouts.app')

@section('title', 'Visão Geral')

@section('content')
    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow flex items-center gap-4 group">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Total de Utilizadores</p>
                <p class="text-3xl font-extrabold text-slate-800">{{ $totalUsers }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow flex items-center gap-4 group">
            <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-comment-dots"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Mensagens</p>
                <p class="text-3xl font-extrabold text-slate-800">{{ $totalMessages }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow flex items-center gap-4 group">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Pagamentos Pendentes</p>
                <p class="text-3xl font-extrabold text-slate-800">{{ $pagamentosPendentes->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow flex items-center gap-4 group">
            <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Denúncias</p>
                <p class="text-3xl font-extrabold text-slate-800">{{ $denuncias->count() }}</p>
            </div>
        </div>
    </div>

    <!-- DENÚNCIAS COM BOTÃO DE BANIR -->
    @if($denuncias->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden mt-8">
        <div class="px-6 py-5 border-b border-red-100 flex items-center gap-3 bg-red-50">
            <i class="fa-solid fa-shield-halved text-red-600 text-lg"></i>
            <h3 class="text-lg font-bold text-red-800">Denúncias de Utilizadores</h3>
            <span class="bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full ml-auto">{{ $denuncias->count() }} recentes</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Quem Denunciou</th>
                        <th class="px-6 py-4 font-semibold">Denunciado</th>
                        <th class="px-6 py-4 font-semibold">Motivo</th>
                        <th class="px-6 py-4 font-semibold">Detalhes</th>
                        <th class="px-6 py-4 font-semibold">Prova</th>
                        <th class="px-6 py-4 font-semibold text-right">Moderação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($denuncias as $denuncia)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $denuncia->reporter->name ?? 'Apagado' }}</td>
                        <td class="px-6 py-4 font-bold text-red-600">{{ $denuncia->reported->name ?? 'Apagado' }}</td>
                        <td class="px-6 py-4"><span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">{{ $denuncia->reason }}</span></td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs truncate">{{ $denuncia->details ?? 'Sem detalhes' }}</td>
                        <td class="px-6 py-4">
                            @if($denuncia->attachment)
                                <a href="{{ $denuncia->attachment }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1 font-semibold text-xs bg-blue-50 px-3 py-1.5 rounded-lg w-max">
                                    <i class="fa-solid fa-image"></i> Ver Prova
                                </a>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($denuncia->reported)
                            <form method="POST" action="{{ route('admin.toggle-suspend', $denuncia->reported->id) }}">
                                @csrf
                                @if($denuncia->reported->is_suspended)
                                    <button type="submit" class="bg-green-100 text-green-700 hover:bg-green-200 px-4 py-2 rounded-lg text-xs font-bold transition-colors flex items-center gap-2 ml-auto">
                                        <i class="fa-solid fa-check"></i> Reativar
                                    </button>
                                @else
                                    <button type="submit" onclick="return confirm('Tens a certeza que queres suspender {{ $denuncia->reported->name }}?')" class="bg-red-600 text-white hover:bg-red-700 shadow-sm shadow-red-600/30 px-4 py-2 rounded-lg text-xs font-bold transition-colors flex items-center gap-2 ml-auto">
                                        <i class="fa-solid fa-ban"></i> Banir
                                    </button>
                                @endif
                            </form>
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
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-8">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
            <i class="fa-solid fa-money-check-dollar text-orange-500 text-lg"></i>
            <h3 class="text-lg font-bold text-slate-800">Pedidos de Pagamento (Premium)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
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
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $pagamento->user->name ?? 'Apagado' }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ strtoupper($pagamento->method) }}</td>
                        <td class="px-6 py-4 font-mono text-slate-600 bg-slate-100 px-2 py-1 rounded inline-block">{{ $pagamento->transaction_reference }}</td>
                        <td class="px-6 py-4 flex justify-end gap-2">
                            <form method="POST" action="{{ route('admin.handle-payment', $pagamento->id) }}">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition-colors flex items-center gap-2 shadow-sm shadow-green-500/30">
                                    <i class="fa-solid fa-check"></i> Aprovar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.handle-payment', $pagamento->id) }}">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-xs font-bold transition-colors flex items-center gap-2">
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

    <!-- CANDIDATURAS A TRADUTOR -->
    @if($candidaturas->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-8">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
            <i class="fa-solid fa-user-tie text-blue-500 text-lg"></i>
            <h3 class="text-lg font-bold text-slate-800">Candidaturas a Tradutor</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Candidato</th>
                        <th class="px-6 py-4 font-semibold">Experiência</th>
                        <th class="px-6 py-4 font-semibold">Documentos</th>
                        <th class="px-6 py-4 font-semibold text-center">Entrevista</th>
                        <th class="px-6 py-4 font-semibold text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($candidaturas as $cand)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            @if($cand->user)
                                <p class="font-bold text-slate-800">{{ $cand->user->name }}</p>
                            @else
                                <p class="font-bold text-red-500">Utilizador Apagado</p>
                            @endif
                            <p class="text-xs text-slate-500">{{ $cand->language_requested ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs truncate">{{ $cand->experience ?? 'Sem experiência descrita' }}</td>
                        
                        <!-- COLUNA DE DOCUMENTOS -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1.5">
                                @if(!empty($cand->video_path))
                                    <a href="{{ $cand->video_path }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1 bg-blue-50 px-2.5 py-1 rounded-md w-max text-[11px] transition-colors">
                                        <i class="fa-solid fa-play"></i> Ver Vídeo
                                    </a>
                                @endif

                                @if(!empty($cand->cv_path))
                                    <a href="{{ $cand->cv_path }}" target="_blank" class="text-purple-600 hover:text-purple-800 font-semibold flex items-center gap-1 bg-purple-50 px-2.5 py-1 rounded-md w-max text-[11px] transition-colors">
                                        <i class="fa-solid fa-file-pdf"></i> Currículo (CV)
                                    </a>
                                @endif

                                @if(!empty($cand->cover_letter_path))
                                    <a href="{{ $cand->cover_letter_path }}" target="_blank" class="text-teal-600 hover:text-teal-800 font-semibold flex items-center gap-1 bg-teal-50 px-2.5 py-1 rounded-md w-max text-[11px] transition-colors">
                                        <i class="fa-solid fa-file-lines"></i> Carta Apresentação
                                    </a>
                                @endif
                            </div>
                        </td>

                        <!-- COLUNA DE ENTREVISTA -->
                        <td class="px-6 py-4 text-center">
                            @if(isset($cand->wants_interview) && $cand->wants_interview)
                                <span class="bg-amber-100 text-amber-700 border border-amber-200 px-2.5 py-1 rounded-md text-xs font-bold inline-flex items-center gap-1">
                                    <i class="fa-solid fa-video"></i> Solicitada
                                </span>
                            @else
                                <span class="text-slate-400 text-xs">Não</span>
                            @endif
                        </td>

                        <!-- BOTÕES DE AÇÃO -->
                        <td class="px-6 py-4 text-right flex flex-col gap-2 items-end justify-center h-full">
                            <form method="POST" action="{{ route('admin.approve-translator', [$cand->id, 'approve']) }}" class="w-full">
                                @csrf
                                <button class="w-full bg-green-500 text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-green-600 shadow-sm shadow-green-500/30 transition-colors">
                                    Aprovar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.approve-translator', [$cand->id, 'reject']) }}" class="w-full">
                                @csrf
                                <button class="w-full bg-slate-100 text-slate-700 px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors">
                                    Rejeitar
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

    <!-- TRADUÇÕES PENDENTES -->
    @if($traducoesPendentes->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-8">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
            <i class="fa-solid fa-language text-amber-500 text-lg"></i>
            <h3 class="text-lg font-bold text-slate-800">Traduções Pendentes (0.50 MT / Palavra)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
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
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $trad->translator->name ?? 'Apagado' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $trad->language->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-800">{{ $trad->source_word }}</td>
                        <td class="px-6 py-4 font-bold text-green-700 bg-green-50 rounded px-2 py-1 inline-block">{{ $trad->translated_word }}</td>
                        <td class="px-6 py-4 text-right flex gap-2 justify-end">
                            <form method="POST" action="{{ route('admin.handle-translation', [$trad->id, 'approve']) }}">
                                @csrf
                                <button class="bg-green-500 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-green-600 shadow-sm shadow-green-500/30 transition-colors">Aprovar e Pagar</button>
                            </form>
                            <form method="POST" action="{{ route('admin.handle-translation', [$trad->id, 'reject']) }}">
                                @csrf
                                <button class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors">Rejeitar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

@endsection