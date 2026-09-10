<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App_config extends CI_Controller
{
    private $config_path;

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'ADMIN') {
            redirect('Auth/open_f_login');
        }
        $this->config_path = APPPATH . 'config/app_config.json';
    }

    public function index()
    {
        $this->load->view('admin/app_config/index', array(
            'title' => 'Aplikasi',
            'config' => $this->read_config(),
        ));
    }

    public function save()
    {
        $this->output->set_content_type('application/json');

        $config = array(
            'nama_perusahaan' => trim($this->input->post('nama_perusahaan', true)),
            'alamat' => trim($this->input->post('alamat', true)),
            'rule_absensi' => array(
                'mulai_masuk' => $this->input->post('mulai_masuk', true),
                'akhir_masuk' => $this->input->post('akhir_masuk', true),
                'mulai_pulang' => $this->input->post('mulai_pulang', true),
                'akhir_pulang' => $this->input->post('akhir_pulang', true),
            ),
        );

        if ($config['nama_perusahaan'] === '' || $config['alamat'] === '') {
            return $this->output->set_status_header(422)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Nama perusahaan dan alamat wajib diisi.',
            )));
        }

        foreach ($config['rule_absensi'] as $name => $value) {
            if ($value === '' || filter_var($value, FILTER_VALIDATE_INT) === false || (int) $value < 0 || (int) $value > 24) {
                return $this->output->set_status_header(422)->set_output(json_encode(array(
                    'success' => false,
                    'message' => 'Jam aturan absensi harus berupa angka 0 sampai 24.',
                )));
            }
            $config['rule_absensi'][$name] = (int) $value;
        }

        if (file_put_contents($this->config_path, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
            return $this->output->set_status_header(500)->set_output(json_encode(array(
                'success' => false,
                'message' => 'app_config.json tidak dapat disimpan. Periksa izin folder config.',
            )));
        }

        return $this->output->set_output(json_encode(array(
            'success' => true,
            'message' => 'Konfigurasi aplikasi berhasil disimpan.',
        )));
    }

    private function read_config()
    {
        $config = is_file($this->config_path) ? json_decode(file_get_contents($this->config_path), true) : array();
        if (!is_array($config)) {
            $config = array();
        }

        $defaults = array(
            'nama_perusahaan' => 'PT LOGAM MURNI',
            'alamat' => 'JL. Jend Ahmad Yani',
            'rule_absensi' => array(
                'mulai_masuk' => 6,
                'akhir_masuk' => 9,
                'mulai_pulang' => 17,
                'akhir_pulang' => 24,
            ),
        );

        return array_replace_recursive($defaults, $config);
    }
}
