<?php declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\User\SocialAccount;
use Illuminate\Support\ServiceProvider;

/**
 * Class SocialAccountService
 *
 * @package App\Services
 */
class SocialAccountService extends ServiceProvider
{
    /**
     * @param \Laravel\Socialite\Contracts\User $providerUser
     * @param string $providerName
     *
     * @return User
     * @throws \League\Flysystem\FileNotFoundException
     */
    public static function createOrGetUser(\Laravel\Socialite\Contracts\User $providerUser, string $providerName): User
    {
        /** @var SocialAccount $account */
        $account = SocialAccount::whereProvider($providerName)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        $userData = \json_encode($providerUser->user, JSON_UNESCAPED_UNICODE);

        if (!$account) {
            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $providerName,
            ]);
            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $user = User::createBySocialProvider($providerUser, $providerName);
            }
            $account->user()->associate($user);
        } else {
            $account->user->updateBySocialProvider($providerUser);
        }

        try {
            $account->user->getOrganizationsBySocialProvider($providerUser);
        } catch (\Exception $e) {
        }

        $account->provider_user_data = $userData;
        $account->access_token = $providerUser->token;

        if ($providerUser->refreshToken !== null) {
            $account->refresh_token = $providerUser->refreshToken;
        }

        $account->save();

        return $account->user;
    }

    public static function renewUserAccessToken(\Laravel\Socialite\Contracts\User $providerUser, string $providerName): User
    {
        /** @var SocialAccount $account */
        $account = SocialAccount::whereProvider($providerName)
            ->whereProviderUserId($providerUser->getId())
            ->firstOrFail();

        $account->access_token = $providerUser->token;

        if ($providerUser->refreshToken !== null) {
            $account->refresh_token = $providerUser->refreshToken;
        }

        $account->save();

        return $account->user;
    }
}
