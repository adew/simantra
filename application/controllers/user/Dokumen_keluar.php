<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dokumen_keluar extends CI_Controller
{
	private $username;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_login', 'm_login');
		$this->load->model('M_dokumen_keluar', 'm_dok_keluar');
		$this->load->model('M_jenis_dokumen', 'm_jns_dokumen');
		$this->load->model('M_kategori', 'm_kategori');
		$this->load->model('M_unit_tujuan', 'm_tujuan');
		$this->load->model('M_pegawai', 'm_pegawai');
		$this->load->model('M_config', 'm_config');

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
		$filter_bulan = $this->input->post('bulan', true);
		$tahun = $this->input->post('tahun', true);
		$data['filter_bulan'] = !empty($filter_bulan) ? $filter_bulan : 'all';
		$data['filter_tahun'] = !empty($tahun) ? $tahun : date('Y');
		$data['nama_user'] = $this->session->userdata('nama_user');

		// $check_data = $this->m_dok_keluar->cek_file_upload($this->session->userdata('id'));
		// foreach ($check_data as $check) {

		// 	$dari  = date_create($check['createDate']); // waktu awal. format penulisan tahun-bulan-tanggal jam:menit:detik
		// 	$sampai = date_create(); // waktu sekarang. saat tutorial ini dibuat, yaitu : 30/1/2024 16:00:00
		// 	// menghitung perbedaan waktu. tanggal dari pada parameter pertama, tanggal sampai pada parameter kedua
		// 	$diff  = date_diff($dari, $sampai);
		// 	// menampilkan output hasil proses date_diff()
		// 	// Menampilkan selisih tahun
		// 	// echo $diff->d . ' Jam, ';

		// 	if ($diff->d > 2) {
		// 		$data['disable_button'] = true;
		// 		break;
		// 	}
		// }
		$key['id'] = $this->session->userdata('id');
		$check_active_user = $this->m_pegawai->read($key)->row_array();
		if ($check_active_user['active'] == "no") {
			$data['disable_button'] = true;
		}

		$page = 'user/v_dokumen_keluar';
		$group = $this->m_config->read(['status' => 1])->row_array();

		$data['title'] = 'Penomoran Surat';
		$data['jns_dokumen'] = $this->m_jns_dokumen->show();
		$data['kategori'] = $this->m_kategori->show();
		// $qry = 'SELECT * FROM tbl_unit WHERE kd_unit != \'' . $group['nm_group'] . '\' ORDER BY CASE WHEN nm_unit LIKE \'%group%\' THEN 1 ELSE 2 END';
		// $data['tujuan'] = $this->db->query($qry)->result_array();
		$data['pembuat'] = $this->m_pegawai->show();

		if ($this->username == "admin") {
			$data['unit'] = $this->m_login->show();
		} else {
			$data['unit'] = $this->m_login->read(['id' => $this->session->userdata('id')])->result_array();
		}

		$this->load->view($page, $data);
	}

	public function validasi($action = '', $no_dok = "")
	{
		$data = array();
		$data['inputerror'] = array();
		$data['error'] = array();
		$data['status'] = true;


		$post = array(
			'jns_dokumen', 'perihal', 'pembuat', 'kategori', 'no_dokumen2', 'tujuan_lain', 'unit_satker'
		);

		foreach ($post as $post) {
			if (input($post) == '') {
				$data['inputerror'][] = $post;
				$data['error'][] = 'Bagian ini harus diisi';
				$data['status'] = false;
			}
		}

		// if ($_FILES['file']['name'] == '') {
		// 	$data['inputerror'][] = 'file';
		// 	$data['error'][] = 'Bagian ini harus diisi';
		// 	$data['status'] = false;
		// }


		// if (!isset($_POST['li_tujuan'])) {
		// 	$data['inputerror'][] = 'li_tujuan';
		// 	$data['error'][] = 'Bagian ini harus diisi';
		// 	$data['status'] = false;
		// }
		if ($action == 'insert') {
			$key['no_dokumen'] = $no_dok;
			$check_nomor = $this->m_dok_keluar->read($key)->num_rows();

			if ($check_nomor > 0) {
				$data['inputerror'][] = 'no_dokumen';
				$data['error'][] = 'Nomor surat sudah ada, ganti dengan nomor yang lain';
				$data['status'] = false;
			}
		}

		if ($data['status'] === false) {
			echo json_encode($data);
			exit();
		}
	}

	public function get_list()
	{
		$filter_bulan = $this->input->post('bulan', true);
		$tahun = $this->input->post('tahun', true);

		$param_bulan = !empty($filter_bulan) ? $filter_bulan : 'all';
		$param_tahun = !empty($tahun) ? $tahun : date('Y');
		// $data['filter_bulan'] = !empty($filter_bulan) ? $filter_bulan : 'all';
		// $data['filter_tahun'] = !empty($tahun) ? $tahun : date('Y');

		$list = $this->m_dok_keluar->get_datatables($param_bulan, $param_tahun, $this->username);
		$data = array();
		$no = $_POST['start'] + 1;
		foreach ($list as $li) {
			$row = array();
			$row[] = '<center>' . $no++ . '</center>';

			$jns_dokumen = '<span style="white-space: pre-line">' . $li['jns_kategori'] . '&nbsp;<br></span>';
			$jns_dokumen .= $li['jns_dokumen'] != 'Biasa' ? '<span class="badge badge-dark">' . $li['jns_dokumen'] . '</span>' : '<span class="badge badge-dark">' . $li['jns_dokumen'] . '</span>';
			$row[] = $jns_dokumen;
			if (empty($li['no_dokumen'])) {
				$detail = 'Nomor: -<br>';
			} else {
				$detail = 'Nomor: <b>' . $li['no_dokumen'] . '</b>/' . $li['no_dokumen2'] . '</span>&nbsp;<br>';
			}
			$detail .= '<span>Perihal: <br>' . $li['perihal'] . '</span><br>';
			$row[] = $detail;

			$exp = '';
			foreach (unserialize($li['unit_tujuan']) as $val) {
				$exp .= $val . '<br>';
			}
			$row[] = 'Kepada: <br>' . $exp;

			$date = explode(' ', $li['createDate']);
			$row[] = '<span>' . tgl_indo($date[0]) . ' | ' . $date[1] . '&nbsp;<br>Oleh: ' . $li['pembuat'] . '&nbsp; <br>Bagian: ' . $li['nm_user'] .  '</span>';

			//STATUS DOKUMEN
			$sts = '<div class="text-center">';
			if ($li['sts_dokumen'] == 'Proses') {
				$sts .= '<span class="badge badge-warning">' . strtoupper($li['sts_dokumen']) . '</span>';
			} else if ($li['sts_dokumen'] == 'Diterima' && $li['file_dokumen'] == null) {
				$sts .= '<span class="badge badge-success">' . strtoupper($li['sts_dokumen']) . '</span><br>';
				$sts .= '<span class="badge badge-info">File belum diupload</span>';
			} else if ($li['sts_dokumen'] == 'Diterima' && $li['file_dokumen'] != null) {
				$sts .= '<span class="badge badge-success">' . strtoupper($li['sts_dokumen']) . '</span><br>';
				$sts .= '<span class="badge badge-warning">Menunggu verifikasi admin</span>';
			} else if ($li['sts_dokumen'] == 'Diperbaiki' && $li['file_dokumen'] != null) {
				$sts .= '<span class="badge badge-success">' . strtoupper($li['sts_dokumen']) . '</span><br>';
				$sts .= '<span class="badge badge-warning">File yang diupload salah</span>';
			} else if ($li['sts_dokumen'] == 'Ditolak') {
				$sts .= '<span class="badge badge-danger">' . strtoupper($li['sts_dokumen']) . '</span>';
			} else {
				$sts .= '<span class="badge badge-success">SELESAI</span>';
			}
			$sts .= '</div>';
			$row[] = $sts;

			//TOMBOL AKSI
			$aksi = '<div class="text-center">';
			//status proses pengajuan nomor
			if ($li['sts_dokumen'] == 'Proses' && $this->username == 'admin')
				$aksi .= '<span class="btn btn-sm btn-success" title="Approval" style="cursor: pointer" onclick="approve(\'' . $li['id_dokumen'] . '\')"><i class="fa fa-check-square"></i></span>&nbsp;';
			//status verifikasi dokumen
			if ($li['sts_dokumen'] == 'Diterima' && $li['file_dokumen'] != null && $this->username == 'admin')
				$aksi .= '<span class="btn btn-sm btn-success" title="Verifkasi" style="cursor: pointer" onclick="verifikasi(\'' . $li['id_dokumen'] . '\')"><i class="fa fa-check-square"></i></span>&nbsp;';

			//file download
			$download = $li['file_dokumen'] != null ? '<a href="' . base_url('assets/' . $li['path_folder'] . '/' . $li['file_dokumen']) . '" target="_blank" class="btn btn-sm btn-info" title="Download" style="cursor: pointer"><i class="fa fa-download" style="color:white"></i></a>&nbsp;' : '';
			$aksi .= $download;
			//Edit sesuai username
			if ($li['sts_dokumen'] == 'Proses' && $this->username != 'admin') {
				$aksi .= '';
			} elseif ($this->session->userdata('id') == $li['kd_unit'] || $this->username == 'admin') {
				$aksi .= '<span class="btn btn-sm btn-warning" title="Edit" style="cursor: pointer" onclick="sunting(\'' . $li['id_dokumen'] . '\')"><i class="fa fa-edit"></i></span>&nbsp;';
			}
			//hapus hanya admin
			if ($this->username == 'admin')
				$aksi .= '<span class="btn btn-sm btn-danger" title="Hapus" style="cursor: pointer" onclick="hapus(\'' . $li['id_dokumen'] . '\')"><i class="fa fa-trash"></i></span>';
			$aksi .= '</div>';
			$row[] = $aksi;

			$data[] = $row;
		}

		$output = array(
			'draw' => intval($_POST['draw']),
			// 'recordsTotal' => $this->m_dok_keluar->get_all_data($param_bulan, $param_tahun, $this->username),
			'recordsTotal' => $this->m_dok_keluar->count_filtered($param_bulan, $param_tahun, $this->username),
			'recordsFiltered' => $this->m_dok_keluar->count_filtered($param_bulan, $param_tahun, $this->username),
			'data' => $data
		);
		echo json_encode($output);
		exit();
	}

	function get_data($id)
	{
		$key['id_dokumen'] = $id;
		$data = $this->m_dok_keluar->read($key)->row_array();

		$unit = unserialize($data['unit_tujuan']);
		// $list = array_walk($unit, function(&$val, $key){
		// 	$val = str_replace(' & ', ' and ', $val);
		// });
		$list = array_map(function ($val) {
			return html_entity_decode($val, ENT_QUOTES, 'UTF-8');
		}, $unit);

		$respon = array(
			'id_dokumen' => $data['id_dokumen'],
			'no_dokumen' => $data['no_dokumen'],
			'no_dokumen2' => $data['no_dokumen2'],
			'jns_dokumen' => $data['jns_dokumen'],
			'dari' => $data['dari'],
			'unit_tujuan' => $list,
			'perihal' => $data['perihal'],
			'pembuat' => $data['pembuat'],
			'lampiran' => $data['lampiran'],
			'kategori' => $data['kategori'],
			'sts_dokumen' => $data['sts_dokumen'],
			'catatan' => $data['catatan'],
			'file_dokumen' => $data['file_dokumen'],
			'kd_unit' => $data['kd_unit'],
			// 'createDate' => $data['createDate']
		);
		echo json_encode($respon);
		exit;
	}

	public function insert()
	{
		// buat folder sesuai tahun dan bulan
		$nm_folder = date('Y-m');
		if (!is_dir('assets/berkas-keluar/' . $nm_folder)) {
			mkdir('./assets/berkas-keluar/' . $nm_folder, 0777, true);
		}

		$config = array(
			'upload_path' => './assets/berkas-keluar/' . $nm_folder,
			'allowed_types' => 'pdf'
		);

		$this->load->library('upload', $config);

		// $dokumen = $this->m_jns_dokumen->read(['id_jns_dokumen' => input('jns_dokumen')])->row_array();
		$config = $this->m_config->read(['status' => 1])->row_array();

		$no = input('nomor_dokumen');
		if (!empty($no)) {
			if ($no > 999) $cond = $no;
			elseif ($no > 99) $cond = '0' . $no;
			elseif ($no > 9) $cond = '00' . $no;
			else $cond = '000' . $no;
			$no_dok = $cond;
			$sts_dok = 'Diterima';
		} else {
			$no_dok = "";
			$sts_dok = 'Proses';
		}
		$this->validasi('insert', $no_dok);

		$userid = !empty(input('unit_satker')) ? input('unit_satker') : $this->session->userdata('id');
		$data = array(
			'no_dokumen' => $no_dok,
			'no_dokumen2' => input('no_dokumen2'),
			'jns_dokumen' => input('jns_dokumen'),
			'dari' => $config['nm_group'],
			// 'unit_tujuan' => serialize($_POST['li_tujuan']),
			'perihal' => input('perihal'),
			'pembuat' => input('pembuat'),
			'createDate' => date("Y-m-d H:i:s"),
			// 'lampiran' => input('lampiran') == '' ? 0 : input('lampiran'),
			'kategori' => input('kategori'),
			'sts_dokumen' => $sts_dok,
			'catatan' => input('catatan') == '' ? NULL : input('catatan'),
			'kd_unit' => $userid
		);


		$tujuan[] = input('tujuan_lain');
		if (isset($_POST['li_tujuan'])) {
			$data['unit_tujuan'] = serialize($_POST['li_tujuan']);
		} else {
			$data['unit_tujuan'] = serialize($tujuan);
		}

		if ($this->upload->do_upload('file')) {
			$fileData = $this->upload->data();
			$data['path_folder'] = 'berkas-keluar/' . $nm_folder;
			$data['file_dokumen'] = $fileData['file_name'];
		}

		$this->db->trans_begin();
		// $this->m_jns_dokumen->update(['counter_dokumen' => $no], ['id_jns_dokumen' => input('jns_dokumen')]);
		$this->m_dok_keluar->create($data);
		if ($this->db->trans_status() === TRUE) {
			$this->db->trans_commit();

			$title = 'Sukses';
			$text = 'Telah disimpan';
			$icon = 'success';
		} else {
			$this->db->trans_rollback();

			$title = 'Oops!';
			$text = 'Terjadi kesalahan pada sistem, harap coba kembali';
			$icon = 'warning';
		}

		echo json_encode(['status' => true, 'title' => $title, 'icon' => $icon, 'text' => $text]);
		exit;
	}

	public function update()
	{
		$this->validasi();

		// buat folder sesuai tahun dan bulan
		$nm_folder = date('Y-m');
		if (!is_dir('assets/berkas-keluar/' . $nm_folder)) {
			mkdir('./assets/berkas-keluar/' . $nm_folder, 0777, true);
		}

		$config = array(
			'upload_path' => './assets/berkas-keluar/' . $nm_folder,
			'allowed_types' => 'pdf'
		);

		$this->load->library('upload', $config);

		$key['id_dokumen'] = input('id_dok');

		$data = array(
			'no_dokumen2' => input('no_dokumen2'),
			'jns_dokumen' => input('jns_dokumen'),
			// 'unit_tujuan' => serialize($_POST['li_tujuan']),
			'perihal' => input('perihal'),
			'pembuat' => input('pembuat'),
			// 'createDate' => date("Y-m-d H:i:s", strtotime(input('tgl_surat'))),
			// 'lampiran' => input('lampiran') == '' ? 0 : input('lampiran'),
			'kategori' => input('kategori'),
			// 'sts_dokumen' => 'Proses',
			'catatan' => input('catatan') == '' ? NULL : input('catatan'),
			// 'kd_unit' => input('unit_satker'),
		);

		$tujuan[] = input('tujuan_lain');
		if (isset($_POST['li_tujuan'])) {
			$data['unit_tujuan'] = serialize($_POST['li_tujuan']);
		} else {
			$data['unit_tujuan'] = serialize($tujuan);
		}

		if ($this->upload->do_upload('file')) {
			$fileData = $this->upload->data();
			$data['file_dokumen'] = $fileData['file_name'];
			$data['sts_dokumen'] = 'Diterima';

			// perikasa apakah path_folder pada database sudah ada atau belum
			$path = $this->m_dok_keluar->read($key)->row_array();
			if ($path['path_folder'] == null) {
				// tambahkan path_folder jika belum ada
				$data['path_folder'] = 'berkas-keluar/' . $nm_folder;
			} else {
				unlink('./assets/' . $path['path_folder'] . '/' . $path['file_dokumen']);
			}
		}

		$this->m_dok_keluar->update($data, $key);

		$title = 'Sukses';
		$text = 'Dokumen telah berhasil disimpan';
		$icon = 'success';

		echo json_encode(['status' => true, 'title' => $title, 'icon' => $icon, 'text' => $text]);
		exit;
	}

	public function status()
	{
		$key['id_dokumen'] = input('id');
		$sts_dok = input('sts_dok');
		$tahun = date('Y');

		// $jml_data = $this->m_dok_keluar->read(['sts_dokumen' => 'Diterima'])->num_rows();
		$jml_data = $this->m_dok_keluar->lihat_nomor($tahun);

		if (empty($jml_data)) {
			$jml_data = 0;
		} else {
			$jml_data = $jml_data['no_dokumen'];
		}

		$no = (int) $jml_data + 1;
		if ($no > 999) $cond = $no;
		elseif ($no > 99) $cond = '0' . $no;
		elseif ($no > 9) $cond = '00' . $no;
		else $cond = '000' . $no;

		if ($sts_dok == 'Diterima') {
			$data = array(
				'no_dokumen' => $cond,
				'sts_dokumen' => $sts_dok
			);
		} else {
			$data = array(
				'sts_dokumen' => $sts_dok
			);
		}
		$this->m_dok_keluar->update($data, $key);

		echo json_encode(['status' => true]);
		exit;
	}

	public function statusverifikasi()
	{
		$key['id_dokumen'] = input('id');
		$data['sts_dokumen'] = input('sts_dok');

		$this->m_dok_keluar->update($data, $key);

		echo json_encode(['status' => true]);
		exit;
	}

	public function delete($id)
	{
		$key['id_dokumen'] = $id;

		$file = $this->m_dok_keluar->read($key)->row_array();
		if ($file['file_dokumen'] != null) {
			unlink('./assets/' . $file['path_folder'] . '/' . $file['file_dokumen']);
		}
		$this->m_dok_keluar->delete($key);

		echo json_encode(['status' => true]);
		exit;
	}
}
