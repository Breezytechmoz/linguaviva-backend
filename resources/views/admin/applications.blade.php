@extends('admin.layouts.app')

@section('title', 'Candidaturas a Tradutor')

@section('content')
    <div class="flex justify-between items-end mb-6">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Candidaturas a Tradutor</h3>
            <p class="text-slate-500 text-sm mt-1">Analisa os vídeos, currículos e cartas de apresentação dos candidatos.</p>
        </div>
        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg border border-blue-100 flex items-center gap-2 text-sm font-bold shadow-sm">
            <i class="fa-solid fa-user-tie"></i>
            {{ $candidaturas->count() }} Candidaturas no total
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-slate-50/80 text-slate-400 text-[11px] uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Candidato</th>
                        <th class="px-6 py-4 font-semibold">Língua</th>
                        <th class="px-6 py-4 font-semibold">Experiência</th>
                        <th class="px-6 py-4 font-semibold">Documentos</th>
                        <th class="px-6 py-4 font-semibold text-center">Entrevista</th>
                        <th class="px-6 py-4 font-semibold text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($candidaturas as $cand)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            @if($cand->user)
                                <p class="font-bold text-slate-800">{{ $cand->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $cand->user->email }}</p>
                            @else
                                <p class="font-bold text-red-500">Utilizador Apagado</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-xs font-bold">
                                {{ $cand->language_requested ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs truncate">{{ $cand->experience ?? 'Sem experiência descrita' }}</td>
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
                        <td class="px-6 py-4 text-center">
                            @if(isset($cand->wants_interview) && $cand->wants_interview)
                                <span class="bg-amber-100 text-amber-700 border border-amber-200 px-2.5 py-1 rounded-md text-xs font-bold inline-flex items-center gap-1">
                                    <i class="fa-solid fa-video"></i> Solicitada
                                </span>
                            @else
                                <span class="text-slate-400 text-xs">Não</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col gap-2 items-end">
                                @if($cand->status == 'pending')
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
                                @else
                                    <span class="text-xs font-bold {{ $cand->status == 'approved' ? 'text-green-600' : 'text-red-500' }}">
                                        {{ $cand->status == 'approved' ? 'Aprovada' : 'Rejeitada' }}
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-user-tie text-2xl text-slate-400"></i>
                                </div>
                                <p class="font-medium text-slate-600">Sem candidaturas pendentes</p>
                                <p class="text-sm text-slate-400 mt-1">As novas candidaturas de tradutores aparecerão aqui.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection