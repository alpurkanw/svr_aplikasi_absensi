<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_reports extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'ADMIN') {
            redirect('Auth/open_f_login');
        }
        $this->load->model('Payroll_reports_model');
    }

    public function summary()
    {
        $period = $this->valid_period($this->input->get('period', true));
        $parts = $this->Payroll_reports_model->period_parts($period);
        $period_row = $this->Payroll_reports_model->period_row($parts);
        $this->load->view('admin/payroll_reports/summary', array(
            'title' => 'Rekap Payroll Periode',
            'period' => $period,
            'parts' => $parts,
            'period_row' => $period_row,
            'summary' => $this->Payroll_reports_model->summary($parts),
            'rows' => $this->Payroll_reports_model->summary_rows($parts),
        ));
    }

    public function issues()
    {
        $period = $this->valid_period($this->input->get('period', true));
        $parts = $this->Payroll_reports_model->period_parts($period);
        $period_row = $this->Payroll_reports_model->period_row($parts);
        $status = $period_row ? $period_row['status'] : 'DRAFT';
        $this->load->view('admin/payroll_reports/issues', array(
            'title' => 'Karyawan Bermasalah',
            'period' => $period,
            'parts' => $parts,
            'period_status' => $status,
            'rows' => $this->Payroll_reports_model->issue_rows($parts, $status),
        ));
    }

    public function deductions()
    {
        $period = $this->valid_period($this->input->get('period', true));
        $parts = $this->Payroll_reports_model->period_parts($period);
        $period_row = $this->Payroll_reports_model->period_row($parts);
        $rows = $this->Payroll_reports_model->deduction_rows($parts);
        $total = 0;
        foreach ($rows as $row) {
            $total += (float) $row['total_amount'];
        }
        $this->load->view('admin/payroll_reports/deductions', array(
            'title' => 'Deduction Berdasarkan Jenis',
            'period' => $period,
            'parts' => $parts,
            'period_row' => $period_row,
            'rows' => $rows,
            'total' => $total,
        ));
    }

    private function valid_period($period)
    {
        $period = trim((string) $period);
        if (!$this->Payroll_reports_model->period_parts($period)) {
            return date('Y-m');
        }
        return $period;
    }
}
