<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\ScoringInterface;
use App\Http\Models\Scoring;
use App\Http\Models\ScoringTemplate;
use App\Http\Models\User;
use Illuminate\Support\Facades\DB;
use League\Fractal\Resource\Collection;

class EloquentScoringRepository implements ScoringInterface
{
    /**
     * Used for creating new role in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        return Scoring::updateOrCreate(['user_id' => $data['user_id']], $data);
    }

    /**
     * Used for returning paginated list of all scorings
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage, $withTrashed)
    {
        $scoring = new Scoring();
        if ($withTrashed === true) {
            $role = $scoring->withTrashed();
        }

        return $scoring->orderBy('updated_at','desc')->paginate($perPage);
    }

    /**
     * Used for returning scoring by userID
     *
     * @param $userId
     * @return bool
     */
    public function getById($userId)
    {
        $scoring = Scoring::where('user_id', '=', $userId)->get();

        if (count($scoring)>0) {
            return $scoring;
        }

        return false;
    }

    /**
     * Used for deleting scoring by userID
     *
     * @param $userId
     * @return bool
     */
    public function delete($userId)
    {
        $scoringForDelete = Scoring::where('user_id', '=', $userId);

        if (count($scoringForDelete->get())>0) {
            return $scoringForDelete->delete();
        }
        return false;
    }

    public function getAutomaticById($userId, $desiredJobId)
    {
        /*$scoring = User::selectRaw("scorings.id, users.id as user_id, scorings.technical_skills, scorings.critical_skills, scorings.assessment")
                   ->leftJoin('scorings', 'scorings.user_id', '=', 'users.id')
                  ->where('users.id', '=', $userId)->get();
       return $scoring;
        */
        if($desiredJobId) {
            $user = User::where('id', '=', $userId)->first();

            $scoringTemplate = ScoringTemplate::where('desired_job_id', '=', $desiredJobId)->first();

            if (count($scoringTemplate) > 0) {
                $levelMatches = array();
                //$users = $users->toArray();
                //dd($user['id']);

                //--- TECHNICAL SKILLS ---
                $technical = $this->countTechnical($user['id']);

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

                $critical = $this->countCritical($user['id']);
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
                $assessments = $this->countAssessment($user['id']);
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

                $user['technical_level_average'] = round((floatval($levelMatches['experience']) + floatval($levelMatches['certification'])) / 2, 2);
                $user['critical_level_average'] = floatval($levelMatches['reference']);
                $user['assessment_level_average'] = round((floatval($levelMatches['disc']) + floatval($levelMatches['values']) + floatval($levelMatches['attributes'])) / 3, 2);

                return $user;
            }
        }
        return false;
    }

    private function countTechnical($userId){

        $results = DB::select( DB::raw("SELECT DISTINCT
                                IF(
                                    (SELECT SUM(years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id) IS NULL, 0,
                                    (SELECT SUM(years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id)
                                ) AS count_experience,
                                IF(
                                    (SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id AND users_certificates.certificate_verified=1) IS NULL, 0,
                                    (SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id AND users_certificates.certificate_verified=1)
                                )  AS count_certificates,
                                IF(
                                    (((SELECT SUM(years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id)
                                    +
                                    (SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id AND users_certificates.certificate_verified=1))
                                    /2) IS NULL, 0,
                                    (((SELECT SUM(years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id)
                                    +
                                    (SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id AND users_certificates.certificate_verified=1))
                                    /2)
                                ) AS average
                                FROM users
                                WHERE users.id='".$userId."'") );

        return $results[0];
    }

    private function  countCritical($userId){
        $results = DB::select( DB::raw("SELECT references_users_qualifications.rating FROM `references`
                                JOIN `references_users_qualifications`
                                ON `references`.id=`references_users_qualifications`.reference_id
                                WHERE `references`.user_id='".$userId."'") );
        //dd($results);
        return $results;
    }

    private function countAssessment($userId){
        $results = DB::select( DB::raw("SELECT
                                    (SUM(
                                    IF(decisive IS NULL,0,decisive)
                                    +IF(interactive IS NULL, 0, interactive)
                                    +IF(stabilizing IS NULL, 0, stabilizing)
                                    +IF(cautious IS NULL, 0, cautious))) AS count_disc,
                                    (SUM(
                                    IF(aesthetic IS NULL,0,aesthetic)
                                    +IF(economic IS NULL,0,economic)
                                    +IF(individualistic IS NULL, 0, individualistic)
                                    +IF(political IS NULL, 0, political)
                                    +IF(altruist IS NULL, 0, altruist)
                                    +IF(regulatory IS NULL, 0, regulatory)
                                    +IF(theoretical IS NULL, 0, theoretical))) AS count_values,
                                    (SUM(
                                    IF(getting_results IS NULL,0,getting_results)
                                    +IF(interpersonal_skills IS NULL, 0, interpersonal_skills)
                                    +IF(making_decisions IS NULL, 0, making_decisions)
                                    +IF(work_ethic IS NULL, 0, work_ethic))) AS count_attributes
                                    FROM `users` LEFT JOIN `users_innermetrix` on `users`.id=`users_innermetrix`.user_id
                                    WHERE users.id='".$userId."'") );
        //dd($results[0]);
        return $results[0];
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
}