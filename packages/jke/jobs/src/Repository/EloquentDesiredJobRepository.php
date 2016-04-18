<?php
namespace Jke\Jobs\Repository;

use Illuminate\Database\Eloquent\Collection;
use Jke\Jobs\Interfaces\DesiredJobInterface;
use Jke\Jobs\Models\DesiredJob;
use Jke\Jobs\Models\UserDesiredJob;

class EloquentDesiredJobRepository implements DesiredJobInterface
{

    /**
     * Used for returning list of all desired jobs
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return DesiredJob::orderBy('created_at','ASC')->get();
    }

    /**
     * Used for creating new job in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        $job = DesiredJob::with('users')->findOrFail($data['desired_job_id']);
        $job->users()->attach($data['user_id'], ['desired_job_id'=>$data['desired_job_id']]);
        $job->user_id = $data['user_id'];
        return $job;
    }

    /**Get desired job by user_id
     * @param $userId
     * @return bool
     */
    public function getById($userId)
    {
        $jobs = DesiredJob::selectRaw("desired_jobs.id, desired_jobs.name, udj.user_id, IF(ISNULL(user_id),'no','yes') as is_selected")
            ->leftJoin('users_desired_jobs as udj', function($join) use ($userId)
            {
                $join->on('desired_jobs.id', '=', 'udj.desired_job_id');
                $join->where('udj.user_id', '=', $userId);
                //$join->whereNull('udj.deleted_at');
            })
            ->orderBy('desired_jobs.created_at', 'ASC')
            ->get();

        if ($jobs) {
            return $jobs;
        }
        return false;
    }

    /**
     * Used for deleting job by user_id and desired_job_id
     * @param $userId
     * @param $desiredJobId
     * @return bool
     */
    public function delete($userId, $desiredJobId)
    {
        $jobForDelete = UserDesiredJob::where('user_id', '=', $userId)->where('desired_job_id', '=', $desiredJobId);
        if ($jobForDelete->count()>0) {
            return $jobForDelete->delete();
        }
        return false;
    }

    /**
     * Get desired job by ID
     *
     * @param $desiredJobId
     * @return mixed
     */
    public function getDesiredJobById($desiredJobId)
    {
        return DesiredJob::find($desiredJobId);
    }

}