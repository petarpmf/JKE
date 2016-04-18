<?php
namespace App\Http\Gateways;

use App\Http\Transformers\TransformersManager;
use App\Http\Interfaces\ClientInterface;
use App\Http\Transformers\UserCompanyTransformer;

class ClientGateway
{
    /**
     * @var ClientInterface
     */
    private $repo;
    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var UserCompanyTransformer
     */
    private $transformer;

    /**
     * @param ClientInterface $repo
     * @param TransformersManager $transformersManager
     * @param UserCompanyTransformer $transformer
     */
    public function __construct(ClientInterface $repo, TransformersManager $transformersManager, UserCompanyTransformer $transformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
    }

    /**
     * @param $queryString
     * @param $perPage
     * @return mixed
     */
    public function search($queryString, $perPage)
    {
        $clients = $this->repo->searchClients($queryString, $perPage);
        return ($clients)?$this->transformersManager->transformCollection($clients, $this->transformer):$clients;
    }

    /**
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $client = $this->repo->create($data);
        return ($client)?$this->transformersManager->transformItem($client, $this->transformer):$client;
    }

    /**
     * Used for getting client by ID
     * @param $id
     * @return array
     */
    public function getById($id)
    {
        $client = $this->repo->getById($id);
        return ($client)?$this->transformersManager->transformItem($client, $this->transformer):$client;
    }

    /**
     * Used for updating client by id (id from pivot table users_companies)
     *
     * @param $id
     * @param $data
     * @return array
     */
    public function update($id, $data)
    {
        $client = $this->repo->update($id, $data);
        return ($client)?$this->transformersManager->transformItem($client, $this->transformer):$client;
    }

    /**
     * Used to delete client.
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}