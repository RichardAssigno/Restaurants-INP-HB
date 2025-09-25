<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transactionsoperateur;

    public function __construct($transactionsoperateur)
    {
        $this->transactionsoperateur = $transactionsoperateur;
    }

    // Canal de diffusion
    public function broadcastOn(): Channel
    {
        return new Channel('transactions');
    }

    public function broadcastAs(): string
    {
        return 'TransactionUpdated';
    }
}
