<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Facades\Role;
use App\Http\Validations\RoleValidation;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var RoleValidation
     */
    private $validation;

    private $trashed;

    /**
     * @param Request $request
     * @param RoleValidation $validation
     */
    public function __construct(Request $request,RoleValidation $validation)
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
        $roles =  Role::getList($this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')), $this->trashed);
        return $roles;
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
        $response = $this->validation->validateCreateRole($request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newRole = Role::create($request->only(['name']));
        return $this->responseCreated($newRole);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $storedRole = Role::getById($id);
        if ($storedRole) {
            return $this->responseOk($storedRole);
        }

        return $this->responseNotFound(['Role not found.']);
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
        $response = $this->validation->validateUpdateRole($request->all(), $id);
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $updatedRole = Role::update($id, $request->only(['name']));
        if($updatedRole){
            return $this->responseOk($updatedRole);
        }

        return $this->responseWithError(['Role was not updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if (!Role::checkDelete($id)) {
            return $this->responseWithError(['Role can not be deleted because it is used for a user.']);
        }

        if (Role::delete($id)) {
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Role was not deleted.']);
    }

    /**
     * Restore the specified resource from storage
     *
     * @param $id
     * @return Response
     */
    public function restore($id)
    {
        if (Role::restore($id)) {
            return $this->responseOk([]);
        }

        return $this->responseWithError(['Record was not restored.']);
    }

}
