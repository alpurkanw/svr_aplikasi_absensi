<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tokens extends CI_Controller
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
        $tokens = $this->db->select('id, name, token_prefix, expires_at, revoked_at, last_used_at, created_at')
            ->order_by('id', 'DESC')->get('api_tokens')->result_array();
        $this->load->view('admin/tokens/index', array(
            'title' => 'Pembuatan Token',
            'tokens' => $tokens,
        ));
    }

    public function generate()
    {
        $this->output->set_content_type('application/json');
        $name = trim($this->input->post('name', true));
        $expires_at = trim($this->input->post('expires_at', true));

        if ($name === '' || strlen($name) > 100) {
            return $this->output->set_status_header(422)->set_output(json_encode(array(
                'success' => false, 'message' => 'Nama token wajib diisi dan maksimal 100 karakter.'
            )));
        }

        if ($expires_at !== '') {
            $date = DateTime::createFromFormat('!Y-m-d', $expires_at);
            $errors = DateTime::getLastErrors();
            if (!$date || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) || $date->format('Y-m-d') !== $expires_at) {
                return $this->output->set_status_header(422)->set_output(json_encode(array(
                    'success' => false, 'message' => 'Tanggal kedaluwarsa tidak valid.'
                )));
            }
            $expires_at .= ' 23:59:59';
        } else {
            $expires_at = null;
        }

        $token = 'hcis_' . bin2hex(random_bytes(32));
        $this->db->insert('api_tokens', array(
            'name' => $name,
            'token_prefix' => substr($token, 0, 16),
            'token_hash' => hash('sha256', $token),
            'expires_at' => $expires_at,
            'created_by' => (int) $this->session->userdata('id'),
        ));

        if (!$this->db->affected_rows()) {
            return $this->output->set_status_header(500)->set_output(json_encode(array(
                'success' => false, 'message' => 'Token gagal dibuat.'
            )));
        }

        return $this->output->set_output(json_encode(array(
            'success' => true,
            'message' => 'Token berhasil dibuat. Simpan token ini sekarang karena tidak akan ditampilkan lagi.',
            'token' => $token,
        )));
    }

    public function revoke($id)
    {
        $this->output->set_content_type('application/json');
        $this->db->where('id', (int) $id)->where('revoked_at IS NULL', null, false)
            ->update('api_tokens', array('revoked_at' => date('Y-m-d H:i:s')));
        $revoked = $this->db->affected_rows() > 0;
        return $this->output->set_output(json_encode(array(
            'success' => $revoked,
            'message' => $revoked ? 'Token berhasil dicabut.' : 'Token tidak ditemukan atau sudah dicabut.',
        )));
    }
}
