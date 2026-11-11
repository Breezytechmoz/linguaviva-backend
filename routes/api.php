<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Password;
use ZEGO\ZegoServerAssistant;
use ZEGO\ZegoErrorCodes;

Route::get('/test-notification/{userId}', function ($userId) {
    $user = \App\Models\User::find($userId);
    if (!$user || !$user->fcm_token) {
        return response()->json(['error' => 'Utilizador sem token FCM'], 400);
    }

    $messaging = app('firebase.messaging');
    $message = CloudMessage::withTarget('token', $user->fcm_token)
        ->withNotification(Notification::create('LínguaViva Moz', 'Olá! Esta é uma notificação de teste.'));

    $messaging->send($message);
    return response()->json(['status' => 'Notificação enviada!']);
});

Route::get('/teste', function () {
    return response()->json(['status' => 'sucesso', 'mensagem' => 'API a funcionar!']);
});

// Rota de Registo
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'status' => 'sucesso',
        'mensagem' => 'Conta criada com sucesso!',
        'user' => $user,
        'token' => $token
    ], 201);
});

// Rota de Login
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['status' => 'erro', 'mensagem' => 'E-mail ou palavra-passe incorretos.'], 401);
    }

    if ($user->is_suspended) {
        return response()->json(['status' => 'erro', 'mensagem' => 'A tua conta foi suspensa por violar as regras da comunidade.'], 403);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'status' => 'sucesso',
        'mensagem' => 'Login efetuado com sucesso!',
        'user' => $user,
        'token' => $token
    ], 200);
});

Route::get('/languages', function () {
    return response()->json(Language::where('is_active', true)->get());
});

Route::middleware('auth:sanctum')->post('/user/languages', function (Request $request) {
    $request->validate([
        'language_id' => 'required|exists:languages,id',
        'type' => 'required|in:native,learning',
        'level' => 'required|in:beginner,intermediate,advanced,fluent,native'
    ]);

    $user = $request->user();

    $user->languages()->syncWithoutDetaching([
        $request->language_id => [
            'type' => $request->type,
            'level' => $request->level
        ]
    ]);

    return response()->json([
        'status' => 'sucesso',
        'mensagem' => 'Língua adicionada ao teu perfil!'
    ], 200);
});

Route::middleware('auth:sanctum')->get('/user/languages', function (Request $request) {
    return response()->json($request->user()->languages);
});

Route::middleware('auth:sanctum')->get('/community', function (Request $request) {
    $currentUserId = $request->user()->id;
    $user = $request->user();
    
    if ($request->filled('location') && !$user->isPremiumActive()) {
        return response()->json(['error' => 'premium_required', 'mensagem' => 'A pesquisa por localização é uma funcionalidade Premium.'], 403);
    }

    $bloqueados = \App\Models\Block::where('user_id', $currentUserId)->pluck('blocked_user_id')->toArray();

    $query = \App\Models\User::where('id', '!=', $currentUserId)
                ->whereNotIn('id', $bloqueados)
                ->with('languages');

    if ($request->filled('search')) {
        $query->where('name', 'LIKE', '%' . $request->search . '%');
    }
    if ($request->filled('location')) {
        $query->where('location', 'LIKE', '%' . $request->location . '%');
    }
    if ($request->filled('native_language')) {
        $query->whereHas('languages', function($q) use ($request) {
            $q->where('languages.id', $request->native_language)->where('type', 'native');
        });
    }
    if ($request->filled('learning_language')) {
        $query->whereHas('languages', function($q) use ($request) {
            $q->where('languages.id', $request->learning_language)->where('type', 'learning');
        });
    }

    $query->orderBy('is_premium', 'desc')->orderBy('name');

    return response()->json($query->get());
});

