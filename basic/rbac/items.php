<?php
return [
    'reserveBooks' => [
        'type' => 2,
        'description' => 'Reserve Books',
    ],
    'manageBooks' => [
        'type' => 2,
        'description' => 'Manage Books',
    ],
    'user' => [
        'type' => 1,
        'children' => [
            'reserveBooks',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'manageBooks',
            'user',
        ],
    ],
];
