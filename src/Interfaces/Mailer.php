<?php namespace App\Interfaces;

interface Mailer
{
    public function __construct( array $smtp );

    // Parameters: to from replyTo subject body
    public function sendMail( array $parameters );
}