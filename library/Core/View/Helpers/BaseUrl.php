<?php
class Core_View_Helpers_BaseUrl
{
    /**
     *  Get base url
     * 
     * @return string
     */
    static public function baseUrl ()
    {
        if (isset($_SERVER['HTTPS'])) {
            $protocol = $_SERVER['HTTPS'] ? 'https' : 'http';
        } else {
            $protocol = 'http';
        }
        $server = $_SERVER['HTTP_HOST'];
        $port = $_SERVER['SERVER_PORT'] != 80 ? ":{$_SERVER['SERVER_PORT']}" : '';

        return "$protocol://$server$port";
    }


}
