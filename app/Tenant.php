<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The domains associated with this tenant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function domains()
    {
        return $this->hasMany(Domain::class)->orderBy('created_at');
    }

    /**
     * The users that belong to this tenancy
     *
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class)->orderBy('created_at');
    }
}
