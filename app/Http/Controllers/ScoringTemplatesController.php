<?php
namespace App\Http\Controllers;

use App\Http\Facades\ScoringTemplate;
use App\Http\Validations\ScoringTemplateValidation;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScoringTemplatesController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ScoringTemplateValidation
     */
    private $validation;

    private $trashed;

    /**
     * @param Request $request
     * @param ScoringTemplateValidation $validation
     */
    public function __construct(Request $request,ScoringTemplateValidation $validation)
    {
        $this->request = $request;
        $this->validation = $validation;
        $this->trashed = $this->request->input('trashed','false');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if($this->request->all()){
            return ScoringTemplate::search($this->request->all(),$this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')), $this->trashed);
        }
        $templates =  ScoringTemplate::getList($this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')), $this->trashed);
        return $templates;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //check if the received data is valid
        $response = $this->validation->validateCreateScoringTemplate($request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newTemplate = ScoringTemplate::create($request->all());
        return $this->responseCreated($newTemplate);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $storedTemplate = ScoringTemplate::getById($id);
        if ($storedTemplate) {
            return $this->responseOk($storedTemplate);
        }

        return $this->responseNotFound(['Template not found.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        //check if the received data is valid
        $response = $this->validation->validateUpdateScoringTemplate($request->all(), $id);
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $updatedTemplate = ScoringTemplate::update($id, $request->all());
        if($updatedTemplate){
            return $this->responseOk($updatedTemplate);
        }

        return $this->responseWithError(['Template was not updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if (ScoringTemplate::delete($id)) {
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Template was not deleted.']);
    }

    /**
     * Restore the specified resource from storage
     *
     * @param $id
     * @return Response
     */
    public function restore($id)
    {
        if (ScoringTemplate::restore($id)) {
            return $this->responseOk([]);
        }

        return $this->responseWithError(['Record was not restored.']);
    }

    /**
     * Assign template by ID to desired job in project
     *
     * @param $templateId
     * @param $desiredJobProjectId
     * @param $type
     * @return Response
     */
    public function assignTemplateToProjectDesiredJob($templateId, $desiredJobProjectId, $type)
    {
        if (ScoringTemplate::assignTemplateToProjectDesiredJob($templateId, $desiredJobProjectId, $type)) {
            return $this->responseOk([]);
        }

        return $this->responseWithError(['Template was not assigned to desired job in project.']);
    }

    /**
     * Revoke assigned template by ID to desired job in project
     *
     * @param $templateId
     * @param $desiredJobProjectId
     * @param $type
     * @return Response
     */
    public function deleteTemplateFromProjectDesiredJob($templateId, $desiredJobProjectId, $type)
    {
        if (ScoringTemplate::deleteTemplateFromProjectDesiredJob($templateId, $desiredJobProjectId, $type)) {
            return $this->responseOk([]);
        }

        return $this->responseWithError(['Assigned template was not removed to desired job in project.']);
    }

    public function getAllTemplatesByProjectDesiredJob($desiredJobProjectId)
    {
        $storedTemplates = ScoringTemplate::getAllTemplatesByProjectDesiredJob($desiredJobProjectId);
        if ($storedTemplates) {
            return $this->responseOk($storedTemplates);
        }

        return [];
    }

}
