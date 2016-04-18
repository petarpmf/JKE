<?php
namespace App\Http\Gateways;

use App\Http\Transformers\TransformersManager;
use App\Http\Interfaces\CompanyInterface;
use App\Http\Transformers\CompanyTransformer;

class CompanyGateway
{
    /**
     * @var CompanyInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var CompanyTransformer
     */
    private $transformer;

    /**
     * @param CompanyInterface $repo
     * @param TransformersManager $transformersManager
     * @param CompanyTransformer $transformer
     */
    public function __construct(CompanyInterface $repo, TransformersManager $transformersManager, CompanyTransformer $transformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
    }

    /**
     * Used for returning paginated results based on the supplied value for items per page
     *
     * @param $perPage
     * @return mixed
     */
    public function getList($perPage)
    {
        $results = $this->repo->paginate($perPage);
        return ($results)?$this->transformersManager->transformCollection($results, $this->transformer):$results;
    }

    public function getAll()
    {
        $results = $this->repo->all();
        $results = array_map(function ($ar) {return array('id'=>$ar['id'], 'company_name'=>$ar['company_name']);}, $results->toArray());
        return array('data'=>$results);
    }

    /**
     * Used for creating company using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $company = $this->repo->create($data);
        return ($company)?$this->transformersManager->transformItem($company, $this->transformer):$company;
    }

    /**
     * Used for getting company by ID
     *
     * @param $id
     * @return array
     */
    public function getById($id)
    {
        $company = $this->repo->getById($id);
        return ($company)?$this->transformersManager->transformItem($company, $this->transformer):$company;
    }

    /**
     * Used for updating company by ID and the provided data
     *
     * @param $id
     * @param $data
     * @return array
     */
    public function update($id, $data)
    {
        $company = $this->repo->update($id, $data);
        return ($company)?$this->transformersManager->transformItem($company, $this->transformer):$company;
    }

    /**
     * Used to delete company by ID
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /**
     * @param $queryString
     * @param $perPage
     * @return mixed
     */
    public function search($queryString, $perPage)
    {
        $companies = $this->repo->searchCompanies($queryString, $perPage);
        return ($companies)?$this->transformersManager->transformCollection($companies, $this->transformer):$companies;
    }
}