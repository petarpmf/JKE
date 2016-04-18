<?php
namespace App\Http\Controllers;

use App\Http\Facades\Media;
use App\Http\Validations\MediaValidation;
use Illuminate\Http\Request;
use App\Http\Facades\Company;
use App\Http\Validations\CompanyValidation;
use App\Http\Facades\Token;
use App\Http\Facades\User;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Facades\FileManipulation;
use App\Http\Services\FileManipulationService;

class CompaniesController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var CompanyValidation
     */
    private $validation;
    /**
     * @var MediaValidation
     */
    private $mediaValidation;
    /**
     * @param Request $request
     * @param CompanyValidation $validation
     */
    public function __construct(Request $request, CompanyValidation $validation, MediaValidation $mediaValidation)
    {
        $this->request = $request;
        $this->validation = $validation;
        $this->mediaValidation = $mediaValidation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if(($this->request->input('show')=='all')){
             $companies =  Company::getAll();
            return $this->responseOk($companies);
         }
        return Company::search($this->request->all(), $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')));
        //return Company::getList($this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')));
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
        $response = $this->validation->validateCreateCompany($request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newCompany = Company::create($request->only(['company_name', 'company_email', 'phone_number', 'street_address', 'city', 'zip', 'state', 'country', 'web_site', 'notes']));
        return $this->responseCreated($newCompany);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $storedCompany = Company::getById($id);
        if ($storedCompany) {
            return $this->responseOk($storedCompany);
        }

        return $this->responseNotFound(['Company not found.']);
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
        $response = $this->validation->validateUpdateCompany($request->all(), $id);
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $updatedCompany = Company::update($id, $request->only(['company_name', 'company_email', 'phone_number', 'street_address', 'city', 'zip', 'state', 'country', 'web_site', 'notes']));
        if($updatedCompany){
            return $this->responseOk($updatedCompany);
        }

        return $this->responseWithError(['Company was not updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if (Company::delete($id)) {
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Company was not deleted.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @internal param Request $request
     */
    public function upload()
    {
        $response = $this->mediaValidation->validateCreateMedia($this->request->all());

        $token = $this->request->header('Authorization');
        $userId = Token::getUserId($token);
        $user = User::getById($userId);

        //check if user is admin
        if($user['data']['role']['role_id']!='1'){
            return $this->responseUnauthorized([]);
        }

        $companyId = $this->request->input('company');

        //check if token exit
        if(empty($userId)) {
            return $this->responseWithError([]);
        }

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $width = $this->request->input('width');
        $height = $this->request->input('height');
        $collection = $this->request->input('collection',false);

        $result = Media::create($collection, $width, $height);

        switch($result){
            case FileManipulationService::BAD_REQUEST:
                return $this->responseWithError([]);
                break;
            case FileManipulationService::CHUNK_NOT_FINAL:
                return $this->responseOk(['message' => 'This is not a final chunk, continue to upload.']);
                break;
        }
        //update appropriate field image_id or file_id
        $uploadField['image_id'] = $result['data']['image_id'];
        Company::update($companyId, $uploadField);
        return $this->responseOk($result);
    }

    public function check()
    {
        $result = Media::check();
        if ($result) {
            return $this->responseOk([]);
        }
        return $this->responseDeleted([]);
    }

    public function deleteLogo($id)
    {
        $storedCompany = Company::getById($id);
        $imageId = $storedCompany['data']['image_id'];

        if($imageId){
            Company::update($id, ['image_id'=>' ']);
            if (Media::delete($imageId)) {
                return $this->responseDeleted();
            }

            return $this->responseWithError(['Company logo was not deleted.']);
        }
        return $this->responseWithError(['Company logo was not found.']);
    }

}
