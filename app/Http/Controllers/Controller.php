<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{

    /**
     * @param $message
     * @param int $code
     * @param array $headers
     * @return JSON
     */
    public function responseUnauthorized($message = "", $code = Response::HTTP_FORBIDDEN, $headers = [])
    {
        return $this->response(
            ['error' => $message ? $message : 'Unauthorized'],
            $code,
            $headers
        );

    }

    /**
     * @param array $data
     * @return JSON
     */
    public function responseCreated(array $data)
    {
        return $this->response($data, Response::HTTP_CREATED);
    }

    /**
     * @param array $data To be returned
     * @param int $code HTTP status code
     * @param array $headers HTTP Headers if any
     * @return JSON Response
     */
    private function response(array $data, $code = Response::HTTP_OK, $headers = [])
    {

        return response()->json(
            array_merge($data, ['code' => $code]),
            $code,
            $headers
        );
    }

    /**
     * @return JSON
     */
    public function responseDeleted(){
       return $this->response([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $errors
     * @param int $code
     * @param array $headers
     * @return JSON
     */
    public function responseWithError($errors, $code = Response::HTTP_BAD_REQUEST, $headers = [])
    {
        return $this->response(['errors' => $errors], $code, $headers);
    }

    /**
     * @param $errors
     * @param array $headers
     * @return JSON
     */
    public function responseNotFound($errors, $headers = [])
    {
        return $this->response(['errors' => $errors], Response::HTTP_NOT_FOUND, $headers);
    }

    /**
     * @param array $data
     * @param int $code
     * @param array $headers
     * @return JSON
     */
    protected function responseOk(array $data,$code = Response::HTTP_OK,$headers = [])
    {
        return $this->response($data,$code,$headers);
    }
}
