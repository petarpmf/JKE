<?php
namespace App\Http\Gateways;

use App\Http\Models\ScoringTemplate;
use App\Http\Models\User;
use App\Http\Paginate\CustomArrayPagination;
use App\Http\Services\Notify;
use App\Http\Transformers\TransformersManager;
use App\Http\Facades\Token;
use App\Http\Interfaces\UserInterface;
use App\Http\Security\Security;
use App\Http\Transformers\UserTransformer;
use \DrewM\MailChimp\MailChimp;

class UserGateway
{
    /**
     * @var UserInterface
     */
    private $repo;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var UserTransformer
     */
    private $transformer;
    private $mailChimp;

    /**
     * @param UserInterface $repo
     */
    public function __construct(UserInterface $repo, Security $security, TransformersManager $transformersManager, UserTransformer $transformer, Notify $notify)
    {
        $this->repo = $repo;
        $this->security = $security;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
        $this->notify = $notify;
        $this->mailChimp = new MailChimp(env('MAILCHIMP_API_KEY'));
    }

    /**
     * Used for logging in specific user by username and password
     *
     * @param $username
     * @param $password
     * @return array|bool| \App\Http\Facades\User
     */
    public function login($username, $password)
    {
        $user = $this->security->attempt($username, $password);

        if ($user) {
            $token = Token::generate();
            Token::save($token, $user);

            $data = ($user)?$this->transformersManager->transformItem($user, $this->transformer):$user;
            $data['data']['token'] = $token;
            return $data;
        }

        return false;
    }

    /**
     * Used for resetting a password
     *
     * @param $data
     * @return mixed
     */
    public function resetPassword($data)
    {
        return $this->repo->resetPassword($data);
    }

    /**
     * Used for returning paginated results based on the supplied value for items per page
     *
     * @param $perPage
     * @param $withTrashed
     * @return mixed
     */
    public function getList($perPage,$withTrashed)
    {
        $withTrashed = $withTrashed === 'true'? true: false;
        $results = $this->repo->paginate($perPage,$withTrashed);
        return ($results)?$this->transformersManager->transformCollection($results, $this->transformer):$results;
    }

    /**
     * Used for creating user using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $user = $this->repo->create($data);

        $data = ($user)?$this->transformersManager->transformItem($user, $this->transformer):$user;
        //$mail = $this->notifyRegister($user['email']);
        //if ($mail) {
            //return $this->responseOk([]);
        //}
        return $data;
    }

    /**
     * Used for getting user by ID
     *
     * @param $id
     * @return array
     */
    public function getById($id)
    {
        $user = $this->repo->getById($id);
        return ($user)?$this->transformersManager->transformItem($user, $this->transformer):$user;
    }

    /**
     * Used for updating user by ID and the provided data
     *
     * @param $id
     * @param $data
     * @return array
     */
    public function update($id, $data)
    {
        $user = $this->repo->update($id, $data);
        return ($user)?$this->transformersManager->transformItem($user, $this->transformer):$user;
    }

    /**
     * Used to delete user by ID
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /**
     * Used to restore deleted user by ID
     *
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        return $this->repo->restore($id);
    }

    /**
     * Used for filtering Users by some criteria
     *
     * @param $data
     * @return mixed
     */
    public function where($data)
    {
        return $this->repo->where($data);
    }

    /**
     * Used for sending message to user with link to reset his password
     *
     * @param $data
     * @return bool
     */
    public function sendChangePasswordUrl($data)
    {
        $user = $this->repo->generateForgotTokenForUser($data['email']);
        if ($user) {
            $subject = 'Forgot Password';
            $view = 'notify.forgotPassword';
            $viewData['forgotToken'] = $user->forgot_token;
            $viewData['firstName'] = $user->first_name;
            $viewData['lastName'] = $user->last_name;
            return ($this->notify->from()
                ->to([$data['email']])
                ->subject($subject)
                ->send($view, $viewData));
        }
        return false;
    }
    
    public function notifyRegister($data)
    {   $email = $data['email'];
        $user = $this->repo->generateForgotTokenForUser($email);
        if ($user) {
            $subject = 'Build your Profile with Joe Knows Energy';
            $view = 'notify.register';
            $viewData['forgotToken'] = $user->forgot_token;
            $viewData['firstName'] = $user->first_name;
            $viewData['lastName'] = $user->last_name;
            $viewData['email'] = $email;
            return ($this->notify->from()
                ->to([$email])
                ->subject($subject)
                //->attach('attachments/testimage.jpg')
                ->send($view, $viewData));
        }
        return false;
    }

