<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateInstitutionsIndex implements MigrationInterface
{
    public function up(): void
    {
        Index::create('institutions_index', function (Mapping $mapping, Settings $settings) {
            $mapping->text('full_name', ['boost' => 3]);
            $mapping->text('short_name', ['boost' => 3]);
            $mapping->text('description', ['boost' => 2]);
            $mapping->text('type');
            $mapping->text('diploma');
            $mapping->text('website');
            $mapping->text('city');
            $mapping->text('contact_description');
            $mapping->text('entrance_test_description');
            $mapping->text('employees');

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
