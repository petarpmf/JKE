<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\DashboardInterface;
use App\Http\Models\ActivityProjectsTeams;
use App\Http\Models\Project;
use App\Http\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Jke\Jobs\Models\DesiredJob;

class EloquentDashboardRepository implements DashboardInterface
{
    /**
     * Return all seeking job positions.
     * @return mixed
     */
    public function seekingJobPosition()
    {
        $result = DesiredJob::selectRaw("desired_jobs.name, ifnull(report.cnt, 0) as count")
                            ->leftJoin(DB::raw("(select count(*) cnt, desired_job_id from users_desired_jobs udj inner join users u on u.id = udj.user_id where u.deleted_at is null group by desired_job_id) AS report"), 'report.desired_job_id', '=', 'desired_jobs.id')
                            ->get()
                            ->toArray();
        return $result;
    }

    /**
     * Return number of all active candidates and number of candidates who seeking job.
     * @return mixed
     */
    public function totalActiveCandidates($roleId)
    {
        $result = User::selectRaw("COUNT(*) AS total_candidates, SUM(currently_seeking_opportunities) AS seeking_job")
                        ->join('profile_inspectors as pi', 'users.profile_id', '=', 'pi.id')
                        ->where('role_id', '=', $roleId)
                        ->get()
                        ->toArray();
        return $result;
    }

    /**
     * Return recently added candidates.
     * @return mixed
     */
    public function recentlyAddedCandidates($perPage, $roleId)
    {   
        $result = User::selectRaw("id, first_name, last_name, created_at")
                        ->where('role_id', '=', $roleId)
                       ->orderBy("created_at", "DESC")
                       ->take($perPage)
                       ->get()
                       ->toArray();
        return $result;
    }

    /**
     * Return number of projects
     * @return mixed
     */
    public function numberOfProjects()
    {
        $result = Project::selectRaw("COUNT(*) AS total_projects")
            ->join('companies as c', function($join)
            {
                $join->on('projects.company_id', '=', 'c.id');
                $join->whereNull('c.deleted_at');
            })
            ->get()
            ->toArray();
        return $result;
    }

