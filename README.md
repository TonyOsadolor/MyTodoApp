<p align="center"><a href="https://github.com/TonyOsadolor/MyTodoApp" target="_blank"><img src="https://osadolor.tinnovations.com.ng/img/relicon.jpg" width="200" height="auto"></a></p>

## About Project => MyTodoApp

MyTodoApp is a simple to do App, specially tailored for proper tasks management, 
it is completely free and open source for anyone who will like to work with it, 
or even use it to build a better version of it. The App was inspired by my days of
Internship with Zuri Internship Training, a similar version was built with pure & 
Core PHP traditionally, but I wanted more to it, tried building with some couples 
of young developers like me, that didn't work out, Last week [Friday 2nd of May 2025] 
I felt the need to revamp the project, using purely Laravel 12 and it starter Kits. 

Hopefully, this project will reach it full core of development, and I hope to always 
have time to update and enhance it features.

What I aim at achieving here is, a Todo WebApp [Mobile App might be introduced later] 
that helps users write down tasks, help them track it and complete it, by keying into 
WhatsApp and SMS APIs, sending reminders to users of their upcoming tasks, and deadlines. 
Personally, I can set up a task, but I might even forget, but with the help of Email, 
SMS and WhatsApp Reminders, I can keep them in mind and have them checked off my List. 
And hopefully, I hope this App does it work completely.

### Stacks

- **Backend : PHP / Laravel**
- **Frontend : Livewire Starter Kit - Blade, Tailwind Css & Flux UI**

## Setup Guide
##### Setting up your workspace offline
##### Laravel Version => '12.10.2'
Before running this app locally make sure you have the following software installed:
<ul>
    <li>XAMPP/WAMP/LAMP or it's equivalent</li>
    <li>Composer</li>
    <li>NPM</li>
    <li>A Web Browser</li>
</ul>
Now, follow this steps:
<ol>
    <li>Go to https://github.com/TonyOsadolor/MyTodoApp .</li>
    <li>Open your terminal, navigate to your preferred folder and Run: <code>git clone https://github.com/TonyOsadolor/MyTodoApp.git</code>.</li>
    <li>Run <code>cd MyTodoApp</code></li>
    <li>Run <code>composer install</code></li>
    <li>Run <code>npm install</code></li>
    <li>Run <code>npm run build</code></li>
    <li>Run <code>composer run dev</code></li>
    <li>Copy all the contents of the <code>.env.example</code> file. Create <code>.env</code> file and paste all the contents you copied from <code>.env.exmaple</code> file to your <code>.env</code> file.</li>
    <li>Run <code>php artisan key:generate --show</code> to retrieve a base64 encoded string; copy same and past in the Laravel's APP_KEY in <code>.env</code> or run <code>php artisan key:generate</code> to have the key generated and attach itself.</li>
    <li>Set your DB_DATABASE = <code>my_todo_app</code> or whatever you prefer.</li>
    <li>If you are using XAMPP, run it as an administrator. Start Apache and Mysql. Go to <code>localhost/phpmyadmin</code> and create new database and name it <code>my_todo_app</code>.</li>
    <li>If you use any other offline server client, simply setup your local database name <code>my_todo_app</code>.</li>
    <li>Once you are done with the database set up, kindly run <code>php artisan migrate</code>.</li>
    <li>When you are done migrating the tables, run <code>php artisan db:seed</code> to see the default dependency models.</li>
    <li>Run php artisan serve from your terminal to start your local server at: <code>http://127.0.0.1:8000/</code> .</li>
    <li>Open the Address in your native browser and signup, voula... you can start using the WebApp.</li>
</ol>

## Using the App on Production
##### Laravel Version => '12.10.2'
When you want to run this project online/production, all you need is an upto date Browser of your choice.

Now, follow this steps:
<ol>
    <li>Go to https://mytodoapp.tinnovations.com.ng .</li>
    <li>If you are a new User, you should see the Login Screen and a link to Register.</li>
    <li>Click on the Registration Link, and fill the required information.</li>
    <li>Upon successful registration, the system will login you in automatically and ask you to verify your email.</li>
    <li>After successful mail verification, you should have access to your dashboard, where your pending tasks will be listed, and from there you can add new tasks as you deem fit.</li>
    <li>If you already have an account, simply login and you will be landed in your Dashboard.</li>
    <li>Enjoy all you want....</li>
</ol>

## Documentation Guide
##### Documentation for the Project
Basic feature of the App built with Laravel Classes includes:
<ol>
    <li>Enum Classes <code>for Uniform naming</code></li>
    <li>Livewire Classes</li>
    <li>Controller Classes</li>
    <li>Middlewares</li>
    <li>Requests</li>
    <li>Resources</li>
    <li>Jobs <code>for running background tasks</code></li>
    <li>Notification <code>handles the mailing</code></li>
    <li>Services <code>for personal preference, I chose services class over repositories</code></li>
    <li>Traits <code>majoring the Response class for returning json responses</code></li>
    <li>Command <code>Make a new Command 'MakeServiceCommand' for handling the auto generation of Service Class</code></li>
    <li>Policies <code>This handles security of Models to ensure the right access by owners</code></li>
</ol>


## Code of Conduct
In order to ensure that the Project is used for it rightful existence, please review and abide by the Code of Conduct.


## Security Vulnerabilities
If you discover a security vulnerability within Project, please send an e-mail to Anthony Osadolor via support@tinnovations.com.ng. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
