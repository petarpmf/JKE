<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\MediaInterface;
use App\Http\Models\Media;
use League\Fractal\Resource\Collection;

class EloquentMediaRepository implements MediaInterface
{
    /**
     * Used for creating new media in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        return Media::create($data);
    }

    /**
     * Used for filtering media by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor)
    {
        $media = Media::where($searchFor)->first();
        return $media ? $media : null;
    }

    /**
     * Used for returning list of all media
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($collectionId)
    {
        $media = new Media();
        if ($collectionId) {
            $media = $media->filterCollection($collectionId);
        }
        return $media->all();
    }

    /**
     * Used for returning paginated list of all media
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage, $collectionId)
    {

        $media = new Media();
        if ($collectionId) {
            $media = $media->filterCollection($collectionId);
        }
        return $media->orderBy('updated_at','desc')->paginate($perPage);
    }

    /**
     * Used for returning media by ID
     *
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $media = Media::find($id);
        if ($media) {
            return $media;
        }

        return false;
    }

    /**
     * Used for deleting media by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $mediaForDelete = Media::find($id);
        if ($mediaForDelete) {
            $result = $mediaForDelete->delete();
            return ($result)?$mediaForDelete:$result;
        }
        return false;
    }

    public function moveToCollection($id, $collection)
    {
        $mediaForUpdate = Media::find($id);
        if ($mediaForUpdate) {
            $mediaForUpdate->collection_id = $collection;
            return $mediaForUpdate->save();
        }

        return false;
    }

    public function moveAllFilesBetweenCollections($collectionIdFrom, $collectionIdTo)
    {
		return (Media::where('collection_id', '=', $collectionIdFrom)->update(['collection_id' => $collectionIdTo]));
    }

    /**
     * @param array $media
     * @return bool|mixed
     */
    public function displayMedia(array $media)
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')?'https://' : 'http://';
        $path = $protocol.$media['data']['cloudfront_url'];
        if (@fopen($path, 'r')) {
            $content = file_get_contents($path);

            $response = response($content, '200')
                ->header('Content-Type', $media['data']['type']);

            return $response;
        }
        return false;
    }

    /**
     * @param array $media
     * @return bool|mixed
     */
    public function downloadMedia(array $media)
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')?'https://' : 'http://';
        $path = $protocol.$media['data']['cloudfront_url'];
        //$path = 'http://lmscontent.it-labs.com/08adc5b8f4f509920e7d.txt';
        if (@fopen($path, 'r')) {
            $content = file_get_contents($path);
           // dd($content);
            $response = response($content, '200')
                ->header('Content-Type', $media['data']['type'])
                ->header('Content-Disposition', 'attachment; filename="'.$media['data']['original_name'].'"');

            return $response;
        }
        return false;
    }

}
