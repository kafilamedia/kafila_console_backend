<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;


use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email','password','display_name','role','departement_id',
    ];

    protected int $id;
    protected $email;
    protected $name;
    protected $password ;
    protected $display_name;
    protected $role;
    protected int $departement_id;
    protected Departement $departement ;
    
    public function __construct()
    {
        parent::addFilterable('id');
        parent::addFilterable('departement');
        parent::addFilterableAlias('departement', 'departements.name');
        parent::addIgnorableSelectField('password');
    }
    
    public function department()
    {
        return $this->hasOne(Departement::class, 'departement_id');
    }

    public function save($options = []) : bool
    {
        if (is_null($this->role)) {
            $this->role = ('user');
        }
        return parent::save($options);
    }

    public function getAuthIdentifierName()
    {
        return 'email';
    }

    public function getAuthIdentifier()
    {
        
        return $this->attributes["email"];
    }

    public function getAuthPassword()
    {
        return ($this->attributes["password"]);
    }
 
    public function isAdmin() :bool
    {
        return $this->hasRole('admin');
    }

    public function hasRole($roles)
    {
        // dd($role, $this->attributes['role']);
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        foreach ($roles as $role) {
            if ($this->attributes['role'] == $role) {
                return true;
            }
        }
        return false;
        // return in_array($role, [$this->attributes['role']]);
        // return in_array($role, $this->roles);
    }

    public static function adjustFieldFilter($filter)
    {
        if (is_null($filter)) {
            return null;
        }
        $user = new User();
        $fieldsFilter = $filter->fieldsFilter;

        foreach ($fieldsFilter as $key => $value) {
            $exist = false;
            foreach ($user->fillable as $fillablekey) {
                if ($key == $fillablekey) {
                    $exist = true;
                    break;
                }
            }
            if (!$exist) {
                unset($filter->fieldsFilter[$key]);
            }
        }

        return $filter;
    }
}
