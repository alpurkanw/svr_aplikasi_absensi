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

        $token = trim($matches[1]);
        $configured_token = (string) $this->CI->config->item('api_token');
        if ($configured_token !== '' && hash_equals($configured_token, $token)) {
            return true;
        }

        $client_config_path = APPPATH . 'config/config_client.json';
        if (is_file($client_config_path)) {
            $client_config = json_decode(file_get_contents($client_config_path), true);
            $client_token = is_array($client_config) && isset($client_config['token_client_credential'])
                ? (string) $client_config['token_client_credential'] : '';
            if ($client_token !== '' && hash_equals($client_token, $token)) {
                return true;
            }
        }

        $token_hash = hash('sha256', $token);
        $row = $this->CI->db->where('token_hash', $token_hash)
            ->where('revoked_at IS NULL', null, false)
            ->group_start()
            ->where('expires_at IS NULL', null, false)
            ->or_where('expires_at >', date('Y-m-d H:i:s'))
            ->group_end()
            ->get('api_tokens')->row_array();

        if (!$row) {
            return false;
        }

        $this->CI->db->where('id', (int) $row['id'])
            ->update('api_tokens', array('last_used_at' => date('Y-m-d H:i:s')));
        return true;
    }
}
