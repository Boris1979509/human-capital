<?php

declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateEmployersIndex implements MigrationInterface
{
    public function up(): void
    {
        Index::create('employers_index', function (Mapping $mapping, Settings $settings) {
            $mapping->text('name', ['boost' => 3]);
            $mapping->text('description', ['boost' => 2]);
            $mapping->text('branch');
            $mapping->text('city');
            $mapping->text('address');
            $mapping->text('website');

            $settings->analysis([
                "filter" => [
                    "russian_stop" => [
                        "type" => "stop",
                        "stopwords" => "_russian_"
                    ],
                    "russian_stemmer" => [
                        "type" => "stemmer",
                        "language" => "russian"
                    ]
                ],
                'analyzer' => [
                    'rebuilt_russian' => [
                        'tokenizer' => 'standard',
                        "char_filter" => [
                            "html_strip"
                        ],
                        "filter" => [
                            "lowercase",
                            "russian_stop",
                            "russian_stemmer"
                        ],
                    ]
                ]
            ]);
        });
    }

    public function down(): void
    {
        //
    }
}
