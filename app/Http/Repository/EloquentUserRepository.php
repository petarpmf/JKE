<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\UserInterface;
use App\Http\Models\ProfileAdmin;
use App\Http\Models\ProfileClient;
use App\Http\Models\ProfileGuest;
use App\Http\Models\ProfileInspector;
use App\Http\Models\ScoringTemplate;
use App\Http\Models\User;
use App\Http\Models\UserDesiredJobProject;
use Illuminate\Support\Facades\DB;
//use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Rhumsaa\Uuid\Uuid;
use Illuminate\Database\Eloquent\Collection;

class EloquentUserRepository implements UserInterface
{
    /**
     * Used for creating new user in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        switch ($data['role_id']) {
            case 1: // If user is Admin
                $lastId = Uuid::uuid4()->toString();
                $profile = ProfileAdmin::create(['id'=>$lastId]);
                $profile = ProfileAdmin::find($profile->id);
                break;
            case 2: // If user is Client
                $lastId = Uuid::uuid4()->toString();
                $profile = ProfileClient::create(['id'=>$lastId]);
                $profile = ProfileClient::find($profile->id);
                break;
            case 3: // If user is Inspector
                $lastId = Uuid::uuid4()->toString();
                $profile = ProfileInspector::create(['id'=>$lastId]);
                $profile = ProfileInspector::find($profile->id);
                break;
            case 4: // If user is Guest
                $lastId = Uuid::uuid4()->toString();
                $profile = ProfileGuest::create(['id'=>$lastId]);
                $profile = ProfileGuest::find($profile->id);
                break;
            default: // default user is Inspector
                $lastId = Uuid::uuid4()->toString();
                $profile = ProfileInspector::create(['id'=>$lastId]);
                $profile = ProfileInspector::find($profile->id);
        }
        //polymorphic-relations
        return $profile->users()->create($data);

    }

    /**
     *  Used to split each module on different database connection
     *
     * @return \Illuminate\Database\ConnectionResolverInterface
     */
    public function getConnection()
    {
        return User::getConnectionResolver();
    }

    /**
     * Used for filtering users by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor)
    {
        $user = User::where($searchFor)->first();
        return $user ? $user : null;
    }

    /**
     * Used for returning list of all users
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return User::all();
    }

    /**
     * Used for returning paginated list of all users
     *
     * @param int $perPage
     * @param $withTrashed
     * @return mixed
     */
    public function paginate($perPage, $withTrashed)
    {
        $user = new User();
        if ($withTrashed === true) {
            $user = $user->withTrashed();
        }

        return $user->orderBy('updated_at','desc')->where("role_id", "=",3)->paginate($perPage);
    }

    /**
     * Used for returning user by ID
     *
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $user = User::find($id);
        if ($user) {
            return $user;
        }

        return false;
    }

    /**
     * Used for updating user by ID
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data)
    {
        $userForUpdate = User::find($id);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($userForUpdate) {
            return $userForUpdate->update($data)?$userForUpdate:false;
        }

        return false;
    }

    /**
     * Used for deleting user by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $userForDelete = User::find($id);

        if ($userForDelete) {
            //polymorfic relations
            return $userForDelete->delete()?$userForDelete->profile()->delete():false;
        }
        return false;
    }

    /**
     * Used for token generation for user identified by email
     *
     * @param $email
     * @return bool or User
     */
    public function generateForgotTokenForUser($email)
    {
        $user = User::where('email','=',$email)->first();
        if ($user) {
            $user->forgot_token = (string)Uuid::uuid4();
            $user->save();
            return $user;
        }
        return false;
    }

    /**
     * Used to reset password by entered token. Token can be used once and is removed after usage.
     *
     * @param $data
     * @return bool
     */
    public function resetPassword($data)
    {
        $user = User::where('forgot_token', $data['forgot_token'])->first();
        if ($user) {
            $user->password = $data['password'];
            $user->forgot_token = null;
            $user->save();

            return true;
        }
        return false;
    }

    /**
     * Used for restoring user by ID
     *
     * @param $id
     * @return bool
     */
    public function restore($id)
    {
        $userForRestore = User::withTrashed()->find($id);
        if ($userForRestore) {
            return $userForRestore->restore();
        }
        return false;
    }

