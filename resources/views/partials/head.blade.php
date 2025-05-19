<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="description" content="Stay organized with our simple and intuitive to-do web app. Easily manage tasks, set priorities, and track progress—all in one place!">
<meta name="keywords" content="to-do list, task manager, productivity, organize tasks, reminders">
<meta name="author" content="Tony Osadolor Innovations">

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/assets/images/icon_logo.jpg" sizes="any">
<link rel="icon" href="/assets/images/icon_logo.jpg" type="image/jpg">
<link rel="apple-touch-icon" href="/assets/images/icon_logo.jpg">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
