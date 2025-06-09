<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GenerationProgressEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $taskID;
    public $progress;
    public $status;
    public $imageData;


    public function __construct(string $taskID, float $progress, string $status, ?string $imageData = null)
    {
        $this->taskID = $taskID;
        $this->progress = $progress;
        $this->status = $status;
        $this->imageData = $imageData; 
    }


    public function broadcastOn()
    {
        return new Channel('generation-progress'.$this->taskID);
            //new PrivateChannel("generation-progress.{$this->taskID}"),
       
    }

    public function broadcastas(){
        return 'generation_progress.update';
    }
}
