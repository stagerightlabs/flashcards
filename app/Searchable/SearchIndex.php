<?php

namespace App\Searchable;

use Illuminate\Database\Eloquent\Model;

class SearchIndex extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'search_indices';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Find or create a search index for a model.
     *
     * @param Model $model
     * @return self|null
     */
    public static function for(Model $model)
    {
        // Ensure this model is using our search trait
        if (! in_array(AutomaticSearchIndices::class, class_uses_recursive($model))) {
            return null;
        }

        $keyName = $model->getKeyName();

        return self::firstOrCreate([
            'searchable_id' => $model->$keyName,
            'searchable_type' => $model->getMorphClass(),
            'link' => $model->prepareSearchResultLink(),
        ]);
    }

    /**
     * The object represented by the search index.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo('searchable');
    }
}
