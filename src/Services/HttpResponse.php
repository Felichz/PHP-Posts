<?php namespace App\Services;

class HttpResponse
{
    function __construct( $HtmlResponseClass, $RedirectResponseClass )
    {
        $this->HtmlResponseClass = $HtmlResponseClass;
        $this->RedirectResponseClass = $RedirectResponseClass;
    }

    public function HtmlResponse ( $html )
    {
        return new $this->HtmlResponseClass( $html );
    }

    public function RedirectResponse ( $uri )
    {
        return new $this->RedirectResponseClass( $uri );
    }
}

?>