Route::middleware('auth:sanctum')->post('/messages', function (Request $request) {
    $user = $request->user();

    if ($user->profile_completion < 60 || empty($user->profile_photo)) {
        return response()->json(['status' => 'erro', 'mensagem' => 'Para enviares mensagens, tens de ter uma foto de perfil e pelo menos 60% do teu perfil preenchido.'], 403);
    }
    
    $request->validate([
        'receiver_id' => 'required|exists:users,id',
        'content' => 'nullable|string|max:1000', // Agora é opcional
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Aceita imagens até 5MB
        'audio' => 'nullable|file|mimetypes:audio/mpeg,audio/wav,audio/x-wav,audio/mp4,audio/x-m4a,audio/aac,audio/webm,application/octet-stream|max:10240',
    ]);

    $sender = $request->user();
    $imageUrl = null;
    $audioUrl = null;

    // Se o utilizador enviou uma imagem, guarda-a
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('messages', 'public');
        $imageUrl = url('storage/' . $path); 
    }

    if ($request->hasFile('audio')) {
        $path = $request->file('audio')->store('messages/audios', 'public');
        $audioUrl = url('storage/' . $path); 
    }

    $message = \App\Models\Message::create([
        'sender_id' => $sender->id,
        'receiver_id' => $request->receiver_id,
        'content' => $request->content ?? '', 
        'image_url' => $imageUrl,
        'audio_url' => $audioUrl,
    ]);

    $receiver = \App\Models\User::find($request->receiver_id);

    if ($receiver && $receiver->fcm_token) {
        try {
            $messaging = app('firebase.messaging');
            $notification = \Kreait\Firebase\Messaging\Notification::create($sender->name, $request->content);
            $cloudMessage = \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $receiver->fcm_token)
                ->withNotification($notification)
                ->withData(['sender_id' => (string) $sender->id, 'type' => 'new_message']);

            $messaging->send($cloudMessage);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar notificação Firebase: ' . $e->getMessage());
        }
    }

    return response()->json(['status' => 'sucesso', 'mensagem' => 'Mensagem enviada!', 'data' => $message], 201);
});

Route::middleware('auth:sanctum')->get('/messages/{userId}', function (Request $request, $userId) {
    $myId = $request->user()->id;

    $mensagens = \App\Models\Message::where(function ($query) use ($myId, $userId) {
        $query->where('sender_id', $myId)->where('receiver_id', $userId);
    })->orWhere(function ($query) use ($myId, $userId) {
        $query->where('sender_id', $userId)->where('receiver_id', $myId);
    })->orderBy('created_at', 'asc')->get();

    \App\Models\Message::where('sender_id', $userId)->where('receiver_id', $myId)->where('is_read', false)->update(['is_read' => true]);

    return response()->json($mensagens);
});

Route::middleware('auth:sanctum')->get('/conversations', function (Request $request) {
    $myId = $request->user()->id;
    $enviados = DB::table('messages')->where('sender_id', $myId)->pluck('receiver_id')->toArray();
    $recebidos = DB::table('messages')->where('receiver_id', $myId)->pluck('sender_id')->toArray();
    $utilizadoresIds = array_unique(array_merge($enviados, $recebidos));
    $utilizadores = \App\Models\User::whereIn('id', $utilizadoresIds)->get();
    return response()->json($utilizadores);
});

Route::middleware('auth:sanctum')->get('/messages/unread/count', function (Request $request) {
    $count = \App\Models\Message::where('receiver_id', $request->user()->id)->where('is_read', false)->count();
    return response()->json(['unread_count' => $count]);
});

Route::middleware('auth:sanctum')->get('/users/{id}/status', function ($id) {
    $user = \App\Models\User::find($id);
    if (!$user) return response()->json(['error' => 'User not found'], 404);
    $isOnline = $user->last_seen_at && \Carbon\Carbon::parse($user->last_seen_at)->diffInMinutes(now()) < 2;
    return response()->json(['is_online' => $isOnline, 'is_typing' => $user->is_typing, 'last_seen_at' => $user->last_seen_at]);
});

Route::middleware('auth:sanctum')->post('/user/activity', function (Request $request) {
    $request->user()->update(['last_seen_at' => now(), 'is_typing' => $request->is_typing ?? false]);
    return response()->json(['status' => 'ok']);
});

Route::middleware('auth:sanctum')->post('/translate', function (Request $request) {
    $request->validate(['text' => 'required|string', 'source' => 'required|string|size:2', 'target' => 'required|string|size:2']);

    $user = $request->user();
    $hoje = now()->toDateString();

    if (!$user->isPremiumActive()) {
        if ($user->last_translation_date && $user->last_translation_date->toDateString() === $hoje) {
            if ($user->daily_translations_count >= 6) {
                return response()->json(['status' => 'premium_required', 'mensagem' => 'Atingiste o limite de 6 traduções gratuitas hoje. Adere ao Premium para traduções ilimitadas.'], 403);
            }
            $user->increment('daily_translations_count');
        } else {
            $user->daily_translations_count = 1;
            $user->last_translation_date = $hoje;
            $user->save();
        }
    }

    $response = \Illuminate\Support\Facades\Http::get("https://api.mymemory.translated.net/get", [
        'q' => $request->text,
        'langpair' => $request->source . '|' . $request->target
    ]);

    if ($response->successful()) {
        return response()->json(['status' => 'sucesso', 'translatedText' => $response->json()['responseData']['translatedText']]);
    }

    return response()->json(['status' => 'erro', 'message' => 'Erro na tradução'], 500);
});

