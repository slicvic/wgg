<?php
namespace App\Http\Controllers\Facebook;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\SocialAccountType;

class LoginController extends BaseController
{
    public function login(Request $request)
    {
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => env('FACEBOOK_DEFAULT_GRAPH_VERSION')
        ]);

        $jsHelper = $fb->getJavaScriptHelper();

        try {
            // Retrieve access token
            $accessToken = $jsHelper->getAccessToken();

            // Throw error if no access token
            if (!$accessToken) {
                throw new FacebookSDKException('The Facebook access token is invalid.');
            }

            // Request profile info
            $response = $fb->get('/me', $accessToken);

            // Throw error if no profile info
            if ($response->getHttpStatusCode() != 200) {
                throw new FacebookSDKException('We could not retrieve your Facebook profile info.');
            }

            // Extract profile info
            $facebookUser = $response->getGraphUser();

            // Check if user exists
            $user = User::where('social_account_id', $facebookUser['id'])
                        ->where('social_account_type_id', SocialAccountType::FACEBOOK)
                        ->first();

            if ($user) {
                // Update user record or throw error if deactivated
                if ($user->active) {
                    $user->update([
                        'name' => $facebookUser['name'],
                        'email' => (isset($facebookUser['email'])) ? $facebookUser['email'] : '',
                        'location_name' => (isset($facebookUser['location'])) ? $facebookUser['location']->getName() : '',
                        'loggedin_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    throw new FacebookSDKException(sprintf('Your %s account has been deactivated.', env('APP_NAME')));
                }
            } else {
                // Create new user record
                $user = User::create([
                    'social_account_id' => $facebookUser['id'],
                    'social_account_type_id' => SocialAccountType::FACEBOOK,
                    'name' => $facebookUser['name'],
                    'email' => (isset($facebookUser['email'])) ? $facebookUser['email'] : '',
                    'location_name' => (isset($facebookUser['location'])) ? $facebookUser['location']->getName() : '',
                    'loggedin_at' => date('Y-m-d H:i:s'),
                    'active' => 1
                ]);
            }

            // Log in user
            Auth::loginUsingId($user->id);
        } catch(FacebookResponseException $e) {
            $this->flashError($e->getMessage());
        } catch(FacebookSDKException $e) {
            $this->flashError($e->getMessage());
        }

        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }
}
