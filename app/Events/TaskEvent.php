<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const TYPE_ADD      = 'add';
    const TYPE_UPDATE   = 'update';
    const TYPE_REMOVE   = 'remove';

    /** @var string */
    private $type;
    /** @var Task|null */
    private $task;
    /** @var int|null */
    private $taskId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $task = null, $id = null)
    {
        $this->type = $type;
        $this->task = $task;
        $this->taskId = $id;

        $this->dontBroadcastToCurrentUser();
    }

    public function broadcastAs() {
        return 'task-monitor';
    }

    public function broadcastQueue() {
        return 'broadcastable';
    }

    public function broadcastWith() {
        $data = [
            'type'  => $this->type,
            'id'    => $this->taskId ?? $this->task->id,
        ];

        if ($this->type !== self::TYPE_REMOVE) {
            $data['name'] = $this->task->name;
            $data['is_complete'] = $this->task->is_complete ? 1 : 0;
        }

        return $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('tasks');
    }
}
