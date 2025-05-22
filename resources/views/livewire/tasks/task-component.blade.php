<div>
    <flux:navbar>
        <flux:navbar.item icon="archive-box" wire:click="openArchivedTask()" wire:loading.class="opacity-50">Archived</flux:navbar.item>
    </flux:navbar>

    @if(count($tasks) > 0)
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        S/N
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Start Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Due Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Date Added
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <?php $sn = 1; ?>
            @foreach($tasks as $task)
            <tbody wire:key="{{$task->uuid}}">
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $sn }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $task->title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $task->category->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse(optional($task)->start_date)->format('F j, Y g:i A') ?? 'No date available' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse(optional($task)->due_date)->format('F j, Y g:i A') ?? 'No date available' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $active = $task->is_active ? 'Active' : 'Inactive'}}
                    </td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse(optional($task)->created_at)->format('F j, Y g:i A') ?? 'No date available' }}
                    </td>
                    <td class="px-6 py-4" style="text-align:center!important;">
                        <a href="/tasks/{{$task->uuid}}" class="font-medium text-gray-200 dark:text-gray-100 hover:underline">View</a>
                        @if($task->archive_at)
                            <hr style="margin: 1px auto!important; border: 0px transparent;">
                            <a href="#" wire:click="unarchiveTask({{$task->id}})" class="font-medium text-green-400 dark:text-green-500 hover:underline">Unarchive</a>
                        @endif
                    </td>
                </tr>
            </tbody>
            <?php $sn++; ?>
            @endforeach
        </table>
        {{ $tasks->links(data: ['scrollTo' => false]) }}
    </div>
    @else
    <div id="toast-success" class="flex items-center w-full p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800" role="alert">
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
            </svg>
            <span class="sr-only">Warning icon</span>
        </div>
        <div class="ms-3 text-sm font-normal">No Task(s) Yet</div>
    </div>
    @endif
</div>
