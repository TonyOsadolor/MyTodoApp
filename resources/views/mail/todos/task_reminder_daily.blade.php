<x-mail::message>
# Yello {{ $user->full_name }}!!

<b>Your tasks for today</b> <br>
<p style="text-align:justify;color:black;">
    Top of the morning to you this bright {{ $day }} morning! <br>
    Very quickly we would like to remind you of your Tasks 
    and Events planned out for today. {{ $today }}
</p>

<p style="text-align:justify;color:black;">
    You have a total of {{ $tasks->count() }} {{ $tasks->count() > 1 ? 'tasks' : 'task' }} 
    for today, and they are as follows:    
</p>

<?php $sn = 1;?>
@foreach($tasks as $task)
<p style="color:black;">
    {{$sn}}. <b>{{$task->title}}</b> - {{$task->category->id === 1 ? 'An ' : 'A '}} {{$task->category->name}} - Starts By {{$task->start_date->format('g:i A')}} 
    @isset($task->due_date)<span>=> Ends By: {{ $task->due_date->format('l, F j, Y g:i A') ?? 'No due date set' }}</span>@endisset
</p>
<?php $sn++; ?>
@endforeach

<x-mail::button :url="'/dashboard'">
View Tasks
</x-mail::button>

Warm Regards,<br>
{{ config('app.name') }}
</x-mail::message>
