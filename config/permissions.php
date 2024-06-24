<?php

return [
    [
        'name' => 'Membership',
        'flag' => 'membership.index',
    ],

    [
        'name' => 'Memberships',
        'flag' => 'membership.memberships.index',
        'parent_flag' => 'membership.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'membership.memberships.create',
        'parent_flag' => 'membership.memberships.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'membership.memberships.edit',
        'parent_flag' => 'membership.memberships.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'membership.memberships.destroy',
        'parent_flag' => 'membership.memberships.index',
    ],
    [
        'name' => 'View',
        'flag' => 'membership.memberships.view',
        'parent_flag' => 'membership.memberships.index',
    ],

    [
        'name' => 'Plans',
        'flag' => 'membership.plans.index',
        'parent_flag' => 'membership.index',
    ],

    [
        'name' => 'Create',
        'flag' => 'membership.plans.create',
        'parent_flag' => 'membership.plans.index',
    ],

    [
        'name' => 'Edit',
        'flag' => 'membership.plans.edit',
        'parent_flag' => 'membership.plans.index',
    ],

    [
        'name' => 'Delete',
        'flag' => 'membership.plans.destroy',
        'parent_flag' => 'membership.plans.index',
    ],

    [
        'name' => 'View',
        'flag' => 'membership.plans.view',
        'parent_flag' => 'membership.plans.index',
    ],
];
