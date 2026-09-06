<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client_credentials extends CI_Controller
{
    private $config_path;

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'ADMIN') {
            redirect('Auth/open_f_login');
        }
        $this->config_path = APPPATH . 'config/config_client.json';
    }

    public function index()
    {
        $config = $this->read_config();
        $this->load->view('admin/client_credentials/index', array(
            'title' => 'Credential client',
            'pin_open_daftar_karyawan' => $config['pin_open_daftar_karyawan'],
        ));
    }

    public function save()
    {
        $this->output->set_content_type('application/json');
        $pin = trim($this->input->post('pin_open_daftar_karyawan', true));

        if ($pin === '' || strlen($pin) > 100) {
            return $this->output->set_status_header(422)->set_output(json_encode(array(
                'success' => false,
                'message' => 'PIN wajib diisi dan maksimal 100 karakter.',
            )));
        }

        $config = $this->read_config();
        $config['pin_open_daftar_karyawan'] = $pin;
        if (file_put_contents($this->config_path, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
            return $this->output->set_status_header(500)->set_output(json_encode(array(
                'success' => false,
                'message' => 'config_client.json tidak dapat disimpan. Periksa izin folder config.',
            )));
        }

        return $this->output->set_output(json_encode(array(
            'success' => true,
            'message' => 'PIN client berhasil disimpan.',
        )));
    }

    public function generate()
    {
        $this->output->set_content_type('application/json');
        $config = $this->read_config();
        $config['token_client_credential'] = 'hcis_client_' . bin2hex(random_bytes(32));
        if (file_put_contents($this->config_path, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
            return $this->output->set_status_header(500)->set_output(json_encode(array(
                'success' => false,
                'message' => 'config_client.json tidak dapat disimpan.',
            )));
        }
        return $this->output->set_output(json_encode(array(
            'success' => true,
            'message' => 'Token client baru berhasil dibuat.',
            'token' => $config['token_client_credential'],
        )));
    }

    private function read_config()
    {
        $config = is_file($this->config_path) ? json_decode(file_get_contents($this->config_path), true) : array();
        if (!is_array($config)) {
            $config = array();
        }
        if (!isset($config['pin_open_daftar_karyawan'])) {
            $config['pin_open_daftar_karyawan'] = 'Poiuyt';
        }
        if (!isset($config['token_client_credential'])) {
            $config['token_client_credential'] = 'hcis_client_' . bin2hex(random_bytes(32));
        }
        return $config;
    }
}
