<?php

namespace App\Events\Project;

use App\Helper\TimelineHelper;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Data implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $project_id;
    public $data;
    public $type;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($project_id, $type, $data)
    {
        $this->project_id = $project_id;
        $this->type = $type;
        if($data instanceof Model) {
            $this->data = TimelineHelper::removeNullAttr($data->toArray());
        } else {
            $this->data = TimelineHelper::removeNullAttr($data);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('project.'.$this->project_id);
    }
}
