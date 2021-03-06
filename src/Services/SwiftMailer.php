<?php namespace App\Services;

use App\Interfaces\Mailer;
use Exception;

class SwiftMailer implements Mailer
{
    public $errorMessage = null;

    public function __construct( array $smtp )
    {
        $this->smtp = $smtp;
    }

    // Parameters: to from replyTo subject body
    public function sendMail( array $parameters )
    {
        $smtp = $this->smtp;

        $to = $parameters['to'] ?? null;
        $from = $parameters['from'] ?? null;
        $name = $parameters['name'] ?? null;
        $replyTo = $parameters['replyTo'] ?? null;
        $subject = $parameters['subject'] ?? null;
        $body = $parameters['body'] ?? null;

        // Create the Transport
        $transport = (new \Swift_SmtpTransport($smtp['HOST'], $smtp['PORT']))
        ->setUsername($smtp['USER'])
        ->setPassword($smtp['PASS'])
        ;

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Create a message
        $message = (new \Swift_Message($subject))
        ->setFrom($from, $name)
        ->setTo($to)
        ->setContentType("text/html")
        ->setBody($body)
        ;

        // Send the message
        $result = $mailer->send($message);
    }
}