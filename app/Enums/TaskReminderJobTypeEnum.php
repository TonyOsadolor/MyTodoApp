<?php

namespace App\Enums;

enum TaskReminderJobTypeEnum: string
{
    const DAILY = 'daily';
    const DUE = 'due';
}
