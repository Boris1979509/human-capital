<?php

declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateVacanciesIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('vacancies_index', function (Mapping $mapping, Settings $settings) {
            $mapping->text('name', ['boost' => 3]);
            $mapping->text('competitions', ['boost' => 2]);
            $mapping->text('skills', ['boost' => 2]);
            $mapping->text('responsibilities', ['boost' => 2]);
            $mapping->text('requirements', ['boost' => 2]);
            $mapping->text('conditions', ['boost' => 2]);
            $mapping->text('description', ['boost' => 2]);
            $mapping->text('schedule');
            $mapping->text('profession');
            $mapping->text('experience_level');
            $mapping->text('employment_type');
            $mapping->text('city');
            $mapping->text('working_address');
            $mapping->text('employer');

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

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        //
    }
}
