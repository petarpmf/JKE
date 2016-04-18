<?php
namespace App\Http\Transformers;

use App\Http\Models\Media;
use League\Fractal\TransformerAbstract;

class MediaTransformer extends TransformerAbstract
{
    public $manager;
    public $transformer;

    function __construct(TransformersManager $manager, MediaCollectionTransformer $transformer)
    {
        $this->manager = $manager;
        $this->transformer = $transformer;
    }

    /**
     * @param Media $media
     * @return array
     */
    public function transform(Media $media)
    {
        $mediaCollection = ($media->collection != null)?$this->manager->transformItem($media->collection,$this->transformer)['data']:$media->collection;

        $imageUrl = null;
        $resumeUrl = null;
        $resumeFileName = null;
        if (preg_match("/image.*/i", $media->type)) {
            $imageUrl= url('media/display/'. $media->id);
        }else{
            $resumeUrl =  url('media/download/'. $media->id.'/'.urlencode($media->original_name));
            $resumeFileName = $media->original_name;
        }

        return [
            'image_id' => $media->id,
            'collection' => $mediaCollection,
            'original_name' => $media->original_name,
            'generated_name' => $media->generated_name,
            'type' => $media->type,
            'cloudfront_url' => $media->cloudfront_url."/".$media->generated_name,
            'image_url' => $imageUrl,
            'resume_url'=> $resumeUrl,
            'resume_file_name'=>$resumeFileName
        ];
    }
}