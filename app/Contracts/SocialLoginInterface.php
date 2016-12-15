<?php

namespace App\Contracts;

interface SocialLoginInterface
{
    /**
     * Create a user account for the logged in facebook user.
     *
     * @return \Models\User
     * @throws \Facebook\Exceptions\FacebookResponseException
     * @throws \Facebook\Exceptions\FacebookSDKException
     * @throws \App\Exceptions\AccountDeactivatedException
     */
    public function registerWithFacebook();
}
