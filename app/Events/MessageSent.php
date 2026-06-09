<?php
namespace App\Events;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // WAJIB NOW

class MessageSent implements ShouldBroadcastNow
{
    use \Illuminate\Foundation\Events\Dispatchable,
        \Illuminate\Broadcasting\InteractsWithSockets,
        \Illuminate\Queue\SerializesModels;

    // Pastikan berstatus PUBLIC
    public $user;
    public $post;

    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    public function broadcastOn(): array
    {
        return [
            new \Illuminate\Broadcasting\Channel('chat-room'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
