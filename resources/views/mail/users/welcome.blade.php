<x-mail::message>
# Hi {{ $user->full_name }}

<h3 style="text-align:center;color:black">
    Welcome to MyTodoApp, your ultimate productivity companion! ✅ 🚀
</h3>

<p style="text-align:justify;color:black;">
    In today's fast-paced digital world, staying organized is essential, 
    and MyTodoApp makes it effortless. Easily create, manage, and track 
    your tasks, while automated to-do alerts ensure you never miss a 
    deadline!
</p>

<p style="text-align:justify;color:black;">
    Stay ahead, stay efficient, and let MyTodoApp handle the reminders while 
    you focus on what truly matters. Happy planning! 🚀
</p>

<p style="text-align:left;color:black">
    Helpful Tips to Get Started:

    Dashboard:
    - Add new tasks with the "Add Task" button.
    - Scroll down to view tasks arranged in a table format.
    - Newly added tasks appear at the top—showing your 12 most recent tasks based on time.
    -- Each task panel includes:
    --- Task Type (top left).
    --- Serial Number (top right).
    --- Task Name (green background) with an edit button.
    --- Start Time and Due Time.
    --- Done" and "Delete" buttons grouped together for quick action.

    Tasks:
    - View all your tasks—past and upcoming.
    - Access archived tasks/events with the "Archived" button at the top.
    - View tasks and unarchive them when needed.

    Subscriptions:
    - Manage all your notification subscription services.
    - View, activate, or deactivate a subscription.
    - Add new subscriptions based on your preferences.
    
    Notifications:
    - See your read and unread notifications.
    - Click on a notification to open and read it.
</p>

<p style="text-align:left;color:black">
    We have many exciting new features and enhancements on the way! 🚀 <br>
    Stay tuned as we continue to roll out updates designed to improve your experience.
</p>

<p style="text-align:left;color:black">
    Welcome once again, {{$user->first_name}} - we’re thrilled to have you on board!
</p>

<p style="text-align:left;color:black">
    Anthony Osadolor,<br>
    <small>Product Designer</small>
</p>

{{ config('app.name') }}
</x-mail::message>
