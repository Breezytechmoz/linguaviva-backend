@extends('admin.layouts.app')

@section('title', 'Histórico de Pagamentos')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Histórico de Pagamentos</h3>
            <p class="text-slate-500 text-sm mt-1">Acede aos pedidos de subscrição Premium dos utilizadores.</p>
        </div>
        
        <!-- Filtros Falsos -->
        <div class="flex bg-slate-100 p-1 rounded-lg">
            <button class="px-4 py-1.5 text-sm font-semibold bg-white shadow-sm rounded-md text-slate-800">Todos</button>
            <button class="px-4 py-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">Pendentes</button>
            <button class="px-4 py-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">Aprovados</button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-slate-50/80 text-slate-400 text-[11px] uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Utilizador</th>
                        <th class="px-6 py-4 font-semibold">Método</th>
                        <th class="px-6 py-4 font-semibold">Referência</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold text-right">Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($pagamentos as $pag)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-800">{{ $pag->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $pag->user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 text-slate-700 font-medium bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200 text-xs">
                                @if(strtolower($pag->method) == 'mpesa')
                                    <i class="fa-solid fa-mobile-screen text-red-500"></i> M-Pesa
                                @else
                                    <i class="fa-solid fa-mobile-screen text-orange-500"></i> E-Mola
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-slate-600 bg-slate-50 border border-slate-200 px-2 py-1 rounded text-xs">{{ $pag->transaction_reference }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($pag->status == 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <i class="fa-regular fa-clock"></i> Pendente
                                </span>
                            @elseif($pag->status == 'approved')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                    <i class="fa-solid fa-check"></i> Aprovado
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                    <i class="fa-solid fa-xmark"></i> Rejeitado
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-slate-500">
                            {{ $pag->created_at->format('d M, Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-receipt text-2xl text-slate-400"></i>
                                </div>
                                <p class="font-medium text-slate-600">Sem pagamentos registados</p>
                                <p class="text-sm text-slate-400 mt-1">Os pedidos de Premium aparecerão aqui.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection