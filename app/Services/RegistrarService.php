<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

use App\Exceptions\AccountDeactivatedException;
use App\Models\User;
use App\Models\SocialAccountType;

class RegistrarService
{
    /**
     * Register a user through Facebook.
     *
     * @return User
     * @throws FacebookResponseException
     * @throws FacebookSDKException
     * @throws AccountDeactivatedException
     */
    public function facebook()
    {
        // Load up the facebook service
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => env('FACEBOOK_DEFAULT_GRAPH_VERSION')
        ]);

        // Get javascript helper
        $jsHelper = $fb->getJavaScriptHelper();

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

        // Throw error if account is deactivated
        if ($user && !$user->active) {
            throw new AccountDeactivatedException;
        }

        // Create/update user record
        $user = ($user) ?: new User;
        $user->social_account_type_id = SocialAccountType::FACEBOOK;
        $user->social_account_id = $facebookUser['id'];
        $user->name = $facebookUser['name'];
        $user->email = (isset($facebookUser['email'])) ? $facebookUser['email'] : '';
        $user->location_name = (isset($facebookUser['location'])) ? $facebookUser['location']->getName() : '';
        $user->loggedin_at = date('Y-m-d H:i:s');
        $user->active = 1;
        $user->save();

        return $user;
    }
}
