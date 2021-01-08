<?php

namespace App;

//use Laravel\Passport\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends BaseModel
{
    use EntrustUserTrait;

    protected $searchable = [
        'columns' => [
            'users.name' => 10,
            'users.username' =>8,
        ],
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'type', 'cid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['role'];


    public function getRoleAttribute()
    {
        return $this->roles->implode('name', ',');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'cid');
    }

}
