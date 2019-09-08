<?php

namespace App;

use App\Card;
use App\Concerns\UlidAttribute;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use UlidAttribute;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The cards that belong to this domain
     *
     * @return void
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
