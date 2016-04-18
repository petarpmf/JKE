<?php
namespace App\Http\Gateways;

use App\Http\Transformers\TransformersManager;
use App\Http\Interfaces\RoleInterface;
use App\Http\Transformers\RoleTransformer;

class RoleGateway
{
    /**
     * @var RoleInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var RoleTransformer
     */
    private $transformer;

    /**
     * @param RoleInterface $repo
     * @param TransformersManager $transformersManager
     * @param RoleTransformer $transformer
     */
    public function __construct(RoleInterface $repo, TransformersManager $transformersManager, RoleTransformer $transformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
    }

    /**
     * Used for returning paginated results based on the supplied value for items per page
     *
     * @param $perPage
     * @param $withTrashed
     * @return mixed
     */
    public function getList($perPage, $withTrashed)
    {
        $withTrashed = $withTrashed === 'true'? true: false;
        $results = $this->repo->paginate($perPage, $withTrashed);
        return ($results)?$this->transformersManager->transformCollection($results, $this->transformer):$results;
    }

    /**
     * Used for creating role using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $role = $this->repo->create($data);
        return ($role)?$this->transformersManager->transformItem($role, $this->transformer):$role;
    }

    /**
     * Used for getting role by ID
     *
     * @param $id
     * @return array
     */
    public function getById($id)
    {
        $role = $this->repo->getById($id);
        return ($role)?$this->transformersManager->transformItem($role, $this->transformer):$role;
    }

    /**
     * Used for updating role by ID and the provided data
     *
     * @param $id
     * @param $data
     * @return array
     */
    public function update($id, $data)
    {
        $role = $this->repo->update($id, $data);
        return ($role)?$this->transformersManager->transformItem($role, $this->transformer):$role;
    }

    /**
     * Used to delete role by ID
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /**
     * Used to check if role can be deleted by ID
     *
     * @param $id
     * @return mixed
     */
    public function checkDelete($id)
    {
        return $this->repo->checkDelete($id);
    }

    /**
     * Used for filtering Users by some criteria
     *
     * @param $data
     * @return mixed
     */
    public function where($data)
    {
        return $this->repo->where($data);
    }

    /**
     * Used to restore deleted role by ID
     *
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        return $this->repo->restore($id);
    }
}