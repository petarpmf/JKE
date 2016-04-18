<?php
namespace App\Http\Transformers;

use Exception;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class TransformersManager
{
    /**
     * @var Manager
     */
    private $fractal;

    /**
     * @param Manager $fractal
     */
    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;
    }

    public function transformItem($item, $transformer){
        $resource = new Item($item, $transformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function transformCollectionWithoutPaginate($collection, $transformer)
    {
        $resource = new Collection($collection, $transformer);
        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @return mixed
     */
    public function transformCollection($collection, $transformer)
    {
        $resource = new Collection($collection, $transformer);
        $this->addFullQueryString($collection);
        $resource->setPaginator(new IlluminatePaginatorAdapter($collection));
        return $this->fractal->createData($resource)->toArray();
    }

    private function addFullQueryString(&$collection)
    {
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        foreach ($queryParams as $key => $value) {
            $collection->addQuery($key, $value);
        }
    }
}