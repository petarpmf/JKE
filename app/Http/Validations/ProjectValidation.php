<?php
namespace App\Http\Validations;

class ProjectValidation extends BaseValidation
{
    public function validateCreateProject($requestData)
    {
        $validationData = ['project_name'=>'required', 'company_id'=>'required', 'start_date'=>'required', 'end_date'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateUpdateProject($requestData,$id)
    {
        $validationData = ['id'=>'required', 'project_name'=>'required', 'start_date'=>'required', 'end_date'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateUpdateAdditionalProjectFelds($requestData)
    {
        $newArray = array_keys($requestData);
        $validColumn = array('id', 'critical_skills','uniform','audit','mentor','sop_training_test','oq_required','drug_test','safety_training_test','envir_training_test','field_tablet','software_forms','how_ot_handled_admin','per_diem_admin','electronics','truck','mileage','day_rate','per_diem','sales_tax_required');

        if (in_array($newArray[0], $validColumn) AND in_array($newArray[1], $validColumn) AND count($newArray)==2) {

            return true;
        }
        return false;
    }

    public function validateCreateStaff($requestData)
    {
        $validationData = ['desired_job_id'=>'required', 'project_id'=>'required', 'quantity'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateUpdateStaff($requestData)
    {
        $validationData = ['id', 'quantity'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateCreateCandidate($requestData)
    {
        $validationData = ['user_id'=>'required', 'desired_job_project_id'=>'required'];

        return $this->validate($validationData, $requestData);
    }
}
