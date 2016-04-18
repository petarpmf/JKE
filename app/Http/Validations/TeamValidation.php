<?php
namespace App\Http\Validations;

class TeamValidation extends BaseValidation
{
    public function validateCreateEditTeam($requestData)
    {
        $validationData = ['name'=>'required', 'project_id'=>'required'];
        return $this->validate($validationData, $requestData);
    }

    public function validateAssignUserToTeam($requestData)
    {
        $validationData = ['user'=>'required', 'team_id'=>'required', 'status'=>'required'];
        return $this->validate($validationData, $requestData);
    }

    public function validateRevokeUserToTeam($requestData)
    {
        $validationData = ['user'=>'required', 'team_id'=>'required'];
        return $this->validate($validationData, $requestData);
    }
}