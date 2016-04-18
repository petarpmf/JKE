<?php
namespace App\Http\Validations;

class MediaCollectionValidation extends BaseValidation
{
    public function validateCreateMediaCollection($requestData)
    {
        $validationData = ['collection_name'=>'required|min:3|unique:media_collections,collection_name'];

        return $this->validate($validationData, $requestData);
    }

    public function validateUpdateMediaCollection($requestData,$id)
    {
        $validationData = ['collection_name'=>'required|min:3|unique:media_collections,collection_name,'.$id];

        return $this->validate($validationData, $requestData);
    }
}