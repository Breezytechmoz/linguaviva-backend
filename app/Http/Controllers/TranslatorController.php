<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Translation;
use App\Models\TranslatorWallet;
use Illuminate\Support\Facades\Auth;

class TranslatorController extends Controller
{
    public function index()
    {
        if (!Auth::user()->is_translator) {
            abort(403, 'Acesso Negado. Apenas tradutores aprovados.');
        }

        $wallet = TranslatorWallet::firstOrCreate(['user_id' => Auth::id()], ['balance' => 0, 'total_earned' => 0]);
        $translations = Translation::where('translator_id', Auth::id())->with('language')->orderBy('created_at', 'desc')->get();

        // NOVA LÓGICA: Mostrar apenas as línguas aprovadas para este tradutor
        $langsIds = Auth::user()->translator_languages ?? [];
        $languages = \App\Models\Language::whereIn('id', $langsIds)->get();

        // Línguas disponíveis para novas candidaturas
        $allLanguages = \App\Models\Language::where('is_active', true)
            ->whereNotIn('id', $langsIds)
            ->whereNotIn('name', ['Português', 'Inglês', 'Francês'])
            ->get();

        return view('translator.dashboard', compact('wallet', 'translations', 'languages', 'allLanguages'));
    }

        public function storeTranslation(Request $request)
    {
        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'source_word' => 'required|string|max:255',
            'translated_word' => 'required|string|max:255',
        ]);

        // VERIFICAR SE JÁ EXISTE
        $existe = Translation::where('language_id', $request->language_id)
            ->where('source_word', $request->source_word)
            ->where('status', 'approved')
            ->first();

        if ($existe) {
            return back()->with('error', 'Esta palavra já existe na base de dados! Podes sugerir uma revisão na lista de submissões existentes.');
        }

        Translation::create([
            'translator_id' => Auth::id(),
            'language_id' => $request->language_id,
            'source_word' => $request->source_word,
            'translated_word' => $request->translated_word,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Tradução submetida!');
    }

    public function requestWithdrawal()
    {
        $wallet = TranslatorWallet::where('user_id', Auth::id())->first();

        if (!$wallet || $wallet->balance < 250) {
            return back()->with('error', 'Precisas de ter pelo menos 250 MT para solicitar o saque.');
        }

        // Aqui podes criar uma tabela de "withdrawals" no futuro. 
        // Para já, vamos apenas registar o pedido no log e notificar o admin visualmente.
        \Log::info("Pedido de saque de {$wallet->balance} MT feito pelo utilizador ID: " . Auth::id());
        
        // Opcional: Zerar o saldo (numa app real, só se zera quando o admin paga)
        // $wallet->balance = 0; $wallet->save();

        return back()->with('success', 'Pedido de saque solicitado! O administrador entrará em contacto.');
    }


        public function submitRevision(Request $request, $translationId)
    {
        $request->validate(['proposed_word' => 'required|string']);
        $user = Auth::user();
        $wallet = TranslatorWallet::firstOrCreate(['user_id' => $user->id]);
        $translation = Translation::findOrFail($translationId);

        // Verifica se tem saldo (pode ficar negativo para pagar depois)
        // Deduz 0.10 MT do revisor
        $wallet->balance -= 0.10;
        $wallet->save();

        // Regista a revisão
        \App\Models\TranslationRevision::create([
            'translation_id' => $translationId,
            'reviewer_id' => $user->id,
            'proposed_word' => $request->proposed_word,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Revisão submetida! Custou 0.10 MT. Aguarda aprovação.');
    }

        public function applyNewLanguage(Request $request)
    {
        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'video' => 'required|file|mimes:mp4,mov,avi|max:20480',
        ]);

        $videoPath = $request->file('video')->store('applications', 'public');

        \App\Models\TranslatorApplication::create([
            'user_id' => Auth::id(),
            'language_requested' => json_encode([$request->language_id]),
            'experience' => 'Candidatura via Portal Web',
            'video_path' => asset('storage/' . $videoPath),
        ]);

        return back()->with('success', 'Candidatura enviada! Aguarda a aprovação do admin.');
    }

        public function showApplyForm()
        {
            $languages = \App\Models\Language::where('is_active', true)->get();
            $user = Auth::user();
            
            // Línguas que o utilizador já traduz (armazenadas como array na base de dados)
            $myLanguages = $user->translator_languages ?? [];
            
            // Verifica se já tem uma candidatura pendente
            $jaCandidatou = \App\Models\TranslatorApplication::where('user_id', $user->id)
                ->where('status', 'pending')
                ->exists();
                
            return view('translator.apply', compact('languages', 'jaCandidatou', 'myLanguages'));
        }

        public function submitWebApplication(Request $request)
        {
            $request->validate([
                'language_id' => 'required|exists:languages,id', // Agora é apenas 1 ID
                'video' => 'required|file|mimes:mp4,mov,avi|max:20480',
                'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'experience' => 'nullable|string'
            ]);

            $user = Auth::user();
            $myLanguages = $user->translator_languages ?? [];

            // Verifica se ele já não traduz esta língua
            if (in_array((int)$request->language_id, array_map('intval', $myLanguages))) {
                return back()->withErrors(['language_id' => 'Já traduzes esta língua. Escolhe outra.']);
            }
            
            $videoPath = $request->file('video')->store('applications/videos', 'public');
            
            $cvUrl = null;
            if ($request->hasFile('cv')) {
                $cvUrl = asset('storage/' . $request->file('cv')->store('applications/cvs', 'public'));
            }

            $letterUrl = null;
            if ($request->hasFile('cover_letter')) {
                $letterUrl = asset('storage/' . $request->file('cover_letter')->store('applications/letters', 'public'));
            }

            \App\Models\TranslatorApplication::create([
                'user_id' => $user->id,
                'language_requested' => json_encode([$request->language_id]), // Guarda como array para manter compatibilidade
                'experience' => $request->experience ?? '',
                'video_path' => asset('storage/' . $videoPath),
                'cv_path' => $cvUrl,
                'cover_letter_path' => $letterUrl,
                'wants_interview' => $request->has('wants_interview') ? true : false,
            ]);

            \App\Models\AdminNotification::create([
                'type' => 'translator',
                'title' => 'Nova Candidatura (Web)',
                'message' => $user->name . ' candidatou-se a tradutor.',
                'url' => route('admin.dashboard'),
            ]);

            return redirect()->route('translator.apply-form')->with('success', 'Candidatura enviada! O admin irá analisar o teu perfil.');
        }
}