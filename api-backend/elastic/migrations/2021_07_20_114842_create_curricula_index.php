<?php

declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateCurriculaIndex implements MigrationInterface
{
    public function up(): void
    {
        Index::create('curricula_index', function (Mapping $mapping, Settings $settings) {
            $mapping->text('name', ['boost' => 3]);
            $mapping->text('description', ['boost' => 2]);
            $mapping->text('direction_of_study');
            $mapping->text('admission_olympiad_conditions');
            $mapping->text('admission_additional_exam_conditions');
            $mapping->text('institution');
            $mapping->text('learning_options');
            $mapping->text('admission_exams');
            $mapping->text('result_professions');
            $mapping->text('competitions');
            $mapping->text('result_skills');
            $mapping->date('published_at');

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
