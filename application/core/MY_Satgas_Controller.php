<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller Induk untuk Halaman Admin.
 * Semua controller di dalam folder 'application/controllers/admin/'
 * harus mewarisi kelas ini untuk memastikan otentikasi.
 */
class MY_Satgas_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Memeriksa status login dan level pengguna
        // Logika ini akan berjalan secara otomatis sebelum
        // method apa pun di dalam controller admin dieksekusi.
        if (!$this->session->userdata('logged_in') || $this->session->userdata('level') != '2') {
            // Jika pengguna belum login atau levelnya bukan '1' (admin),
            // maka arahkan ke halaman login dan berikan pesan kesalahan.

            // Set pesan flashdata
            $this->session->set_flashdata(
                'pesan',
                '<div class= "alert alert-warning" role="alert">Anda tidak memiliki akses ke halaman ini. Silakan login sebagai admin.</div>'
            );

            // Arahkan pengguna ke halaman login
            redirect('Auth/open_f_login');
        }
    }
}
