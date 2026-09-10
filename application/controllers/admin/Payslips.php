<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payslips extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'ADMIN') {
            redirect('Auth/open_f_login');
        }
        $this->load->model('Employee_model');
    }

    public function input()
    {
        $employees = $this->Employee_model->all(true);
        $employees_with_components = array();
        foreach ($employees as $employee) {
            if ($this->Employee_model->salary_details((int) $employee['id'])) {
                $employees_with_components[] = $employee;
            }
        }

        $this->load->view('admin/payslips/input', array(
            'title' => 'Input Slip Gaji',
            'employees' => $employees_with_components,
            'ready_count' => count($employees_with_components),
            'without_components_count' => count($employees) - count($employees_with_components),
            'period' => date('Y-m'),
        ));
    }

    public function generate()
    {
        $this->output->set_content_type('application/json');
        $period = trim($this->input->post('period', true));
        $employee_ids = $this->input->post('employee_ids');
        $employee_ids = is_array($employee_ids) ? array_values(array_unique(array_map('intval', $employee_ids))) : array();

        $date = DateTime::createFromFormat('!Y-m', $period);
        $errors = DateTime::getLastErrors();
        if (!$date || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) || $date->format('Y-m') !== $period) {
            return $this->output->set_status_header(422)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Periode harus menggunakan format bulan yang valid.'
            )));
        }
        if (!$employee_ids) {
            return $this->output->set_status_header(422)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Pilih minimal satu karyawan.'
            )));
        }

        $year = (int) $date->format('Y');
        $month = (int) $date->format('m');
        $months = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $period_label = $months[$month] . ' ' . $year;
        $employees = $this->db->where_in('id', $employee_ids)->where('is_active', 1)->get('employees')->result_array();
        $components = $this->db->where('is_active', 1)->order_by('component_type', 'ASC')->order_by('sort_order', 'ASC')->order_by('id', 'ASC')->get('payroll_components')->result_array();

        if (count($employees) !== count($employee_ids)) {
            return $this->output->set_status_header(422)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Ada karyawan yang tidak ditemukan atau sudah tidak aktif.'
            )));
        }
        if (!$components) {
            return $this->output->set_status_header(422)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Belum ada komponen gaji aktif.'
            )));
        }

        $this->db->trans_begin();
        foreach ($employees as $employee) {
            $salary_details = $this->Employee_model->salary_details((int) $employee['id']);
            $amounts = array();
            foreach ($salary_details as $detail) {
                $amounts[(int) $detail['component_id']] = (float) $detail['amount'];
            }

            $this->db->where('employee_id', (int) $employee['id'])
                ->where('period_year', $year)
                ->where('period_month', $month)
                ->delete('trx_payslip');

            foreach ($components as $component) {
                if (!array_key_exists((int) $component['id'], $amounts)) {
                    continue;
                }
                $this->db->insert('trx_payslip', array(
                    'employee_id' => (int) $employee['id'],
                    'period_year' => $year,
                    'period_month' => $month,
                    'period_label' => $period_label,
                    'component_id' => (int) $component['id'],
                    'component_code' => $component['code'],
                    'component_name' => $component['name'],
                    'component_type' => $component['component_type'],
                    'sort_order' => (int) $component['sort_order'],
                    'amount' => $amounts[(int) $component['id']],
                ));
            }
        }

        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            return $this->output->set_status_header(500)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Data slip gaji gagal dibuat.'
            )));
        }
        $this->db->trans_commit();

        return $this->output->set_output(json_encode(array(
            'success' => true,
            'message' => 'Slip gaji berhasil dibuat untuk ' . count($employees) . ' karyawan periode ' . $period_label . '.'
        )));
    }

    public function view()
    {
        $period = trim($this->input->get_post('period', true));
        if ($period === '') {
            $period = date('Y-m');
        }

        $date = DateTime::createFromFormat('!Y-m', $period);
        $errors = DateTime::getLastErrors();
        if (!$date || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) || $date->format('Y-m') !== $period) {
            $period = date('Y-m');
            $date = DateTime::createFromFormat('!Y-m', $period);
        }

        $year = (int) $date->format('Y');
        $month = (int) $date->format('m');
        $employees = $this->db
            ->select('e.id, e.employee_code, e.name, e.position_name, e.department_name, e.employment_status, MAX(tp.period_label) AS period_label, SUM(CASE WHEN tp.component_type = "EARNING" THEN tp.amount ELSE 0 END) AS total_earning, SUM(CASE WHEN tp.component_type = "DEDUCTION" THEN tp.amount ELSE 0 END) AS total_deduction', false)
            ->from('trx_payslip tp')
            ->join('employees e', 'e.id = tp.employee_id')
            ->where('tp.period_year', $year)
            ->where('tp.period_month', $month)
            ->group_by('e.id')
            ->order_by('e.name', 'ASC')
            ->get()
            ->result_array();

        $months = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $this->load->view('admin/payslips/view', array(
            'title' => 'Lihat Slip Gaji',
            'period' => $period,
            'period_label' => $months[$month] . ' ' . $year,
            'employees' => $employees,
        ));
    }

    private function slip_data($employee_id, $period)
    {
        $date = DateTime::createFromFormat('!Y-m', $period);
        if (!$date || $date->format('Y-m') !== $period) {
            return null;
        }
        $employee = $this->db->where('id', (int) $employee_id)->get('employees')->row_array();
        $rows = $this->db
            ->where('employee_id', (int) $employee_id)
            ->where('period_year', (int) $date->format('Y'))
            ->where('period_month', (int) $date->format('m'))
            ->order_by('component_type', 'ASC')
            ->order_by('sort_order', 'ASC')
            ->get('trx_payslip')
            ->result_array();
        if (!$employee || !$rows) {
            return null;
        }
        $earnings = array();
        $deductions = array();
        foreach ($rows as $row) {
            if ($row['component_type'] === 'EARNING') {
                $earnings[] = $row;
            } else {
                $deductions[] = $row;
            }
        }
        return array(
            'employee' => $employee,
            'period' => $date->format('Y-m'),
            'period_label' => $rows[0]['period_label'],
            'earnings' => $earnings,
            'deductions' => $deductions,
            'total_earning' => array_sum(array_column($earnings, 'amount')),
            'total_deduction' => array_sum(array_column($deductions, 'amount')),
        );
    }

    public function detail($employee_id)
    {
        $period = trim($this->input->get('period', true));
        $data = $this->slip_data($employee_id, $period);
        if (!$data) {
            return $this->output->set_status_header(404)->set_output('Slip gaji tidak ditemukan.');
        }
        $this->load->view('admin/payslips/_slip', $data);
    }

    public function print_slip($employee_id)
    {
        $period = trim($this->input->get('period', true));
        $data = $this->slip_data($employee_id, $period);
        if (!$data) {
            show_404();
        }
        $data['title'] = 'Slip Gaji - ' . $data['employee']['name'];
        $this->load->view('admin/payslips/print', $data);
    }
}
