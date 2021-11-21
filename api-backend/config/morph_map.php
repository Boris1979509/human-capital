<?php

use App\Models\Comment;
use App\Models\Employer\Employer;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\Journal\Content;
use App\Models\Selection\Selection;

return [
    'journal' => Content::class,
    'curriculum' => InstitutionCurriculum::class,
    'comment' => Comment::class,
    'institution' => Institution::class,
    'employer' => Employer::class,
    'selection' => Selection::class
];
