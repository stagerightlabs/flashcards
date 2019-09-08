<?php

namespace App;

use App\Domain;
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
}
