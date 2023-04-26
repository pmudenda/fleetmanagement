<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug', 'description'];
    protected $table = 'SEC_ROLES';
    public function permissions(): BelongsToMany
    {

        return $this->belongsToMany(Permission::class,'SEC_ROLE_PERMISSIONS');

    }

    public function users(): BelongsToMany
    {

        return $this->belongsToMany(User::class,'SEC_USER_ROLES');

    }
}
