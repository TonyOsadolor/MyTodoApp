<?php

namespace App\Enums;

enum TaskTypeEnum: string
{
    const EVENT = 'event';
    const TODO = 'todo';
    const TASK = 'task';
    const REMINDER = 'reminder';
}
