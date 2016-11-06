<?php

namespace App\Presenters;

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
                $url .= "?width={$width}&height={$height}";
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

    /**
     * Get the join date.
     *
     * @return string
     */
    public function joinDate()
    {
        return date('F Y', strtotime($this->model->created_at));
    }
}
