<?php

return [
    'frontend' => [
        'middleware-identifier' => [
            'target' => \VV\T3languageredirection\Middleware\LanguageRedirectMiddleware::class,
            'before' => [
                'another-middleware-identifier',
            ],
            'after' => [
                'yet-another-middleware-identifier',
            ],
        ],
    ],
    'backend' => [
        'middleware-identifier' => [
            'target' => \VV\T3languageredirection\Middleware\LanguageRedirectMiddleware::class,
            'before' => [
                'another-middleware-identifier',
            ],
            'after' => [
                'yet-another-middleware-identifier',
            ],
        ],
    ],
];
