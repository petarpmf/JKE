<?php
namespace App\Http\Models;

class MediaCollection extends BaseModel
{

    public $incrementing = false;
    protected $fillable = ['id', 'collection_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media()
    {
        return $this->hasMany('App\Http\Models\Media');
    }
}