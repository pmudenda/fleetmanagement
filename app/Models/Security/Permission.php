<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'value', 'slug', 'guard_name', 'status', 'description'];
    protected $table = 'SEC_PERMISSIONS';

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'SEC_ROLE_PERMISSIONS');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'SEC_USER_PERMISSIONS');
    }
}
