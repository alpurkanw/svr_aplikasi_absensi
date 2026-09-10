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
    }

    public function index()
    {
        $date = $this->input->get('date', true) ?: date('Y-m-d');
        $config = $this->read_app_config();
        $rules = $config['rule_absensi'];
        $date_start = new DateTime($date . ' 00:00:00');
        $date_end = (clone $date_start)->modify('+1 day');
        $entry_start = (clone $date_start)->modify('+' . $rules['mulai_masuk'] . ' hours');
        $entry_end = (clone $date_start)->modify('+' . $rules['akhir_masuk'] . ' hours');
        $exit_start = (clone $date_start)->modify('+' . $rules['mulai_pulang'] . ' hours');
        $exit_end = (clone $date_start)->modify('+' . $rules['akhir_pulang'] . ' hours');

        $punches = $this->db->select('p.id, p.user_id, p.timestamp, e.employee_code, e.name')
            ->from('presensi p')
            ->join('employees e', 'e.employee_code = p.user_id')
            ->where('p.timestamp >=', $date_start->format('Y-m-d H:i:s'))
            ->where('p.timestamp <', $date_end->format('Y-m-d H:i:s'))
            ->order_by('e.name', 'ASC')
            ->order_by('p.timestamp', 'ASC')
            ->order_by('p.id', 'ASC')
            ->get()->result_array();

        $rows = array();
        foreach ($punches as $punch) {
            $employee_code = $punch['employee_code'];
            if (!isset($rows[$employee_code])) {
                $rows[$employee_code] = array(
                    'employee_code' => $employee_code,
                    'name' => $punch['name'],
                    'check_in' => null,
                    'check_out' => null,
                    'late_minutes' => 0,
                    'early_leave_minutes' => 0,
                    'overtime_minutes' => 0,
                    'attendance_status' => 'TIDAK LENGKAP',
                );
            }

            $timestamp = new DateTime($punch['timestamp']);
            if ($rows[$employee_code]['check_in'] === null && $timestamp >= $entry_start && $timestamp <= $entry_end) {
                $rows[$employee_code]['check_in'] = $timestamp->format('Y-m-d H:i:s');
            }
            if ($timestamp >= $exit_start && $timestamp < $exit_end) {
                $rows[$employee_code]['check_out'] = $timestamp->format('Y-m-d H:i:s');
            }
        }

        foreach ($rows as &$row) {
            if ($row['check_in'] !== null) {
                $actual_in = new DateTime($row['check_in']);
                if ($actual_in > $entry_end) {
                    $row['late_minutes'] = (int) floor(($actual_in->getTimestamp() - $entry_end->getTimestamp()) / 60);
                }
            }
            if ($row['check_out'] !== null) {
                $actual_out = new DateTime($row['check_out']);
                if ($actual_out < $exit_start) {
                    $row['early_leave_minutes'] = (int) floor(($exit_start->getTimestamp() - $actual_out->getTimestamp()) / 60);
                }
            }
            if ($row['check_in'] !== null && $row['check_out'] !== null) {
                $row['attendance_status'] = $row['late_minutes'] > 0 ? 'TERLAMBAT' : 'HADIR';
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
            'rule_absensi' => array(
                'mulai_masuk' => 6,
                'akhir_masuk' => 9,
                'mulai_pulang' => 17,
                'akhir_pulang' => 24,
            ),
        );
        $path = APPPATH . 'config/app_config.json';
        $config = is_file($path) ? json_decode(file_get_contents($path), true) : array();
        return array_replace_recursive($defaults, is_array($config) ? $config : array());
    }
}
