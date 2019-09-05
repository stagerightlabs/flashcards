<?php

namespace App;

use App\Concerns\UlidAttribute;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use UlidAttribute;
}
