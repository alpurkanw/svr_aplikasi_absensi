<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_monthly extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'ADMIN') {
            redirect('Auth/open_f_login');
        }
    }

    public function index()
    {
        $month = $this->input->get('month', true) ?: date('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        $config = $this->read_app_config();
        $rules = $config['rule_absensi'];
        $period_start = new DateTime($month . '-01 00:00:00');
        $period_end = (clone $period_start)->modify('+1 month');
        $calculation_end = clone $period_end;
        $current_month = date('Y-m');
        if ($month === $current_month) {
            $calculation_end = new DateTime('tomorrow 00:00:00');
        }
        $workdays = $this->count_workdays($period_start, $calculation_end);
        $entry_start = (int) $rules['mulai_masuk'];
        $entry_end = (int) $rules['akhir_masuk'];
        $exit_start = (int) $rules['mulai_pulang'];
        $exit_end = (int) $rules['akhir_pulang'];

        $employees = $this->db->select('employee_code, name')->from('employees')
            ->where('is_active', 1)->order_by('name', 'ASC')->get()->result_array();
        $punches = $this->db->select('p.user_id, p.timestamp')
            ->from('presensi p')->join('employees e', 'e.employee_code = p.user_id')
            ->where('p.timestamp >=', $period_start->format('Y-m-d H:i:s'))
            ->where('p.timestamp <', $period_end->format('Y-m-d H:i:s'))
            ->order_by('p.timestamp', 'ASC')->get()->result_array();

        $daily = array();
        foreach ($punches as $punch) {
            $timestamp = new DateTime($punch['timestamp']);
            $day = $timestamp->format('Y-m-d');
            $code = $punch['user_id'];
            if (!isset($daily[$code][$day])) {
                $daily[$code][$day] = array('check_in' => null, 'check_out' => null);
            }

            $hour = (int) $timestamp->format('G');
            if ($hour >= $entry_start && $hour <= $entry_end && $daily[$code][$day]['check_in'] === null) {
                $daily[$code][$day]['check_in'] = $timestamp;
            }
            if ($hour >= $exit_start && $hour < $exit_end) {
                $daily[$code][$day]['check_out'] = $timestamp;
            }
        }

        $rows = array();
        foreach ($employees as $employee) {
            $code = $employee['employee_code'];
            $summary = array(
                'employee_code' => $code,
                'name' => $employee['name'],
                'hadir' => 0,
                'terlambat' => 0,
                'pulang_cepat' => 0,
                'tidak_hadir' => 0,
                'total_menit_terlambat' => 0,
                'total_menit_pulang_cepat' => 0,
            );

            for ($day = clone $period_start; $day < $calculation_end; $day->modify('+1 day')) {
                if ((int) $day->format('N') > 5) {
                    continue;
                }
                $date = $day->format('Y-m-d');
                $attendance = isset($daily[$code][$date]) ? $daily[$code][$date] : null;
                if (!$attendance || $attendance['check_in'] === null) {
                    $summary['tidak_hadir']++;
                    continue;
                }

                $summary['hadir']++;
                $late_minutes = max(0, ((int) $attendance['check_in']->format('G') * 60 + (int) $attendance['check_in']->format('i')) - ($entry_end * 60));
                if ($late_minutes > 0) {
                    $summary['terlambat']++;
                    $summary['total_menit_terlambat'] += $late_minutes;
                }
                if ($attendance['check_out'] !== null) {
                    $early_minutes = max(0, ($exit_start * 60) - ((int) $attendance['check_out']->format('G') * 60 + (int) $attendance['check_out']->format('i')));
                    if ($early_minutes > 0) {
                        $summary['pulang_cepat']++;
                        $summary['total_menit_pulang_cepat'] += $early_minutes;
                    }
                }
            }
            $summary['workdays'] = $workdays;
            $rows[] = $summary;
        }

        $this->load->view('admin/attendance_monthly/index', array(
            'title' => 'Rekap Absensi Bulanan',
            'month' => $month,
            'workdays' => $workdays,
            'rows' => $rows,
        ));
    }

    private function count_workdays(DateTime $start, DateTime $end)
    {
        $total = 0;
        for ($day = clone $start; $day < $end; $day->modify('+1 day')) {
            if ((int) $day->format('N') <= 5) {
                $total++;
            }
        }
        return $total;
    }

    private function read_app_config()
    {
        $defaults = array('rule_absensi' => array(
            'mulai_masuk' => 6,
            'akhir_masuk' => 9,
            'mulai_pulang' => 17,
            'akhir_pulang' => 24,
        ));
        $path = APPPATH . 'config/app_config.json';
        $config = is_file($path) ? json_decode(file_get_contents($path), true) : array();
        return array_replace_recursive($defaults, is_array($config) ? $config : array());
    }
}
