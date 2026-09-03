<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_engine
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('Employee_model');
        $this->CI->load->model('Work_shift_model');
    }

    public function process_date($date)
    {
        $raw_rows = $this->CI->db->select('user_id, device_sn, timestamp')
            ->from('presensi')
            ->where('timestamp >=', $date . ' 00:00:00')
            ->where('timestamp <=', $date . ' 23:59:59')
            ->order_by('timestamp', 'ASC')
            ->get()->result_array();

        $employee_punches = array();
        foreach ($raw_rows as $raw) {
            $employee = $this->CI->Employee_model->find_by_code($raw['user_id']);
            if (!$employee) {
                $employee = $this->find_by_mapping($raw['user_id'], $raw['device_sn']);
            }
            if (!$employee) {
                continue;
            }
            $employee_id = (int) $employee['id'];
            if (!isset($employee_punches[$employee_id])) {
                $employee_punches[$employee_id] = array('employee' => $employee, 'check_in' => $raw['timestamp'], 'check_out' => $raw['timestamp']);
            } else {
                $employee_punches[$employee_id]['check_out'] = $raw['timestamp'];
            }
        }

        $processed = 0;
        $this->CI->db->trans_begin();
        foreach ($employee_punches as $punches) {
            $employee = $punches['employee'];
            $raw = array('check_in' => $punches['check_in'], 'check_out' => $punches['check_out']);

            $shift = $this->CI->Work_shift_model->find_for_employee($employee['id'], $date);
            $values = $this->calculate($date, $raw, $shift);
            $values['employee_id'] = $employee['id'];
            $values['attendance_date'] = $date;
            $values['shift_id'] = $shift ? $shift['id'] : null;
            $values['source'] = 'FINGERPRINT';
            $values['processed_at'] = date('Y-m-d H:i:s');

            $exists = $this->CI->db->where('employee_id', $employee['id'])
                ->where('attendance_date', $date)->get('attendance_daily')->row_array();
            if ($exists) {
                $this->CI->db->where('id', $exists['id'])->update('attendance_daily', $values);
            } else {
                $this->CI->db->insert('attendance_daily', $values);
            }
            $processed++;
        }

        if (!$this->CI->db->trans_status()) {
            $this->CI->db->trans_rollback();
            throw new RuntimeException('Attendance processing failed');
        }
        $this->CI->db->trans_commit();
        return $processed;
    }

    protected function find_by_mapping($fingerprint_user_id, $device_sn)
    {
        return $this->CI->db->select('e.*')->from('employee_fingerprint_mappings m')
            ->join('employees e', 'e.id = m.employee_id')
            ->where('m.fingerprint_user_id', $fingerprint_user_id)
            ->where('m.is_active', 1)
            ->group_start()->where('m.device_sn', $device_sn)->or_where('m.device_sn IS NULL', null, false)->group_end()
            ->where('e.is_active', 1)->get()->row_array();
    }

    protected function calculate($date, array $raw, $shift)
    {
        $check_in = $raw['check_in'];
        $check_out = $raw['check_out'];
        $late = 0;
        $early = 0;
        $overtime = 0;
        $status = 'HADIR';

        if ($shift) {
            $scheduled_in = new DateTime($date . ' ' . $shift['check_in_time']);
            $scheduled_out = new DateTime($date . ' ' . $shift['check_out_time']);
            $actual_in = new DateTime($check_in);
            $actual_out = new DateTime($check_out);
            $late = max(0, (int) floor(($actual_in->getTimestamp() - $scheduled_in->getTimestamp()) / 60) - (int) $shift['late_tolerance_minutes']);
            if ((int) $shift['early_leave_enabled'] === 1) {
                $early = max(0, (int) floor(($scheduled_out->getTimestamp() - $actual_out->getTimestamp()) / 60));
            }
            $extra = max(0, (int) floor(($actual_out->getTimestamp() - $scheduled_out->getTimestamp()) / 60));
            $overtime = $extra >= (int) $shift['minimum_overtime_minutes'] ? $extra : 0;
            $status = $late > 0 ? 'TERLAMBAT' : 'HADIR';
        }

        return array('check_in' => $check_in, 'check_out' => $check_out, 'late_minutes' => $late, 'early_leave_minutes' => $early, 'overtime_minutes' => $overtime, 'attendance_status' => $status);
    }
}
