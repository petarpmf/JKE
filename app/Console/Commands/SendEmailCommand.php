<?php

namespace App\Console\Commands;

use App\Http\Models\ProfileInspector;
use App\Http\Models\User;
use DateTime;
use Illuminate\Console\Command;
use App\Http\Services\Notify;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailCommand extends Command {
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'email:AvailableForJob';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send email for available for job.";
    /**
     * Execute the console command.
     *
     * @return void
     */

    //public function __construct(Notify $notify)
    //{
       // $this->notify = $notify;
    //}

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {   //Log::debug('This will fire,...BEFORE');

        $users = User::selectRaw("users.id, users.profile_id, users.first_name, users.last_name, users.email, pi.street_address, pi.city, pi.zip, pi.state, pi.country,
                                    pi.mobile_phone, pi.other_phone, pi.resume_link, pi.job_title, pi.summary, pi.currently_seeking_opportunities,
                                    pi.other_jobs, users.role_id, users.created_at")
            ->join('profile_inspectors as pi', 'users.profile_id', '=', 'pi.id')
            ->where('currently_seeking_opportunities','=',0)
            ->whereNull('available_for_job')

            /*->whereNotNull('send_email_date')*/

            ->where(function ($query) {
                $query->where(function ($query) {
                    //$query->where(DB::raw('DATE_ADD(send_email_date, INTERVAL 7 DAY)'), '<=', DB::raw('NOW()'));
                    $query->where(DB::raw("DATE_FORMAT(DATE_ADD(send_email_date, INTERVAL 30 DAY), '%Y-%m-%d')"), "<=", DB::raw("DATE_FORMAT(NOW(), '%Y-%m-%d')"));
                })->orWhere(function ($query) {
                    $query->whereNull('send_email_date');
                });
            })
            ->whereNotIn('users.email', function ($query)
            {
                $query->select('email')->from('bounced_emails');
            })
            ->where("users.role_id", "=",3)
            ->get();
        //dd($users->count());
        if($users){
            //Log::debug('This will fire,...IN');
            $view = 'notify.availableForJob';
            $data=array();
            foreach($users as $user){

                $data['fromEmail'] = env('MAIL_FROM_ADDRESS');
                $data['fromName'] = 'Joe Knows Energy';
                $data['toEmail'] = $user->email;
                $data['toName'] = $user->first_name.' '.$user->last_name;
                $data['subject'] = 'JOE KNOWS ENERGY Availability Reminder';
                $data['profile_id'] = $user->profile_id;

             $mail = Mail::queue($view, $data, function($message) use ($data)
                {
                    $message->from($data['fromEmail'], $data['fromName']);
                    $message->to($data['toEmail'], $data['toName'])->subject($data['subject']);

                    $date = new DateTime();
                    $profile = ProfileInspector::find($data['profile_id']);
                    $profile->update(['send_email_date' => $date->format('Y-m-d H:i:s')]);
                });
                //Log::debug('This will fire,...AFTER');
            }
        }


    }
}