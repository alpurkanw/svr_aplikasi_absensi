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
        $rows = $this->db->select('a.*, e.employee_code, e.name')->from('attendance_daily a')
            ->join('employees e', 'e.id = a.employee_id')->where('a.attendance_date', $date)
            ->order_by('e.name', 'ASC')->get()->result_array();
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
}
