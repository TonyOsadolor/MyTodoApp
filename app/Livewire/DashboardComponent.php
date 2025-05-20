<?php

namespace App\Livewire;

date_default_timezone_set("Africa/Lagos");

use Illuminate\Support\Facades\Notification;
use App\Notifications\UserAppNotification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Traits\AppNotificationTrait;
use Illuminate\Validation\Validator;
use App\Http\Resources\TaskResource;
use App\Enums\AppNotificationEnum;
use Livewire\WithoutUrlPagination;
use App\Livewire\Tasks\ViewTask;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use App\Services\TaskService;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Task;
use Flux\Flux;

class DashboardComponent extends Component
{
    use AppNotificationTrait;
    use WithPagination;

    /**
     * Handle an incoming task request.
     */   
    public $category_id = null;
    public string $title = '';
    public string $description = '';
    public string $start_date = '';
    public string $start_time = '';
    public string $end_date = '';
    public string $end_time = '';
    public $notify_me = false;
    public $all_day = false;

    /**
     * Save Task
     */
    public function addTask(): void
    {
        $response = Gate::inspect('create', \App\Models\Task::class);
        if (!$response->allowed()) {
            Flux::modal('add-new-task')->close();
            $msg = 'Sorry, Operation Not Complete';
            $this->notify('error', $msg, AppNotificationEnum::ERROR);
            return;
        }

        $validated = $this->validate(
            [
                'category_id' => ['required', 'integer', 'exists:task_categories,id'],
                'title' => [
                    'required',
                    'string',
                    Rule::unique('tasks')->where(function ($query) {
                        return $query->where('user_id', Auth::user()->id)
                            ->whereNull('deleted_at')
                            ->where('task_category_id', $this->category_id);
                    })
                ],
                'description' => ['required', 'string'],
                'start_date' => ['required', 'date', 'date_format:Y-m-d'],
                'start_time' => ['required_if:all_day,false', 'date_format:H:i'],                
                'all_day' => ['nullable', 'boolean'],
                'end_date' => [
                    // Rule::requiredIf($this->category_id === 1 && $this->all_day === false),
                    // 'required_if:category_id,1,all_day,false', 
                    'date', 'date_format:Y-m-d'
                ],
                'end_time' => ['required_with:end_date', 'date_format:H:i'],
                'notify_me' => ['nullable', 'boolean'],
            ],
            [
                'category_id.required' => 'Task Category is required',                
                'category_id.integer' => 'Invalid category selected',                
                'category_id.exists' => 'Category not found',

                'title.required' => 'Task :attribute is required',
                'title.string' => ':attribute must be a string',
                'title.unique' => 'You already have a Task with same name',

                'description.required' => 'Task :attribute is required',
                'description.string' => 'Task :attribute must be a string',

                'start_date.required' => 'Task :attribute is required',
                'start_date.date' => 'Task :attribute must be a date',
                'start_date.date_format' => 'Task :attribute must match YYYY-MM-DD',

                'start_time.required_if' => 'Start time is required for Event',
                'start_time.time' => 'Task :attribute must be a time',
                'start_time.time_format' => 'Task :attribute must match HH-MM-SS - AM/PM',

                'end_date.required_if' => 'End date is required for Event',
                'end_date.date' => 'Task :attribute must be a date',
                'end_date.date_format' => 'Task :attribute must match YYYY-MM-DD',

                'end_time.required_with' => 'Task :attribute is required',
                'end_time.time' => 'Task :attribute must be a time',
                'end_time.time_format' => 'Task :attribute must match HH-MM-SS - AM/PM',

                'notify_me.boolean' => 'Notify me must be in True or False',
                'all_day.boolean' => 'Notify me must be in True or False',
            ]
        );
        
        $startTime = \Carbon\Carbon::parse($validated['start_date']." ".$validated['start_time'])->toDateTimeLocalString();
        $endTime = \Carbon\Carbon::parse($validated['end_date']." ".$validated['end_time'])->toDateTimeLocalString();
        $validated['task_category_id'] = $validated['category_id'];
        $dbStartDate = !empty($validated['start_date']) ? $startTime : null;
        $dbEndDate = !empty($validated['end_date']) ? $endTime : null;

        if (\Carbon\Carbon::parse($dbStartDate)->lessThanOrEqualTo(now()) && $this->all_day != true) {
            Flux::modal('add-new-task')->close();
            $msg = 'Start Time must be in the future';
            $this->notify('error', $msg, AppNotificationEnum::ERROR);
            return;
        }

        // dd($validated);

        $task = Task::create([
            'user_id' => Auth::user()->id,
            'task_category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => preg_replace('/\bhttps?:\/\/\S+/i', '<a href="$0" target="_blank">$0</a>', $validated['description']),
            'start_date' => $dbStartDate ?? null,
            'due_date' => $dbEndDate ?? null,
            'notify_me' => (bool) $validated['notify_me'],
            'is_active' => true,
        ]);

        if ($task->notify_me === true) {
            $taskService = app()->make(TaskService::class)->setReminders(Auth::user(), $task);
        }

        $this->reset();
        Flux::modal('add-new-task')->close();
        $msg = 'Task Added Successfully';
        $this->notify('success', $msg, AppNotificationEnum::SUCCESS);
    }

