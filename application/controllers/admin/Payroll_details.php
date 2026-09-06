<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_details extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'ADMIN') {
            redirect('Auth/open_f_login');
        }
        $this->load->model('Employee_model');
    }

    public function index()
    {
        $this->load->view('admin/payroll_details/index', array(
            'title' => 'Detail Komponen Gaji',
            'employees' => $this->Employee_model->all(true),
        ));
    }

    public function edit($employee_id)
    {
        $employee = $this->Employee_model->find_by_id($employee_id);
        if (!$employee) {
            show_404();
        }

        $components = $this->db->where('is_active', 1)
            ->order_by('component_type', 'ASC')
            ->order_by('id', 'ASC')
            ->get('payroll_components')->result_array();
        $saved = $this->Employee_model->salary_details($employee_id);
        $amounts = array();
        foreach ($saved as $row) {
            $amounts[(int) $row['component_id']] = (float) $row['amount'];
        }

        $this->load->view('admin/payroll_details/edit', array(
            'title' => 'Detail Komponen Gaji',
            'employee' => $employee,
            'components' => $components,
            'amounts' => $amounts,
        ));
    }

    public function save($employee_id)
    {
        $this->output->set_content_type('application/json');
        if (!$this->Employee_model->find_by_id($employee_id)) {
            return $this->output->set_status_header(404)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Karyawan tidak ditemukan.'
            )));
        }

        $amounts = $this->input->post('amount');
        try {
            $this->Employee_model->save_salary_details($employee_id, is_array($amounts) ? $amounts : array());
        } catch (Throwable $exception) {
            log_message('error', 'Payroll detail save error: ' . $exception->getMessage());
            return $this->output->set_status_header(500)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Detail komponen gagal disimpan.'
            )));
        }

        return $this->output->set_output(json_encode(array(
            'success' => true,
            'message' => 'Detail komponen gaji berhasil disimpan.'
        )));
    }
}
