<?php

namespace App\Notifications;

use App\Models\Solicitar;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SolicitacaoDeVeiculo extends Notification
{
    protected $solicitar;

    public function __construct(Solicitar $solicitar)
    {
        $this->solicitar = $solicitar;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'solicitacao_id' => $this->solicitar->id,
            'user_name' => $this->solicitar->user->name,
            'veiculo' => $this->solicitar->veiculo->marca . ' ' . $this->solicitar->veiculo->modelo,
            'data_inicial' => $this->solicitar->data_inicial,
        ];
    }
}
