<?php
namespace App\Http\Models;

class Media extends BaseModel
{

    public $incrementing = false;
    protected $fillable = ['id', 'collection_id', 'original_name', 'generated_name', 'type', 'cloudfront_url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collection()
    {
        return $this->belongsTo('App\Http\Models\MediaCollection');
    }

    public function scopeFilterCollection($query, $collectionId)
    {
        return $query->where('collection_id', '=', $collectionId);
    }

}