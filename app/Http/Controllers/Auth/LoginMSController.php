<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Microsoft\Graph\Core\GraphConstants;
use \League\OAuth2\Client\Provider\GenericProvider;

class LoginMSController extends Controller
{
    use RedirectsUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function oauth(Request $request)
    {
        $provider = $this->getProvider();
        $authUrl = $provider->getAuthorizationUrl();
        session()->put(config('auth.microsoft.session.ms_state'), $provider->getState());

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        if(session()->get(config('auth.microsoft.session.ms_state')) !== $request->get('state')) {
            session()->forget(config('auth.microsoft.session.ms_state'));
            return response()->json('State value does not match the one initially sent');
        }
        return $this->getAccessData($request->get('code'));
    }

    private function getAccessData(string $code)
    {
        // With the authorization code, we can retrieve access tokens and other data.
        try {
            $provider = $this->getProvider();
            // Get an access token using the authorization code grant
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code'     => $code
            ]);
            $_SESSION['access_token'] = $accessToken->getToken();

            // The id token is a JWT token that contains information about the user
            // It's a base64 coded string that has a header, payload and signature
            $idToken = $accessToken->getValues()['id_token'];
            $decodedAccessTokenPayload = base64_decode(
                explode('.', $idToken)[1]
            );
            $jsonAccessTokenPayload = json_decode($decodedAccessTokenPayload, true);
            return  $this->authorizeUser($jsonAccessTokenPayload['oid'], $jsonAccessTokenPayload['name'], $jsonAccessTokenPayload['preferred_username']);
            // The following user properties are needed in the next page
            $_SESSION['preferred_username'] = $jsonAccessTokenPayload['preferred_username'];
            $_SESSION['given_name'] = $jsonAccessTokenPayload['name'];

        } catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            echo 'Something went wrong, couldn\'t get tokens: ' . $e->getMessage();
        }
    }


    private function getProvider():GenericProvider
    {
        return new GenericProvider([
            'clientId'                => config('auth.microsoft.client_id'),
            'clientSecret'            => config('auth.microsoft.client_secret'),
            'redirectUri'             => locale_route(config('auth.microsoft.redirect_route')),
            'urlAuthorize'            => config('auth.microsoft.url_authorize'),
            'urlAccessToken'          => config('auth.microsoft.url_access_token'),
            'urlResourceOwnerDetails' => config('auth.microsoft.url_resource_owner_details'),
            'scopes'                  => config('auth.microsoft.scopes')
        ]);
    }


    private function authorizeUser(string $oid, string $name, string $username)
    {
        if(($user = User::where('uuid', $oid)->where('is_ms_account', true)->first()) === null) {
            $user = new User;
            $user->uuid = $oid;
            $user->email = $username;
            $user->is_ms_account = true;
        }
        $user->name = $name;
        $user->password = Hash::make(time());
        $user->save();
        Auth::login($user, config('auth.microsoft.stayedLoggedIn', false));
        return redirect()->intended($this->redirectPath());
    }
}
