<?php
namespace App\Http\Transformers;

use App\Http\Models\MediaCollection;
use League\Fractal\TransformerAbstract;

class MediaCollectionTransformer extends TransformerAbstract
{
    /**
     * @param MediaCollection $mediaCollection
     * @return array
     */
    public function transform(MediaCollection $mediaCollection)
    {
        return [
            'media_collection_id'=>$mediaCollection->id,
            'media_collection_name'=>$mediaCollection->collection_name
        ];
    }
}