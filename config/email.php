<?php

return [
    'name' => 'Membership',
    'description' => 'Config email templates for Membership',
    'templates' => [
        'welcome-member' => [
            'title' => 'Welcome Member',
            'description' => 'Send email to member after purchase',
            'subject' => 'Welcome to {{ site_title }} Membership!',
            'can_off' => true,
            'enabled' => false,
            'variables' => [
                'customer_name' => 'plugins/ecommerce::ecommerce.customer_name',
            ],
        ],
        'renew-reminder' => [
            'title' => 'Reminder to Renew Your Membership',
            'description' => 'Send email to member to remind them to renew their membership',
            'subject' => 'Renew your membership at {{ site_title }}!',
            'can_off' => true,
            'enabled' => false,
            'variables' => [
                'customer_name' => 'plugins/ecommerce::ecommerce.customer_name',
                'membership_expire_date' => 'plugins/membership::membership.membership_expire_date',
            ],
        ],
        'membership-expire' => [
            'title' => 'Membership Expired',
            'description' => 'Send email to member to notify them that their membership has expired',
            'subject' => 'Your membership at {{ site_title }} has expired!',
            'can_off' => true,
            'enabled' => false,
            'variables' => [
                'customer_name' => 'plugins/ecommerce::ecommerce.customer_name',
                'membership_expire_date' => 'plugins/membership::membership.membership_expire_date',
            ],
        ],
    ],
];
