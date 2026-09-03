<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('api_json_response')) {
    function api_json_response($status_code, array $payload)
    {
        $CI = get_instance();
        $CI->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload, JSON_UNESCAPED_SLASHES));
    }
}

if (!function_exists('api_error')) {
    function api_error($status_code, $message)
    {
        api_json_response($status_code, array(
            'success' => false,
            'message' => $message,
        ));
    }
}

if (!function_exists('api_request_json')) {
    function api_request_json()
    {
        $content_type = isset($_SERVER['CONTENT_TYPE']) ? strtolower($_SERVER['CONTENT_TYPE']) : '';
        if (strpos($content_type, 'application/json') !== 0) {
            api_error(400, 'Content-Type must be application/json');
            return null;
        }

        $raw_body = file_get_contents('php://input');
        $data = json_decode($raw_body, true);
        if (!is_array($data) || json_last_error() !== JSON_ERROR_NONE) {
            api_error(400, 'Request body must contain valid JSON');
            return null;
        }

        return $data;
    }
}
