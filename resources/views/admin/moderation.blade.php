@extends('admin.layouts.app')

@section('title', 'Moderação da Comunidade')

@section('content')
    <div class="flex justify-between items-end mb-6">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Moderação da Comunidade</h3>
            <p class="text-slate-500 text-sm mt-1">Analisa as denúncias e mantém a plataforma segura.</p>
        </div>
        <div class="bg-red-50 text-red-700 px-4 py-2 rounded-lg border border-red-100 flex items-center gap-2 text-sm font-bold shadow-sm">
            <i class="fa-solid fa-triangle-exclamation"></i>
            {{ $denuncias->count() }} Denúncias Ativas
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-slate-50/80 text-slate-400 text-[11px] uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Denunciante</th>
                        <th class="px-6 py-4 font-semibold">Utilizador Denunciado</th>
                        <th class="px-6 py-4 font-semibold">Motivo</th>
                        <th class="px-6 py-4 font-semibold">Detalhes</th>
                        <th class="px-6 py-4 font-semibold text-center">Prova</th>
                        <th class="px-6 py-4 font-semibold text-right">Moderação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($denuncias as $den)
                    <tr class="hover:bg-red-50/30 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $den->reporter->name }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-red-600">{{ $den->reported->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-1 rounded-md text-xs font-bold">{{ $den->reason }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 max-w-[200px] truncate text-xs" title="{{ $den->details }}">
                            {{ $den->details ?? 'Sem detalhes adicionais.' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($den->attachment)
                                <a href="{{ $den->attachment }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Ver Prova">
                                    <i class="fa-solid fa-image"></i>
                                </a>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('admin.toggle-suspend', $den->reported->id) }}" class="inline-block">
                                @csrf
                                @if($den->reported->is_suspended)
                                    <button type="submit" class="bg-white border border-green-200 text-green-700 hover:bg-green-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm flex items-center gap-1.5 ml-auto">
                                        <i class="fa-solid fa-check"></i> Reativar
                                    </button>
                                @else
                                    <button type="submit" onclick="return confirm('Tens a certeza que queres suspender {{ $den->reported->name }}?')" class="bg-red-600 text-white hover:bg-red-700 shadow-sm shadow-red-600/20 px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 ml-auto">
                                        <i class="fa-solid fa-ban"></i> Banir Utilizador
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center border-b border-slate-100">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-4 ring-8 ring-green-50/50">
                                    <i class="fa-solid fa-shield-check text-3xl text-green-500"></i>
                                </div>
                                <h4 class="text-lg font-bold text-slate-800">Tudo limpo!</h4>
                                <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">Não há denúncias pendentes neste momento. A tua comunidade está segura e a comportar-se bem.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection