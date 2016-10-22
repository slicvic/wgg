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
    * Override the method to ignore the remember token.
    */
    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = ($key == $this->getRememberTokenName());

        if (!$isRememberTokenAttribute) {
            parent::setAttribute($key, $value);
        }
    }

    /**
     * Find a user with the given social account id and social account type id.
     *
     * @param  string $socialAccountId
     * @param  int $socialAccountTypeId
     * @return User|null
     */
    public static function findBySocialAccountIdAndSocialAccountTypeId($socialAccountId, $socialAccountTypeId)
    {
        return static::where('social_account_id', $socialAccountId)
                    ->where('social_account_type_id', $socialAccountTypeId)
                    ->first();
    }

    /**
     * Create or update a facebook user.
     *
     * @param  array $attributes
     * @param  User $user
     * @return User
     */
    public static function createOrUpdateFacebookUser(array $attributes = [], User $user = null)
    {
        $attributes['social_account_type_id'] = SocialAccountType::FACEBOOK;

        if (!$user) {
            $user = new static;
        }

        $user->fill($attributes)->save();

        return $user;
    }
}
