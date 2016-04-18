<?php
namespace Jke\Jobs\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jke\Jobs\Facades\Certificate;
use Jke\Jobs\Validations\CertificateValidation;
use Symfony\Component\HttpFoundation\Response;

class CertificatesController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var CertificateValidation
     */
    private $validation;

    /**
     * @param Request $request
     * @param CertificateValidation $validation
     */
    public function __construct(Request $request,CertificateValidation $validation)
    {
        $this->request = $request;
        $this->validation = $validation;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Certificate::getList();
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
        //get user_id from SecureRoute
        $params['user_id'] = $this->request->attributes->get('user_id');

        //check if the received data is valid
        $response = $this->validation->validateCreateCertificate($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newCertificate = Certificate::create($params);
        return $this->responseCreated($newCertificate);
    }

    /**
     * Display the specified resource.
     *
     * @param $userId
     * @return Response
     */
    public function show($userId)
    {
        //get user_id from SecureRoute
        //$userId = $this->request->attributes->get('user_id');
        $storedCertificates = Certificate::getById($userId);
        if ($storedCertificates) {
            return $this->responseOk($storedCertificates);
        }

        return $this->responseWithError(['Certificate not found.']);
    }

    /**
     * Display specified certificate for user by userId and certificateId.
     * @param $userId
     * @param $certificateId
     * @return \App\Http\Controllers\JSON
     */
    public function showCertificate($userId, $certificateId)
    {
        //get user_id from SecureRoute
        $userId = $this->request->attributes->get('user_id');
        $storedCertificate = Certificate::getCertificateById($userId, $certificateId);

        if ($storedCertificate) {
            return $this->responseOk($storedCertificate);
        }

        return $this->responseWithError(['Certificate not found.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $userId
     * @param Request $request
     * @return Response
     */
    public function update($userId, Request $request)
    {
        $params = $request->all();

        //get user_id from SecureRoute
        $params['user_id'] = $this->request->attributes->get('user_id');

        //check if the received data is valid
        $response = $this->validation->validateUpdateCertificate($params, $params['user_id']);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $updatedCertificate = Certificate::update($params);

        if($updatedCertificate){
            return $this->responseOk($updatedCertificate);
        }

        return $this->responseWithError(['Record was not updated.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param $userId
     * @param $certificateId
     * @return \App\Http\Controllers\JSON
     */
    public function destroy($userId, $certificateId)
    {
        //get user_id from SecureRoute
        $userId = $this->request->attributes->get('user_id');
        if(Certificate::delete($userId, $certificateId)){
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Certificate was not deleted.']);
    }
}