<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Facades\PersonalDetail;
use App\Http\Facades\User;
use App\Http\Validations\PersonalDetailValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PersonalDetailsController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var PersonalDetailValidation
     */
    private $validation;

    public function __construct(Request $request,PersonalDetailValidation $validation)
    {
        $this->request = $request;
        $this->validation = $validation;
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
        $response = $this->validation->validateCreateUpdatePersonalDetail($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $storedUser = User::getById($params['user_id']);

        $user['data']['email'] = $storedUser['data']['email'];
        $user['data']['first_name'] = $params['first_name'];
        $user['data']['last_name'] = $params['last_name'];


        User::updateMailChimp($storedUser['data']['email'], $user);

        $newPersonalDetail = PersonalDetail::create($params);
        return $this->responseCreated($newPersonalDetail);
    }

    /**
     * Show personal details for user by user id.
     * @param $userId
     * @return JSON
     */
    public function show($userId)
    {
        $storedPersonalDetail = PersonalDetail::getById($userId);
        if ($storedPersonalDetail) {
            return $this->responseOk($storedPersonalDetail);
        }

        return $this->responseWithError(['Personal details not found.']);
    }

    /**
     * Store additional fields from Employment details page to to appropriate profile table (profile_admins,
     * profile_clients, profile_inspectors).
     * @return JSON
     */
    public function storeAdditional()
    {
        $params = $this->request->all();
        //get user_id from SecureRoute
        $params['user_id'] = $this->request->attributes->get('user_id');

        //check if the received data is valid
        $response = $this->validation->validateAdditionalFieldCreateUser($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newJob = PersonalDetail::createAdditional($params);
        return $this->responseCreated($newJob);
    }
}
