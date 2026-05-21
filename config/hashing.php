<?php
return [
    'driver' => 'bcrypt',
    'bcrypt' => [
        'rounds' => 12, // Default cost factor
    ],
    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
    ],
];