    /**
     * Render the View
     */
    public function render()
    {
        $user = Auth::user();
        $myTasks = Task::query()->active()->mytasks($user);
        $greetings = "";
        $callOutColor = "";
        $todaysDate = date('Y-m-d');
        $currentTime = now()->format('H:i:s');
        $endOfToday = now()->endOfDay()->format('H:i');
        $hour = date("H:i");
        $time = date("h:i:sA");
        if($hour <= "11:59"){
            $greetings = "Good Morning...";
            $callOutColor = 'success';
        } elseif($hour <= "16:59") {
            $greetings = "Good Afternoon...";
            $callOutColor = 'warning';
        } else{
            $greetings = "Good Evening...";
            $callOutColor = 'danger';
        }
        $activeTasks = $myTasks->where('start_date', '>', now())->whereNull('archive_at')
            ->whereNull('completed_at')->orderBy('created_at', 'desc')->take(12)->get();
        $upcomingTasks = $myTasks->whereBetween('due_date', [now(), now()->addHours(2)])
            ->whereNull('completed_at')
            ->whereNull('archive_at')->orderBy('created_at', 'desc')->take(12)->get();

        return view('livewire.dashboard-component')->with([
            'greetings' => $greetings,
            'callOutColor' => $callOutColor,
            'todaysDate' => $todaysDate,
            'currentTime' => $currentTime,
            'endOfToday' => $endOfToday,
            'time' => $time,
            'activeTasks' => $activeTasks,
            'upcomingTasks' => $upcomingTasks,
            'tableTasks' => Task::active()->mytasks($user)->orderBy('created_at', 'desc')->paginate(10),
        ]);
    }

    /**
     * View a Task
     */
    public function closeModal(string $name)
    {
        // Flux::modal($name)->close();
        $this->modal($name)->close();
    }

    /**
     * Edit Task
     */
    public $update_event_id;
    public $update_title;
    public $update_description;
    public $update_start_date;
    public $update_start_time;
    public $update_due_date;
    public $update_due_time;
    public $update_all_day;
    public function editTask(int $taskId)
    {
        $task = Task::find($taskId);

        $this->update_event_id = $task->category->name;
        $this->update_title = $task->title;
        $this->update_description = $task->description;
        $this->update_start_date = $task->start_date ? $task->start_date->toDateString() : null;
        $this->update_start_time = $task->start_date ? $task->start_date->toTimeString() : null;
        $this->update_due_date = $task->due_date ? $task->due_date->toDateString() : null;
        $this->update_due_time = $task->due_date ? $task->due_date->toTimeString() : null;
        $this->update_all_day = $task->all_day === 1 ? true : false;
    }