Route::middleware(['auth:sanctum'])->post('/admin/users/{id}/toggle-suspend', function (Request $request, $id) {
    if (!$request->user()->is_admin) return response()->json(['error' => 'Acesso negado'], 403);
    $user = \App\Models\User::find($id);
    if (!$user) return response()->json(['error' => 'Utilizador não encontrado'], 404);
    if ($user->id === $request->user()->id) return response()->json(['error' => 'Não podes suspender-te a ti mesmo'], 400);
    $user->is_suspended = !$user->is_suspended;
    $user->save();
    return response()->json(['status' => 'ok', 'is_suspended' => $user->is_suspended]);
});

Route::middleware('auth:sanctum')->post('/premium/request', function (Request $request) {
    $request->validate(['method' => 'required|in:mpesa,emola', 'reference' => 'required|string|unique:payment_requests,transaction_reference']);
    $existePendente = \App\Models\PaymentRequest::where('user_id', $request->user()->id)->where('status', 'pending')->exists();
    if ($existePendente) return response()->json(['status' => 'erro', 'mensagem' => 'Já tens um pedido pendente. Aguarda a aprovação.'], 400);

    \App\Models\PaymentRequest::create(['user_id' => $request->user()->id, 'amount' => 199.00, 'method' => $request->method, 'transaction_reference' => $request->reference]);
    return response()->json(['status' => 'sucesso', 'mensagem' => 'Pedido enviado! Aguarda a confirmação do administrador.']);
});

Route::middleware('auth:sanctum')->get('/premium/status', function (Request $request) {
    return response()->json(['is_premium' => $request->user()->isPremiumActive(), 'expires_at' => $request->user()->premium_expires_at]);
});

Route::middleware('auth:sanctum')->post('/user/fcm-token', function (Request $request) {
    $request->validate(['token' => 'required|string']);
    $request->user()->update(['fcm_token' => $request->token]);
    return response()->json(['status' => 'ok']);
});

// =========================================================================
// ROTA CORRIGIDA DO PERFIL (Lê a carteira, palavras e línguas do tradutor)
// =========================================================================
Route::middleware('auth:sanctum')->get('/user/profile', function (Request $request) {
    $user = $request->user();
    
    $walletBalance = 0;
    $wordsSubmitted = 0;
    $translatorLanguagesNames = [];

    if ($user->is_translator) {
        $wallet = \App\Models\TranslatorWallet::where('user_id', $user->id)->first();
        $walletBalance = $wallet ? (float) $wallet->balance : 0.0;
        
        $wordsSubmitted = \App\Models\Translation::where('translator_id', $user->id)->count();
        
        $langs = $user->translator_languages;
        if ($langs && !is_array($langs)) {
            $langs = [$langs];
        }
        if ($langs && is_array($langs) && count($langs) > 0) {
            $translatorLanguagesNames = \App\Models\Language::whereIn('id', $langs)->pluck('name')->toArray();
        }
    }

    $userData = $user->toArray();
    $userData['wallet_balance'] = $walletBalance;
    $userData['words_submitted'] = $wordsSubmitted;
    $userData['translator_languages_names'] = $translatorLanguagesNames;

    return response()->json($userData);
});
// =========================================================================

Route::middleware('auth:sanctum')->get('/users/{id}/profile', function (Request $request, $id) {
    $user = \App\Models\User::with('languages')->find($id);
    if (!$user) return response()->json(['error' => 'Utilizador não encontrado'], 404);
    
    // Verifica se eu bloqueei este utilizador
    $isBlocked = \App\Models\Block::where('user_id', $request->user()->id)
                ->where('blocked_user_id', $id)
                ->exists();

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'bio' => $user->bio,
        'location' => $user->location,
        'age' => $user->age,
        'profile_photo' => $user->profile_photo,
        'extra_photos' => $user->extra_photos,
        'last_seen_at' => $user->last_seen_at,
        'is_premium' => $user->isPremiumActive(),
        'languages' => $user->languages,
        'is_blocked' => $isBlocked, // <-- ADICIONADO AQUI
    ]);
});

