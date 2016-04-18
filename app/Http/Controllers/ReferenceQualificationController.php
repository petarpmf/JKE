<?php
namespace App\Http\Controllers;

use App\Http\Facades\ReferenceQualification;
use Illuminate\Http\Request;

use App\Http\Validations\ReferenceQualificationValidation;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Response;

class ReferenceQualificationController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var QualificationValidation
     */
    private $validation;

    /**
     * @param Request $request
     * @param QualificationValidation $validation
     */
    public function __construct(Request $request,ReferenceQualificationValidation $validation)
    {
        $this->request = $request;
        $this->validation = $validation;
    }

    /**
     * Display the specified resource.
     *
     * @param $referenceId
     * @return Response
     */
    public function show($referenceId)
    {
        $isAdmin = $this->request->attributes->get('is_admin');
        $referenceEmail = Input::get('reference_email');

        $storedQualifications = ReferenceQualification::getById($referenceId, $referenceEmail, $isAdmin);
        if ($storedQualifications) {
            return $this->responseOk($storedQualifications);
        }

        return $this->responseWithError(['Reference qualifications not found.', 'Access denied for this operation or you are missing reference_email.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $isAdmin = $this->request->attributes->get('is_admin');

        //check if the received data is valid
        $response = $this->validation->validateCreateUpdateQualification($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        if ($params['rating'] !== 'N/A') {
            $newQualification = ReferenceQualification::create($params, $isAdmin);
            if ($newQualification) {
                return $this->responseCreated($newQualification);
            } else {
                return $this->responseWithError(['Access denied for this operation or you are missing reference_email.']);
            }
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param $referenceId
     * @param Request $request
     * @return Response
     */
    public function update($referenceId, Request $request)
    {
        $params = $request->all();
        $isAdmin = $this->request->attributes->get('is_admin');

        //check if the received data is valid
        $response = $this->validation->validateCreateUpdateQualification($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }
        $updatedQualification = ReferenceQualification::update($params, $isAdmin);

        if($updatedQualification){
            return $this->responseOk($updatedQualification);
        }

        return $this->responseWithError(['Record was not updated.', 'Access denied for this operation or you are missing reference_email.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param $referenceId
     * @param $qualificationId
     * @return \App\Http\Controllers\JSON
     */
    public function destroy($referenceId, $qualificationId)
    {
        $isAdmin = $this->request->attributes->get('is_admin');
        $referenceEmail = Input::get('reference_email');

        //get user_id from SecureRoute
        if (ReferenceQualification::delete($referenceId, $qualificationId, $referenceEmail, $isAdmin)) {
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Qualification was not deleted.', 'Access denied for this operation or you are missing reference_email.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function storeNote(Request $request)
    {
        $params = $request->all();
        $isAdmin = $this->request->attributes->get('is_admin');

        //check if the received data is valid
        $response = $this->validation->validateCreateUpdateNote($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newQualification = ReferenceQualification::createNote($params, $isAdmin);
        if ($newQualification) {
            return $this->responseCreated($newQualification);
        } else {
            return $this->responseWithError(['Access denied for this operation or you are missing reference_email.']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param $referenceId
     * @return Response
     */
    public function showNote($referenceId)
    {
        $isAdmin = $this->request->attributes->get('is_admin');
        $referenceEmail = Input::get('reference_email');

        $storedNote = ReferenceQualification::getNote($referenceId, $referenceEmail, $isAdmin);
        if ($storedNote) {
            return $this->responseOk($storedNote);
        }

        return $this->responseWithError(['Note not found.', 'Access denied for this operation or you are missing reference_email.']);
    }

    public function sendAuditForm($referenceId)
    {
        $isAdmin = $this->request->attributes->get('is_admin');

        if ($isAdmin) {
            ReferenceQualification::sendReferenceQualificationUrl($referenceId);
            return $this->responseOk([]);
        }

        return $this->responseUnauthorized();

    }
}
