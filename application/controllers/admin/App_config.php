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
            'potong_gapok' => $this->input->post('potong_gapok', true) === 'true',
            'cut_off_absensi' => $this->input->post('cut_off_absensi', true),
            'rule_absensi' => array(
                'mulai_masuk' => $this->input->post('mulai_masuk', true),
                'akhir_masuk' => $this->input->post('akhir_masuk', true),
                'mulai_pulang' => $this->input->post('mulai_pulang', true),
                'akhir_pulang' => $this->input->post('akhir_pulang', true),
                'potongan_keterlambatan' => array(
                    array(
                        'mulai_menit' => 6,
                        'sampai_menit' => 10,
                        'persentase' => $this->input->post('potongan_6_10', true),
                    ),
                    array(
                        'mulai_menit' => 11,
                        'sampai_menit' => 15,
                        'persentase' => $this->input->post('potongan_11_15', true),
                    ),
                    array(
                        'mulai_menit' => 16,
                        'sampai_menit' => 20,
                        'persentase' => $this->input->post('potongan_16_20', true),
                    ),
                ),
            ),
        );

        if ($config['nama_perusahaan'] === '' || $config['alamat'] === '') {
            return $this->respond_save(422, array(
                'success' => false,
                'message' => 'Nama perusahaan dan alamat wajib diisi.',
            ));
        }

        if (filter_var($config['cut_off_absensi'], FILTER_VALIDATE_INT) === false || (int) $config['cut_off_absensi'] < 1 || (int) $config['cut_off_absensi'] > 31) {
            return $this->respond_save(422, array(
                'success' => false,
                'message' => 'Cut off absensi harus berupa angka tanggal 1 sampai 31.',
            ));
        }
        $config['cut_off_absensi'] = (int) $config['cut_off_absensi'];

        foreach (array('mulai_masuk', 'akhir_masuk', 'mulai_pulang', 'akhir_pulang') as $name) {
            $value = $config['rule_absensi'][$name];
            $normalized_time = $this->normalize_time($value);
            if ($normalized_time === null) {
                return $this->respond_save(422, array(
                    'success' => false,
                    'message' => 'Jam aturan absensi harus menggunakan format HH:MM, antara 00:00 sampai 24:00.',
                ));
            }
            $config['rule_absensi'][$name] = $normalized_time;
        }

        if ($this->time_to_minutes($config['rule_absensi']['akhir_masuk']) >= $this->time_to_minutes($config['rule_absensi']['mulai_pulang'])) {
            return $this->respond_save(422, array(
                'success' => false,
                'message' => 'Akhir masuk harus lebih kecil dari mulai pulang.',
            ));
        }

        foreach ($config['rule_absensi']['potongan_keterlambatan'] as $index => $rule) {
            if ($rule['persentase'] === '' || filter_var($rule['persentase'], FILTER_VALIDATE_FLOAT) === false || (float) $rule['persentase'] < 0 || (float) $rule['persentase'] > 100) {
                return $this->respond_save(422, array(
                    'success' => false,
                    'message' => 'Persentase potongan keterlambatan harus berupa angka 0 sampai 100.',
                ));
            }
            $config['rule_absensi']['potongan_keterlambatan'][$index]['persentase'] = (float) $rule['persentase'];
        }

        if (file_put_contents($this->config_path, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
            return $this->respond_save(500, array(
                'success' => false,
                'message' => 'app_config.json tidak dapat disimpan. Periksa izin folder config.',
            ));
        }

        return $this->respond_save(200, array(
            'success' => true,
            'message' => 'Konfigurasi aplikasi berhasil disimpan.',
        ));
    }

    private function respond_save($status, array $payload)
    {
        if ($this->input->is_ajax_request()) {
            return $this->output->set_status_header($status)->set_output(json_encode($payload));
        }

        $this->session->set_flashdata($payload['success'] ? 'success' : 'error', $payload['message']);
        redirect('admin/app-config');
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
            'potong_gapok' => true,
            'cut_off_absensi' => 15,
            'rule_absensi' => array(
                'mulai_masuk' => '06:00',
                'akhir_masuk' => '09:00',
                'mulai_pulang' => '17:00',
                'akhir_pulang' => '24:00',
                'potongan_keterlambatan' => array(
                    array('mulai_menit' => 6, 'sampai_menit' => 10, 'persentase' => 1),
                    array('mulai_menit' => 11, 'sampai_menit' => 15, 'persentase' => 2),
                    array('mulai_menit' => 16, 'sampai_menit' => 20, 'persentase' => 3),
                ),
            ),
        );

        $config = array_replace_recursive($defaults, $config);
        $config['potong_gapok'] = filter_var($config['potong_gapok'], FILTER_VALIDATE_BOOLEAN);
        $config['cut_off_absensi'] = filter_var($config['cut_off_absensi'], FILTER_VALIDATE_INT, array('options' => array('default' => 15)));
        if ($config['cut_off_absensi'] < 1 || $config['cut_off_absensi'] > 31) {
            $config['cut_off_absensi'] = $defaults['cut_off_absensi'];
        }
        foreach (array('mulai_masuk', 'akhir_masuk', 'mulai_pulang', 'akhir_pulang') as $name) {
            $config['rule_absensi'][$name] = $this->normalize_time($config['rule_absensi'][$name]) ?: $defaults['rule_absensi'][$name];
        }
        return $config;
    }

    private function normalize_time($value)
    {
        if (is_int($value) || (is_string($value) && preg_match('/^\d{1,2}$/', trim($value)))) {
            $value = (int) $value;
            return $value >= 0 && $value <= 24 ? sprintf('%02d:00', $value) : null;
        }
        if (!is_string($value) || !preg_match('/^(\d{1,2}):(\d{2})$/', trim($value), $matches)) {
            return null;
        }
        $hour = (int) $matches[1];
        $minute = (int) $matches[2];
        if ($hour < 0 || $hour > 24 || $minute < 0 || $minute > 59 || ($hour === 24 && $minute !== 0)) {
            return null;
        }
        return sprintf('%02d:%02d', $hour, $minute);
    }

    private function time_to_minutes($value)
    {
        list($hour, $minute) = array_map('intval', explode(':', $value));
        return ($hour * 60) + $minute;
    }
}
