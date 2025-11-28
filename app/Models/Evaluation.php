<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'scope_id', 'title'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwner($query)
    {
        if (in_array(Role::ROLE_ADMIN, auth()->user()->rolesArray()))
            return $query;
        return $query->where('user_id', auth()->id());
    }

    public function scopeScope($query)
    {
        if (in_array(Role::ROLE_ADMIN, auth()->user()->rolesArray()))
            return $query;
        return $query->where('scope_id', auth()->user()->scope_id);
    }
}
