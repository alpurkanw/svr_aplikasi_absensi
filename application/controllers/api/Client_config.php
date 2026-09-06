<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client_config extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('api');
        $this->load->library('api_auth');
    }

    public function index()
    {
        if (!$this->api_auth->is_authenticated()) {
            return api_error(401, 'Unauthorized');
        }

        $path = APPPATH . 'config/config_client.json';
        $config = is_file($path) ? json_decode(file_get_contents($path), true) : null;
        if (!is_array($config) || empty($config['pin_open_daftar_karyawan'])) {
            return api_error(500, 'PIN daftar karyawan belum dikonfigurasi');
        }

        return api_json_response(200, array(
            'success' => true,
            'data' => array('pin_open_daftar_karyawan' => (string) $config['pin_open_daftar_karyawan']),
        ));
    }
}
