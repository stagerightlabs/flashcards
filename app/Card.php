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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['snippet', 'is_longer_than_snippet'];

    /**
     * The length of computed snippets.
     *
     * @var integer
     */
    protected $snippetLength = 256;

    /**
     * The activity logs for this card.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities()
    {
        return $this->morphMany(\Spatie\Activitylog\Models\Activity::class, 'subject');
    }

    /**
     * The user that created this card
     *
     * @return void
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Compute a snippet for this card based on the body content.
     *
     * @return string
     */
    public function getSnippetAttribute()
    {
        return substr($this->body, 0, $this->snippetLength);
    }

    /**
     * Is the content of this card longer than snippet length?
     *
     * @return bool
     */
    public function getIsLongerThanSnippetAttribute()
    {
        return strlen($this->body) > $this->snippetLength;
    }
}
