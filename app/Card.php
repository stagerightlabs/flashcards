<?php

namespace App;

use App\Concerns\UlidAttribute;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use UlidAttribute;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cards';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