Route::middleware('auth:sanctum')->post('/user/profile/photo', function (Request $request) {
    $request->validate(['photo' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
    $path = $request->file('photo')->store('profiles', 'public');
    $url = asset('storage/' . $path);
    $user = $request->user();
    $user->profile_photo = $url;
    $completion = 20;
    if ($user->age) $completion += 10;
    if ($user->location) $completion += 10;
    if ($user->bio) $completion += 10;
    if ($user->profile_photo) $completion += 20;
    if ($user->extra_photos && count($user->extra_photos) >= 3) $completion += 30;
    $user->profile_completion = min($completion, 100);
    $user->save();
    return response()->json(['status' => 'sucesso', 'url' => $url, 'completion' => $user->profile_completion]);
});

Route::middleware('auth:sanctum')->post('/user/profile/extra-photo', function (Request $request) {
    $request->validate(['photo' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
    $user = $request->user();
    $fotosExtras = $user->extra_photos ?? [];
    $path = $request->file('photo')->store('profiles', 'public');
    $url = asset('storage/' . $path);
    $fotosExtras[] = $url;
    $user->extra_photos = $fotosExtras;
    $completion = 20;
    if ($user->age) $completion += 10;
    if ($user->location) $completion += 10;
    if ($user->bio) $completion += 10;
    if ($user->profile_photo) $completion += 20;
    if (count($fotosExtras) >= 3) $completion += 30;
    $user->profile_completion = min($completion, 100);
    $user->save();
    return response()->json(['status' => 'sucesso', 'extra_photos' => $user->extra_photos, 'completion' => $user->profile_completion]);
});

Route::middleware('auth:sanctum')->post('/users/{id}/block', function (Request $request, $id) {
    $myId = $request->user()->id;
    if ($myId == $id) return response()->json(['error' => 'Não podes bloquear-te a ti mesmo.'], 400);
    $block = \App\Models\Block::where('user_id', $myId)->where('blocked_user_id', $id)->first();
    if ($block) {
        $block->delete();
        return response()->json(['status' => 'unblocked', 'mensagem' => 'Utilizador desbloqueado.']);
    } else {
        \App\Models\Block::create(['user_id' => $myId, 'blocked_user_id' => $id]);
        return response()->json(['status' => 'blocked', 'mensagem' => 'Utilizador bloqueado.']);
    }
});

Route::middleware('auth:sanctum')->post('/users/{id}/report', function (Request $request, $id) {
    $request->validate(['reason' => 'required|string', 'details' => 'nullable|string', 'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:4096']);
    $path = null;
    if ($request->hasFile('attachment')) {
        $path = asset('storage/' . $request->file('attachment')->store('reports', 'public'));
    }
    \App\Models\Report::create(['reporter_id' => $request->user()->id, 'reported_id' => $id, 'reason' => $request->reason, 'details' => $request->details, 'attachment' => $path]);
    return response()->json(['status' => 'sucesso', 'mensagem' => 'Denúncia enviada. Obrigado por nos ajudar a manter a comunidade segura.']);
});

Route::middleware('auth:sanctum')->post('/translator/apply', function (Request $request) {
    $request->validate([
        'language_ids' => 'required|array',
        'video' => 'required|file|mimes:mp4,mov,avi|max:20480',
        'experience' => 'nullable|string'
    ]);

    $user = $request->user();
    $videoPath = $request->file('video')->store('applications', 'public');

    \App\Models\TranslatorApplication::create([
        'user_id' => $user->id,
        'language_requested' => json_encode($request->language_ids),
        'experience' => $request->experience ?? '',
        'video_path' => asset('storage/' . $videoPath),
    ]);

    return response()->json(['status' => 'sucesso', 'mensagem' => 'Candidatura enviada! Aguarda aprovação.']);
});

Route::middleware('auth:sanctum')->post('/translator/add-language', function (Request $request) {
    $request->validate(['language_id' => 'required|exists:languages,id']);
    $user = $request->user();
    if (!$user->is_translator) return response()->json(['error' => 'Apenas tradutores podem adicionar línguas.'], 403);
    $currentLanguages = $user->translator_languages ?? [];
    if (in_array($request->language_id, $currentLanguages)) return response()->json(['status' => 'erro', 'mensagem' => 'Já traduzes esta língua.'], 400);
    $currentLanguages[] = $request->language_id;
    $user->translator_languages = $currentLanguages;
    $user->save();
    return response()->json(['status' => 'sucesso', 'mensagem' => 'Nova língua adicionada ao teu perfil de tradutor!']);
});

// Rota para eliminar a conta
Route::middleware('auth:sanctum')->delete('/user/delete', function (Request $request) {
    $user = $request->user();
    $user->tokens()->delete();
    $user->delete();
    return response()->json(['status' => 'sucesso', 'mensagem' => 'Conta eliminada permanentemente.']);
});

// Pedir link de recuperação
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
                ? response()->json(['status' => 'sucesso', 'mensagem' => 'Link enviado para o teu e-mail.'])
                : response()->json(['status' => 'erro', 'mensagem' => 'Não encontrámos esse e-mail.'], 400);
});

// Gravar a nova password
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill(['password' => Hash::make($password)])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
                ? response()->json(['status' => 'sucesso', 'mensagem' => 'Password alterada com sucesso!'])
                : response()->json(['status' => 'erro', 'mensagem' => 'Token inválido ou expirado.'], 400);
});

