<?php

namespace App\Livewire\Tasks;

use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskComponent extends Component
{
    use WithPagination;

    public $archived = false;

    public function mount(Request $request)
    {
        $validated = $request->validate(['archived' => 'nullable']);
        $this->archived = isset($validated['archived']) ? $validated['archived'] : false;
    }

    public function openArchivedTask()
    {
        return redirect("/tasks?archived=true");
    }

    public function render()
    {
        $tasks = $this->archived 
            ? Task::mytasks(Auth::user())->whereNotNull('archive_at')->orderBy('created_at', 'desc')->paginate(20) 
            : Task::mytasks(Auth::user())->whereNull('archive_at')->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.tasks.task-component')->with([
            'tasks' => $tasks,
            'archived' => $this->archived ? true : false,
        ]);
    }
}
