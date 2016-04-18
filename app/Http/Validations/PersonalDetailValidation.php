<?php
namespace App\Http\Validations;

class PersonalDetailValidation extends BaseValidation
{
    public function validateCreateUpdatePersonalDetail($requestData)
    {
        $validationData = ['user_id'=>'required', 'last_name'=>'required|min:2', 'first_name'=>'required|min:2'];

        return $this->validate($validationData, $requestData);
    }

    public function validateAdditionalFieldCreateUser($requestData)
    {
        $validationData = array();
        $column = array('user_id','currently_seeking_opportunities', 'other_jobs', 'resume_link', 'jke_note', 'available_for_job', 'source', 'rating');
        $newArray = array_keys($requestData);

        if(in_array($newArray[0],$column)){
            //check if currently_seeking_opportunities is updated
            if($newArray[0]=='currently_seeking_opportunities')
            {
                $requestData[$newArray[0]]==true?$requestData[$newArray[0]]=1:$requestData[$newArray[0]]=0;
            }
            $newArray[0]=='file'?$value='mimes:pdf,doc,txt,docx':$value=array("regex:/^$|./i");
            $validationData[$newArray[0]]=$value;
        }

        if(in_array($newArray[1],$column)){
            //check if currently_seeking_opportunities is updated
            if($newArray[1]=='currently_seeking_opportunities')
            {
                $requestData[$newArray[0]]==true?$requestData[$newArray[1]]=1:$requestData[$newArray[1]]=0;
            }
            $newArray[1]=='file'?$value='mimes:pdf,doc,txt,docx':$value=array("regex:/^$|./i");
            $validationData[$newArray[1]]=$value;
        }

        return count($validationData)==2?$this->validate($validationData, $requestData):false;
    }
}