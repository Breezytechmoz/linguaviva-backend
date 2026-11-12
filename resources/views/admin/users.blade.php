@extends('admin.layouts.app')

@section('title', 'Gestão de Utilizadores')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
            <i class="fa-solid fa-users-gear text-slate-500 text-lg"></i>
            <h3 class="text-lg font-bold text-slate-800">Todos os Utilizadores</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Utilizador</th>
                        <th class="px-6 py-4 font-semibold">Registo</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold border border-slate-300">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            @if($user->is_admin)
                                <span class="bg-purple-100 text-purple-700 px-3 py-1.5 rounded-full text-xs font-bold inline-flex items-center"><i class="fa-solid fa-crown mr-1.5"></i> Admin</span>
                            @elseif($user->is_suspended)
                                <span class="bg-red-100 text-red-700 px-3 py-1.5 rounded-full text-xs font-bold inline-flex items-center"><i class="fa-solid fa-ban mr-1.5"></i> Suspenso</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-xs font-bold inline-flex items-center"><i class="fa-solid fa-check mr-1.5"></i> Ativo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(!$user->is_admin)
                                <form method="POST" action="{{ route('admin.toggle-suspend', $user->id) }}">
                                    @csrf
                                    <button type="submit" class="text-sm font-bold px-4 py-2 rounded-lg transition-colors border {{ $user->is_suspended ? 'text-green-600 border-green-200 hover:bg-green-50' : 'text-red-500 border-red-200 hover:bg-red-50' }}">
                                        {{ $user->is_suspended ? 'Reativar Conta' : 'Suspender Conta' }}
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