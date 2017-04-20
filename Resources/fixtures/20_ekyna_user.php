<?php

declare(strict_types=1);

use Ekyna\Bundle\UserBundle\Model;

return [
    Model\UserInterface::class => [
        'user_john' => [
            '__factory'     => [
                '@ekyna_user.factory.user::create' => [],
            ],
            'email'         => 'john.doe@example.org',
            'plainPassword' => 'password',
            'enabled'       => true,
        ],
        'user_jane' => [
            '__factory'     => [
                '@ekyna_user.factory.user::create' => [],
            ],
            'email'         => 'jane.doe@example.org',
            'plainPassword' => 'password',
            'enabled'       => true,
        ],
    ],
];
