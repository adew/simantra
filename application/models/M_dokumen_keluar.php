<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dokumen_keluar extends CI_Model
{
	/*
	|--------------------------------------------------------------------------
	| Datatables Server-side Processing
	|--------------------------------------------------------------------------
	*/

	var $table = 'tbl_dok_keluar';
	var $column_order = array(null, 'a.no_dokumen', 'a.perihal', 'b.jns_dokumen', 'd.jns_kategori', 'a.createDate', null);
	var $column_search = array('b.jns_dokumen', 'a.no_dokumen', 'a.no_dokumen2', 'a.perihal', 'a.sts_dokumen', 'a.createDate', 'a.pembuat', 'd.jns_kategori', 'a.unit_tujuan', 'c.nm_unit');
	var $order = array('a.no_dokumen' => 'desc');

	function _get_datatable_query($bulan = '', $tahun = '', $username = '')
	{
		// $this->db->from($this->table);
		$this->db->select('a.*, c.nm_unit, b.jns_dokumen, d.jns_kategori')->from($this->table . ' a')
			->join('tbl_jns_dokumen b', 'a.jns_dokumen = b.id_jns_dokumen', 'left')
			->join('tbl_unit c', 'a.kd_unit = c.kd_unit', 'left')
			->join('tbl_kategori d', 'a.kategori = d.id_kategori', 'left');

		if ($username == 'admin') {
			if ($bulan == 'all') {
				$array = array('YEAR(a.createDate)' => $tahun);
			} else {
				$array = array('YEAR(a.createDate)' => $tahun, 'MONTH(a.createDate)' => $bulan);
			}
		} else {
			if ($bulan == 'all') {
				$array = array('YEAR(a.createDate)' => $tahun, 'c.bagian' => $username);
			} else {
				$array = array('YEAR(a.createDate)' => $tahun, 'MONTH(a.createDate)' => $bulan, 'c.bagian' => $username);
			}
		}

		$this->db->where($array);

		$i = 0;
		foreach ($this->column_search as $item) {
			if ($_POST['search']['value']) {
				if ($i === 0) {
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) $this->db->group_end();
			}
			$i++;
		}

		if (isset($_POST['order'])) {
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($bulan, $tahun, $username)
	{
		$this->_get_datatable_query($bulan, $tahun, $username);
		if ($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			return $query->result_array();
		}
	}

	function count_filtered()
	{
		$this->_get_datatable_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_all_data()
	{
		$this->db->get($this->table);
		return $this->db->count_all_results();
	}

	/*
	|--------------------------------------------------------------------------
	| Module CRUD
	|--------------------------------------------------------------------------
	*/

	public function show()
	{
		$data = $this->db->get($this->table)->result_array();
		return $data;
	}

	public function create($data)
	{
		$this->db->insert($this->table, $data);
	}

	public function read($key)
	{
		$data = $this->db->get_where($this->table, $key);
		return $data;
	}

	public function update($data, $key)
	{
		$this->db->update($this->table, $data, $key);
	}

	public function delete($key)
	{
		$this->db->delete($this->table, $key);
	}

	public function get_data_chart($tahun, $username)
	{
		$where = "";
		if ($username != 'admin')
			$where = "AND b.bagian = '$username'";

		$sql = "SELECT DATE_FORMAT(a.createDate, '%m') AS bulan, COUNT(a.id_dokumen) AS count FROM tbl_dok_keluar a
		LEFT JOIN tbl_unit b
		ON a.kd_unit= b.kd_unit
		WHERE YEAR(a.createDate) = $tahun $where
		GROUP BY MONTH(a.createDate)";

		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function get_jumlah_surat($tahun, $username)
	{
		$where = "";
		if ($username != 'admin')
			$where = "AND b.bagian = '$username'";

		$sql = "SELECT * FROM tbl_dok_keluar a
		LEFT JOIN tbl_unit b
		ON a.kd_unit= b.kd_unit
		WHERE YEAR(a.createDate) = $tahun $where";

		$query = $this->db->query($sql);

		return $query->num_rows();
	}

	public function lihat_nomor($tahun)
	{

		$sql = "SELECT no_dokumen FROM tbl_dok_keluar WHERE YEAR(createDate) = $tahun AND sts_dokumen = 'Diterima' ORDER BY no_dokumen DESC LIMIT 1";

		$query = $this->db->query($sql);

		return $query->row_array();
	}
}
