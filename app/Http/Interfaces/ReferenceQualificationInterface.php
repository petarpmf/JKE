<?php
/**
 * Created by PhpStorm.
 * User: blagojce.jankulovski
 * Date: 12/16/2015
 * Time: 1:50 PM
 */
namespace App\Http\Interfaces;

interface ReferenceQualificationInterface
{
    /**
     * Used for creating new Reference qualification in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data);

    /** Display all Reference qualifications for user by $userId
     * @param $referencesId
     * @return bool
     */
    public function getById($referencesId);

    /**
     * Used for updating guests_users_qualifications table by $guestId, $userId and $qualificationId
     * @param $data
     * @return bool
     */
    public function update($data);

    /**
     * Used for deleting users_qualifications table by $userId and $qualificationId
     * @param $referencesId
     * @param $qualificationId
     * @return bool
     */
    public function delete($referencesId, $qualificationId);

    /**
     * Used for creating note for reference
     *
     * @param array $data
     * @return static
     */
    public function createNote(array $data);

    /**
     * Used for returning note for reference id
     *
     * @param $referencesId
     * @return static
     */
    public function getNoteByReferenceId($referencesId);
}