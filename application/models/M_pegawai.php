<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_pegawai extends CI_Model
{
	private $_table = 'tbl_user';

	public function show()
	{
		$data = $this->db->select('*')->from($this->_table)->get()->result_array();
		return $data;
	}

	public function create($data)
	{
		$this->db->insert($this->_table, $data);
	}

	public function read($key)
	{
		$data = $this->db->select('*')->from($this->_table)->where($key)->get();
		return $data;
	}

	public function update($data, $key)
	{
		$this->db->update($this->_table, $data, $key);
	}

	public function delete($key)
	{
		$this->db->delete($this->_table, $key);
	}
}
