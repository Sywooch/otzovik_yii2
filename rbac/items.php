<?php
return [
    'createSalon' => [
        'type' => 2,
        'description' => 'Добавить салон',
    ],
    'updateSalon' => [
        'type' => 2,
        'description' => 'Изменить салон',
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'createSalon',
            'updateSalon',
        ],
    ],
];
