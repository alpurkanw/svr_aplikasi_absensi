<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_reports_model extends CI_Model
{
    public function period_parts($period)
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

    public function period_row($parts)
    {
        return $this->db->where('period_year', $parts['year'])
            ->where('period_month', $parts['month'])
            ->get('payroll_periods')->row_array();
    }

    public function summary($parts)
    {
        $row = $this->db->select('COUNT(DISTINCT employee_id) AS employee_count,
                COALESCE(SUM(CASE WHEN component_type = "EARNING" THEN amount ELSE 0 END), 0) AS total_earning,
                COALESCE(SUM(CASE WHEN component_type = "DEDUCTION" THEN amount ELSE 0 END), 0) AS total_deduction', false)
            ->where('period_year', $parts['year'])
            ->where('period_month', $parts['month'])
            ->get('trx_payslip')->row_array();

        $row['employee_count'] = (int) $row['employee_count'];
        $row['total_earning'] = (float) $row['total_earning'];
        $row['total_deduction'] = (float) $row['total_deduction'];
        $row['net_salary'] = $row['total_earning'] - $row['total_deduction'];
        return $row;
    }

    public function summary_rows($parts)
    {
        return $this->db->select('e.employee_code, e.name, e.department_name,
                SUM(CASE WHEN tp.component_type = "EARNING" THEN tp.amount ELSE 0 END) AS total_earning,
                SUM(CASE WHEN tp.component_type = "DEDUCTION" THEN tp.amount ELSE 0 END) AS total_deduction', false)
            ->from('trx_payslip tp')
            ->join('employees e', 'e.id = tp.employee_id')
            ->where('tp.period_year', $parts['year'])
            ->where('tp.period_month', $parts['month'])
            ->group_by('e.id')
            ->order_by('e.name', 'ASC')
            ->get()->result_array();
    }

    public function issue_rows($parts, $period_status)
    {
        $start = $this->db->escape($parts['start']);
        $end = $this->db->escape($parts['end']);
        $year = (int) $parts['year'];
        $month = (int) $parts['month'];
        $rows = $this->db->select('e.id, e.employee_code, e.name, e.department_name, e.bank_name, e.bank_account_number,
                COALESCE(pc.component_count, 0) AS component_count,
                COALESCE(ad.attendance_count, 0) AS attendance_count,
                COALESCE(ps.payslip_count, 0) AS payslip_count', false)
            ->from('employees e')
            ->join('(SELECT epc.employee_id, COUNT(DISTINCT epc.component_id) AS component_count
                FROM employee_payroll_components epc
                JOIN payroll_components pc ON pc.id = epc.component_id AND pc.is_active = 1
                WHERE epc.effective_from <= ' . $end . '
                AND (epc.effective_until IS NULL OR epc.effective_until >= ' . $start . ')
                GROUP BY epc.employee_id) pc', 'pc.employee_id = e.id', 'left', false)
            ->join('(SELECT employee_id, COUNT(*) AS attendance_count
                FROM attendance_daily
                WHERE attendance_date >= ' . $start . ' AND attendance_date <= ' . $end . '
                GROUP BY employee_id) ad', 'ad.employee_id = e.id', 'left', false)
            ->join('(SELECT employee_id, COUNT(*) AS payslip_count
                FROM trx_payslip
                WHERE period_year = ' . $year . ' AND period_month = ' . $month . '
                GROUP BY employee_id) ps', 'ps.employee_id = e.id', 'left', false)
            ->where('e.is_active', 1)
            ->where('e.payroll_status', 1)
            ->order_by('e.name', 'ASC')
            ->get()->result_array();

        foreach ($rows as &$row) {
            $issues = array();
            if ((int) $row['component_count'] === 0) {
                $issues[] = 'Belum memiliki komponen gaji aktif';
            }
            if ($period_status !== 'DRAFT' && (int) $row['payslip_count'] === 0) {
                $issues[] = 'Belum memiliki hasil payroll periode ini';
            }
            if ($period_status !== 'DRAFT' && (int) $row['attendance_count'] === 0) {
                $issues[] = 'Belum memiliki data attendance periode ini';
            }
            if (trim((string) $row['bank_account_number']) === '') {
                $issues[] = 'Nomor rekening belum diisi';
            }
            $row['issues'] = $issues;
        }
        unset($row);

        return array_values(array_filter($rows, function ($row) {
            return count($row['issues']) > 0;
        }));
    }

    public function deduction_rows($parts)
    {
        return $this->db->select('component_code, component_name,
                COUNT(DISTINCT employee_id) AS employee_count,
                SUM(amount) AS total_amount', false)
            ->from('trx_payslip')
            ->where('period_year', $parts['year'])
            ->where('period_month', $parts['month'])
            ->where('component_type', 'DEDUCTION')
            ->group_by(array('component_id', 'component_code', 'component_name'))
            ->order_by('total_amount', 'DESC')
            ->get()->result_array();
    }
}
