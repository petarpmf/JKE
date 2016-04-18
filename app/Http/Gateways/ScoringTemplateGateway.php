<?php
namespace App\Http\Gateways;

use App\Http\Interfaces\ScoringTemplateInterface;
use App\Http\Transformers\ScoringTemplateProjectTransformer;
use App\Http\Transformers\ScoringTemplateTransformer;
use App\Http\Transformers\TransformersManager;
use App\Http\Interfaces\RoleInterface;
use App\Http\Transformers\RoleTransformer;

class ScoringTemplateGateway
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
     * @var ScoringTemplateProjectTransformer
     */
    private $templateProjectTransformer;

    /**
     * @param RoleInterface $repo
     * @param TransformersManager $transformersManager
     * @param RoleTransformer $transformer
     */
    public function __construct(ScoringTemplateInterface $repo, TransformersManager $transformersManager,
            ScoringTemplateTransformer $transformer, ScoringTemplateProjectTransformer $templateProjectTransformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
        $this->templateProjectTransformer = $templateProjectTransformer;
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

    public function search($queryString, $perPage, $withTrashed)
    {
        if (isset($queryString['order_by']) && $queryString['order_by'] != '') {
            $orderByVariable = ($queryString['order_by'] == 'name')?'template_name':'created_at';
            if (isset($queryString['order_dir']) && $queryString['order_dir'] == 'asc') {
                $orderDirVariable = 'asc';
            } else {
                $orderDirVariable = 'desc';
            }
        } else {
            $orderByVariable = 'updated_at';
            $orderDirVariable = 'desc';
        }

        $withTrashed = $withTrashed === 'true'? true: false;
        $results = $this->repo->searchTemplate($queryString, $perPage, $withTrashed, $orderByVariable, $orderDirVariable);
        return ($results)?$this->transformersManager->transformCollection($results, $this->transformer):$results;
    }


    /**
     * Used for creating template using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $template = $this->repo->create($data);
        return ($template)?$this->transformersManager->transformItem($template, $this->transformer):$template;
    }

    /**
     * Used for getting template by ID
     *
     * @param $id
     * @return array
     */
    public function getById($id)
    {
        $template = $this->repo->getById($id);
        return ($template)?$this->transformersManager->transformItem($template, $this->transformer):$template;
    }

    /**
     * Used for updating template by ID and the provided data
     *
     * @param $id
     * @param $data
     * @return array
     */
    public function update($id, $data)
    {
        $template = $this->repo->update($id, $data);
        return ($template)?$this->transformersManager->transformItem($template, $this->transformer):$template;
    }

    /**
     * Used to delete template by ID
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /**
     * Used for filtering templates by some criteria
     *
     * @param $data
     * @return mixed
     */
    public function where($data)
    {
        return $this->repo->where($data);
    }

    /**
     * Used to restore template by ID
     *
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        return $this->repo->restore($id);
    }

    /**
     * Used to assign template to desired job in projects
     *
     * @param $templateId
     * @param $desiredJobProjectId
     * @param $type
     * @return bool
     */
    public function assignTemplateToProjectDesiredJob($templateId, $desiredJobProjectId, $type)
    {
        $result = $this->repo->assignTemplateToProjectDesiredJob($templateId, $desiredJobProjectId, $type);
        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * Used to remove assigned template from desired job in projects
     *
     * @param $templateId
     * @param $desiredJobProjectId
     * @param $type
     * @return bool
     */
    public function deleteTemplateFromProjectDesiredJob($templateId, $desiredJobProjectId, $type)
    {
        $result = $this->repo->deleteTemplateFromProjectDesiredJob($templateId, $desiredJobProjectId, $type);
        if ($result) {
            return true;
        }

        return false;
    }

    public function getAllTemplatesByProjectDesiredJob($desiredJobProjectId)
    {
        $templates = $this->repo->getAllTemplatesByProjectDesiredJob($desiredJobProjectId);
        return ($templates)?$this->transformersManager->transformCollectionWithoutPaginate($templates, $this->templateProjectTransformer):$templates;
    }

    public function checkIfTemplateExists($desiredJobId)
    {
        return $this->repo->checkIfTemplateExists($desiredJobId);
    }
}