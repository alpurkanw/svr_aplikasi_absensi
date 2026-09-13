<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Holidays extends CI_Controller
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
        $holidays = $this->db->order_by('holiday_date', 'DESC')
            ->order_by('id', 'DESC')
            ->get('holidays')->result_array();
        $this->load->view('admin/holidays/index', array(
            'title' => 'Manage Holiday',
            'holidays' => $holidays,
        ));
    }

    public function save()
    {
        $id = (int) $this->input->post('id', true);
        $date = trim($this->input->post('holiday_date', true));
        $name = trim($this->input->post('name', true));
        $type = strtoupper(trim($this->input->post('holiday_type', true)));

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !DateTime::createFromFormat('!Y-m-d', $date) || $name === '' || !in_array($type, array('NASIONAL', 'CUTI_BERSAMA', 'PERUSAHAAN'), true)) {
            $this->session->set_flashdata('error', 'Tanggal, nama, dan tipe libur wajib diisi dengan benar.');
            redirect('admin/holidays');
        }

        $duplicate = $this->db->where('holiday_date', $date);
        if ($id > 0) {
            $duplicate->where('id !=', $id);
        }
        if ($duplicate->count_all_results('holidays') > 0) {
            $this->session->set_flashdata('error', 'Tanggal libur tersebut sudah terdaftar.');
            redirect('admin/holidays');
        }

        $data = array(
            'holiday_date' => $date,
            'name' => $name,
            'holiday_type' => $type,
            'is_active' => (int) ($this->input->post('is_active', true) === '1'),
        );
        if ($id > 0) {
            $this->db->where('id', $id)->update('holidays', $data);
        } else {
            $this->db->insert('holidays', $data);
        }
        $this->session->set_flashdata('success', 'Data hari libur berhasil disimpan.');
        redirect('admin/holidays');
    }

    public function delete($id)
    {
        $this->db->where('id', (int) $id)->delete('holidays');
        $this->session->set_flashdata('success', 'Data hari libur berhasil dihapus.');
        redirect('admin/holidays');
    }
}
