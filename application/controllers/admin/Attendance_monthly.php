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
        $work_days = $this->work_days_from_config($config['workin_day']);
        $period_end = $this->cutoff_period_end($month, (int) $config['cut_off_absensi']);
        $period_start = (clone $period_end)->modify('-1 month');
        $calculation_end = clone $period_end;
        $tomorrow = new DateTime('tomorrow 00:00:00');
        if ($tomorrow < $calculation_end) {
            $calculation_end = $tomorrow;
        }
        $holiday_rows = $this->db->where('is_active', 1)
            ->where('holiday_date >=', $period_start->format('Y-m-d'))
            ->where('holiday_date <', $period_end->format('Y-m-d'))
            ->get('holidays')->result_array();
        $holidays = array();
        foreach ($holiday_rows as $holiday) {
            $holidays[$holiday['holiday_date']] = true;
        }
        $workdays = $this->count_workdays($period_start, $calculation_end, $holidays, $work_days);
        $entry_start = $this->normalize_time($rules['mulai_masuk']);
        $entry_end = $this->normalize_time($rules['akhir_masuk']);
        $exit_start = $this->normalize_time($rules['mulai_pulang']);
        $exit_end = $this->normalize_time($rules['akhir_pulang']);

        $employees = $this->db->select('id, employee_code, name')->from('employees')
            ->where('is_active', 1)->order_by('name', 'ASC')->get()->result_array();
        $this->load->model('Employee_model');
        $employee_ids = array_column($employees, 'id');
        $salary_bases = $this->Employee_model->salary_bases($employee_ids, $config['potong_gapok'], $period_start->format('Y-m-d'));
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
                'employee_id' => (int) $employee['id'],
                'employee_code' => $code,
                'name' => $employee['name'],
                'salary' => isset($salary_bases[(int) $employee['id']]) ? (float) $salary_bases[(int) $employee['id']] : 0,
                'hadir' => 0,
                'terlambat_kurang_atau_sama_5' => 0,
                'terlambat_lebih_5' => 0,
                'pulang_cepat' => 0,
                'tidak_hadir' => 0,
                'total_menit_terlambat' => 0,
                'total_menit_pulang_cepat' => 0,
                'total_denda' => 0,
            );

            for ($day = clone $period_start; $day < $calculation_end; $day->modify('+1 day')) {
                if (!in_array((int) $day->format('N'), $work_days, true)) {
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
                    if ($late_minutes <= 5) {
                        $summary['terlambat_kurang_atau_sama_5']++;
                    } else {
                        $summary['terlambat_lebih_5']++;
                    }
                    $summary['total_menit_terlambat'] += $late_minutes;
                    $summary['total_denda'] += $summary['salary'] * $this->late_penalty_percentage($late_minutes, $rules) / 100;
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

        $total_fine = array_sum(array_map(function ($row) {
            return (float) $row['total_denda'];
        }, $rows));

        $this->load->view('admin/attendance_monthly/index', array(
            'title' => 'Rekap Absensi Bulanan',
            'month' => $month,
            'workdays' => $workdays,
            'period_start' => $period_start->format('Y-m-d'),
            'period_end' => (clone $period_end)->modify('-1 day')->format('Y-m-d'),
            'workin_day' => $config['workin_day'],
            'holiday_count' => count($holidays),
            'employee_count' => count($rows),
            'total_fine' => $total_fine,
            'rows' => $rows,
        ));
    }

    public function detail($employee_id)
    {
        $month = $this->input->get('month', true) ?: date('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        $employee = $this->db->select('id, employee_code, name')->where('id', (int) $employee_id)
            ->where('is_active', 1)->get('employees')->row_array();
        if (!$employee) {
            show_404();
        }

        $config = $this->read_app_config();
        $rules = $config['rule_absensi'];
        $work_days = $this->work_days_from_config($config['workin_day']);
        $period_end = $this->cutoff_period_end($month, (int) $config['cut_off_absensi']);
        $period_start = (clone $period_end)->modify('-1 month');
        $calculation_end = clone $period_end;
        $tomorrow = new DateTime('tomorrow 00:00:00');
        if ($tomorrow < $calculation_end) {
            $calculation_end = $tomorrow;
        }

        $holiday_rows = $this->db->where('is_active', 1)
            ->where('holiday_date >=', $period_start->format('Y-m-d'))
            ->where('holiday_date <', $period_end->format('Y-m-d'))
            ->get('holidays')->result_array();
        $holidays = array();
        foreach ($holiday_rows as $holiday) {
            $holidays[$holiday['holiday_date']] = $holiday['name'];
        }

        $entry_start = $this->normalize_time($rules['mulai_masuk']);
        $entry_end = $this->normalize_time($rules['akhir_masuk']);
        $exit_start = $this->normalize_time($rules['mulai_pulang']);
        $exit_end = $this->normalize_time($rules['akhir_pulang']);
        $punches = $this->db->select('timestamp')->from('presensi')
            ->where('user_id', $employee['employee_code'])
            ->where('timestamp >=', $period_start->format('Y-m-d H:i:s'))
            ->where('timestamp <', $period_end->format('Y-m-d H:i:s'))
            ->order_by('timestamp', 'ASC')->get()->result_array();

        $daily = array();
        foreach ($punches as $punch) {
            $timestamp = new DateTime($punch['timestamp']);
            $date = $timestamp->format('Y-m-d');
            if (!isset($daily[$date])) {
                $daily[$date] = array('check_in' => null, 'check_out' => null);
            }
            $entry_window_start = new DateTime($date . ' ' . $entry_start);
            $entry_window_start->modify('-2 hours');
            $entry_window_end = new DateTime($date . ' ' . $entry_end);
            $exit_window_start = new DateTime($date . ' ' . $exit_start);
            $exit_window_end = new DateTime($date . ' ' . $exit_end);
            if ($timestamp >= $entry_window_start && $timestamp <= $entry_window_end && $daily[$date]['check_in'] === null) {
                $daily[$date]['check_in'] = $timestamp;
            }
            if ($timestamp >= $exit_window_start && $timestamp < $exit_window_end) {
                $daily[$date]['check_out'] = $timestamp;
            }
        }

        $this->load->model('Employee_model');
        $salary_bases = $this->Employee_model->salary_bases(array((int) $employee['id']), $config['potong_gapok'], $period_start->format('Y-m-d'));
        $salary = isset($salary_bases[(int) $employee['id']]) ? (float) $salary_bases[(int) $employee['id']] : 0;
        $rows = array();
        for ($day = clone $period_start; $day < $calculation_end; $day->modify('+1 day')) {
            $date = $day->format('Y-m-d');
            $is_workday = in_array((int) $day->format('N'), $work_days, true);
            if (!$is_workday && !isset($holidays[$date])) {
                continue;
            }
            if (isset($holidays[$date])) {
                $rows[] = array('date' => $date, 'day_name' => $this->indonesian_day_name($day), 'status' => 'HARI LIBUR', 'holiday_name' => $holidays[$date], 'check_in' => null, 'check_out' => null, 'late_minutes' => 0, 'fine' => 0);
                continue;
            }

            $attendance = isset($daily[$date]) ? $daily[$date] : array('check_in' => null, 'check_out' => null);
            $late_minutes = 0;
            $fine = 0;
            if ($attendance['check_in'] !== null) {
                $entry_time = new DateTime($date . ' ' . $entry_start);
                $late_minutes = max(0, (int) floor(($attendance['check_in']->getTimestamp() - $entry_time->getTimestamp()) / 60));
                $fine = $salary * $this->late_penalty_percentage($late_minutes, $rules) / 100;
            }
            $rows[] = array(
                'date' => $date,
                'day_name' => $this->indonesian_day_name($day),
                'status' => $attendance['check_in'] === null ? 'TIDAK HADIR' : ($late_minutes > 0 ? 'TERLAMBAT' : 'HADIR'),
                'holiday_name' => '',
                'check_in' => $attendance['check_in'],
                'check_out' => $attendance['check_out'],
                'late_minutes' => $late_minutes,
                'fine' => $fine,
            );
        }

        $total_fine = 0;
        $late_days = 0;
        $absent_days = 0;
        foreach ($rows as $row) {
            $total_fine += (float) $row['fine'];
            if ($row['status'] === 'TERLAMBAT') {
                $late_days++;
            } elseif ($row['status'] === 'TIDAK HADIR') {
                $absent_days++;
            }
        }

        $this->load->view('admin/attendance_monthly/detail', array(
            'title' => 'Detail Absensi Bulanan',
            'employee' => $employee,
            'month' => $month,
            'period_start' => $period_start->format('Y-m-d'),
            'period_end' => (clone $period_end)->modify('-1 day')->format('Y-m-d'),
            'salary' => $salary,
            'total_fine' => $total_fine,
            'late_days' => $late_days,
            'absent_days' => $absent_days,
            'rows' => $rows,
        ));
    }

    private function count_workdays(DateTime $start, DateTime $end, array $holidays, array $work_days)
    {
        $total = 0;
        for ($day = clone $start; $day < $end; $day->modify('+1 day')) {
            if (in_array((int) $day->format('N'), $work_days, true) && !isset($holidays[$day->format('Y-m-d')])) {
                $total++;
            }
        }
        return $total;
    }

    private function cutoff_period_end($month, $cut_off_absensi)
    {
        $month_start = new DateTime($month . '-01 00:00:00');
        $last_day = (int) $month_start->format('t');
        $cutoff_day = min(max(1, $cut_off_absensi), $last_day);
        return (new DateTime($month . '-' . sprintf('%02d', $cutoff_day) . ' 00:00:00'))->modify('+1 day');
    }

    private function work_days_from_config($workin_day)
    {
        $work_days = array(
            'senin_jumat' => array(1, 2, 3, 4, 5),
            'senin_sabtu' => array(1, 2, 3, 4, 5, 6),
            'senin_minggu' => array(1, 2, 3, 4, 5, 6, 7),
        );
        return isset($work_days[$workin_day]) ? $work_days[$workin_day] : $work_days['senin_jumat'];
    }

    private function read_app_config()
    {
        $defaults = array(
            'potong_gapok' => true,
            'cut_off_absensi' => 15,
            'workin_day' => 'senin_jumat',
            'rule_absensi' => array(
                'mulai_masuk' => 6,
                'akhir_masuk' => 9,
                'mulai_pulang' => 17,
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
        if (!in_array($config['workin_day'], array('senin_jumat', 'senin_sabtu', 'senin_minggu'), true)) {
            $config['workin_day'] = $defaults['workin_day'];
        }
        $config['cut_off_absensi'] = (int) $config['cut_off_absensi'];
        if ($config['cut_off_absensi'] < 1 || $config['cut_off_absensi'] > 31) {
            $config['cut_off_absensi'] = $defaults['cut_off_absensi'];
        }
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

    private function indonesian_day_name(DateTime $date)
    {
        return array('', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')[(int) $date->format('N')];
    }
}
