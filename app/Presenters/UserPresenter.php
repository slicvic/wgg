<?php

namespace App\Presenters;

use Illuminate\Database\Eloquent\Model;
use App\Models\SocialAccountType;

class UserPresenter extends BasePresenter
{
    /**
     * Get the profile picture URL.
     *
     * @param  int $width
     * @param  int $height
     * @return string
     */
    public function profilePictureUrl($width = 30, $height = null)
    {
        switch($this->model->social_account_type_id) {
            case SocialAccountType::FACEBOOK:
                $url = str_replace('{id}', $this->model->social_account_id, env('FACEBOOK_PROFILE_PICTURE_URL'));
                $url .= sprintf('?width=%s&height=%s', $width, $height);
                return $url;
        }
    }

    /**
     * Get the font awesome social icon CSS class.
     *
     * @return string
     */
    public function socialAccountIconCssClass()
    {
        switch($this->model->social_account_type_id) {
            case SocialAccountType::FACEBOOK:
                return 'fa fa-facebook-square';
        }
    }
}
