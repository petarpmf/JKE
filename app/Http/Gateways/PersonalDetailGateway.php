<?php
namespace App\Http\Gateways;

use App\Http\Interfaces\PersonalDetailInterface;
use App\Http\Models\ProfileAdmin;
use App\Http\Transformers\PersonalDetailTransformer;
use App\Http\Transformers\ProfileAdminTransformer;
use App\Http\Transformers\ProfileClientTransformer;
use App\Http\Transformers\ProfileGuestTransformer;
use App\Http\Transformers\ProfileInspectorTransformer;
use App\Http\Transformers\TransformersManager;

class PersonalDetailGateway
{
    /**
     * @var PersonalDetailInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var PersonalDetailTransformer
     */
    private $transformer;

    /**
     * @param PersonalDetailInterface $repo
     * @param TransformersManager $transformersManager
     */
    public function __construct(PersonalDetailInterface $repo, TransformersManager $transformersManager)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
    }

    /**
     * Used for creating personal detail using the provided data.
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $personalDetails = $this->repo->create($data);

        if($personalDetails) {
            $personalDetail = $personalDetails->first();
            $this->transformer = $this->_getTransformer($personalDetail);
            $response = ($personalDetail) ? $this->transformersManager->transformItem($personalDetail, $this->transformer) : $personalDetail;

            return $response;
        }
        return false;
    }

    /**
     * Used for getting user by ID
     * @param $userId
     * @return array
     */
    public function getById($userId)
    {
        $personalDetail = $this->repo->getById($userId);
        if($personalDetail){
            $this->transformer = $this->_getTransformer($personalDetail->first());
            return ($personalDetail)?$this->transformersManager->transformCollectionWithoutPaginate($personalDetail, $this->transformer):$personalDetail;
        }
        return false;
    }

    /**
     * @param $data
     * @return array
     */
    public function createAdditional($data)
    {
        $user = $this->repo->createAdditional($data);
        if($user) {
            $this->transformer = $this->_getTransformer($user->first());
            return ($user) ? $this->transformersManager->transformCollectionWithoutPaginate($user, $this->transformer) : $user;
        }
        return false;
    }
    /**
     * Get transformer.
     * @param $model
     * @return ProfileAdminTransformer|ProfileClientTransformer|ProfileInspectorTransformer
     */
    public function _getTransformer($model)
    {
        $class = class_basename($model);
        switch ($class) {
            case 'ProfileAdmin':
                return new ProfileAdminTransformer();
                break;
            case 'ProfileClient':
                return new ProfileClientTransformer();
                break;
            case 'ProfileInspector':
                return new ProfileInspectorTransformer();
                break;
            case 'ProfileGuest':
                return new ProfileGuestTransformer();
                break;
        }
    }
}
