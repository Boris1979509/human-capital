<?php

declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateContentsIndex implements MigrationInterface
{
    public function up(): void
    {
        Index::create('contents_index', function (Mapping $mapping, Settings $settings) {
            $mapping->text('title', ['boost' => 3]);
            $mapping->text('text', ['boost' => 2]);
            $mapping->text('institution');
            $mapping->text('address');
            $mapping->text('tags');
            $mapping->date('published_at');
            $mapping->date('date_start');
            $mapping->date('date_end');
            $mapping->text('participants_age');
            $mapping->text('target_audience');
            $mapping->text('speakers');

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
