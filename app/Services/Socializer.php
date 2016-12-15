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

/**
 * This service class is to assist with user registration and login through
 * social media sites i.e. Facebook.
 */
class Socializer
{
    /**
     * Create a user account for the authenticated Facebook user.
     *
     * @return \Models\User
     * @throws \Facebook\Exceptions\FacebookSDKException
     * @throws \Facebook\Exceptions\FacebookSDKException
     * @throws \App\Exceptions\AccountDeactivatedException
     */
    public function registerWithFacebook()
    {
        // Load up the facebook sdk
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => env('FACEBOOK_DEFAULT_GRAPH_VERSION')
        ]);

        // Retrieve the access token
        $jsHelper = $fb->getJavaScriptHelper();
        $accessToken = $jsHelper->getAccessToken();

        if (!$accessToken) {
            throw new FacebookSDKException('The access token is invalid.');
        }

        // Get the profile info
        $profileResponse = $fb->get('/me', $accessToken);

        if ($profileResponse->getHttpStatusCode() != 200) {
            throw new FacebookSDKException('We could not retrieve your profile info.');
        }

        $profileInfo = $profileResponse->getGraphUser();

        // Check if the user is already registered
        $user = User::findBySocialAccountIdAndTypeId($profileInfo['id'], SocialAccountType::FACEBOOK);

        if ($user && !$user->active) {
            throw new AccountDeactivatedException;
        }

        // Create a new user account or update the existing one
        $user = ($user) ?: new User;
        $user->social_account_type_id = SocialAccountType::FACEBOOK;
        $user->social_account_id = $profileInfo['id'];
        $user->name = $profileInfo['name'];
        $user->email = (isset($profileInfo['email'])) ? $profileInfo['email'] : '';
        $user->location_name = (isset($profileInfo['location'])) ? $profileInfo['location']->getName() : '';
        $user->loggedin_at = date('Y-m-d H:i:s');
        $user->active = true;
        $user->save();

        return $user;
    }
}