    /**
     * Search user function.
     * @param array $queryString
     * @return mixed
     */
    public function searchUser(array $queryString, $perPage)
    {   //dd($queryString);
        $articles = User::selectRaw("users.id, users.first_name, users.last_name, users.email, pi.street_address, pi.city, pi.zip, pi.state, pi.country,
                                    pi.mobile_phone, pi.other_phone, pi.resume_link, pi.job_title, pi.summary, pi.currently_seeking_opportunities,
                                    pi.other_jobs, users.role_id, users.created_at, dj.id as desire_job_id, dj.name as desire_job_name,
                                        IF((SELECT SUM(users_experiences.years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id) IS NULL, 0, (SELECT SUM(users_experiences.years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id)) as sum_experiences,
                                           (SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id) as count_certificates")

            ->join('profile_inspectors as pi', 'users.profile_id', '=', 'pi.id')
            ->leftJoin('users_desired_jobs as udj', 'users.id', '=', 'udj.user_id')
            ->leftJoin('desired_jobs as dj', 'udj.desired_job_id', '=', 'dj.id')
            ->leftJoin('users_certificates', 'users_certificates.user_id', '=', 'users.id')
            ->leftJoin('users_experiences', 'users_experiences.user_id', '=', 'users.id')
            ->leftJoin('scorings', 'scorings.user_id', '=', 'users.id')
            //->leftJoin('users_teams', 'users_teams.user_id', '=', 'users.id')
            ->groupBy('users.id')
            ->filter($queryString);

        if (isset($queryString['multi']) && $queryString['multi'] == 'true') {
            /*
            $articles = $articles->whereNotIn('users.id',function ($query)
            {
                $query->select('user_id')->from('users_desired_jobs_projects');
            });
            */
            //List all inspectors that aren't hired in team
            //$articles = $articles->whereNotIn('users.id',function ($query)
            //{
                //$query->select('user_id')->from('users_teams')->where("status", "<>", "'Hired'");
            //});
            //$articles = $articles->where("users_teams.status","<>","'Hired'");

            $articles = $articles->where(function ($query) {
                $query->whereNotIn('users.id', function ($query) {
                    $query->select('user_id')->from('users_teams');
                })->orWhere(function ($query) {
                    $query->whereIn('users.id', function ($query) {
                        $query->select(DB::raw("users_teams.user_id FROM users_teams
                                       JOIN `desired_jobs_projects` ON users_teams.desired_job_project_id=desired_jobs_projects.id
                                       WHERE users_teams.status <> 'Hired'"));
                    });
                });
            });

        }

        $articles = $articles->where("users.role_id", "=",3);

           if (isset($queryString['multi']) && $queryString['multi'] == 'true') {

               if (isset($queryString['desired_job'])) {
                   $scoringTemplate = ScoringTemplate::where('desired_job_id', '=', $queryString['desired_job'])->first();

                   if(count($scoringTemplate)>0){
                       $articles = $articles->get();
                   }else{
                       $articles = $articles->paginate($perPage);
                   }
               }else{
                   $articles = $articles->paginate($perPage);
               }
           }else{
               $articles = $articles->paginate($perPage);
           }

       // dd($articles->toArray());
        return $articles;
    }

    /**
     * Search user function.
     * @param array $queryString
     * @return mixed
     */
    public function searchInspectorUser(array $queryString, $perPage)
    {
        $articles = User::selectRaw("users.id, users.first_name, users.last_name, users.email, pi.street_address, pi.city, pi.zip, pi.state, pi.country,
                                    pi.mobile_phone, pi.other_phone, pi.resume_link, pi.job_title, pi.summary, pi.currently_seeking_opportunities,
                                    pi.other_jobs, users.role_id, users.created_at, dj.id as desire_job_id, dj.name as desire_job_name")
            ->join('profile_inspectors as pi', 'users.profile_id', '=', 'pi.id')
            ->leftJoin('users_desired_jobs as udj', 'users.id', '=', 'udj.user_id')
            ->leftJoin('desired_jobs as dj', 'udj.desired_job_id', '=', 'dj.id')
            ->leftJoin('users_desired_jobs_projects as udjp', 'udjp.user_id', '=', 'users.id')
            ->leftJoin('users_certificates', 'users_certificates.user_id', '=', 'users.id')
            ->groupBy('users.id')
            ->filter($queryString);

        if (isset($queryString['multi']) && $queryString['multi'] == 'true' && isset($queryString['project_id'])) {

            $articles = $articles->whereNotIn('users.id',function ($query) use ($queryString)
            {
                $query->select('user_id')->from('users_desired_jobs_projects')
                      ->join('desired_jobs_projects', 'users_desired_jobs_projects.desired_job_project_id', '=', 'desired_jobs_projects.id')
                      ->where('desired_jobs_projects.project_id', '!=', $queryString['project_id']);
            });
        }

        $articles = $articles->where("users.role_id", "=",3)
            ->orderBy('udjp.created_at', 'DESC')
            ->paginate($perPage);

        return $articles;
    }

    /**
     * Set user id (If user is logged as admin than get user id from post/get,
     * if not than get logged user id)
     * @param $user
     * @return mixed
     */
    public function setUserId($user)
    {
        $user = $user->toArray();
        //if role_id == 1 than is admin
        if($user['role_id']=='1'){
            switch (strtolower(Request::method())) {
                case 'get':
                case 'delete':
                    if(!is_null(Request::segment(2))){
                        $userId = Request::segment(2);
                    }else{
                        $userId = $user['id'];
                    }
                    break;
                case 'post':
                    $userId = Request::input('user_id');
                    break;
                default:
                    $userId = $user['id'];
            }
            return $userId;
        }
        return $user['id'];
    }

    public function validForgotToken($data)
    {
        $user = User::where('forgot_token', $data['forgot_token'])->first();
        if ($user) {
            return true;
        }
        return false;
    }

    /**
     * @param $rigzoneId
     */
    public function getFromRigzone($rigzoneId)
    {
        $results = DB::select( DB::raw("SELECT * FROM rigzone_unique WHERE `Resume ID` = '".$rigzoneId."'") );
        return $results;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function changePassword(array $data)
    {
        unset($data['old_password']);

        $user = User::where('id', $data['user_id'])->first();
        if ($user) {
            $user->password = $data['password'];
            $user->save();

            return true;
        }
        return false;

    }

    public function countTechnical($userId){

        $results = DB::select( DB::raw("SELECT DISTINCT
                                IF(
                                    (SELECT SUM(years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id) IS NULL, 0,
                                    (SELECT SUM(years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id)
                                ) AS count_experience,
                                IF(
                                    (SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id AND users_certificates.certificate_verified=1) IS NULL, 0,
                                    (SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id AND users_certificates.certificate_verified=1)
                                )  AS count_certificates,
                                IF(
                                    (((SELECT SUM(years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id)
                                    +
                                    (SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id AND users_certificates.certificate_verified=1))
                                    /2) IS NULL, 0,
                                    (((SELECT SUM(years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id)
                                    +
                                    (SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id AND users_certificates.certificate_verified=1))
                                    /2)
                                ) AS average
                                FROM users
                                WHERE users.id='".$userId."'") );

        return $results[0];
    }

    public function  countCritical($userId){
        $results = DB::select( DB::raw("SELECT references_users_qualifications.rating FROM `references`
                                JOIN `references_users_qualifications`
                                ON `references`.id=`references_users_qualifications`.reference_id
                                WHERE `references`.user_id='".$userId."'") );
        //dd($results);
        return $results;
    }

    public function countAssessment($userId){
        $results = DB::select( DB::raw("SELECT
                                    (SUM(
                                    IF(decisive IS NULL,0,decisive)
                                    +IF(interactive IS NULL, 0, interactive)
                                    +IF(stabilizing IS NULL, 0, stabilizing)
                                    +IF(cautious IS NULL, 0, cautious))) AS count_disc,
                                    (SUM(
                                    IF(aesthetic IS NULL,0,aesthetic)
                                    +IF(economic IS NULL,0,economic)
                                    +IF(individualistic IS NULL, 0, individualistic)
                                    +IF(political IS NULL, 0, political)
                                    +IF(altruist IS NULL, 0, altruist)
                                    +IF(regulatory IS NULL, 0, regulatory)
                                    +IF(theoretical IS NULL, 0, theoretical))) AS count_values,
                                    (SUM(
                                    IF(getting_results IS NULL,0,getting_results)
                                    +IF(interpersonal_skills IS NULL, 0, interpersonal_skills)
                                    +IF(making_decisions IS NULL, 0, making_decisions)
                                    +IF(work_ethic IS NULL, 0, work_ethic))) AS count_attributes
                                    FROM `users` LEFT JOIN `users_innermetrix` on `users`.id=`users_innermetrix`.user_id
                                    WHERE users.id='".$userId."'") );
        //dd($results[0]);
        return $results[0];
    }
}
