<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employees extends CI_Controller
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
        $components = $this->db->where('is_active', 1)->order_by('component_type', 'ASC')->order_by('id', 'ASC')->get('payroll_components')->result_array();
        $fingerprint_counts = $this->db
            ->select('employee_id, COUNT(*) AS fingerprint_count')
            ->where('is_active', 1)
            ->group_by('employee_id')
            ->get('employee_fingerprint_templates')
            ->result_array();
        $fingerprint_counts_by_employee = array();
        foreach ($fingerprint_counts as $row) {
            $fingerprint_counts_by_employee[(int) $row['employee_id']] = (int) $row['fingerprint_count'];
        }
        $employees = $this->Employee_model->all();
        foreach ($employees as &$employee) {
            $employee['fingerprint_count'] = $fingerprint_counts_by_employee[(int) $employee['id']] ?? 0;
        }
        unset($employee);

        $this->load->view('admin/employees/index', array(
            'title' => 'Data Karyawan',
            'employees' => $employees,
            'components' => $components,
        ));
    }

    public function save()
    {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('employee_code', 'Employee Code', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('name', 'Nama', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('nik', 'No. KTP', 'trim|max_length[50]');
        if ($this->form_validation->run() === false) {
            return $this->output->set_status_header(422)->set_output(json_encode(array('success' => false, 'message' => validation_errors())));
        }

        $employee_code = trim($this->input->post('employee_code', true));
        $name = trim($this->input->post('name', true));
        $nik = trim($this->input->post('nik', true));

        if ($this->Employee_model->exists_by_code($employee_code)) {
            return $this->output->set_status_header(422)->set_output(json_encode(array('success' => false, 'message' => 'Employee Code sudah terdaftar.')));
        }

        if ($nik !== '' && $this->Employee_model->exists_by_nik($nik)) {
            return $this->output->set_status_header(422)->set_output(json_encode(array('success' => false, 'message' => 'No. KTP sudah terdaftar.')));
        }

        $id = $this->Employee_model->save(array(
            'employee_code' => $employee_code,
            'name' => $name,
            'nik' => $nik !== '' ? $nik : null,
            'gender' => $this->input->post('gender', true) ?: null,
            'birth_place' => trim($this->input->post('birth_place', true)) !== '' ? trim($this->input->post('birth_place', true)) : null,
            'birth_date' => $this->input->post('birth_date', true) ?: null,
            'address' => trim($this->input->post('address', true)) !== '' ? trim($this->input->post('address', true)) : null,
            'phone' => trim($this->input->post('phone', true)) !== '' ? trim($this->input->post('phone', true)) : null,
            'position_name' => trim($this->input->post('position_name', true)),
            'department_name' => trim($this->input->post('department_name', true)),
            'employment_status' => $this->input->post('employment_status', true) ?: 'TETAP',
            'base_salary' => (float) ($this->input->post('base_salary', true) ?: 0),
        ));
        return $this->output->set_output(json_encode(array('success' => true, 'id' => $id)));
    }

    public function salary_details($employee_id)
    {
        $employee = $this->Employee_model->find_by_id($employee_id);
        if (!$employee) {
            return $this->output->set_status_header(404)->set_output(json_encode(array('success' => false, 'message' => 'Karyawan tidak ditemukan')));
        }
        $rows = $this->Employee_model->salary_details($employee_id);
        $details = array();
        foreach ($rows as $row) {
            $details[(int) $row['component_id']] = (float) $row['amount'];
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true, 'data' => $details)));
    }

    public function detail($employee_id)
    {
        $employee = $this->Employee_model->find_by_id($employee_id);
        if (!$employee) {
            return $this->output->set_status_header(404)->set_output(json_encode(array('success' => false, 'message' => 'Karyawan tidak ditemukan')));
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'success' => true,
            'data' => array(
                'employee' => array(
                    'employee_code' => $employee['employee_code'],
                    'name' => $employee['name'],
                    'nik' => $employee['nik'] ?? '-',
                    'gender' => $employee['gender'] ?? '-',
                    'birth_place' => $employee['birth_place'] ?? '-',
                    'birth_date' => $employee['birth_date'] ?? '-',
                    'phone' => $employee['phone'] ?? '-',
                    'address' => $employee['address'] ?? '-',
                    'position_name' => $employee['position_name'] ?? '-',
                    'department_name' => $employee['department_name'] ?? '-',
                    'employment_status' => $employee['employment_status'] ?? '-',
                    'join_date' => $employee['join_date'] ?? '-',
                ),
                'salary' => $this->Employee_model->salary_details_with_names($employee_id),
            ),
        )));
    }

    public function save_salary_details($employee_id)
    {
        $employee = $this->Employee_model->find_by_id($employee_id);
        if (!$employee) {
            return $this->output->set_status_header(404)->set_output(json_encode(array('success' => false, 'message' => 'Karyawan tidak ditemukan')));
        }
        $amounts = $this->input->post('amount');
        $this->Employee_model->save_salary_details($employee_id, is_array($amounts) ? $amounts : array());
        return $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true)));
    }
}
