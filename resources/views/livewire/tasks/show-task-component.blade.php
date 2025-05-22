<div>

    <div class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm sm:p-8 dark:bg-gray-800 dark:border-gray-700" wire:key="{{$task->id}}">
        @if($task->archive_at)
            <flux:icon.archive-box-arrow-down variant="solid" class="text-amber-500 dark:text-amber-300"/>
        @endif
        <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $task->title }}</h5>
        <p class="mb-5 text-xs text-gray-500 sm:text-lg dark:text-gray-400">{{ $task->created_at->format('F j, Y g:i A') }}</p>
    </div>

    <hr style="margin:5px auto!important;border: 0px transparent!important;">

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

        <p class="mb-3 font-normal text-gray-400 dark:text-gray-100">
            {{ $task->description }}
        </p>

        <hr style="border:1px solid whitesmoke;">

        <div class="flow-root">
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">

                <li class="py-3 sm:py-4">
                    <div class="flex items-center">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                Start Date:
                            </p>
                        </div>
                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                            {{ $task->start_date->format('F j, Y g:i A') }}
                        </div>
                    </div>
                </li>

                <li class="py-3 sm:py-4">
                    <div class="flex items-center">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                End Date:
                            </p>
                        </div>
                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                            {{ $task->due_date->format('F j, Y g:i A') }}
                        </div>
                    </div>
                </li>

                @if($task->completed_by)
                <li class="py-3 sm:py-4">
                    <div class="flex items-center">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                Completed By:
                            </p>
                        </div>
                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                            {{ $task->completeBy->full_name }}
                        </div>
                    </div>
                </li>
                <li class="py-3 sm:py-4">
                    <div class="flex items-center">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                Completed At:
                            </p>
                        </div>
                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                            {{ $task->completed_at->format('F j, Y g:i A') }}
                        </div>
                    </div>
                </li>
                @endif

                <li class="py-3 sm:py-4">
                    <div class="flex items-center">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                All Day Event?
                            </p>
                        </div>
                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                            {{ $task->all_day === 1 ? 'Yes' : 'No' }}
                        </div>
                    </div>
                </li>

                <li class="py-3 sm:py-4">
                    <div class="flex items-center">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                Send Me Notifications?
                            </p>
                        </div>
                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                            {{ $task->notify_me === 1 ? 'Yes' : 'No' }}
                        </div>
                    </div>
                </li>

                <li class="py-3 sm:py-4">
                    <div class="flex items-center">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                Active?
                            </p>
                        </div>
                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                            {{ $task->is_active  === 1 ? 'Yes' : 'No' }}
                        </div>
                    </div>
                </li>
                
            </ul>
        </div>
    </div>

    <hr style="margin:5px auto!important;border: 0px transparent!important;">
    
    <flux:button variant="primary" icon="arrow-left" onclick="history.back()">Go Back</flux:button>

</div>
