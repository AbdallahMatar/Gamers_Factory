<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class Author extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'gender', 'birth_date', 'status', 'image'];

    /**
     * Find the user instance for the given username.
     *
     * @param  string  $username
     * @return \App\Models\Admin
     */
    public function findForPassport($username)
    {
        return $this->where('email', $username)->first();
    }

    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param  string  $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->password);
    }
}
