<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'ADMIN') {
            redirect('Auth/open_f_login');
        }
        $this->load->library('attendance_engine');
        $this->load->model('Employee_model');
    }

    public function index()
    {
        $date = $this->input->get('date', true) ?: date('Y-m-d');
        $config = $this->read_app_config();
        $rules = $config['rule_absensi'];
        $date_start = new DateTime($date . ' 00:00:00');
        $date_end = (clone $date_start)->modify('+1 day');
        $entry_start = new DateTime($date . ' ' . $this->normalize_time($rules['mulai_masuk']));
        $entry_window_start = (clone $entry_start)->modify('-2 hours');
        $entry_end = new DateTime($date . ' ' . $this->normalize_time($rules['akhir_masuk']));
        $exit_start = new DateTime($date . ' ' . $this->normalize_time($rules['mulai_pulang']));
        $exit_end = new DateTime($date . ' ' . $this->normalize_time($rules['akhir_pulang']));

        $punches = $this->db->select('p.id, p.user_id, p.timestamp, e.id AS employee_id, e.employee_code, e.name')
            ->from('presensi p')
            ->join('employees e', 'e.employee_code = p.user_id')
            ->where('p.timestamp >=', $date_start->format('Y-m-d H:i:s'))
            ->where('p.timestamp <', $date_end->format('Y-m-d H:i:s'))
            ->order_by('e.name', 'ASC')
            ->order_by('p.timestamp', 'ASC')
            ->order_by('p.id', 'ASC')
            ->get()->result_array();

        $employee_ids = array();
        foreach ($punches as $punch) {
            $employee_ids[] = (int) $punch['employee_id'];
        }
        $salary_bases = $this->Employee_model->salary_bases($employee_ids, $config['potong_gapok'], $date);

        $rows = array();
        foreach ($punches as $punch) {
            $employee_code = $punch['employee_code'];
            if (!isset($rows[$employee_code])) {
                $rows[$employee_code] = array(
                    'employee_code' => $employee_code,
                    'name' => $punch['name'],
                    'salary' => isset($salary_bases[(int) $punch['employee_id']]) ? $salary_bases[(int) $punch['employee_id']] : 0,
                    'check_in' => null,
                    'check_out' => null,
                    'late_minutes' => 0,
                    'late_penalty_percentage' => 0,
                    'late_penalty_amount' => 0,
                    'early_leave_minutes' => 0,
                    'overtime_minutes' => 0,
                    'attendance_status' => 'TIDAK LENGKAP',
                );
            }

            $timestamp = new DateTime($punch['timestamp']);
            if ($rows[$employee_code]['check_in'] === null && $timestamp >= $entry_window_start && $timestamp <= $entry_end) {
                $rows[$employee_code]['check_in'] = $timestamp->format('Y-m-d H:i:s');
            }
            if ($timestamp >= $exit_start && $timestamp <= $exit_end) {
                $current_check_out = $rows[$employee_code]['check_out'];
                if ($current_check_out === null || $timestamp > new DateTime($current_check_out)) {
                    $rows[$employee_code]['check_out'] = $timestamp->format('Y-m-d H:i:s');
                }
            }
        }

        foreach ($rows as &$row) {
            if ($row['check_in'] !== null) {
                $actual_in = new DateTime($row['check_in']);
                $row['late_minutes'] = max(0, (int) floor(($actual_in->getTimestamp() - $entry_start->getTimestamp()) / 60));
                $row['late_penalty_percentage'] = $this->late_penalty_percentage($row['late_minutes'], $rules);
                $row['late_penalty_amount'] = $row['salary'] * $row['late_penalty_percentage'] / 100;
            }
            if ($row['check_out'] !== null) {
                $actual_out = new DateTime($row['check_out']);
                if ($actual_out < $exit_start) {
                    $row['early_leave_minutes'] = (int) floor(($exit_start->getTimestamp() - $actual_out->getTimestamp()) / 60);
                }
            }
            if ($row['check_in'] !== null) {
                if ($row['late_minutes'] === 0) {
                    $row['attendance_status'] = 'MASUK';
                } elseif ($row['late_minutes'] <= 20) {
                    $row['attendance_status'] = 'TERLAMBAT';
                } else {
                    $row['attendance_status'] = 'TIDAK HADIR';
                    $row['late_penalty_percentage'] = 0;
                    $row['late_penalty_amount'] = 0;
                }
            }
        }
        unset($row);

        $rows = array_values($rows);
        $this->load->view('admin/attendance/index', array('title' => 'Rekap Absensi', 'date' => $date, 'rows' => $rows));
    }

    public function process()
    {
        $date = $this->input->post('date', true) ?: date('Y-m-d');
        try {
            $processed = $this->attendance_engine->process_date($date);
            $this->session->set_flashdata('success', $processed . ' data attendance berhasil diproses.');
        } catch (Throwable $exception) {
            log_message('error', 'Attendance engine error: ' . $exception->getMessage());
            $this->session->set_flashdata('error', 'Proses attendance gagal.');
        }
        redirect('admin/attendance?date=' . rawurlencode($date));
    }

    private function read_app_config()
    {
        $defaults = array(
            'potong_gapok' => true,
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
        $path = APPPATH . 'config/app_config.json';
        $config = is_file($path) ? json_decode(file_get_contents($path), true) : array();
        $config = array_replace_recursive($defaults, is_array($config) ? $config : array());
        $config['potong_gapok'] = filter_var($config['potong_gapok'], FILTER_VALIDATE_BOOLEAN);
        foreach (array('mulai_masuk', 'akhir_masuk', 'mulai_pulang', 'akhir_pulang') as $name) {
            $config['rule_absensi'][$name] = $this->normalize_time($config['rule_absensi'][$name]);
        }
        return $config;
    }

    private function normalize_time($value)
    {
        if (is_int($value) || (is_string($value) && preg_match('/^\d{1,2}$/', trim($value)))) {
            return sprintf('%02d:00', (int) $value);
        }
        if (preg_match('/^(\d{1,2}):(\d{2})$/', trim((string) $value), $matches)) {
            return sprintf('%02d:%02d', (int) $matches[1], (int) $matches[2]);
        }
        return '00:00';
    }

    private function late_penalty_percentage($late_minutes, array $rules)
    {
        foreach ($rules['potongan_keterlambatan'] as $rule) {
            if ($late_minutes >= (int) $rule['mulai_menit'] && $late_minutes <= (int) $rule['sampai_menit']) {
                return (float) $rule['persentase'];
            }
        }
        return 0;
    }
}
