<?php

namespace App\Models\Employer;

use App\Models\Employer\ResponseState\VacancyResponseState;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\ModelStates\HasStates;

class VacancyResponse extends Model
{
    use HasFactory;
    use HasStates;

    public const CV_TYPE_GENERATED = 1;
    public const CV_TYPE_UPLOADED = 2;

    protected $guarded = [];

    protected $casts = [
        'status' => VacancyResponseState::class,
        'invite' => 'array',
        'rejection' => 'array',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class, 'vacancy_id');
    }

    public function hideFromUser(): void
    {
        $this->update(['deleted_by_user' => true]);
    }

    public function cvFile(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cv_file_id');
    }
}
