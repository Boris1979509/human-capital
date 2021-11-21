<?php declare(strict_types=1);

return [
    'hosts' => [
        env('SCOUT_ELASTIC_HOST', 'hcap-elasticsearch:9200'),
    ]
];
