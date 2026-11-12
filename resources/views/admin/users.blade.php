@extends('admin.layouts.app')

@section('title', 'Gestão de Utilizadores')

@section('content')
    <div class="flex justify-between items-end mb-6">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Todos os Utilizadores</h3>
            <p class="text-slate-500 text-sm mt-1">Gere as contas, permissões e estados da comunidade.</p>
        </div>
        <button class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-download"></i> Exportar CSV
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-slate-50/80 text-slate-400 text-[11px] uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Utilizador</th>
                        <th class="px-6 py-4 font-semibold">Registo</th>
                        <th class="px-6 py-4 font-semibold">Estado da Conta</th>
                        <th class="px-6 py-4 font-semibold text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-600 font-bold border border-slate-200 shadow-inner group-hover:ring-2 ring-green-100 transition-all">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $user->created_at->format('d M, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($user->is_admin)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    <i class="fa-solid fa-crown text-[10px]"></i> Admin
                                </span>
                            @elseif($user->is_suspended)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                    <i class="fa-solid fa-ban text-[10px]"></i> Suspenso
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Ativo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(!$user->is_admin)
                                <form method="POST" action="{{ route('admin.toggle-suspend', $user->id) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" title="{{ $user->is_suspended ? 'Reativar Conta' : 'Suspender Conta' }}" class="p-2 rounded-lg transition-all border border-transparent {{ $user->is_suspended ? 'text-green-600 hover:bg-green-50 hover:border-green-200' : 'text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200' }}">
                                        <i class="fa-solid {{ $user->is_suspended ? 'fa-check-circle' : 'fa-user-slash' }} text-lg"></i>
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
@endsection