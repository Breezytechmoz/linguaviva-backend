@extends('admin.layouts.app')

@section('title', 'Histórico de Pagamentos')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3 bg-slate-50">
            <i class="fa-solid fa-money-check-dollar text-orange-500 text-lg"></i>
            <h3 class="text-lg font-bold text-slate-800">Todos os Pedidos de Premium</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Utilizador</th>
                        <th class="px-6 py-4 font-semibold">Método</th>
                        <th class="px-6 py-4 font-semibold">Referência</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold">Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($pagamentos as $pag)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $pag->user->name }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ strtoupper($pag->method) }}</td>
                        <td class="px-6 py-4 font-mono text-slate-600 bg-slate-100 px-2 py-1 rounded inline-block">{{ $pag->transaction_reference }}</td>
                        <td class="px-6 py-4">
                            @if($pag->status == 'pending')
                                <span class="bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full text-xs font-bold">Pendente</span>
                            @elseif($pag->status == 'approved')
                                <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-xs font-bold">Aprovado</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1.5 rounded-full text-xs font-bold">Rejeitado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">{{ $pag->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection