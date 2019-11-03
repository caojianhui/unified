<?php
return [
    'enable' => true,
    'go_url' => env('GO_URL','http://tenant.cw100.la'),
    'go_domain' => env('GO_DOMAIN','cw100.la'),
    'go_key'=>env('GO_KEY','platform_fuwu'),//加密密钥
    'minutes'=>env('UNIFIED_MINUTES',86400),
    'guard'=>env('GUARD','tenant'),
    'is_domain'=>false,
];
