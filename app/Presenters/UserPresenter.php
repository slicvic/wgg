<?php

namespace App\Presenters;

use Illuminate\Database\Eloquent\Model;

use App\Models\SocialAccountType;

class UserPresenter extends BasePresenter
{
    /**
     * Present profile picture URL.
     *
     * @param  int $width
     * @param  int $height
     * @return string
     */
    public function profilePictureUrl($width = 30, $height = 30)
    {
        $url = '';

        switch($this->model->social_account_type_id) {
            case SocialAccountType::FACEBOOK:
                $url = str_replace('{id}', $this->model->social_account_id, env('FACEBOOK_PROFILE_PICTURE_URL'));
                $url .= ($height) ? sprintf('?width=%s&height=%s', $width, $height) : sprintf('?width=%s', $width);
                break;
        }

        return $url;
    }
}
