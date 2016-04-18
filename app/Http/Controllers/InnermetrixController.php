<?php
namespace App\Http\Controllers;

use App\Http\Facades\Innermetrix;
use App\Http\Validations\InnermetrixValidation;
use Illuminate\Http\Request;
use App\Http\Validations\RoleValidation;
use Symfony\Component\HttpFoundation\Response;

class InnermetrixController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var InnermetrixValidation
     */
    private $validation;

    /**
     * @param Request $request
     * @param InnermetrixValidation $validation
     */
    public function __construct(Request $request,InnermetrixValidation $validation)
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
        //check if the received data is valid
        $response = $this->validation->validateCreateInnermetrix($request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newInnermetrix = Innermetrix::create($request->all());
        return $this->responseCreated($newInnermetrix);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($userId)
    {
        $storedInnermetrix = Innermetrix::getByUserId($userId);
        if ($storedInnermetrix) {
            return $this->responseOk($storedInnermetrix);
        }

        return $this->responseNotFound(['Innermetrix scores not found for user.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update($userId, Request $request)
    {
        $updatedInnermetrix = Innermetrix::updateByUserId($userId, $request->all());
        if($updatedInnermetrix){
            return $this->responseOk($updatedInnermetrix);
        }

        return $this->responseWithError(['Innermetrix scores not updated for user.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($userId)
    {
        if (Innermetrix::deleteByUserId($userId)) {
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Innermetrix scores were not deleted.']);
    }
}
