<?php

namespace App\Enums;

enum SubscriptionPlanEnum: string
{
    const ALERT = 'alert';
    const EMAIL = 'email';
    const SMS = 'sms';
    const WHATSAPP = 'whatsapp';
}
