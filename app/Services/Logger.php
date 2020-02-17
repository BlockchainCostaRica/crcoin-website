<?php namespace App\Services;

/**  * Logger class  */
class Logger
{
    public function __construct()
    {
        $this->path = '/var/log/crypto.log';
    }

    public function log($type, $text = '', $timestamp = true)
    {
        if ($timestamp) {
            $datetime = date("d-m-Y H:i:s");
            $text = "$datetime, $type: $text \r\n\r\n";
        } else {
            $text = "$type\r\n\r\n";
        }

        error_log($text, 3, $this->path);
    }
}
