<?php
namespace App\Http\Gateways;

use App\Http\Interfaces\TokenInterface;
use App\Http\Models\User;

/**
 * Class TokenGateway
 */
class TokenGateway
{
    /**
     * @var TokenInterface
     */
    private $repo;

    /**
     * @param TokenInterface $repo
     */
    public function __construct(TokenInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Return generated token from the Repository
     *
     * @return mixed
     */
    public function generate()
    {
        return $this->repo->generate();
    }

    /**
     * Return the result from saving the token to the Repository for the specified user
     *
     * @param $token
     * @param User $user
     * @return mixed
     */
    public function save($token, User $user)
    {
        return $this->repo->save($token, $user);
    }

    /**
     * Return result from token verification
     *
     * @param $token
     * @return mixed
     */
    public function verify($token)
    {
        return $this->repo->verify($token);
    }

    /**
     * Return result from destroying the token
     *
     * @param $token
     * @return mixed
     */
    public function destroy($token)
    {
        return $this->repo->destroy($token);
    }

    public function getUserId($token)
    {
        return $this->repo->getUserId($token);
    }
}