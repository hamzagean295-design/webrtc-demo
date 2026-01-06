<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebRTCSignal implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $consultationId,
        public string $type,
        public mixed $data
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // A presence channel allows us to know who is "here"
        return [
            new PresenceChannel('consultation.' . $this->consultationId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'WebRTCSignal';
    }

    /**
     * By default, Laravel broadcasts events to everyone on the channel,
     * including the sender. The toOthers() method on broadcast()
     * allows us to exclude the current user from the broadcast's recipients.
     *
     * @return bool
     */
    public function broadcastWhen(): bool
    {
        return true; // We will call toOthers() manually when dispatching
    }
}
