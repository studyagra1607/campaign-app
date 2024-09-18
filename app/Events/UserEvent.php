<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    private $userId;
    private $event;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $event, $data = null)
    {
        $this->userId = $userId;
        $this->event = $event;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return ["user.$this->userId"];
    }

    public function broadcastAs()
    {
        return "$this->event";
    }

    // public function broadcastWith()
    // {
    //     return $this->data;
    // }
}
