<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\InnermetrixInterface;
use App\Http\Models\UserInnermatrix;

class EloquentInnermetrixRepository implements InnermetrixInterface
{
    /**
     * Used for adding innermetrix scores in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        return UserInnermatrix::create($data);
    }

    /**
     *  Used to split each module on different database connection
     *
     * @return \Illuminate\Database\ConnectionResolverInterface
     */
    public function getConnection()
    {
        return UserInnermatrix::getConnectionResolver();
    }

    /**
     * Used for filtering innermatrix scores by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor)
    {
        $userScore = UserInnermatrix::where($searchFor)->first();
        return $userScore ? $userScore : null;
    }

    /**
     * Used for returning user scores by user ID
     *
     * @param $id
     * @return bool
     */
    public function getByUserId($userId)
    {
        $userScores = UserInnermatrix::where('user_id', '=', $userId)->first();
        if ($userScores) {
            return $userScores;
        }

        return false;
    }

    /**
     * Used for updating inneermetrix scores by user ID
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateByUserId($userId, $data)
    {
        $userScoresForUpdate = UserInnermatrix::where('user_id', '=', $userId)->first();
        if ($userScoresForUpdate) {
            return $userScoresForUpdate->update($data)?$userScoresForUpdate:false;
        }

        return false;
    }

    /**
     * Used for deleting innermetrix scores for user
     *
     * @param $id
     * @return bool
     */
    public function deleteByUserId($userId)
    {
        $userScoresForDelete = UserInnermatrix::where('user_id', '=', $userId)->first();

        if ($userScoresForDelete) {
            return $userScoresForDelete->delete();
        }
        return false;
    }
}