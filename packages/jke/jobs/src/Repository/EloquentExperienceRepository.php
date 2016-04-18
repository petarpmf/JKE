<?php
namespace Jke\Jobs\Repository;

use Illuminate\Support\Facades\DB;
use Jke\Jobs\Interfaces\ExperienceInterface;
use Jke\Jobs\Models\Experience;
use Jke\Jobs\Models\UserExperience;

class EloquentExperienceRepository implements ExperienceInterface
{

    /**
     * Used for returning list of all experiences
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Experience::get();
    }

    /**
     * Used for creating new Experience in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        $experience = Experience::with('users')->findOrFail($data['experience_id']);
        $experience->users()->attach($data['user_id'], $data);
        $lastInsertId = DB::getPdo()->lastInsertId();

        foreach($data as $key=>$value){
            $experience->$key = $value;
            $experience->id = $lastInsertId;
        }
        return $experience;
    }

    /**
     * Get all experiences for user by $userId
     * @param $userId
     * @return bool
     */
    public function getById($userId)
    {
        $experiences = UserExperience::where('user_id','=',$userId)->with('experience')->get();

        //get experience name from experiences table
        foreach($experiences as &$experience){
            $experience->experience_name = $experience->experience->experience_name;
        }

        if ($experiences) {
            return $experiences;
        }
        return false;
    }

    /**
     * Get selected experience for user by $userId and $experienceId
     * @param $userId
     * @param $experienceId
     * @return bool
     */
    public function getExperienceById($userId, $experienceId)
    {
        $experience = UserExperience::where('user_id','=',$userId)->where('id','=',$experienceId)->get();
        if ($experience) {
            return $experience;
        }
        return false;
    }

    /**
     * Used for updating users_experiences table by user_id and id (from users_experiences table)
     * @param $data
     * @return bool
     */
    public function update($data)
    {
        $experienceForUpdate = UserExperience::where('user_id', '=', $data['user_id'])->where('id', '=', $data['id']);
        if ($experienceForUpdate) {
            return $experienceForUpdate->update($data)?$experienceForUpdate->get():false;
        }

        return false;
    }

    /**
     * Used for deleting from users_experiences table by $userId and $experienceId
     * @param $userId
     * @param $experienceId
     * @return bool
     */
    public function delete($userId, $experienceId)
    {
        $experienceForDelete = UserExperience::where('user_id', '=', $userId)->where('id', '=', $experienceId);
        if ($experienceForDelete->count()>0) {
            return $experienceForDelete->delete();
        }
        return false;
    }
}