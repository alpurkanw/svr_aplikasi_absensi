<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'ADMIN') {
            redirect('Auth/open_f_login');
        }
        $this->load->model('Payroll_model');
        $this->load->library('attendance_engine');
    }

    public function index()
    {
        $period = trim($this->input->get('period', true)) ?: date('Y-m');
        $period_row = $this->Payroll_model->ensure_period($period);
        if (!$period_row) {
            $period = date('Y-m');
            $period_row = $this->Payroll_model->ensure_period($period);
        }

        $this->load->view('admin/payroll/index', array(
            'title' => 'Payroll',
            'period' => $period,
            'period_row' => $period_row,
            'rows' => $this->Payroll_model->review_rows($period),
            'attendance' => $this->Payroll_model->attendance_stats($period),
        ));
    }

    public function process()
    {
        $period = trim($this->input->post('period', true));
        $period_row = $this->Payroll_model->ensure_period($period);
        if (!$period_row) {
            return $this->respond_error('Periode payroll tidak valid.');
        }
        if ($period_row['status'] === 'FINALIZED') {
            return $this->respond_error('Periode payroll sudah final dan terkunci.');
        }

        try {
            $this->Payroll_model->mark_processing($period);
            $start = new DateTime($period_row['period_start']);
            $end = new DateTime($period_row['period_end']);
            $today = new DateTime(date('Y-m-d'));
            if ($end > $today) {
                $end = $today;
            }
            for ($date = clone $start; $date <= $end; $date->modify('+1 day')) {
                $this->attendance_engine->process_date($date->format('Y-m-d'));
            }
            $employee_count = $this->Payroll_model->calculate_period($period);
            $this->session->set_flashdata('success', 'Payroll berhasil dihitung untuk ' . $employee_count . ' karyawan.');
        } catch (Throwable $exception) {
            $this->Payroll_model->mark_failed($period);
            log_message('error', 'Payroll process error: ' . $exception->getMessage());
            $this->session->set_flashdata('error', 'Payroll gagal diproses: ' . $exception->getMessage());
        }
        redirect('admin/payroll?period=' . rawurlencode($period));
    }

    public function finalize()
    {
        $period = trim($this->input->post('period', true));
        if ($this->Payroll_model->finalize_period($period, (int) $this->session->userdata('id'))) {
            $this->session->set_flashdata('success', 'Payroll berhasil difinalisasi dan dikunci.');
        } else {
            $this->session->set_flashdata('error', 'Payroll belum siap difinalisasi atau sudah terkunci.');
        }
        redirect('admin/payroll?period=' . rawurlencode($period));
    }

    private function respond_error($message)
    {
        $this->session->set_flashdata('error', $message);
        redirect('admin/payroll');
    }
}
