<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Presensi_log extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'ADMIN') {
            redirect('Auth/open_f_login');
        }
        $this->load->model('Presensi_model');
    }

    public function index()
    {
        $today = date('Y-m-d');
        $minimum_date = date('Y-m-d', strtotime('-3 months'));
        $selected_date = $this->input->get('date', true) ?: $today;
        $message = null;

        $parsed_date = DateTime::createFromFormat('!Y-m-d', $selected_date);
        $date_errors = DateTime::getLastErrors();
        $date_is_valid = $parsed_date && ($date_errors === false || ($date_errors['warning_count'] === 0 && $date_errors['error_count'] === 0)) && $parsed_date->format('Y-m-d') === $selected_date;

        if (!$date_is_valid) {
            $selected_date = $today;
            $message = 'Format tanggal tidak valid. Menampilkan log hari ini.';
        } elseif ($selected_date < $minimum_date || $selected_date > $today) {
            $selected_date = $selected_date < $minimum_date ? $minimum_date : $today;
            $message = 'Log hanya tersedia dari ' . $minimum_date . ' sampai ' . $today . '.';
        }

        $this->load->view('admin/presensi_log/index', array(
            'title' => 'Log Absensi',
            'date' => $selected_date,
            'minimum_date' => $minimum_date,
            'maximum_date' => $today,
            'rows' => $this->Presensi_model->list_by_date($selected_date),
            'message' => $message,
        ));
    }
}
