<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_suspended',
        'is_premium',
        'premium_expires_at',
        'fcm_token',
        'age', 'bio', 'location', 'profile_photo', 'extra_photos', 'profile_completion', 'date_of_birth',
        'daily_translations_count', 'last_translation_date',
        'is_translator',
        'translator_languages', // Adicione este campo para armazenar os idiomas do tradutor
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'premium_expires_at' => 'datetime',
            'extra_photos' => 'array',
            'date_of_birth' => 'date',
            'last_translation_date' => 'date',
            'translator_languages' => 'array', 
            
        ];
    }

            public function languages()
                {
                    // O segundo argumento 'user_languages' diz ao Laravel o nome exato da nossa tabela
                    return $this->belongsToMany(Language::class, 'user_languages')
                                ->withPivot('type', 'level')
                                ->withTimestamps();
                }

                    // Mensagens que o utilizador enviou
            public function sentMessages()
            {
                return $this->hasMany(Message::class, 'sender_id');
            }

            // Mensagens que o utilizador recebeu
            public function receivedMessages()
            {
                return $this->hasMany(Message::class, 'receiver_id');
            }

                public function paymentRequests()
    {
        return $this->hasMany(PaymentRequest::class);
    }
    
    // Função auxiliar para verificar se o premium está ativo
    public function isPremiumActive()
    {
        return $this->is_premium && $this->premium_expires_at && $this->premium_expires_at->isFuture();
    }

        public function translations()
    {
        return $this->hasMany(Translation::class, 'translator_id');
    }

    public function wallet()
    {
        return $this->hasOne(TranslatorWallet::class);
    }


     /**
     * Envia a notificação de recuperação de password com um link personalizado.
     */
    public function sendPasswordResetNotification($token)
    {
        // Em vez de usar a rota padrão, criamos um link com o token e o email
        // O {email} é necessário para o reset funcionar
        $url = url('/reset-password?token=' . $token . '&email=' . $this->email);

        $this->notify(new class($url) extends ResetPassword {
            public $url;
            public function __construct($url) { $this->url = $url; }
            public function toMail($notifiable)
            {
                return (new MailMessage)
                    ->subject('Recuperação de Palavra-passe - LínguaViva Moz')
                    ->line('Recebemos um pedido para alterar a palavra-passe da tua conta.')
                    ->action('Alterar Palavra-passe', $this->url)
                    ->line('Se não fizeste este pedido, ignora este e-mail.');
            }
        });
    }

        // Pessoas que este utilizador segue
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // Pessoas que seguem este utilizador (seguidores)
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }
}
