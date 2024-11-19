<?php

namespace App\Events;

use App\Models\Question;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuestionSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $question;

    public $receiver;

    /**
     * Create a new event instance.
     */
    public function __construct(Question $question, User $receiver)
    {
        $this->question = $question;
        $this->receiver = $receiver;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
