<?php
namespace App\Http\Services;
use Illuminate\Contracts\Mail\Mailer;

class Notify
{
    protected $from;
    protected $to;
    protected $cc;
    protected $bcc;
    protected $subject;
    protected $message;
    protected $name;
    protected $toName;
    protected $ccName;
    protected $bccName;

    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Set from address and name in email
     * @param $from
     * @param null $name
     * @return $this
     */
    public function from($from = null, $name = null)
    {
        $this->from = $from;
        if ($from == null) {
            $this->from = env('MAIL_FROM_ADDRESS');
        }
        $this->fromName = $name;
        return $this;
    }

    /**
     * Set to address and name in email
     * @param $to - array
     * @param null $name
     * @return $this
     */
    public function to($to,  $name = null)
    {
        $this->to = $to;
        $this->toName = $name;
        return $this;
    }

    /**
     * Set cc address and name in email
     * @param $cc - array
     * @param null $name
     * @return $this
     */
    public function cc($cc, $name = null)
    {
        $this->cc = $cc;
        $this->ccName = $name;
        return $this;
    }

    /**
     * Set bcc address and name in email
     * @param $bcc - array
     * @param null $name
     * @return $this
     */
    public function bcc($bcc, $name = null)
    {
        $this->bcc = $bcc;
        $this->bccName = $name;
        return $this;
    }

    /**
     * Set subject of email
     * @param $subject - string
     * @return $this
     */
    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     *
     * Set message in raw email
     * @param $message - string
     * @return $this
     */
    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Attach file to email with path of attachment
     * @param $path - array
     * @return $this
     */
    public function attach($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Send raw email without view, just message
     * @return array|bool
     */
    public function sendRaw()
    {
        //send raw email
        $this->mailer->raw($this->message, function($message)
        {
            //set from
            $message->from($this->from, $this->name);

            //set receivers
            foreach ($this->to as $address) {
                $message->to($address)->subject($this->subject);
            }
        });

        if (count($this->mailer->failures()) > 0){
            return ($this->mailer->failures());
        } else {
            return true;
        }
    }

    /**
     * Send email with view
     * @param $view - email template
     * @param $data - data shown in email template
     * @return array|bool
     */
    public function send($view, $data)
    {
        //send email
        $this->mailer->send($view, $data, function ($message) {

            //set from address
            $message->from($this->from, $this->name);

            //set cc address
            if ($this->cc) {
                foreach ($this->cc as $address) {
                    $message->cc($address);
                }
            }
            //set bcc address
            if ($this->bcc) {
                foreach ($this->bcc as $address) {
                    $message->bcc($address);
                }
            }

            //set attachments
            if (isset($this->path)) {
                foreach ($this->path as $path) {
                    if (file_exists($path)) {
                    $message->attach($path);
                    }
                }
            }
            //set subject
            foreach ($this->to as $address) {
                $message->to($address)->subject($this->subject);
            }
        });

        if (count($this->mailer->failures()) > 0){
            return ($this->mailer->failures());
        } else {
            return true;
        }
    }
}
