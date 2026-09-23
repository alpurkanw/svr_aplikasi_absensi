<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_model extends CI_Model
{
    protected $period_table = 'payroll_periods';

    public function find_period($period)
    {
        $parts = $this->period_parts($period);
        if (!$parts) {
            return null;
        }

        return $this->db->where('period_year', $parts['year'])
            ->where('period_month', $parts['month'])
            ->get($this->period_table)->row_array();
    }

    // Di dalam Payroll_model.php
    public function check_periode_exists($bulan, $tahun)
    {
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);
        // Jika tabel Anda menggunakan kolom 'periode' bertipe string (misal: '2026-09'), sesuaikan query-nya:
        // $this->db->where('periode', $periode);

        return $this->db->get('nama_tabel_payroll')->num_rows();
    }

    public function ensure_period($period)
    {
        $parts = $this->period_parts($period);
        if (!$parts) {
            return null;
        }

        $existing = $this->find_period($period);
        if ($existing) {
            return $existing;
        }

        $has_payslips = $this->db->where('period_year', $parts['year'])
            ->where('period_month', $parts['month'])
            ->count_all_results('trx_payslip') > 0;
        $this->db->insert($this->period_table, array(
            'period_year' => $parts['year'],
            'period_month' => $parts['month'],
            'period_label' => $parts['label'],
            'period_start' => $parts['start'],
            'period_end' => $parts['end'],
            'status' => $has_payslips ? 'REVIEW' : 'DRAFT',
            'processed_at' => $has_payslips ? date('Y-m-d H:i:s') : null,
        ));

        return $this->find_period($period);
    }

    public function review_rows($period)
    {
        $parts = $this->period_parts($period);
        if (!$parts) {
            return array();
        }

        return $this->db->select('e.id, e.employee_code, e.name, e.position_name, e.department_name,
                SUM(CASE WHEN tp.component_type = "EARNING" THEN tp.amount ELSE 0 END) AS total_earning,
                SUM(CASE WHEN tp.component_type = "DEDUCTION" THEN tp.amount ELSE 0 END) AS total_deduction,
                COUNT(DISTINCT tp.component_id) AS component_count,
                COALESCE(ad.late_minutes, 0) AS late_minutes,
                COALESCE(ad.early_leave_minutes, 0) AS early_leave_minutes,
                COALESCE(ad.overtime_minutes, 0) AS overtime_minutes', false)
            ->from('trx_payslip tp')
            ->join('employees e', 'e.id = tp.employee_id')
            ->join('(SELECT employee_id,
                        SUM(late_minutes) AS late_minutes,
                        SUM(early_leave_minutes) AS early_leave_minutes,
                        SUM(overtime_minutes) AS overtime_minutes
                    FROM attendance_daily
                    WHERE attendance_date >= ' . $this->db->escape($parts['start']) . '
                    AND attendance_date <= ' . $this->db->escape($parts['end']) . '
                    GROUP BY employee_id) ad', 'ad.employee_id = e.id', 'left', false)
            ->where('tp.period_year', $parts['year'])
            ->where('tp.period_month', $parts['month'])
            ->group_by('e.id')
            ->order_by('e.name', 'ASC')
            ->get()->result_array();
    }

    public function attendance_stats($period)
    {
        $parts = $this->period_parts($period);
        if (!$parts) {
            return array('attendance_rows' => 0, 'employees' => 0, 'late_minutes' => 0, 'early_leave_minutes' => 0, 'overtime_minutes' => 0);
        }

        $row = $this->db->select('COUNT(*) AS attendance_rows, COUNT(DISTINCT employee_id) AS employees,
                COALESCE(SUM(late_minutes), 0) AS late_minutes,
                COALESCE(SUM(early_leave_minutes), 0) AS early_leave_minutes,
                COALESCE(SUM(overtime_minutes), 0) AS overtime_minutes', false)
            ->where('attendance_date >=', $parts['start'])
            ->where('attendance_date <=', $parts['end'])
            ->get('attendance_daily')->row_array();

        return array(
            'attendance_rows' => (int) $row['attendance_rows'],
            'employees' => (int) $row['employees'],
            'late_minutes' => (int) $row['late_minutes'],
            'early_leave_minutes' => (int) $row['early_leave_minutes'],
            'overtime_minutes' => (int) $row['overtime_minutes'],
        );
    }

    public function is_finalized($period)
    {
        $row = $this->find_period($period);
        return $row && $row['status'] === 'FINALIZED';
    }

    public function mark_processing($period)
    {
        $row = $this->ensure_period($period);
        if (!$row || $row['status'] === 'FINALIZED') {
            return false;
        }

        return (bool) $this->db->where('id', (int) $row['id'])
            ->update($this->period_table, array('status' => 'PROCESSING'));
    }

    public function mark_failed($period)
    {
        $row = $this->find_period($period);
        if ($row && $row['status'] !== 'FINALIZED') {
            $this->db->where('id', (int) $row['id'])
                ->update($this->period_table, array('status' => 'DRAFT'));
        }
    }

    public function calculate_period($period)
    {
        $parts = $this->period_parts($period);
        $row = $this->find_period($period);
        if (!$parts || !$row || $row['status'] === 'FINALIZED') {
            throw new RuntimeException('Periode payroll tidak valid atau sudah final.');
        }

        $employees = $this->db->where('is_active', 1)
            ->where('payroll_status', 1)
            ->order_by('name', 'ASC')
            ->get('employees')->result_array();
        $components = $this->db->where('is_active', 1)
            ->order_by('component_type', 'ASC')
            ->order_by('sort_order', 'ASC')
            ->order_by('id', 'ASC')
            ->get('payroll_components')->result_array();
        if (!$employees || !$components) {
            throw new RuntimeException('Karyawan atau komponen payroll aktif belum tersedia.');
        }

        $this->db->trans_begin();
        foreach ($employees as $employee) {
            $details = $this->salary_details((int) $employee['id'], $parts['end']);
            $attendance = $this->attendance_totals((int) $employee['id'], $parts['start'], $parts['end']);
            $amounts = array();
            foreach ($details as $detail) {
                $amount = (float) $detail['amount'];
                if ($detail['calculation_type'] === 'PER_MINUTE' && $detail['component_type'] === 'EARNING') {
                    $amount *= $attendance['overtime_minutes'];
                }
                $amounts[(int) $detail['component_id']] = $amount;
            }

            $this->db->where('employee_id', (int) $employee['id'])
                ->where('period_year', $parts['year'])
                ->where('period_month', $parts['month'])
                ->delete('trx_payslip');

            foreach ($components as $component) {
                $component_id = (int) $component['id'];
                if (!array_key_exists($component_id, $amounts)) {
                    continue;
                }
                $this->db->insert('trx_payslip', array(
                    'employee_id' => (int) $employee['id'],
                    'period_year' => $parts['year'],
                    'period_month' => $parts['month'],
                    'period_label' => $parts['label'],
                    'component_id' => $component_id,
                    'component_code' => $component['code'],
                    'component_name' => $component['name'],
                    'component_type' => $component['component_type'],
                    'sort_order' => (int) $component['sort_order'],
                    'amount' => $amounts[$component_id],
                ));
            }
        }

        $this->db->where('id', (int) $row['id'])->update($this->period_table, array(
            'status' => 'REVIEW',
            'processed_at' => date('Y-m-d H:i:s'),
        ));
        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            throw new RuntimeException('Data payroll gagal dihitung.');
        }
        $this->db->trans_commit();
        return count($employees);
    }

    public function finalize_period($period, $user_id)
    {
        $row = $this->find_period($period);
        if (!$row || $row['status'] === 'FINALIZED') {
            return false;
        }
        if ($row['status'] !== 'REVIEW') {
            return false;
        }
        $has_results = $this->db->where('period_year', (int) $row['period_year'])
            ->where('period_month', (int) $row['period_month'])
            ->count_all_results('trx_payslip') > 0;
        if (!$has_results) {
            return false;
        }

        return (bool) $this->db->where('id', (int) $row['id'])->update($this->period_table, array(
            'status' => 'FINALIZED',
            'finalized_at' => date('Y-m-d H:i:s'),
            'finalized_by' => (int) $user_id,
        ));
    }

    private function salary_details($employee_id, $effective_date)
    {
        return $this->db->select('epc.component_id, epc.amount, pc.component_type, pc.calculation_type')
            ->from('employee_payroll_components epc')
            ->join('payroll_components pc', 'pc.id = epc.component_id')
            ->where('epc.employee_id', $employee_id)
            ->where('pc.is_active', 1)
            ->where('epc.effective_from <=', $effective_date)
            ->group_start()
            ->where('epc.effective_until IS NULL', null, false)
            ->or_where('epc.effective_until >=', $effective_date)
            ->group_end()
            ->get()->result_array();
    }

    private function attendance_totals($employee_id, $start, $end)
    {
        $row = $this->db->select('COALESCE(SUM(late_minutes), 0) AS late_minutes,
                COALESCE(SUM(early_leave_minutes), 0) AS early_leave_minutes,
                COALESCE(SUM(overtime_minutes), 0) AS overtime_minutes', false)
            ->where('employee_id', $employee_id)
            ->where('attendance_date >=', $start)
            ->where('attendance_date <=', $end)
            ->get('attendance_daily')->row_array();
        return array(
            'late_minutes' => (int) $row['late_minutes'],
            'early_leave_minutes' => (int) $row['early_leave_minutes'],
            'overtime_minutes' => (int) $row['overtime_minutes'],
        );
    }

    private function period_parts($period)
    {
        $date = DateTime::createFromFormat('!Y-m', (string) $period);
        $errors = DateTime::getLastErrors();
        if (!$date || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) || $date->format('Y-m') !== $period) {
            return null;
        }
        $months = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        return array(
            'year' => (int) $date->format('Y'),
            'month' => (int) $date->format('m'),
            'label' => $months[(int) $date->format('m')] . ' ' . $date->format('Y'),
            'start' => $date->format('Y-m-01'),
            'end' => $date->format('Y-m-t'),
        );
    }
}
