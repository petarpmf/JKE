<?php
namespace App\Http\Models;

class UserInnermatrix extends BaseModel
{
    protected $table = 'users_innermetrix';
    public $incrementing = false;
    protected $fillable = ['id', 'user_id', 'decisive', 'interactive', 'stabilizing', 'cautious', 'aesthetic',
        'economic', 'individualistic', 'political', 'altruist', 'regulatory', 'theoretical', 'getting_results',
        'interpersonal_skills', 'making_decisions', 'work_ethic'];
}