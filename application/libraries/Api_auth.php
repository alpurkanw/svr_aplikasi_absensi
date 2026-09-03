<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_auth
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function is_authenticated()
    {
        $authorization = $this->CI->input->get_request_header('Authorization', true);
        if (!$authorization && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        }

        if (!is_string($authorization) || !preg_match('/^Bearer\s+(.+)$/i', trim($authorization), $matches)) {
            return false;
        }

        $configured_token = (string) $this->CI->config->item('api_token');
        return $configured_token !== '' && hash_equals($configured_token, trim($matches[1]));
    }
}