    public function updateTask(int $taskId)
    {
        $task = Task::find($taskId);

        $data = $this->validate(
            [
                'update_title' => [
                    'required',
                    'string',
                    Rule::unique('tasks', 'title')->ignore($task->id)->where(function ($query) {
                        return $query->where('user_id', Auth::user()->id);
                    }),
                ],
                'update_description' => ['required', 'string'],
                'update_start_date' => ['required', 'date', 'date_format:Y-m-d'],
                'update_start_time' => ['required_if:update_all_day,false'],                
                'update_all_day' => ['nullable', 'boolean'],
                'update_due_date' => ['required_if:update_all_day,false', 'date_format:Y-m-d'],
                'update_due_time' => ['required_with:end_date'],
            ],
            [
                'update_title.required' => 'Title is Required',
                'update_title.unique' => 'You already have a task with same name',

                'update_description.required' => 'Description is Required',

                'update_start_date.required' => 'Start date is Required',

                'update_start_time.required' => 'Start time is Required', 

                'update_due_date.required' => 'Due date is Required',

                'update_due_time.required_with' => 'Due time is Required',                
            ]
        );

        $startTime = \Carbon\Carbon::parse($data['update_start_date']." ".$data['update_start_time'])->toDateTimeLocalString();
        $endTime = \Carbon\Carbon::parse($data['update_due_date']." ".$data['update_due_time'])->toDateTimeLocalString();
        $dbStartDate = !empty($data['update_start_date']) ? $startTime : null;
        $dbEndDate = !empty($data['update_due_date']) ? $endTime : null;

        if (\Carbon\Carbon::parse($dbEndDate)->lessThanOrEqualTo(now())) {
            Flux::modal("editTask-".$task->id)->close();
            $msg = 'Due Date must be in the future';
            $this->notify('error', $msg, AppNotificationEnum::ERROR);
            return;
        }

        $task->update([
            'title' => $data['update_title'],
            'description' => $data['update_description'],
            'start_date' => $dbStartDate,
            'due_date' => $dbEndDate,
            'all_day' => $data['update_all_day'],
            'is_active' => true,
        ]);

        $taskService = app()->make(TaskService::class)->setReminders(Auth::user(), $task);

        $this->reset();
        $this->closeModal("editTask-".$task->id);
        $msg = $task->title ." Updated!!";
        $this->notify('success', $msg, AppNotificationEnum::SUCCESS);
    }

    /**
     * Delete Task
     */
    public function markDone(int $taskId)
    {
        $user = Auth::user();
        $task = Task::find($taskId);

        $task->update([
            'completed_at' => now(),
            'completed_by' => $user->id,
        ]);

        $this->closeModal("markDone-".$task->id);
        $msg = $task->title ." Completed!!";
        $this->notify('success', $msg, AppNotificationEnum::SUCCESS);
    }

    /**
     * Delete Task
     */
    public function deleteTask(int $taskId)
    {
        $user = Auth::user();
        $task = Task::find($taskId);

        $response = Gate::inspect('delete', $task);
        if (!$response->allowed()) {
            $this->closeModal("deleteTask-".$task->id);
            $msg = 'Sorry, Operation Not Complete';
            $this->notify('error', $msg, AppNotificationEnum::ERROR);
            return;
        }

        $task->delete();

        $this->closeModal("deleteTask-".$task->id);
        $msg = "Task Deleted!!";
        $this->notify('success', $msg, AppNotificationEnum::SUCCESS);
    }

    /**
     * Delete Task
     */
    public function archiveTask(int $taskId)
    {
        $user = Auth::user();
        $task = Task::find($taskId);

        $response = Gate::inspect('update', $task);
        if (!$response->allowed()) {
            $this->closeModal("deleteTask-".$task->id);
            $msg = 'Sorry, Operation Not Complete';
            $this->notify('error', $msg, AppNotificationEnum::ERROR);
            return;
        }

        $task->update([
            'archive_at' => now(),
            'is_active' => false,
        ]);

        $this->closeModal("editTask-".$task->id);
        $msg = $task->title." Archived!!";
        $this->notify('success', $msg, AppNotificationEnum::SUCCESS);
    }

    /**
     * Redirect for View Task
     */
    public function dashboardViewTask(int $taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            return redirect("/tasks/{$task->uuid}");
        }
        
    }
}
