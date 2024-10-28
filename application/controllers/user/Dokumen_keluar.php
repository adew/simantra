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
		$data['filter_bulan'] = !empty($filter_bulan) ? $filter_bulan : date('m');
		$data['filter_tahun'] = !empty($tahun) ? $tahun : date('Y');

		$page = 'user/v_dokumen_keluar';
		$group = $this->m_config->read(['status' => 1])->row_array();

		$data['title'] = 'Penomoran Surat';
		$data['jns_dokumen'] = $this->m_jns_dokumen->show();
		$data['kategori'] = $this->m_kategori->show();
		// $qry = 'SELECT * FROM tbl_unit WHERE kd_unit != \'' . $group['nm_group'] . '\' ORDER BY CASE WHEN nm_unit LIKE \'%group%\' THEN 1 ELSE 2 END';
		// $data['tujuan'] = $this->db->query($qry)->result_array();
		$data['pembuat'] = $this->m_pegawai->show();
		$data['unit'] = $this->m_tujuan->show();

		$this->load->view($page, $data);
	}

	public function validasi()
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

		if ($_FILES['file']['name'] == '') {
			$data['inputerror'][] = 'file';
			$data['error'][] = 'Bagian ini harus diisi';
			$data['status'] = false;
		}


		// if (!isset($_POST['li_tujuan'])) {
		// 	$data['inputerror'][] = 'li_tujuan';
		// 	$data['error'][] = 'Bagian ini harus diisi';
		// 	$data['status'] = false;
		// }

		if ($data['status'] === false) {
			echo json_encode($data);
			exit();
		}
	}

	public function get_list()
	{
		$filter_bulan = $this->input->post('bulan', true);
		$tahun = $this->input->post('tahun', true);

		$data['filter_bulan'] = !empty($filter_bulan) ? $filter_bulan : date('m');
		$data['filter_tahun'] = !empty($tahun) ? $tahun : date('Y');

		$list = $this->m_dok_keluar->get_datatables($data['filter_bulan'], $data['filter_tahun'], $this->username);
		$data = array();
		$no = $_POST['start'] + 1;
		foreach ($list as $li) {
			$row = array();
			$row[] = '<center>' . $no++ . '</center>';

			$jns_dokumen = $li['jns_kategori'] . '<br>';
			$jns_dokumen .= $li['jns_dokumen'] != 'Biasa' ? '<span class="badge badge-dark">' . $li['jns_dokumen'] . '</span>' : '<span class="badge badge-dark">' . $li['jns_dokumen'] . '</span>';
			$row[] = $jns_dokumen;
			if (empty($li['no_dokumen'])) {
				$detail = 'Nomor: -<br>';
			} else {
				$detail = 'Nomor: <b>' . $li['no_dokumen'] . '</b>/' . $li['no_dokumen2'] . '</span><br>';
			}
			$detail .= '<span>Perihal: <br>' . $li['perihal'] . '</span><br>';
			$row[] = $detail;

			$exp = '';
			foreach (unserialize($li['unit_tujuan']) as $val) {
				$exp .= $val . '<br>';
			}
			$row[] = 'Kepada: <br>' . $exp;

			$date = explode(' ', $li['createDate']);
			$row[] = '<span>' . tgl_indo($date[0]) . ' | ' . $date[1] . '<br>Oleh: ' . $li['pembuat'] . '<br>Bagian: ' . $li['nm_unit'] .  '</span>';

			if ($li['sts_dokumen'] == 'Proses') {
				$row[] = '<span class="badge badge-warning">' . strtoupper($li['sts_dokumen']) . '</span>';
			} else if ($li['sts_dokumen'] == 'Diterima') {
				$row[] = '<span class="badge badge-success">' . strtoupper($li['sts_dokumen']) . '</span>';
			} else {
				$row[] = '<span class="badge badge-danger">' . strtoupper($li['sts_dokumen']) . '</span>';
			}

			$aksi = '<div class="text-right">';
			// priview file before download
			if ($li['sts_dokumen'] == 'Proses')
				$aksi .= '<span class="btn btn-success" style="cursor: pointer" onclick="approve(\'' . $li['id_dokumen'] . '\')"><i class="fa fa-check-square"></i></span>&nbsp;';

			$download = $li['file_dokumen'] != null ? '<a href="' . base_url('assets/' . $li['path_folder'] . '/' . $li['file_dokumen']) . '" target="_blank" class="btn btn-info" style="cursor: pointer"><i class="fa fa-download" style="color:white"></i></a>&nbsp;' : '';
			$aksi .= $download;

			// $aksi .= '<span class="btn btn-info" style="cursor: pointer" onclick="view(\'' . $li['id_dokumen'] . '\')"><i class="fa fa-eye"></i></span>&nbsp;';
			$aksi .= '<span class="btn btn-warning" style="cursor: pointer" onclick="sunting(\'' . $li['id_dokumen'] . '\')"><i class="fa fa-edit"></i></span>&nbsp;';
			$aksi .= '<span class="btn btn-danger" style="cursor: pointer" onclick="hapus(\'' . $li['id_dokumen'] . '\')"><i class="fa fa-trash"></i></span>';
			$aksi .= '</div>';
			$row[] = $aksi;

			$data[] = $row;
		}

		$output = array(
			'draw' => intval($_POST['draw']),
			'recordsTotal' => $this->m_dok_keluar->get_all_data(),
			'recordsFiltered' => $this->m_dok_keluar->count_filtered(),
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

		// $dokumen = $this->m_jns_dokumen->read(['id_jns_dokumen' => input('jns_dokumen')])->row_array();
		$config = $this->m_config->read(['status' => 1])->row_array();

		// $no = (int) $dokumen['counter_dokumen'] + 1;

		$jml_data = $this->db->get('tbl_dok_keluar')->num_rows();
		$no = (int) $jml_data + 1;
		if ($no > 999) $cond = $no;
		elseif ($no > 99) $cond = '0' . $no;
		elseif ($no > 9) $cond = '00' . $no;
		else $cond = '000' . $no;

		// set tahun terbitan
		// $no_dok = substr($config['thn_dokumen'], 2, 1) - 2 . substr($config['thn_dokumen'], -1);
		// // set counter nomor dokumen & kode dokumen
		// $no_dok .= '/' . $cond . '-' . $dokumen['id_jns_dokumen'];
		// // set kode group
		// $no_dok .= '/' . $config['nm_group'];
		// $no_dok = $cond;

		$data = array(
			// 'no_dokumen' => $no_dok,
			'no_dokumen2' => input('no_dokumen2'),
			'jns_dokumen' => input('jns_dokumen'),
			'dari' => $config['nm_group'],
			// 'unit_tujuan' => serialize($_POST['li_tujuan']),
			'perihal' => input('perihal'),
			'pembuat' => input('pembuat'),
			'createDate' => date("Y-m-d H:i:s"),
			// 'lampiran' => input('lampiran') == '' ? 0 : input('lampiran'),
			'kategori' => input('kategori'),
			'sts_dokumen' => 'Proses',
			'catatan' => input('catatan') == '' ? NULL : input('catatan'),
			'kd_unit' => input('unit_satker'),
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
		$this->m_jns_dokumen->update(['counter_dokumen' => $no], ['id_jns_dokumen' => input('jns_dokumen')]);
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
			'kd_unit' => input('unit_satker'),
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
		$text = 'Dokumen telah berhasil diubah';
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
