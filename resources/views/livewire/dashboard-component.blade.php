<div>

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        
        <div>
            <!-- Greetings Banner Starts -->
            <flux:callout icon="clock" color="lime" inline>
                <flux:callout.text ><span id="clock">Live Time</span></flux:callout.text>
            </flux:callout>
            <!-- Greetings Banner End -->

            <hr style="margin: 8px auto!important; border: 0px transparent;">

            <!-- Greetings Banner Starts -->
            <flux:callout icon="check-circle" variant="{{$callOutColor}}" inline>
                <flux:callout.heading><i>{{$greetings}}</i> <h3 class="text-center">{{ auth()->user()->full_name }}</h3></flux:callout.heading>
                <flux:callout.text>What would you like to do today?</flux:callout.text>

                <x-slot name="actions">
                    <flux:modal.trigger name="add-new-task">
                        <flux:button variant="primary">Add Task?</flux:button>
                    </flux:modal.trigger>
                    <flux:button variant="ghost" href="#dashboardOpenTask">View Tasks?</flux:button>
                </x-slot>
            </flux:callout>
            <!-- Greetings Banner End -->

            <!-- Upcoming Tasks Starts -->
            <hr style="margin: 8px auto!important; border: 0px transparent;">
            @if(isset($upcomingTasks) && $upcomingTasks->count() > 0)
                <flux:callout icon="clock" color="purple" inline>
                    <flux:callout.heading>Don't forget your UPCOMING TASKS....</flux:callout.heading>

                <?php $calloutsn = 1; ?>

                @foreach($upcomingTasks as $upcomingTask)

                    <flux:callout.text wire:key="{{ $upcomingTask->id }}">
                        {{ $calloutsn }}. <span>{{ucwords(optional($upcomingTask)->title)}}</span>
                        <flux:modal.trigger name="viewTask-{{$upcomingTask->id}}">
                            <flux:button>View Task</flux:button>
                        </flux:modal.trigger>
                    </flux:callout.text>

                    <?php $calloutsn++; ?>

                    {{-- View Modal Ends --}}
                    <flux:modal :name="'viewTask-'.$upcomingTask->id" 
                            :dismissible="false">
                        <div class="md:w-96 space-y-6">
                            <div>
                                <flux:button icon="archive-box" wire:click="archiveTask({{$upcomingTask->id}})" variant="primary">Archive Task</flux:button>                                
                            </div>
                            <div>
                                <flux:text class="mt-2">Type:</flux:text>
                                <flux:heading size="lg">{{optional($upcomingTask)->category->name}}</flux:heading>
                            </div>

                            <div>
                                <flux:text class="mt-2">Title:</flux:text>
                                <flux:heading size="lg">{{optional($upcomingTask)->title}}</flux:heading>
                            </div>

                            <div>
                                <flux:text class="mt-2">Description:</flux:text>
                                <flux:heading size="lg" style="text-align:justify;">{{optional($upcomingTask)->description}}</flux:heading>
                            </div>

                            <div class="grid grid-cols-2 gap-2 p-2">
                                <div>
                                    <flux:text class="mt-2">Start Time:</flux:text>
                                    <flux:heading size="lg">
                                        {{ \Carbon\Carbon::parse(optional($upcomingTask)->start_date)->format('F j, Y g:i A') ?? 'No date available' }}
                                    </flux:heading>
                                </div>
                                @isset($upcomingTask->due_date)
                                <div>
                                    <flux:text class="mt-2">Due Time:</flux:text>
                                    <flux:heading size="lg">
                                        {{ \Carbon\Carbon::parse(optional($upcomingTask)->due_date)->format('F j, Y g:i A') ?? 'No date available' }}
                                    </flux:heading>
                                </div>
                                @endisset                            
                            </div>

                            @isset($upcomingTask->completed_at)
                            <div class="grid grid-cols-2 gap-2 p-2">
                                <div>
                                    <flux:text class="mt-2">Completed At:</flux:text>
                                    <flux:heading size="lg">
                                        {{ \Carbon\Carbon::parse(optional($upcomingTask)->completed_at)->format('F j, Y g:i A') ?? 'No date available' }}
                                    </flux:heading>
                                </div>
                                <div>
                                    <flux:text class="mt-2">Completed By:</flux:text>
                                    <flux:heading size="lg">
                                        {{ optional($upcomingTask)->completeBy->full_name ?? 'N/A' }}
                                    </flux:heading>
                                </div>                           
                            </div>
                            @endisset

                            @if($upcomingTask->notify_me && $upcomingTask->notify_me === 1)
                            <div>
                                <flux:text class="mt-2">Send Notifications?</flux:text>
                                <flux:heading size="lg">
                                    {{ $upcomingTask->notify_me === 1 ? 'Yes' : 'No'}}
                                </flux:heading>
                            </div>
                            @endif

                            <div class="flex">
                                <flux:spacer />
                                <flux:button icon="x-mark" wire:click="closeModal('viewTask-{{$upcomingTask->id}}')" variant="danger">Close</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                @endforeach
                </flux:callout>
            @endif
            <!-- Upcoming Tasks End -->
        </div>

        @if ($activeTasks && count($activeTasks) > 0)
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <?php $titleSn = 1; ?>            
            @foreach ($activeTasks as $task)

                <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-2" 
                        style="max-height: 450px!important;height:450px!important;">
                    
                    {{-- Number and Task type Tag Starts --}}
                    <?php $indicator = $task->category->id === 1 ? 'yellow-300' : 'red-500'; ?>
                    <div class="grid grid-cols-2 p-0.5 mb-1 text-centertext-white-800 rounded-lg dark:text-white-400" role="alert">
                        <div class="flex justify-start">
                            <span class="flex items-center text-sm font-medium text-gray-900 dark:text-white me-3"><span class="flex w-3 h-3 me-3 bg-{{$indicator}} rounded-full shrink-0"></span>
                                {{ $task->category->name }}
                            </span>
                        </div>
                        <div class="flex justify-end">
                            <span class="text-yellow-300 dark:text-yellow-300 font-bold text-center">
                                {{$titleSn}}
                            </span>
                        </div>
                    </div>
                    {{-- Number and Task type Tag Ends --}}

                    <div class="grid grid-flow-row-dense grid-cols-3 p-4 mb-4 text-center bg-green-900 text-white-800 rounded-lg dark:bg-green-800 dark:text-white-400" role="alert">
                        <div class="flex justify-start col-span-2">
                            <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ ucwords(optional($task)->title) }}</h5>
                        </div>
                        <div class="flex justify-end">
                            <flux:modal.trigger name="editTask-{{$task->id}}" wire:click="editTask({{ $task->id }})">
                                <flux:button icon="pencil-square" variant="ghost" class="text-gray-900 bg-yellow-400 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-400 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-yellow-400 dark:hover:bg-yellow-300 dark:focus:ring-yellow-600 dark:text-gray-900 cursor-pointer">
                                    {{-- <x-heroicon-o-pencil-square class="w-4 h-4 text-gray-900" /> --}}
                                </flux:button>
                            </flux:modal.trigger>
                        </div>
                    </div>

                    <!-- Edit Task Modal Starts -->
                    <flux:modal :name="'editTask-'.$task->id" :dismissible="false" wire:key="editTask{{ $task->id }}">
                        <form wire:submit="updateTask({{$task->id}})">
                            <div class="space-y-6 md:w-96">
                                <div>
                                    <flux:button icon="archive-box" wire:click="archiveTask({{$task->id}})" variate="primary">Archive Task</flux:button>
                                </div>
                                <div>
                                    <flux:heading size="lg">Update "{{ optional($task)->title }}" Task</flux:heading>
                                    <flux:text class="mt-2">Make changes to the Task and Click Submit</flux:text>
                                </div>

                                <flux:input label="Task Category" readonly wire:model.live="update_event_id" />

                                <flux:input label="Title" wire:model.live="update_title" />

                                <flux:textarea rows="6" resize="none" label="Description" wire:model.live="update_description"></flux:textarea>

                                <div>
                                    <label for="update_start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date</label>
                                    <input type="date" wire:model.live="update_start_date" min="{{optional($task)->start_date->toDateString() }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                                    @error('update_start_date')
                                        <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="update_start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Time</label>
                                    <input type="time" wire:model.live="update_start_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                                    @error('update_start_time')
                                        <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <flux:field variant="inline">
                                    <flux:label>All Day Event?</flux:label>
                                    <flux:switch wire:model.live="update_all_day" />
                                    <flux:error name="update_all_day" />
                                </flux:field>

                                <div>
                                    <label for="update_due_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Due Date</label>
                                    <input type="date" wire:model.live="update_due_date" min="{{$todaysDate}}" value="{{$todaysDate}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                                    @error('update_due_date')
                                        <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="update_due_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Due Time</label>
                                    <input type="time" wire:model.live="update_due_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                                    @error('update_due_time')
                                        <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex">
                                    <flux:spacer />

                                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                                </div>
                                
                            </div>
                        </form>
                    </flux:modal>
                    <!-- Edit Task Modal Ends -->

                    <div class="flex flex-col items-center pb-10 p-4">
                        {{-- <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="/docs/images/people/profile-picture-3.jpg" alt="Bonnie image"/> --}}

                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ substr(optional($task)->description, 0, 250) . (strlen(optional($task)->description) > 250 ? ' ... Continued' : '') }}
                        </span>

                        <hr style="margin: 8px auto!important; border: 0px transparent;">
                        <div class="grid grid-cols-1 gap-2 p-2">
                            <div>
                                <span class="bg-green-500 text-gray-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-600 dark:text-gray-100">
                                    Starts By: <span>{{ \Carbon\Carbon::parse(optional($task)->start_date)->format('F j, Y g:i A') ?? 'No date available' }}</span>
                                </span>
                            </div>
                            @isset($task->due_date)
                            <?php
                                $isExpiringSoon = $task->due_date > now()->addMinutes(10);
                                $themeColor = $isExpiringSoon ? 'yellow' : 'red';
                                $textColor = $isExpiringSoon ? '800' : '100';
                                $addClass = $isExpiringSoon ?: 'animate__animated animate__flash animate__infinite animate__slower animate__delay-2s';
                            ?>
                            <div>
                                {{-- <flux:text variant="strong" hidden
                                    class="text-center tracking-normal font-semibold font-stretch-normal font-serif  p-px m-auto">
                                    Countdown Timer: <span>HERE</span>
                                </flux:text> --}}
                                <span class="bg-{{$themeColor}}-400 {{$addClass}} text-gray-{{$textColor}} text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-{{$themeColor}}-500 dark:text-gray-{{$textColor}}">
                                    
                                    Due Date: 
                                    {{ \Carbon\Carbon::parse(optional($task)->due_date)->format('F j, Y g:i A') ?? 'No date available' }}
                                </span>
                            </div>
                            @endisset
                        </div>

                        <hr style="margin: 8px auto!important; border: 0px transparent;">
                        
                        {{-- <div class="inline-flex rounded-md shadow-xs mt-px-5" role="group">
                            <button type="button" class="inline-flex items-center cursor-pointer px-4 py-2 text-sm font-medium text-white-900 bg-green-900 border border-green-200 rounded-s-lg hover:bg-green-100 hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-700 focus:text-green-700 dark:bg-green-800 dark:border-green-700 dark:text-white dark:hover:text-white dark:hover:bg-green-700 dark:focus:ring-green-500 dark:focus:text-white">
                                <x-heroicon-o-check-badge class="w-4 h-4 text-white-800" />
                                Done?
                            </button>
                            <button data-modal-target="popup-modal" data-modal-toggle="popup-modal" wire:key="{{ $task->id }}" type="button" class="inline-flex items-center cursor-pointer px-4 py-2 text-sm font-medium text-white-900 bg-red border border-red-200 rounded-e-lg hover:bg-red-100 hover:text-red-700 focus:z-10 focus:ring-2 focus:ring-red-700 focus:text-red-700 dark:bg-red-800 dark:border-red-700 dark:text-white dark:hover:text-white dark:hover:bg-red-700 dark:focus:ring-red-500 dark:focus:text-white">
                                <x-heroicon-o-trash class="w-4 h-4 text-white-800" />
                                Delete
                            </button>
                        </div> --}}

                        <!-- Mark Done and Delete Task Section Starts -->
                        <flux:button.group>
                            <flux:modal.trigger name="markDone-{{$task->id}}">
                                <flux:button variant="primary" icon="check-badge">Done?</flux:button>
                            </flux:modal.trigger>
                            <flux:modal.trigger name="deleteTask-{{$task->id}}">
                                <flux:button variant="danger" icon="trash">Delete?</flux:button>
                            </flux:modal.trigger>
                        </flux:button.group>

                        <!-- Done Modal Starts -->
                        <flux:modal :name="'markDone-'.$task->id" class="min-w-[22rem]" wire:key="{{ $task->id }}">
                            <div class="space-y-6">
                                <div>
                                    <flux:heading size="lg">Are you Done?</flux:heading>
                                    <flux:text class="mt-2">
                                        <p>You're about to mark  this task as DONE.</p>
                                        {{-- <p>This action cannot be reversed.</p> --}}
                                    </flux:text>
                                </div>
                                <div class="flex gap-2">
                                    <flux:spacer />
                                    <flux:modal.close>
                                        <flux:button variant="danger">No Cancel</flux:button>
                                    </flux:modal.close>
                                    <flux:button type="button" variant="filled" wire:click="markDone({{$task->id}})">YES, I am Done</flux:button>
                                </div>
                            </div>
                        </flux:modal>
                        <!-- Done Modal Ends -->

                        <!-- Delete Modal Starts -->
                        <flux:modal :name="'deleteTask-'.$task->id" class="min-w-[22rem]" wire:key="{{ $task->id }}">
                            <div class="space-y-6">
                                <div>
                                    <flux:heading size="lg">Delete Task?</flux:heading>
                                    <flux:text class="mt-2">
                                        <p>You're about to delete this task.</p>
                                        <p>This action cannot be reversed.</p>
                                    </flux:text>
                                </div>
                                <div class="flex gap-2">
                                    <flux:spacer />
                                    <flux:modal.close>
                                        <flux:button variant="ghost">No Cancel</flux:button>
                                    </flux:modal.close>
                                    <flux:button type="button" variant="danger" wire:click="deleteTask({{$task->id}})">Delete TASK</flux:button>
                                </div>
                            </div>
                        </flux:modal>
                        <!-- Delete Modal Ends -->

                        <!-- Mark Done and Delete Task Section Ends -->

                    </div>
                </div>

                <?php $titleSn++; ?>             
            @endforeach
            
        </div>
        @else
        <div id="toast-success" class="flex items-center w-full p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                </svg>
                <span class="sr-only">Warning icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">No Task on your desk</div>
        </div>
        @endif

        <flux:separator />
        
        {{-- Task Table Starts --}}
        @if($tableTasks && count($tableTasks) > 0)
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                S/N
                            </th>
                            <th scope="col" class="px-6 py-3">
                                NAME
                            </th>
                            <th scope="col" class="px-6 py-3">
                                START TIME
                            </th>
                            <th scope="col" class="px-6 py-3">
                                ACTION
                            </th>
                        </tr>
                    </thead>
                    <?php $sn = 1; ?>
                    @foreach($tableTasks as $tableTask)
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $sn }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ optional($tableTask)->title}}
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse(optional($tableTask)->start_date)->format('F j, Y g:i A') ?? 'No date available' }}
                                </td>
                                <td class="px-6 py-4">
                                    <flux:button variant="filled" cursor-pointer wire:click="dashboardViewTask({{optional($tableTask)->id}})"> View </flux:button>
                                </td>
                            </tr>
                        </tbody>
                        <?php $sn++; ?>
                    @endforeach
                </table>
                <!-- Pagination Links Starts -->
                {{ $tableTasks->links(data: ['scrollTo' => false]) }}
                <!-- Pagination Links Ends -->
            </div>

        </div>
        @endif
        {{-- Task Table Ends --}}

    </div>

    <!-- Add New Task Modal Starts --> 
    <flux:modal name="add-new-task" id="add-new-task" class="md:w-96" :dismissible="false">
        <div class="space-y-6 space-x-6">
            <div>
                <flux:heading size="lg">Add New Task</flux:heading>
                <flux:text class="mt-2">Enter new Task Details</flux:text>
            </div>
            
            <form wire:submit="addTask">
                <div class="grid gap-6 mb-6">
                    <div>
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select a Category</label>
                        <select 
                            required id="category_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                                focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                wire:model.live="category_id">
                            <option selected>Choose a Category</option>
                            @foreach (\App\Models\TaskCategory::active()->get() as $taskCat)
                                <option value="{{ optional($taskCat)->id }}">{{ optional($taskCat)->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Task Name</label>
                        <input type="text" id="title" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                            focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 
                            dark:border-gray-600 dark:placeholder-gray-400 dark:text-white 
                            dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Ladies Day Out" required 
                            wire:model.live="title" autocomplete="off"/>
                        @error('title')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Task Description</label>
                        <textarea id="description" rows="6" 
                            class="block p-2.5 w-full text-sm text-gray-900 
                            bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 
                            focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
                            dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 
                            dark:focus:border-blue-500" placeholder="Describe your Task here..." 
                            required style="resize:none" wire:model.live="description"></textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div class="relative max-w-sm">
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" wire:model.live="start_date" min="{{$todaysDate}}" value="{{$todaysDate}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John" required />
                        @error('start_date')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="relative">
                        <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start time:</label>
                        <input type="time" id="start_time" wire:model.live="start_time" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        @error('start_time')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="relative">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="all_day" id="all_day" name="all_day" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">All Day Event</span>
                    </label>
                    @error('all_day')
                        <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div class="relative max-w-sm">
                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Date:</label>
                        <input type="date" id="end_date" name="end_date" wire:model.live="end_date" min="{{$todaysDate}}" value="{{$todaysDate}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        @error('end_date')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="relative">
                        <label for="end_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End time:</label>
                        <input type="time" id="end_time" name="end_time" wire:model.live="end_time" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        @error('end_time')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="relative">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="notify_me" id="notify_me" name="notify_me" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Send Me Notifications</span>
                    </label>
                    @error('notify_me')
                        <p class="mt-2 text-sm text-red-500 dark:text-red-400"><span class="font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div class="relative">
                        <button type="submit" wire:loading.attr="disabled" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                    </div>

                    <div class="relative" wire:loading wire:target="addTask">
                        <div role="status">
                            <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-yellow-400" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                            <span class="sr-only">Adding...</span>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </flux:modal>
    <!-- Add New Task Modal Stops -->

    <script>
            function startTime() {
                const today = new Date();
                const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                const months = ["January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"];

                let day = days[today.getDay()];
                let date = today.getDate();
                let month = months[today.getMonth()];
                let year = today.getFullYear();

                let h = today.getHours();
                let m = today.getMinutes();
                let s = today.getSeconds();

                let ampm = h >= 12 ? "PM" : "AM";
                h = h % 12 || 12; // Convert 24-hour time to 12-hour format

                m = checkTime(m);
                s = checkTime(s);

                document.getElementById("clock").innerHTML = `${day} ${date}th ${month}, ${year} ${h}:${m}:${s} ${ampm}`;

                setTimeout(startTime, 1000);
            }

            function checkTime(i) {
                return i < 10 ? "0" + i : i;  // Add leading zero if needed
            }
        </script>
</div>
