<?php

return [
    [
        'name' => 'Memberships',
        'flag' => 'plugin.membership',
    ],
    [
        'name'        => 'Members',
        'flag'        => 'memberships.index',
        'parent_flag' => 'plugin.membership',
    ],
    [
        'name'        => 'Create Member',
        'flag'        => 'memberships.create',
        'parent_flag' => 'memberships.index',
    ],
    [
        'name'        => 'Edit Member',
        'flag'        => 'memberships.edit',
        'parent_flag' => 'memberships.index',
    ],
    [
        'name'        => 'Delete Member',
        'flag'        => 'memberships.destroy',
        'parent_flag' => 'memberships.index',
    ],
    [
        'name'        => 'Plans & Tiers',
        'flag'        => 'plans.index',
        'parent_flag' => 'plugin.membership',
    ],
    [
        'name'        => 'Create Plan',
        'flag'        => 'plans.create',
        'parent_flag' => 'plans.index',
    ],
    [
        'name'        => 'Edit Plan',
        'flag'        => 'plans.edit',
        'parent_flag' => 'plans.index',
    ],
    [
        'name'        => 'Delete Plan',
        'flag'        => 'plans.destroy',
        'parent_flag' => 'plans.index',
    ],
];
