<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\CompanyInterface;
use App\Http\Models\Company;

use App\Http\Models\User;
use League\Fractal\Resource\Collection;

class EloquentCompanyRepository implements CompanyInterface
{
    /**
     * Used for creating new company in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        return Company::create($data);
    }

    /**
     * Used for returning list of all companies
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Company::all();
    }

    /**
     * Used for returning paginated list of all companies
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage)
    {
        $company = new Company();
        return $company->orderBy('updated_at','desc')->paginate($perPage);
    }

    /**
     * Used for returning company by ID
     *
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $company = Company::find($id);
        if ($company) {
            return $company;
        }

        return false;
    }

    /**
     * Used for updating company by ID
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data)
    {
        $companyForUpdate = Company::find($id);
        if ($companyForUpdate) {
            return $companyForUpdate->update($data)?$companyForUpdate:false;
        }

        return false;
    }

    /**
     * Used for deleting company by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $companyForDelete = Company::find($id);

        if ($companyForDelete) {

            //Update availability status
            $userIds = Company::selectRaw("users_teams.user_id")
                ->join('projects', 'companies.id', '=', 'projects.company_id')
                ->join('desired_jobs_projects', 'projects.id', '=', 'desired_jobs_projects.project_id')
                ->join('users_desired_jobs_projects', 'desired_jobs_projects.id', '=', 'users_desired_jobs_projects.desired_job_project_id')
                ->join('users_teams', 'desired_jobs_projects.id', '=', 'users_teams.desired_job_project_id')
                ->where('companies.id', '=', $id)
                ->where('users_teams.status', '=', 'Hired')
                ->groupBy('users_teams.user_id')
                ->get()
                ->toArray();

            if(count($userIds)>0){
                foreach($userIds as $userId){
                    $user = User::find($userId['user_id']);
                    $user->profile()->update(['currently_seeking_opportunities'=>1]);
                }
            }

            //End update availability status
            return $companyForDelete->delete();
        }
        return false;
    }

    /**
     * @param array $queryString
     * @param $perPage
     * @return mixed
     */
    public function searchCompanies(array $queryString, $perPage)
    {
        $articles = Company::filter($queryString)->paginate($perPage);
        return $articles;
    }
}