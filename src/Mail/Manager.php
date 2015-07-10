<?php

namespace Orbit\Machine\Mail;

use Phalcon\Mvc\User\Component;

class Mail extends Component
{
    protected $sender;

    protected $name;

    protected $to;

    protected $subject;

    public function send($from = null, $to = null, $subject = null, $body = null)
    {

    }

}
