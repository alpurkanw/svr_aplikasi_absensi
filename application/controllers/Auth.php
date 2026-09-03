<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        // echo "auth";
    }

    public function index()
    {

        // print_r($_SESSION);
        // return;

        if ($this->session->userdata('logged_in')) {
            // Redirect berdasarkan role user jika diperlukan
            redirect('admin/attendance');
        } else {
            // $this->load->view('auth/login');
            // echo "belum pernah login , arahkan ke form login ";
            // return;
            redirect('Auth/open_f_login');
        }
    }
    public function open_f_login()
    {

        $this->load->view('auth/login');
    }



    public function login_process()
    {

        $this->form_validation->set_rules(
            'username',
            'Username',
            'trim|required'
        );
        $this->form_validation->set_rules(
            'password',
            'password',
            'trim|required'
        );

        if ($this->form_validation->run() == TRUE) {



            $usernm = $this->input->post('username');
            $pass = $this->input->post('password');
            $this->db->select('A.usernm,A.email, A.nama, A.id iduser, A.role,A.pass');
            $this->db->from('tbl_user A');
            $this->db->where(["usernm" => $usernm]);

            $user = $this->db->get()->row_array();

            // print_r($pass);
            // echo "hasil query : <br>";
            // echo $this->db->error()["message"];
            // return;



            if ($user) {
                // print_r($user["pass"]);
                // return;
                if (password_verify($pass, $user["pass"])) {
                    $data = [
                        'email' => $user['email'],
                        'usernm' => $user['usernm'],
                        'nama' => $user['nama'],
                        'id' => $user['iduser'],
                        'role' => $user['role'],
                        'logged_in' => 1

                    ];


                    $this->session->set_userdata($data);
                    if ($data['role'] === 'ADMIN') {
                        redirect('admin/attendance');
                        return;
                    } else {
                        $this->session->set_flashdata(
                            'pesan',
                            '<div class= "alert alert-danger" role="alert" >Role belum disetting, silakan hubungi Admin!</div>'
                        );
                        // $this->logout();


                        redirect('Auth/open_f_login');
                        return;
                    }
                } else {

                    // print_r($user["iduser"]);
                    // echo $this->db->error()["message"];
                    // echo password_verify($pass, $user["pass"]);
                    // return;

                    $this->session->set_flashdata(
                        'pesan',
                        '<div class= "alert alert-danger" role="alert" >Password Salah</div>'
                    );
                    redirect('Auth/open_f_login');
                    return;
                }
            } else {
                $this->session->set_flashdata(
                    'pesan',
                    '<div class= "alert alert-danger" role="alert" >Anda Belum Approve, Silahkan Hubungi Admin!</div>'
                );
                redirect('Auth/open_f_login');
                return;
            }
        } else {
            // redirect('C_daftar/');
            // $data['judul'] = 'Login';
            $this->session->set_flashdata(
                'pesan',
                '<div class= "alert alert-danger" role="alert" >Parameter Belum Lengkap!</div>'
            );
            redirect('Auth/open_f_login');
            return;
        }
    }

    public function gantiPass()
    {
        $data['judul'] = 'Ubah Password';
        // $data['method'] = "open_index";
        $this->load->view('auth/V_chgpass', $data);
    }


    public function gantiPass_Proses()
    {


        // $data['title'] = 'Ganti Password';
        // $data['user'] = $this->session->userdata('usernm');


        $this->form_validation->set_rules('old_password', 'Password Lama', 'required');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required');
        $this->form_validation->set_rules('new_password_confirm', 'Konfirmasi Password Baru', 'required|trim|matches[new_password]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('Auth/gantiPass', $data);
        } else {
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');

            $usernm =  $this->session->userdata('usernm');
            $email =  $this->session->userdata('email');

            $this->db->select('*');
            $this->db->from('tbl_user A');
            $this->db->where(["usernm" => $usernm]);
            $this->db->where(["email" => $email]);

            $user = $this->db->get()->row_array();



            if (password_verify($old_password, $user['pass'])) {
                // echo "boleh ganti pass";

                $data = array(
                    'pass' => password_hash($new_password, PASSWORD_DEFAULT)
                );

                $this->db->where(["usernm" => $usernm]);
                $this->db->where(["email" => $email]);
                $this->db->update('tbl_user', $data);


                $this->session->set_flashdata(
                    'pesan',
                    '<div class="alert alert-success py-1" role="alert">
                                Password berhasil diubah!
                            </div>'
                );
                redirect('Auth/logout');
            } else {

                $this->session->set_flashdata(
                    'pesan',
                    '<div class="alert alert-danger py-1" role="alert">
                                Password Gagal diubah!
                            </div>'
                );
                redirect('Auth/gantiPass');
            }
        }
    }


    public function logout()
    {


        // Hapus semua data session
        $this->session->unset_userdata(['usernm', 'logged_in', 'role']);

        // Hancurkan session sepenuhnya (opsional)
        $this->session->sess_destroy();

        // Set pesan flash data untuk notifikasi logout
        $this->session->set_flashdata('success', 'Anda telah berhasil logout');

        // Redirect ke halaman login
        redirect('Auth');
    }
}
