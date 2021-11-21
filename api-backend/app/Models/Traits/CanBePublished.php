<?php

namespace App\Models\Traits;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait CanBePublished
 *
 * @mixin Eloquent
 */
trait CanBePublished
{
    protected static function bootCanBePublished(): void
    {
        static::created(function (self $model) {
            if ($model->is_published) {
                $model->update(['published_at' => now()]);
            }
        });

        static::updating(function (self $model) {
            if ($model->hasBeenPublished()) {
                $model->published_at = now();
            }
        });
    }

    protected function hasBeenPublished(): bool
    {
        return !$this->getOriginal('is_published') && $this->is_published;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function isPublic(): bool
    {
        return $this->is_published;
    }
}
