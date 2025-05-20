<x-mail::message>
# Yello {{ $user->full_name }}!!

Reminder for your <b><i>{{$task->category->name}}</i> - {{$task->title}}</b> <br>
<p style="text-align:justify;color:black;">
    Quick one Dear, don't forget you have {{ $task->category->id === 1 ? 'An Event' : 'A Task' }} 
    that is coming up {{$time}} minutes from now. Find the {{ $task->category->id === 1 ? 'Event' : 'Task' }} 
    details below:
</p>

<p style="color:black;">
    <b>{{$task->title}}</b> - {{$task->category->id === 1 ? 'An ' : 'A '}} {{$task->category->name}} - Starts By {{$task->start_date->format('g:i A')}} 
    @isset($task->due_date)<span>=> Ends By: {{ $task->due_date->format('l, F j, Y g:i A') ?? 'No due date set' }}</span>@endisset
</p>

<x-mail::button :url="'/dashboard'">
View Task
</x-mail::button>

Warm Regards,<br>
{{ config('app.name') }}
</x-mail::message>
