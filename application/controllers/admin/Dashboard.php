<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	private $username;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_login', 'm_login');
		$this->load->model('M_dokumen_keluar', 'm_dok_keluar');

		$is_login = $this->session->userdata('is_login');
		$this->username = $this->session->userdata('username');


		if ($is_login === true) {
			// $cek_role = $this->m_login->get_user($this->session->userdata('username'));
			// if ($cek_role['lv_user'] != $this->uri->segment('1')) {
			// 	session_destroy();
			// 	redirect(base_url());
			// }
		} else {
			session_destroy();
			redirect(base_url());
		}
	}

	public function index()
	{
		$year = date('Y');
		$page = 'v_dashboard';
		$data['title'] = 'Dashboard';
		// $data['dok_masuk'] = $this->db->get('tbl_dok_masuk')->num_rows();
		// $data['disposisi'] = $this->db->get_where('tbl_dok_masuk', ['tgl_disposisi !=' => null])->num_rows();
		$data['dok_keluar'] = $this->m_dok_keluar->get_jumlah_surat($year, $this->username);
		$data['kepaniteraan'] = $this->m_dok_keluar->get_jumlah_surat($year, 'kepaniteraan');
		$data['kesekretariatan'] = $this->m_dok_keluar->get_jumlah_surat($year, 'kesekretariatan');

		// $data['dok_keluar'] = $this->db->get_where('tbl_dok_keluar', ['YEAR(createDate)' => $year])->num_rows();
		$data['pegawai'] = $this->db->get('tbl_pegawai')->num_rows();

		$data['bulan'] = array(
			1 => 'Januari',
			2 => 'Februari',
			3 => 'Maret',
			4 => 'April',
			5 => 'Mei',
			6 => 'Juni',
			7 => 'Juli',
			8 => 'Agustus',
			9 => 'September',
			10 => 'Oktober',
			11 => 'November',
			12 => 'Desember'
		);
		$list = $this->m_dok_keluar->get_data_chart($year, $this->username);
		$surat_keluar = array();
		foreach ($list as $row) {
			if (isset($row['username'])) {
				$surat_keluar[ltrim($row['bulan'], '0')][$row['username']] = $row['count'];
			} else {
				$surat_keluar[ltrim($row['bulan'], '0')] = $row['count'];
			}
		}
		// print_r($list);die;

		$data['jumlah'] = $surat_keluar;

		$this->load->view($page, $data);
	}
}