    public function notifyRegisterAnonymous($data)
    {
        $email = $data['email'];
        $user = User::where('email','=',$email)->first();
        if($user){
            $subject = 'Build your Profile with Joe Knows Energy';
            $view = 'notify.registerAnonymous';
            $viewData['firstName'] = $user->first_name;
            $viewData['lastName'] = $user->last_name;

            return ($this->notify->from()
                ->to([$email])
                ->subject($subject)
                //->attach('attachments/testimage.jpg')
                ->send($view, $viewData));
        }
        return false;
    }

    public function notifyContactUs($data)
    {
        $subject = $data['title'];
        $view = 'notify.contactUs';
        $viewData['title'] =$data['title'];
        $viewData['body'] =$data['body'];
        $viewData['firstName'] = $data['user']['data']['first_name'];
        $viewData['lastName'] = $data['user']['data']['last_name'];
        $viewData['email'] = $data['user']['data']['email'];

        $viewData['mobilePhone'] = $data['user']['data']['mobile_phone'];
        $viewData['haveMobilePhone'] = false;
        if($viewData['mobilePhone']){
            $viewData['haveMobilePhone'] = true;
        }
        return ($this->notify->from()
            ->to([env('SEND_TO_EMAIL')])
            ->subject($subject)
            //->attach('attachments/testimage.jpg')
            ->send($view, $viewData));
    }

