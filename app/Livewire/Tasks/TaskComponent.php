<?php

namespace App\Livewire\Tasks;

use App\Traits\AppNotificationTrait;
use Illuminate\Support\Facades\Auth;
use App\Enums\AppNotificationEnum;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Task;

class TaskComponent extends Component
{
    use WithPagination;
    use AppNotificationTrait;

    public $archived = false;

    public function mount(Request $request)
    {
        $validated = $request->validate(['archived' => 'nullable']);
        $this->archived = isset($validated['archived']) ? $validated['archived'] : false;
    }

    /**
     * Open Archived Tasks
     */
    public function openArchivedTask()
    {
        return redirect("/tasks?archived=true");
    }

    /**
     * Unarchive Tasks
     */
    public function unarchiveTask($taskId)
    {
        $task = Task::where('id', $taskId)->where('user_id', Auth::user()->id)->first();

        if (!$task) {
            $msg = 'Sorry, Task Not Found';
            $this->notify('error', $msg, AppNotificationEnum::ERROR);
            return;
        }

        $task->update([
            'archive_at' => null,
            'is_active' => true,
        ]);

        $msg = $task->title." Unarchived!!";
        $this->notify('success', $msg, AppNotificationEnum::SUCCESS);
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
