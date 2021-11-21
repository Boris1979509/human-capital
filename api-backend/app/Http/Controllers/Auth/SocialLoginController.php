<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Helpers\KeycloakHelper;
use App\Http\Controllers\Controller;
use App\Services\SocialAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Contracts\Factory as Socialite;
use League\Flysystem\FileNotFoundException;
use RuntimeException;

use function config;
use function parse_url;
use function redirect;
use function sprintf;

/**
 * Class SocialLoginController
 *
 * @package App\Http\Controllers\Auth
 */
class SocialLoginController extends Controller
{
    protected const LATEST_URL = 'latest_url';

    /**
     * @var Socialite
     */
    private $socialite;

    /**
     * SocialLoginController constructor.
     *
     * @param  Socialite  $socialite
     */
    public function __construct(Socialite $socialite)
    {
        $this->socialite = $socialite;
    }

    /**
     * @param  string  $provider
     * @param  Request  $request
     *
     * @return mixed
     * @throws RuntimeException
     */
    public function login(string $provider = null, Request $request): RedirectResponse
    {
        $provider ??= 'keycloak';

        $request->session()->flash(self::LATEST_URL, URL::previous());

        return $this->socialite->driver($provider)
            ->with([
                'scope' => 'offline_access'
            ])
            ->redirect();
    }

    /**
     * Log the user out of the application.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     * @throws RuntimeException
     */
    public function logout(Request $request): RedirectResponse
    {
        if (auth()->guard('api')->check()) {
            auth()->guard('api')->user()->regenerateToken();
        }

        $request->session()->flush();
        $request->session()->regenerate();

        return $this->socialite->driver('keycloak_extended')
            ->with([
                'approval_prompt' => 'auto',
                'response_type' => 'code'
            ])
            ->stateless()
            ->redirect();
    }

    /**
     * @param  string  $provider
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     * @throws RuntimeException
     * @throws FileNotFoundException
     */
    public function callback(string $provider, Request $request): RedirectResponse
    {
        $socialiteUser = $this->socialite->driver($provider)->stateless()->user();

        $user = SocialAccountService::createOrGetUser($socialiteUser, $provider);

        $tokenResult = $user->createToken('UserPersonal Access Token');
        $token = $tokenResult->token;

        $token->save();

        return redirect()->away(env('APP_FRONT_URL').'?api_token='.$tokenResult->accessToken);
    }

    /**
     * Refresh user data from provider.
     *
     * @param  string|null  $provider
     *
     * @return mixed
     * @throws RuntimeException
     * @throws FileNotFoundException
     */
    public function refresh(string $provider = null)
    {
        $provider ??= 'keycloak';

        $user = KeycloakHelper::checkProviderUserAuth();

        $refreshToken = $user->keycloak->refresh_token;

        /** @var $keycloakHelper */
        $keycloakHelper = new KeycloakHelper();

        $accessToken = $keycloakHelper->getAccessToken($refreshToken);

        if ($accessToken === null) {
            return new JsonResponse('Cannot get user token', 401);
//            return $this->socialite->driver($provider)->redirect();
        }

        $socialiteUser = $keycloakHelper->getUserByToken($accessToken);

        SocialAccountService::createOrGetUser($socialiteUser, $provider);

        return new JsonResponse('User info updated', 200);
    }
}
