<?php
namespace App\Http\Controllers\Facebook;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

use App\Http\Controllers\BaseLoginController;
use App\Models\User;
use App\Models\SocialAccountType;
use App\Exceptions\AccountDeactivatedException;

class LoginController extends BaseLoginController
{
    /**
     * Logs in a facebook user.
     *
     * @param  Request $request
     * @return \Illuminate\Routing\Redirector
     */
    public function login(Request $request)
    {
        // Load up the facebook service
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => env('FACEBOOK_DEFAULT_GRAPH_VERSION')
        ]);

        // Get javascript helper
        $jsHelper = $fb->getJavaScriptHelper();

        try {
            // Retrieve access token
            $accessToken = $jsHelper->getAccessToken();

            // Throw error if no access token
            if (!$accessToken) {
                throw new FacebookSDKException('The access token is invalid.');
            }

            // Request user profile info
            $response = $fb->get('/me', $accessToken);

            // Throw error if no user profile info
            if ($response->getHttpStatusCode() != 200) {
                throw new FacebookSDKException('We could not retrieve your profile info.');
            }

            // Extract user profile info
            $facebookUser = $response->getGraphUser();

            // Check if user exists on our end
            $user = User::findBySocialAccountIdAndSocialAccountTypeId($facebookUser['id'], SocialAccountType::FACEBOOK);

            // Throw error if account deactivated
            if ($user && !$user->active) {
                throw new AccountDeactivatedException();
            }

            // Create/update user record
            $user = ($user) ?: new User;
            $user->social_account_id = $facebookUser['id'];
            $user->social_account_type_id = SocialAccountType::FACEBOOK;
            $user->name = $facebookUser['name'];
            $user->email = (isset($facebookUser['email'])) ? $facebookUser['email'] : '';
            $user->location_name = (isset($facebookUser['location'])) ? $facebookUser['location']->getName() : '';
            $user->loggedin_at = date('Y-m-d H:i:s');
            $user->active = 1;
            $user->save();

            // Log in user
            Auth::loginUsingId($user->id);
        } catch(FacebookResponseException $e) {
            $this->flashError('Facebook login error: ' . $e->getMessage());
        } catch(FacebookSDKException $e) {
            $this->flashError('Facebook login error: ' . $e->getMessage());
        } catch(AccountDeactivatedException $e) {
            $this->flashError($e->getMessage());
        }

        return redirect()->route('home');
    }
}
