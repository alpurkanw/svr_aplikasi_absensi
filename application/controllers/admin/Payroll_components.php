<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_components extends CI_Controller
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
        $components = $this->db->order_by('component_type', 'ASC')->order_by('id', 'ASC')->get('payroll_components')->result_array();
        $grouped = array(
            'EARNING' => array(),
            'DEDUCTION' => array(),
        );

        foreach ($components as $component) {
            $grouped[$component['component_type']][] = $component;
        }

        $this->load->view('admin/payroll_components/index', array(
            'title' => 'Komponen Gaji',
            'components' => $grouped,
        ));
    }

    public function save()
    {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('code', 'Kode Komponen', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('name', 'Nama Komponen', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('component_type', 'Kelompok', 'required|in_list[EARNING,DEDUCTION]');
        $this->form_validation->set_rules('calculation_type', 'Metode Perhitungan', 'required|in_list[FIXED,PERCENTAGE,PER_MINUTE,RANGE]');

        if ($this->form_validation->run() === false) {
            return $this->output->set_status_header(422)->set_output(json_encode(array(
                'success' => false,
                'message' => validation_errors('', "<br>")
            )));
        }

        $id = (int) $this->input->post('id', true);
        $code = strtoupper(trim($this->input->post('code', true)));
        $existing = $this->db->where('code', $code);
        if ($id > 0) {
            $existing->where('id !=', $id);
        }
        if ($existing->count_all_results('payroll_components') > 0) {
            return $this->output->set_status_header(422)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Kode komponen sudah digunakan.'
            )));
        }

        $data = array(
            'code' => $code,
            'name' => trim($this->input->post('name', true)),
            'component_type' => $this->input->post('component_type', true),
            'calculation_type' => $this->input->post('calculation_type', true),
            'is_active' => (int) ($this->input->post('is_active', true) ?: 1),
        );

        if ($id > 0) {
            $this->db->where('id', $id)->update('payroll_components', $data);
            $message = 'Komponen gaji berhasil diperbarui.';
        } else {
            $this->db->insert('payroll_components', $data);
            $message = 'Komponen gaji berhasil ditambahkan.';
        }

        return $this->output->set_output(json_encode(array(
            'success' => true,
            'message' => $message,
        )));
    }

    public function toggle_status($id)
    {
        $this->output->set_content_type('application/json');
        $row = $this->db->where('id', (int) $id)->get('payroll_components')->row_array();
        if (!$row) {
            return $this->output->set_status_header(404)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Komponen tidak ditemukan.'
            )));
        }

        $this->db->where('id', (int) $id)->update('payroll_components', array(
            'is_active' => (int) !$row['is_active'],
        ));

        return $this->output->set_output(json_encode(array(
            'success' => true,
            'message' => 'Status komponen berhasil diubah.',
        )));
    }
}
