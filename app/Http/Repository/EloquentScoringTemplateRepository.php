<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\RoleInterface;
use App\Http\Interfaces\ScoringTemplateInterface;
use App\Http\Models\Role;
use App\Http\Models\ScoringTemplate;
use App\Http\Models\ScoringTemplatesProject;
use App\Http\Models\ScoringTemplateType;
use App\Http\Models\User;
use League\Fractal\Resource\Collection;

class EloquentScoringTemplateRepository implements ScoringTemplateInterface
{
    /**
     * Used for creating new template in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        return ScoringTemplate::create($data);
    }

    /**
     *  Used to split each module on different database connection
     *
     * @return \Illuminate\Database\ConnectionResolverInterface
     */
    public function getConnection()
    {
        return ScoringTemplate::getConnectionResolver();
    }

    /**
     * Used for filtering templates by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor)
    {
        $template = ScoringTemplate::where($searchFor)->first();
        return $template ? $template : null;
    }

    /**
     * Used for returning list of all templates
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return ScoringTemplate::all();
    }

    /**
     * Used for returning paginated list of all templates
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage, $withTrashed)
    {
        $template = new ScoringTemplate();
        if ($withTrashed === true) {
            $template = $template->withTrashed();
        }

        return $template->orderBy('updated_at','desc')->paginate($perPage);
    }

    /**
     * Used for returning template by ID
     *
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $template = ScoringTemplate::find($id);
        if ($template) {
            return $template;
        }

        return false;
    }

    /**
     * Used for updating template by ID
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data)
    {
        $templateForUpdate = ScoringTemplate::find($id);
        if ($templateForUpdate) {
            return $templateForUpdate->update($data)?$templateForUpdate:false;
        }

        return false;
    }

    /**
     * Used for deleting template by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $templateForDelete = ScoringTemplate::find($id);

        if ($templateForDelete) {
            return $templateForDelete->delete();
        }
        return false;
    }

    /**
     * Used for restoring template by ID
     *
     * @param $id
     * @return bool
     */
    public function restore($id)
    {
        $templateForRestore = ScoringTemplate::withTrashed()->find($id);
        if ($templateForRestore) {
            return $templateForRestore->restore();
        }
        return false;
    }

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
    public function searchTemplate($queryString, $perPage, $withTrashed, $orderByVariable, $orderDirVariable)
    {
        $template = new ScoringTemplate();

        if ($withTrashed === true) {
            $template = $template->withTrashed();
        }

        if (isset($queryString['name']) && $queryString['name'] != '') {

            //Safety Inspector template
            $template = $template::selectRaw("scoring_templates.*, desired_jobs.name")->join('desired_jobs', 'desired_jobs.id', '=', 'scoring_templates.desired_job_id');
                             //->where('template_name', 'LIKE', "%".$queryString['name']."%");

                    if($queryString['name']!='template'){

                        if(preg_match('/template$/', $queryString['name'])){
                            $queryString['name'] = preg_replace('/ (template)$/', '', $queryString['name']);
                            $template =$template ->where('desired_jobs.name', 'LIKE', "".$queryString['name']."%");
                        }else{
                            $template =$template ->where('desired_jobs.name', 'LIKE', "".$queryString['name']."%");
                        }
                    }

        }else{
            $template = $template::selectRaw("scoring_templates.*, desired_jobs.name")->join('desired_jobs', 'desired_jobs.id', '=', 'scoring_templates.desired_job_id');
        }
        if (isset($queryString['order_by']) && $queryString['order_by'] != '') {
            $orderByVariable = ($queryString['order_by'] == 'name') ? 'desired_jobs.name' : 'scoring_templates.created_at';
        }
        $template = $template->orderBy($orderByVariable, $orderDirVariable);

        return $template->paginate($perPage);
    }

    /**
     * Used for assigning template to desired job in a project
     *
     * @param $templateId
     * @param $desiredJobProjectId
     * @return static
     */
    public function assignTemplateToProjectDesiredJob($templateId, $desiredJobProjectId, $type)
    {
        if (in_array(mb_strtolower($type), ['technical', 'critical', 'assessment'])) {
            return ScoringTemplatesProject::firstOrCreate(['scoring_template_id' => $templateId, 'desired_job_project_id' => $desiredJobProjectId, 'type' => $type]);
        }

        return false;
    }

    /**
     * Used for deleting a template from desired job in a project
     *
     * @param $templateId
     * @param $desiredJobProjectId
     * @return bool
     */
    public function deleteTemplateFromProjectDesiredJob($templateId, $desiredJobProjectId, $type)
    {
        $assignedTemplateForDelete = ScoringTemplatesProject::where('scoring_template_id', '=', $templateId)
                                                            ->where('desired_job_project_id', '=', $desiredJobProjectId)
                                                            ->where('type', '=', $type)
                                                            ->first();

        if ($assignedTemplateForDelete) {
            return $assignedTemplateForDelete->delete();
        }
        return false;
    }

    public function getAllTemplatesByProjectDesiredJob($desiredJobProjectId)
    {
        return ScoringTemplatesProject::join('scoring_templates', 'scoring_template_id', '=', 'scoring_templates.id')
                                     ->where('desired_job_project_id', '=', $desiredJobProjectId)->get();
    }

    public function checkIfTemplateExists($desiredJobId)
    {
        $template = ScoringTemplate::where('desired_job_id', '=', $desiredJobId)->first();
        if ($template) {
            return true;
        }

        return false;
    }

}