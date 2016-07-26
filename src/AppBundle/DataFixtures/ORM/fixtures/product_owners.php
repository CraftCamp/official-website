<?php
return [
    [
        'id' => 1,
        'username' => 'kern',
        'email' => 'kern046@gmail.com',
        'plain_password' => 'test',
        'roles' => ['ROLE_USER'],
        'organization_id' => 1,
        'is_enabled' => true,
        'is_locked' => false,
        'created_at' => '2016-02-17 15:52:59',
        'updated_at' => '2016-02-17 15:52:59',
        'activation_link_id' => null
    ],
    [
        'id' => 2,
        'username' => 'test',
        'email' => 'test@gmail.com',
        'plain_password' => 'test',
        'roles' => ['ROLE_USER'],
        'organization_id' => 1,
        'is_enabled' => false,
        'is_locked' => false,
        'created_at' => '2016-02-17 15:52:59',
        'updated_at' => '2016-02-17 15:52:59',
        'activation_link_id' => 1
    ]
];
