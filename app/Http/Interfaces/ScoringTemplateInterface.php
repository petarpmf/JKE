<?php
/**
 * Created by PhpStorm.
 * User: blagojce.jankulovski
 * Date: 12/4/2015
 * Time: 11:25 AM
 */
namespace App\Http\Interfaces;

use League\Fractal\Resource\Collection;

interface ScoringTemplateInterface
{
    /**
     * Used for creating new template in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data);

    /**
     *  Used to split each module on different database connection
     *
     * @return \Illuminate\Database\ConnectionResolverInterface
     */
    public function getConnection();

    /**
     * Used for filtering templates by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor);

    /**
     * Used for returning list of all templates
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all();

    /**
     * Used for returning paginated list of all templates
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage, $withTrashed);

    /**
     * Used for returning template by ID
     *
     * @param $id
     * @return bool
     */
    public function getById($id);

    /**
     * Used for updating template by ID
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data);

    /**
     * Used for deleting template by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id);

    /**
     * Used for restoring template by ID
     *
     * @param $id
     * @return bool
     */
    public function restore($id);

    /**
     * Used for returning paginated list of all templates
     *
     * @param $queryString
     * @param int $perPage
     * @param $withTrashed
     * @param $orderByVariable
     * @param $orderDirVariable
     * @return mixed
     */
    public function searchTemplate($queryString, $perPage, $withTrashed, $orderByVariable, $orderDirVariable);

    /**
     * Used for assigning template to desired job in a project
     *
     * @param $templateId
     * @param $desiredJobProjectId
     * @param $type
     * @return static
     */
    public function assignTemplateToProjectDesiredJob($templateId, $desiredJobProjectId, $type);

    /**
     * Used for deleting a template from desired job in a project
     *
     * @param $templateId
     * @param $desiredJobProjectId
     * @param $type
     * @return bool
     */
    public function deleteTemplateFromProjectDesiredJob($templateId, $desiredJobProjectId, $type);
}