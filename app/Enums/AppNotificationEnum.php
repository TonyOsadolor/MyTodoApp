<?php

namespace App\Enums;

enum AppNotificationEnum: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case INFO = 'info';
    case WARNING = 'warning';
    case QUESTION = 'question';
}
