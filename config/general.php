<?php

return [
    'name' => 'Membership',
    'enabled' => env('MEMBERSHIP_ENABLED', true),
    'enable_same_session_discount' => true,
    'default_quota_duration_days' => 30,
    'send_expiry_reminder_days' => [7, 1],
    'enable_admin_expiry_digest' => true,
];