    public function search($queryString, $perPage)
    {
        $users = $this->repo->searchUser($queryString, $perPage);

        if (isset($queryString['multi']) && $queryString['multi'] == 'true') {

            if(isset($queryString['desired_job'])) {

            $scoringTemplate = ScoringTemplate::where('desired_job_id', '=', $queryString['desired_job'])->first();

            if(count($scoringTemplate)>0){
                $levelMatches = array();
                $users = $users->toArray();
                foreach ($users as $key => &$user) {

                    $user = User::find($user['id']);
                    $profiles = $user->profile()->first();

                    $imageUrl = null;
                    if(!empty($profiles->image_id)){
                        $imageUrl= url('media/display/'. $profiles->image_id);
                    }
                    $user['image_url'] = $imageUrl;
                    //--- TECHNICAL SKILLS ---
                    $technical = $this->repo->countTechnical($user['id']);
                    //dd($technical);
                    $user['count_experience'] = $technical->count_experience;
                    $user['count_certificates'] = $technical->count_certificates;
                    $user['average_technical'] = $technical->average;
                    $levelMatches['experience'] = 0;
                    for ($i = 1; $i <= 5; $i++) {
                        $scoringValues = $this->_getScoringValues($scoringTemplate['work_experience_criteria_level' . $i]);

                        if ($this->_belongsToLevel($scoringValues, $technical->count_experience)) {
                            $levelMatches['experience'] = $i;
                            break;
                        }
                    }
                    $levelMatches['certification'] = 0;
                    for ($i = 1; $i <= 5; $i++) {
                        $scoringValues = $this->_getScoringValues($scoringTemplate['certificates_criteria_level' . $i]);
                        if ($this->_belongsToLevel($scoringValues, $technical->count_certificates)) {
                            $levelMatches['certification'] = $i;
                            break;
                        }
                    }

                    //--- CRITICAL SKILLS ---
                    $ratings = array('N/A' => 0, 'Never' => 1, 'Seldom' => 2, 'Often' => 3, 'Mostly' => 4, 'Always' => 5);

                    $critical = $this->repo->countCritical($user['id']);
                    $ratingSum = 0;
                    foreach ($critical as $value) {
                        $ratingSum = $ratingSum + $ratings[$value->rating];
                    }

                    $user['count_references'] = $ratingSum;
                    $averageCritical = count($critical) > 0 ? round($ratingSum / count($critical), 2) : 0;
                    $user['average_critical'] = $averageCritical;
                    $levelMatches['reference'] = 0;
                    for ($i = 1; $i <= 5; $i++) {
                        if ($averageCritical <= $i && $averageCritical >= $i - 1) {
                            $levelMatches['reference'] = $i;
                            break;
                        }
                    }

                    //--- ASSESSMENT ---
                    $assessments = $this->repo->countAssessment($user['id']);
                    $levelMatches['disc'] = 0;
                    for ($i = 1; $i <= 5; $i++) {
                        $scoringValues = $this->_getScoringValues($scoringTemplate['disc_criteria_level' . $i]);

                        if ($this->_belongsToLevel($scoringValues, $assessments->count_disc)) {
                            $levelMatches['disc'] = $i;
                            break;
                        }
                    }
                    $levelMatches['values'] = 0;
                    for ($i = 1; $i <= 5; $i++) {
                        $scoringValues = $this->_getScoringValues($scoringTemplate['values_criteria_level' . $i]);

                        if ($this->_belongsToLevel($scoringValues, $assessments->count_values)) {
                            $levelMatches['values'] = $i;
                            break;
                        }
                    }
                    $levelMatches['attributes'] = 0;
                    for ($i = 1; $i <= 5; $i++) {
                        $scoringValues = $this->_getScoringValues($scoringTemplate['attributes_criteria_level' . $i]);

                        if ($this->_belongsToLevel($scoringValues, $assessments->count_attributes)) {
                            $levelMatches['attributes'] = $i;
                            break;
                        }
                    }

                    $weightSum = 0;
                    foreach ($levelMatches as $keyLevel => $value) {
                        if ($keyLevel == 'experience') {
                            $experienceWeightSum = round((floatval($scoringTemplate->work_experience_weight) / 5) * floatval($value), 2);
                            $weightSum += $experienceWeightSum;
                            $user['experience_weight_sum'] = $experienceWeightSum;

                        }
                        if ($keyLevel == 'certification') {
                            $certificationWeightSum = round((floatval($scoringTemplate->certificates_weight) / 5) * floatval($value), 2);
                            $weightSum += $certificationWeightSum;
                            $user['certification_weight_sum'] = $certificationWeightSum;
                        }
                        if ($keyLevel == 'reference') {
                            $referenceWeightSum = round((floatval($scoringTemplate->auditor_weight) / 5) * floatval($value), 2);
                            $weightSum += $referenceWeightSum;
                            $user['reference_weight_sum'] = $referenceWeightSum;
                        }
                        if ($keyLevel == 'disc') {
                            $discWeightSum = round((floatval($scoringTemplate->disc_weight) / 5) * floatval($value), 2);
                            $weightSum += $discWeightSum;
                            $user['disc_weight_sum'] = $discWeightSum;
                        }
                        if ($keyLevel == 'values') {
                            $valuesWeightSum = round((floatval($scoringTemplate->values_weight) / 5) * floatval($value), 2);
                            $weightSum += $valuesWeightSum;
                            $user['values_weight_sum'] = $valuesWeightSum;
                        }
                        if ($keyLevel == 'attributes') {
                            $attributesWeightSum = round((floatval($scoringTemplate->attributes_weight) / 5) * floatval($value), 2);
                            $weightSum += $attributesWeightSum;
                            $user['attributes_weight_sum'] = $attributesWeightSum;
                        }
                    }

                    if (isset($levelMatches['experience']) && isset($levelMatches['certification'])) {
                        $user['technical_visual_representation'] = round((floatval(50) / 5) * floatval($levelMatches['experience']), 2) + round((floatval(50) / 5) * floatval($levelMatches['certification']), 2);
                    }
                    if (isset($levelMatches['reference'])) {
                        $user['critical_visual_representation'] = round((floatval(100) / 5) * floatval($levelMatches['reference']), 2);
                    }

                    if (isset($levelMatches['disc']) && isset($levelMatches['values']) && isset($levelMatches['attributes'])) {
                        $user['assessment_visual_representation'] = round((floatval(33.33) / 5) * floatval($levelMatches['disc']), 2)
                            + round((floatval(33.33) / 5) * floatval($levelMatches['values']), 2)
                            + round((floatval(33.33) / 5) * floatval($levelMatches['attributes']), 2);
                    }
                    $user['weight_sum'] = $weightSum;

                    if (isset($queryString['technical_knowledge'])) {
                        if (isset($levelMatches['experience']) && isset($levelMatches['certification'])) {
                            $levelAverage = round((floatval($levelMatches['experience']) + floatval($levelMatches['certification'])) / 2, 2);
                            if (floatval($levelAverage) > floatval($queryString['technical_knowledge'])) {
                                unset($users[$key]);
                            }
                        }
                    }
                    if (isset($queryString['critical_skills'])) {
                        if (isset($levelMatches['reference'])) {
                            $levelAverage = floatval($levelMatches['reference']);
                            if (floatval($levelAverage) > floatval($queryString['critical_skills'])) {
                                unset($users[$key]);
                            }
                        }
                    }
                    if (isset($queryString['assessment'])) {
                        if (isset($levelMatches['disc']) && isset($levelMatches['values']) && isset($levelMatches['attributes'])) {
                            $levelAverage = round((floatval($levelMatches['disc']) + floatval($levelMatches['values']) + floatval($levelMatches['attributes'])) / 3, 2);
                            if (floatval($levelAverage) > floatval($queryString['assessment'])) {
                                unset($users[$key]);
                            }
                        }
                    }

                }
                $this->_arraySortByColumn($users, 'weight_sum', SORT_DESC);

                $paginator = new CustomArrayPagination();
                return ($users) ? $paginator->paginate($users, $perPage) : $users;
                }
            }

        }

        return ($users)?$this->transformersManager->transformCollection($users, $this->transformer):$users;
    }

