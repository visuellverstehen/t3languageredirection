<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 't3languageredirection',
    'description' => "This TYPO3 package provides a middleware that automatically redirects users to the preferred language URL based on their browser's Accept-Language header.",
    'category' => 'be',
    'author' => 'visuellverstehen',
    'author_email' => 'hello@visuellverstehen.de',
    'author_company' => 'visuellverstehen GmbH',
    'state' => 'stable',
    'clearCacheOnLoad' => false,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5-12.4',
        ],
    ],
];
