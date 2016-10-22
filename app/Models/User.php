<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'social_account_id',
        'social_account_type_id',
        'name',
        'email',
        'location_name',
        'active',
        'loggedin_at'
    ];

    /**
     * Get the social account type record associated with the user.
     *
     * @return \App\Models\SocialAccountType
     */
    public function socialAccountType()
    {
        return $this->hasOne('App\Models\SocialAccountType');
    }

   /**
    * Overrides the method to ignore the remember token.
    */
    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = ($key == $this->getRememberTokenName());

        if (!$isRememberTokenAttribute) {
            parent::setAttribute($key, $value);
        }
    }
}
