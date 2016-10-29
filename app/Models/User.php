<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\SocialAccountType;
use App\Presenters\PresentableTrait;

class User extends Authenticatable
{
    use Notifiable, PresentableTrait;

    /**
     * The presenter class name.
     *
     * @var string
     */
    protected $presenterClassName = 'App\Presenters\UserPresenter';

    /**
     * {@inheritdoc}
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
     * Get the events associated with the user.
     *
     * @return \App\Models\Event[]
     */
    public function events()
    {
        return $this->hasMany('App\Models\Event');
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
}