    public function searchInspector($queryString, $perPage)
    {
        $users = $this->repo->searchInspectorUser($queryString, $perPage);
        return ($users)?$this->transformersManager->transformCollection($users, $this->transformer):$users;
    }

    /**
     * Set user id (If user is logged as admin than get user id from post/get,
     * if not than get logged user id)
     * @param $user
     * @return mixed
     */
    public function setUserId($user)
    {
        return $this->repo->setUserId($user);
    }

    public function validForgotToken($data)
    {
        return $this->repo->validForgotToken($data);
    }

    public function getFromRigzone($rigzoneId)
    {
        $rigzone = $this->repo->getFromRigzone($rigzoneId);
        return array('data'=>$rigzone['0']);
    }

    public function changePassword($data)
    {
        return $this->repo->changePassword($data);
    }

    /**
     * Register user to mail chimp with email address, first name and last name
     *
     * @param $user
     */
    public function registerMailChimp($user)
    {
        if (isset($user['data']['email']) && isset($user['data']['first_name']) && isset($user['data']['last_name'])) {
            $this->mailChimp->post('lists/'.env('MAILCHIMP_GENERAL_LIST_ID').'/members', array(
                'email_address'     => $user['data']['email'],
                'status'            => 'subscribed',
                'merge_fields'      => array('FNAME' => $user['data']['first_name'], 'LNAME' => $user['data']['last_name'])
            ));
        }
    }

    /**
     * Update user to mail chimp with email address, first name and last name
     *
     * @param $user
     */
    public function updateMailChimp($email, $user)
    {
        if (isset($user['data']['email']) && isset($user['data']['first_name']) && isset($user['data']['last_name'])) {
            $result = $this->mailChimp->patch('lists/'.env('MAILCHIMP_GENERAL_LIST_ID').'/members/'.md5(strtolower($email)), array(
                'email_address'     => $email,
                'status'            => 'subscribed',
                'merge_fields'      => array('FNAME' => $user['data']['first_name'], 'LNAME' => $user['data']['last_name'])
            ));
        }
    }

    public function removeMailChimp($email)
    {
        if ($email) {
            $this->mailChimp->delete('lists/'.env('MAILCHIMP_GENERAL_LIST_ID').'/members/'.md5(strtolower($email)));
        }
    }

    /**
     * @param $scoringValues
     * @return array
     */
    public function _getScoringValues($scoringValues)
    {
        $min=$max=0;
        if (preg_match('/^\d{1,3}\-\d{1,3}$/', $scoringValues)) {
            $values = explode("-", $scoringValues);

            $min = min($values);
            $max = max($values);
        } else if (preg_match('/^\d{1,3}$/', $scoringValues)) {
            $min = 0;
            $max = $scoringValues;
        } else if (preg_match('/^(\d{1,3})\+$/', $scoringValues, $matches)) {
            $min = $matches[1];
            $max = '+';
        }

        return array('min'=>$min,'max'=>$max);
    }

    public function _belongsToLevel($scoringValues, $userValues){
        if($userValues==null){
            $userValues=0;
        }

       if((floatval($scoringValues['min'])<=floatval($userValues) &&  floatval($userValues)<=floatval($scoringValues['max'])) || (floatval($scoringValues['min'])<=floatval($userValues) && $scoringValues['max']==='+')){
           return true;
       }
        return false;
    }

    function _arraySortByColumn(&$arr, $col, $dir = SORT_DESC) {
        $sort_col = array();
        foreach ($arr as $key=>&$row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }
}