// Rota para gerar o token de acesso à sala de voz
Route::middleware('auth:sanctum')->post('/zego/token', function (Request $request) {
    $request->validate(['room_id' => 'required|string']);

    $user = $request->user();
    $appId = (int) env('ZEGO_APP_ID');
    $secret = env('ZEGO_SERVER_SECRET');
    $userId = (string) $user->id;
    $roomId = $request->room_id;
    $effectiveTimeInSeconds = 3600;

    $token = ZegoServerAssistant::generateToken04($appId, $userId, $secret, $effectiveTimeInSeconds, '');

    if ($token->code === ZegoErrorCodes::success) {
        return response()->json(['token' => $token->token]);
    }

    return response()->json(['error' => 'Não foi possível gerar o token de acesso.'], 500);
});

// Listar salas
Route::middleware('auth:sanctum')->get('/rooms', function () {
    $rooms = \App\Models\VoiceRoom::where('is_active', true)
        ->with(['host:id,name,profile_photo', 'language:id,name'])
        ->withCount('participants')
        ->orderBy('created_at', 'desc')
        ->get();
    return response()->json($rooms);
});

// Criar sala (A ÚNICA ROTA POST /rooms AGORA)
Route::middleware('auth:sanctum')->post('/rooms', function (Request $request) {
    $request->validate([
        'title' => 'required|string|max:100',
        'description' => 'nullable|string|max:255',
        'language_id' => 'required|exists:languages,id',
    ]);

    $room = \App\Models\VoiceRoom::create([
        'title' => $request->title,
        'description' => $request->description,
        'host_id' => $request->user()->id,
        'language_id' => $request->language_id,
        'is_active' => true,
    ]);

    return response()->json(['status' => 'sucesso', 'room' => $room], 201);
});

// Entrar na sala (registar participante)
Route::middleware('auth:sanctum')->post('/rooms/{id}/join', function (Request $request, $id) {
    \DB::table('room_participants')->updateOrInsert(
        ['room_id' => $id, 'user_id' => $request->user()->id],
        ['updated_at' => now(), 'created_at' => now()]
    );
    return response()->json(['status' => 'ok']);
});

// Sair da sala (remover participante)
Route::middleware('auth:sanctum')->post('/rooms/{id}/leave', function (Request $request, $id) {
    \DB::table('room_participants')->where('room_id', $id)->where('user_id', $request->user()->id)->delete();
    return response()->json(['status' => 'ok']);
});

// Fechar uma sala (apenas o host)
Route::middleware('auth:sanctum')->delete('/rooms/{id}', function (Request $request, $id) {
    $room = \App\Models\VoiceRoom::findOrFail($id);
    if ($room->host_id !== $request->user()->id) {
        return response()->json(['error' => 'Apenas o anfitrião pode fechar a sala.'], 403);
    }
    $room->is_active = false;
    $room->save();
    return response()->json(['status' => 'sucesso']);
});


// Rota para listar os utilizadores que eu bloquei
Route::middleware('auth:sanctum')->get('/blocked-users', function (Request $request) {
    $blockedIds = \App\Models\Block::where('user_id', $request->user()->id)->pluck('blocked_user_id');
    $users = \App\Models\User::whereIn('id', $blockedIds)->get(['id', 'name', 'profile_photo']);
    return response()->json($users);
});