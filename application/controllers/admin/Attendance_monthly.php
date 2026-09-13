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
        $holiday_rows = $this->db->where('is_active', 1)
            ->where('holiday_date >=', $period_start->format('Y-m-d'))
            ->where('holiday_date <', $period_end->format('Y-m-d'))
            ->get('holidays')->result_array();
        $holidays = array();
        foreach ($holiday_rows as $holiday) {
            $holidays[$holiday['holiday_date']] = true;
        }
        $workdays = $this->count_workdays($period_start, $calculation_end, $holidays);
        $entry_start = $this->normalize_time($rules['mulai_masuk']);
        $entry_end = $this->normalize_time($rules['akhir_masuk']);
        $exit_start = $this->normalize_time($rules['mulai_pulang']);
        $exit_end = $this->normalize_time($rules['akhir_pulang']);

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

            $entry_window_start = new DateTime($day . ' ' . $entry_start);
            $entry_window_start->modify('-2 hours');
            $entry_window_end = new DateTime($day . ' ' . $entry_end);
            $exit_window_start = new DateTime($day . ' ' . $exit_start);
            $exit_window_end = new DateTime($day . ' ' . $exit_end);
            if ($timestamp >= $entry_window_start && $timestamp <= $entry_window_end && $daily[$code][$day]['check_in'] === null) {
                $daily[$code][$day]['check_in'] = $timestamp;
            }
            if ($timestamp >= $exit_window_start && $timestamp < $exit_window_end) {
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
                if (isset($holidays[$date])) {
                    continue;
                }
                $attendance = isset($daily[$code][$date]) ? $daily[$code][$date] : null;
                if (!$attendance || $attendance['check_in'] === null) {
                    $summary['tidak_hadir']++;
                    continue;
                }

                $summary['hadir']++;
                $entry_time = new DateTime($date . ' ' . $entry_start);
                $late_minutes = max(0, (int) floor(($attendance['check_in']->getTimestamp() - $entry_time->getTimestamp()) / 60));
                if ($late_minutes > 0) {
                    $summary['terlambat']++;
                    $summary['total_menit_terlambat'] += $late_minutes;
                }
                if ($attendance['check_out'] !== null) {
                    $exit_time = new DateTime($date . ' ' . $exit_start);
                    $early_minutes = max(0, (int) floor(($exit_time->getTimestamp() - $attendance['check_out']->getTimestamp()) / 60));
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

    private function count_workdays(DateTime $start, DateTime $end, array $holidays = array())
    {
        $total = 0;
        for ($day = clone $start; $day < $end; $day->modify('+1 day')) {
            if ((int) $day->format('N') <= 5 && !isset($holidays[$day->format('Y-m-d')])) {
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
            'akhir_pulang' => '24:00',
        ));
        $path = APPPATH . 'config/app_config.json';
        $config = is_file($path) ? json_decode(file_get_contents($path), true) : array();
        $config = array_replace_recursive($defaults, is_array($config) ? $config : array());
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
}
