<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	//  * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		// echo "tes welcome";
		// print_r($_SESSION);
		// return;
		if ($this->session->userdata('logged_in')) {
			// Redirect berdasarkan role user jika diperlukan
			if ($this->session->userdata('level') === '1') {
				redirect('admin/Home');
			} elseif ($this->session->userdata('level') === '3') {
				redirect('owner/Home');
			} else {
				redirect('satgas/Home');
			}
		} else {
			// $this->load->view('auth/login');
			// echo "belum pernah login , arahkan ke form login ";
			// return;
			redirect('Auth/open_f_login');
		}


		// $this->load->view('welcome_message');
	}
}
