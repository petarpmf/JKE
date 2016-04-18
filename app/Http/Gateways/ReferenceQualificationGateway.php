<?php
namespace App\Http\Gateways;

use App\Http\Interfaces\ReferenceQualificationInterface;
use App\Http\Services\Notify;
use App\Http\Transformers\GuestUserTransformer;
use App\Http\Transformers\ReferenceNoteTransformer;
use App\Http\Transformers\ReferenceQualificationFullTransformer;
use App\Http\Transformers\ReferenceQualificationTransformer;
use App\Http\Transformers\TransformersManager;
use Illuminate\Support\Facades\Request;
use Jke\Jobs\Interfaces\ReferenceInterface;

class ReferenceQualificationGateway
{
    /**
     * @var ReferenceQualificationInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;

    /**
     * @var GuestUserTransformer
     */
    private $guestUserTransformer;
    /**
     * @var ReferenceQualificationFullTransformer
     */
    private $referenceQualificationFullTransformer;
    /**
     * @var ReferenceQualificationFullTransformer
     */
    private $qualificationFullTransformer;
    /**
     * @var ReferenceInterface
     */
    private $referenceRepo;
    /**
     * @var ReferenceNoteTransformer
     */
    private $noteTransformer;
    /**
     * @var Notify
     */
    private $notify;

    /**
     * @param ReferenceQualificationInterface $repo
     * @param TransformersManager $transformersManager
     * @param ReferenceQualificationFullTransformer $qualificationFullTransformer
     * @param ReferenceQualificationTransformer $qualificationTransformer
     * @param ReferenceInterface $referenceRepo
     */
    public function __construct(ReferenceQualificationInterface $repo, TransformersManager $transformersManager,
                                ReferenceQualificationFullTransformer $qualificationFullTransformer, Notify $notify,
                                ReferenceQualificationTransformer $qualificationTransformer, ReferenceNoteTransformer $noteTransformer, ReferenceInterface $referenceRepo)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->qualificationTransformer = $qualificationTransformer;
        $this->qualificationFullTransformer = $qualificationFullTransformer;
        $this->referenceRepo = $referenceRepo;
        $this->noteTransformer = $noteTransformer;
        $this->notify = $notify;
    }

    /**
     * Used for creating reference using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data, $isAdmin)
    {
        if ((isset($data['reference_email']) && $this->referenceRepo->checkReferenceByIdAndEmail($data['reference_id'], $data['reference_email'])) || ($isAdmin)) {
            $reference = $this->repo->create($data);
            return ($reference)?$this->transformersManager->transformItem($reference, $this->qualificationTransformer):$reference;
        } else {
            return false;
        }
    }

    /**
     * Get all qualifications for specific guest-user relation ID
     *
     * @param $referenceId
     * @return array
     */
    public function getById($referenceId, $referenceEmail, $isAdmin)
    {
        if ((isset($referenceEmail) && $this->referenceRepo->checkReferenceByIdAndEmail($referenceId, $referenceEmail)) || ($isAdmin)) {
            $referenceQualifications = $this->repo->getById($referenceId);
            return ($referenceQualifications)?$this->transformersManager->transformCollectionWithoutPaginate($referenceQualifications, $this->qualificationFullTransformer):$referenceQualifications;
        } else {
            return false;
        }
    }

    /**
     * Used for updating references by user_id and the provided data
     *
     * @param $data
     * @return array
     */
    public function update($data, $isAdmin)
    {
        if ((isset($data['reference_email']) && $this->referenceRepo->checkReferenceByIdAndEmail($data['reference_id'], $data['reference_email'])) || ($isAdmin)) {
            $reference = $this->repo->update($data);
            return ($reference)?$this->transformersManager->transformCollectionWithoutPaginate($reference, $this->qualificationTransformer):$reference;
        } else {
            return false;
        }
    }

    /**
     * Used to delete references by $userId and $referenceId
     *
     * @param $referenceId
     * @param $qualificationId
     * @return mixed
     */
    public function delete($referenceId, $qualificationId, $referenceEmail, $isAdmin)
    {
        if ((isset($referenceEmail) && $this->referenceRepo->checkReferenceByIdAndEmail($referenceId, $referenceEmail)) || ($isAdmin)) {
            return $this->repo->delete($referenceId, $qualificationId);
        } else {
            return false;
        }
    }

    /**
     * Used for creating rnote for a reference
     *
     * @param $data
     * @param $isAdmin
     * @return array
     */
    public function createNote($data, $isAdmin)
    {
        if ((isset($data['reference_email']) && $this->referenceRepo->checkReferenceByIdAndEmail($data['reference_id'], $data['reference_email'])) || ($isAdmin)) {
            $note = $this->repo->createNote($data);
            return ($note)?$this->transformersManager->transformItem($note, $this->noteTransformer):$note;
        } else {
            return false;
        }
    }

    /**
     * Used for getting note for a reference
     *
     * @param $referenceId
     * @param $referenceEmail
     * @param $isAdmin
     * @return array
     */
    public function getNote($referenceId, $referenceEmail, $isAdmin)
    {
        if ((isset($referenceEmail) && $this->referenceRepo->checkReferenceByIdAndEmail($referenceId, $referenceEmail)) || ($isAdmin)) {
            $note = $this->repo->getNoteByReferenceId($referenceId);
            return ($note)?$this->transformersManager->transformItem($note, $this->noteTransformer):$note;
        } else {
            return false;
        }
    }

    /**
     * Used for sending message to user with link to rate an inspector as his reference
     *
     * @param $data
     * @return bool
     */
    public function sendReferenceQualificationUrl($referenceId)
    {
        $reference = $this->referenceRepo->getReferenceByReferenceId($referenceId);
        if ($reference) {
            //mark email as sent
            $this->referenceRepo->markReferenceAsSent($referenceId);

            $subject = 'Joe Knows Energy Audit form';
            $view = 'notify.auditForm';
            $viewData['referenceId'] = $referenceId;
            $viewData['referenceEmail'] = $reference->reference_email;
            $viewData['referenceName'] = $reference->reference_name;
            $viewData['inspectorFirstName'] = $reference->first_name;
            $viewData['inspectorLastName'] = $reference->last_name;
            return ($this->notify->from()
                ->to(['ljubica.stoilkova@it-labs.com']) //switch to $reference->reference_email before going live
                ->subject($subject)
                ->send($view, $viewData));
        }
        return false;
    }

}