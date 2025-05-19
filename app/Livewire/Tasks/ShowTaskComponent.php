<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;
// use Livewire\Attributes\On;
use Livewire\Attributes\On;

class ShowTaskComponent extends Component
{
    public Task $task;

    #[On('view-task.{task.id}')]
    public function updateTaskView()
    {
        // $this->dispatch('view-task', $taskId);
        dd('Yes I got it');        
    }

    // #[On('view-task')]
    public function render()
    {
        return view('livewire.tasks.show-task-component')->with([
            'task' => $this->task,
        ]);
    }
}