    public function recentActivityViewAll($queryString, $roleId, $perPage)
    {

        //if(isset($queryString['show']) && $queryString['show']=='all'){

            $resultCreatedAt = User::selectRaw("id, first_name, last_name, CONVERT_TZ(users.created_at,'+5:00','+00:00') as date, @var1 := 1 AS type")
                ->where('role_id', '=', $roleId)
                ->filterRecentActivityByUser($queryString)
                //->whereRaw('users.created_at <> users.updated_at')
                ->orderBy("created_at", "DESC");

            $resultUsersUpdatedAt = User::selectRaw("users.id, first_name, last_name,
            GREATEST(
            CONVERT_TZ(users.updated_at,'+5:00','+00:00'), CONVERT_TZ(profile_inspectors.updated_at,'+5:00','+00:00')
            ) AS `date`,
            @var2 := 2 AS type")
                ->join('profile_inspectors', 'users.profile_id', '=', 'profile_inspectors.id')
                ->where('role_id', '=', $roleId)
                ->filterRecentActivityByUser($queryString)
                ->whereRaw('users.created_at <> users.updated_at')
                ->orderBy("date", "DESC");

            $resultDesiredJobsUpdatedAt = User::selectRaw("users.id, first_name, last_name,
             CONVERT_TZ(users_desired_jobs.created_at,'+5:00','+00:00') AS `date`,
            @var2 := 2 AS type")
                ->join('users_desired_jobs', 'users.id', '=', 'users_desired_jobs.user_id')
                ->where('role_id', '=', $roleId)
                ->filterRecentActivityByUser($queryString)
                ->orderBy("date", "DESC");

            $resultExperiencesUpdatedAt = User::selectRaw("users.id, first_name, last_name,
            GREATEST(
              COALESCE(CONVERT_TZ(users_experiences.created_at,'+5:00','+00:00'),0), COALESCE(CONVERT_TZ(users_experiences.updated_at,'+5:00','+00:00'),0)
            ) AS `date`,
            @var2 := 2 AS type")
                ->join('users_experiences', 'users.id', '=', 'users_experiences.user_id')
                ->where('role_id', '=', $roleId)
                ->filterRecentActivityByUser($queryString)
                ->orderBy("date", "DESC");

            $resultQualificationsUpdatedAt = User::selectRaw("users.id, first_name, last_name,
            GREATEST(
              COALESCE(CONVERT_TZ(users_qualifications.created_at,'+5:00','+00:00'),0), COALESCE(CONVERT_TZ(users_qualifications.updated_at,'+5:00','+00:00'),0)
            ) AS `date`,
            @var2 := 2 AS type")
                ->join('users_qualifications', 'users.id', '=', 'users_qualifications.user_id')
                ->where('role_id', '=', $roleId)
                ->filterRecentActivityByUser($queryString)
                ->orderBy("date", "DESC");

            $resultCertificatesUpdatedAt = User::selectRaw("users.id, first_name, last_name,
            GREATEST(
               COALESCE(CONVERT_TZ(users_certificates.created_at,'+5:00','+00:00'),0), COALESCE(CONVERT_TZ(users_certificates.updated_at,'+5:00','+00:00'),0)
            ) AS `date`,
            @var2 := 2 AS type")
                ->join('users_certificates', 'users.id', '=', 'users_certificates.user_id')
                ->where('role_id', '=', $roleId)
                ->filterRecentActivityByUser($queryString)
                ->orderBy("date", "DESC");

            $resultReferencesUpdatedAt = User::selectRaw("users.id, first_name, last_name,
            GREATEST(
               COALESCE(CONVERT_TZ(references.created_at,'+5:00','+00:00'),0), COALESCE(CONVERT_TZ(references.updated_at,'+5:00','+00:00'),0)
            ) AS `date`,
            @var2 := 2 AS type")
                ->join('references', 'users.id', '=', 'references.user_id')
                ->where('role_id', '=', $roleId)
                ->filterRecentActivityByUser($queryString)
                ->orderBy("date", "DESC");

            $resultInnermetrixUpdatedAt = User::selectRaw("users.id, first_name, last_name,
            GREATEST(
               COALESCE(CONVERT_TZ(users_innermetrix.created_at,'+5:00','+00:00'),0), COALESCE(CONVERT_TZ(users_innermetrix.updated_at,'+5:00','+00:00'),0)
            ) AS `date`,
            @var2 := 2 AS type")
                ->join('users_innermetrix', 'users.id', '=', 'users_innermetrix.user_id')
                ->where('role_id', '=', $roleId)
                ->filterRecentActivityByUser($queryString)
                ->orderBy("date", "DESC");

            $resultLoginAt = User::selectRaw("users.id, first_name, last_name, CONVERT_TZ(report.created_at,'+5:00','+00:00') as date, @var3 := 3 AS type")
                ->join(DB::raw("(SELECT user_id, tokens.created_at FROM tokens INNER JOIN users ON users.id = tokens.user_id where users.deleted_at is null ORDER BY tokens.created_at DESC) AS report"), 'report.user_id', '=', 'users.id')
                ->where('role_id', '=', $roleId)
                ->whereRaw('users.created_at <> report.created_at')
                ->union($resultCreatedAt)
                ->union($resultUsersUpdatedAt)
                ->union($resultDesiredJobsUpdatedAt)
                ->union($resultExperiencesUpdatedAt)
                ->union($resultQualificationsUpdatedAt)
                ->union($resultCertificatesUpdatedAt)
                ->union($resultReferencesUpdatedAt)
                ->union($resultInnermetrixUpdatedAt)
                ->filterRecentActivityByUser($queryString)
                ->orderBy("date", "DESC")
                ->get()->toArray();


        if(isset($queryString['show']) && $queryString['show']=='all'){
            $allActivity = $resultLoginAt;
        }else{//Showing Activity feed for projects/teams only on dashboard

            ///Activity feed for projects/teams
            $activityProjectsTeams = User::selectRaw("users.id, users.first_name, users.last_name,
                                      COALESCE(CONVERT_TZ(activity_projects_teams.created_at,'+5:00','+00:00'),0) AS `date`,
                                    activity_projects_teams.activity_type AS type, activity_projects_teams.project_team_name")
                ->join('activity_projects_teams', 'users.id', '=', 'activity_projects_teams.user_id')
                ->where('users.role_id', '=', $roleId)
                ->orderBy("date", "DESC")
                ->get()->toArray();
            //End activity feed for projects/teams
            //merge arrays
            $allActivity = array_merge($resultLoginAt, $activityProjectsTeams);
            $this->arraySortByColumn($allActivity, 'date', SORT_DESC);
        }


        return $allActivity;
       /*
        }else{
            $resultCreatedAt = User::selectRaw("id, first_name, last_name, CONVERT_TZ(users.updated_at,'+5:00','+00:00') as date, @var1 := 1 AS type")
                ->where('role_id', '=', $roleId)
                ->filterRecentActivityByUser($queryString)
                ->orderBy("created_at", "DESC");

            //$resultUpdatedAt = User::selectRaw("users.id, first_name, last_name, IF(users.updated_at>profile_inspectors.updated_at,users.updated_at,profile_inspectors.updated_at) AS `date`, @var2 := 2 AS type")
                $resultUpdatedAt = User::selectRaw("users.id, first_name, last_name,
            GREATEST(
            CONVERT_TZ(users.updated_at,'+5:00','+00:00'), CONVERT_TZ(profile_inspectors.updated_at,'+5:00','+00:00'),
            CONVERT_TZ(users_desired_jobs.created_at,'+5:00','+00:00'),CONVERT_TZ(users_desired_jobs.updated_at,'+5:00','+00:00'),

            COALESCE(CONVERT_TZ(users_experiences.created_at,'+5:00','+00:00'),0), COALESCE(CONVERT_TZ(users_experiences.updated_at,'+5:00','+00:00'),0),
            COALESCE(CONVERT_TZ(users_qualifications.created_at,'+5:00','+00:00'),0), COALESCE(CONVERT_TZ(users_qualifications.updated_at,'+5:00','+00:00'),0),
            COALESCE(CONVERT_TZ(users_certificates.created_at,'+5:00','+00:00'),0), COALESCE(CONVERT_TZ(users_certificates.updated_at,'+5:00','+00:00'),0),
            COALESCE(CONVERT_TZ(references.created_at,'+5:00','+00:00'),0), COALESCE(CONVERT_TZ(references.updated_at,'+5:00','+00:00'),0),
            COALESCE(CONVERT_TZ(users_innermetrix.created_at,'+5:00','+00:00'),0), COALESCE(CONVERT_TZ(users_innermetrix.updated_at,'+5:00','+00:00'),0)) AS `date`,
            @var2 := 2 AS type")
                    ->leftJoin('profile_inspectors', 'users.profile_id', '=', 'profile_inspectors.id')
                    ->leftJoin('users_desired_jobs', 'users.id', '=', 'users_desired_jobs.user_id')
                    ->leftJoin('users_experiences', 'users.id', '=', 'users_experiences.user_id')
                    ->leftJoin('users_qualifications', 'users.id', '=', 'users_qualifications.user_id')
                    ->leftJoin('users_certificates', 'users.id', '=', 'users_certificates.user_id')
                    ->leftJoin('references', 'users.id', '=', 'references.user_id')
                    ->leftJoin('users_innermetrix', 'users.id', '=', 'users_innermetrix.user_id')
                    ->where('role_id', '=', $roleId)
                    ->filterRecentActivityByUser($queryString)
                    //->whereRaw('users.created_at <> users.updated_at')
                    ->orderBy("date", "DESC");

                //dd($resultUpdatedAt);
                $resultLoginAt = User::selectRaw("users.id, first_name, last_name, CONVERT_TZ(report.created_at,'+5:00','+00:00') as date, @var3 := 3 AS type")
                    ->join(DB::raw("(SELECT user_id, tokens.created_at FROM tokens INNER JOIN users ON users.id = tokens.user_id where users.deleted_at is null ORDER BY tokens.created_at DESC) AS report"), 'report.user_id', '=', 'users.id')
                    ->where('role_id', '=', $roleId)
                    ->whereRaw('users.created_at <> report.created_at')

                    //->groupBy('users.id')
                    ->union($resultCreatedAt)
                    ->union($resultUpdatedAt)
                    ->filterRecentActivityByUser($queryString)
                    ->orderBy("date", "DESC")
                    ->get()->toArray();

                return $resultLoginAt;
        }
       */

    }
/*
    public function recentActivity($roleId, $perPage)
    {
        $resultCreatedAt = User::selectRaw("id, first_name, last_name, created_at as date")
            ->where('role_id', '=', $roleId)
            ->orderBy("created_at", "DESC")
            ->get()
            ->toArray();

        $resultUpdatedAt = User::selectRaw("id, first_name, last_name, updated_at as date")
            ->where('role_id', '=', $roleId)
            ->whereRaw('created_at <> updated_at')
            ->orderBy("updated_at", "DESC")
            ->get()
            ->toArray();

        $resultLoginAt = User::selectRaw("users.id, first_name, last_name, report.created_at as date")
            ->join(DB::raw("(SELECT user_id, tokens.created_at FROM tokens INNER JOIN users ON users.id = tokens.user_id where users.deleted_at is null ORDER BY tokens.created_at DESC) AS report"), 'report.user_id', '=', 'users.id')
            ->where('role_id', '=', $roleId)
            ->whereRaw('users.created_at <> report.created_at')
            ->groupBy('users.id')
            ->get()
            ->toArray();
        //Type 1 means recent activity by created_at
        $this->arraySortByColumn($resultCreatedAt, 'date', SORT_DESC, 1);

        //Type 2 means recent activity by updated_at
        $this->arraySortByColumn($resultUpdatedAt, 'date', SORT_DESC, 2);

        //Type 3 means recent activity by login_at
        $this->arraySortByColumn($resultLoginAt, 'date', SORT_DESC, 3);

        //merging 3 arrays
        $array = array_merge_recursive($resultCreatedAt, $resultUpdatedAt, $resultLoginAt);

        //sorting merged arrays
        $this->arraySortByColumn($array, 'date', SORT_DESC);
        $array = array_slice($array, 0, $perPage);
        return $array;
    }

    public function recentCreated($roleId, $perPage)
    {
        $result = User::selectRaw("id, first_name, last_name, created_at as date")
            ->where('role_id', '=', $roleId)
            ->orderBy("created_at", "DESC")
            ->paginate($perPage);

        return $result;
    }

    public function recentUpdated($roleId, $perPage)
    {
        $result = User::selectRaw("id, first_name, last_name, updated_at as date")
            ->where('role_id', '=', $roleId)
            //->whereRaw('created_at <> updated_at')
            ->orderBy("updated_at", "DESC")
            ->paginate($perPage);

        return $result;
    }

    public function recentLogged($roleId, $perPage)
    {
        $result = User::selectRaw("users.id, first_name, last_name, report.created_at as date")
            ->join(DB::raw("(SELECT user_id, tokens.created_at FROM tokens INNER JOIN users ON users.id = tokens.user_id where users.deleted_at is null ORDER BY tokens.created_at DESC) AS report"), 'report.user_id', '=', 'users.id')
            ->where('role_id', '=', $roleId)
            ->whereRaw('users.created_at <> report.created_at')
            ->groupBy('users.id')
            ->orderBy("report.created_at", "DESC")
            ->paginate($perPage);

        return $result;
    }
*/
    function arraySortByColumn(&$arr, $col, $dir = SORT_DESC, $type=0) {
        $sort_col = array();
        foreach ($arr as $key=>&$row) {
            ($type!=0)?$row['type'] = $type:"";
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    public function recentProjectTeamActivity($userId, $roleId, $perPage){

      $activityProjectsTeams = ActivityProjectsTeams::selectRaw("user_id, activity_type, project_team_name,
                                                    COALESCE(CONVERT_TZ(created_at,'+5:00','+00:00'),0) as created_at")
                                                     ->where('user_id', '=', $userId)
                                                     ->orderBy("created_at", "DESC")
                                                    ->get();
        if($activityProjectsTeams->count() > 0){
            return $activityProjectsTeams->toArray();
        }
        return false;

    }
}