<?php

namespace App\Services\Tag;

use App\Models\Task;

class CreateTags
{
    private Task $task;
    private $tags;

    public function __construct(Task $task, $tags)
    {
        $this->task = $task;
        $this->tags = $tags;
    }

    public function handle()
    {
        $tags = [];
        foreach(explode(',',$this->tags) as $tag) {
            $tags[]  = ['name' => $tag];
        }
        return $this->task->tags()->createMany($tags);
    }

}
