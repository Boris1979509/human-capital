<?php declare(strict_types=1);

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;

/**
 * App\Models\User\SocialAccount
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider_user_id
 * @property string $provider
 * @property array $provider_user_data
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\SocialAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\SocialAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\SocialAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\SocialAccount whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\SocialAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\SocialAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\SocialAccount whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\SocialAccount whereProviderUserData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\SocialAccount whereProviderUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\SocialAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\SocialAccount whereUserId($value)
 * @mixin \Eloquent
 */
class SocialAccount extends Model
{
    protected $table = 'user_social_accounts';

    protected $fillable
        = [
            'user_id',
            'provider_user_id',
            'provider',
            'provider_user_data',
        ];

    protected $casts
        = [
            'provider_user_data' => 'json',
        ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
