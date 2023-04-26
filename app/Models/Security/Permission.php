<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'value', 'slug'];
    protected $table = 'SEC_PERMISSIONS';

    public function roles()
    {

        return $this->belongsToMany(Role::class, 'SEC_ROLE_PERMISSIONS');

    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'SEC_USER_PERMISSIONS');
    }
}
