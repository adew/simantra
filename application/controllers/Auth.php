<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_login', 'm_login');
	}

	public function index()
	{
		$data['bagian'] = $this->m_login->show();
		$data['page'] = 'v_login';
		$this->load->view('v_login', $data);
	}

	public function login()
	{
		$username = input('nm_user');
		$password = md5(input('password'));
		// print_r($password);
		// die;

		$check = $this->m_login->login($username, $password);
		if ($check > 0) {
			$user = $this->m_login->get_user($username);
			$sess = array(
				'id' => $user['id'],
				'username' => $user['username'],
				'nama_user' => $user['nm_user'],
				'lv_user' => $user['lv_user'],
				'is_login' => true
			);

			$this->session->set_userdata($sess);
			redirect(site_url('page/dashboard'));
		} else {
			$this->session->set_flashdata('msg', 'Username atau Password tidak sesuai');
			$this->index();
		}
	}

	public function logout()
	{
		session_destroy();
		redirect(base_url());
	}
}
