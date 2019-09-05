<?php

namespace App;

use App\Concerns\UlidAttribute;
use Illuminate\Database\Eloquent\Model;
use App\Searchable\AutomaticSearchIndices;

class Card extends Model
{
    use UlidAttribute;

    /*
     * Use model events to synchronize search indices
     */
    use AutomaticSearchIndices;

    /**
     * Create a weighted index structure for this model.
     *
     * @return array
     */
    public function composeSearchIndex()
    {
        return [
            'A' => $this->title,
            'B' => $this->body,
            'C' => '',
            'D' => '',
        ];
    }

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

    /**
     * The activity logs for this card.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities()
    {
        return $this->morphMany(\Spatie\Activitylog\Models\Activity::class, 'subject');
    }
}
