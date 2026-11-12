@extends('admin.layouts.app')

@section('title', 'Moderação da Comunidade')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-red-100 flex items-center gap-3 bg-red-50">
            <i class="fa-solid fa-shield-halved text-red-600 text-lg"></i>
            <h3 class="text-lg font-bold text-red-800">Denúncias de Utilizadores</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Denunciante</th>
                        <th class="px-6 py-4 font-semibold">Denunciado</th>
                        <th class="px-6 py-4 font-semibold">Motivo</th>
                        <th class="px-6 py-4 font-semibold">Detalhes</th>
                        <th class="px-6 py-4 font-semibold">Prova</th>
                        <th class="px-6 py-4 font-semibold text-right">Moderação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($denuncias as $den)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $den->reporter->name }}</td>
                        <td class="px-6 py-4 font-bold text-red-600">{{ $den->reported->name }}</td>
                        <td class="px-6 py-4"><span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">{{ $den->reason }}</span></td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs truncate">{{ $den->details ?? 'Sem detalhes' }}</td>
                        <td class="px-6 py-4">
                            @if($den->attachment)
                                <a href="{{ $den->attachment }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1 font-semibold text-xs bg-blue-50 px-3 py-1.5 rounded-lg w-max">
                                    <i class="fa-solid fa-image"></i> Ver Prova
                                </a>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('admin.toggle-suspend', $den->reported->id) }}">
                                @csrf
                                @if($den->reported->is_suspended)
                                    <button type="submit" class="bg-green-100 text-green-700 hover:bg-green-200 px-4 py-2 rounded-lg text-xs font-bold transition-colors flex items-center gap-2 ml-auto">
                                        <i class="fa-solid fa-check"></i> Reativar Conta
                                    </button>
                                @else
                                    <button type="submit" onclick="return confirm('Tens a certeza que queres suspender {{ $den->reported->name }}?')" class="bg-red-600 text-white hover:bg-red-700 shadow-sm shadow-red-600/30 px-4 py-2 rounded-lg text-xs font-bold transition-colors flex items-center gap-2 ml-auto">
                                        <i class="fa-solid fa-ban"></i> Banir Utilizador
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                            <i class="fa-solid fa-check-circle text-4xl text-green-400 mb-3 block"></i>
                            Nenhuma denúncia pendente. A comunidade está segura!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection