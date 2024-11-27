<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_login extends CI_Model
{
	private $_table = 'tbl_user';

	public function login($username, $password)
	{
		$where = array(
			'nm_user' => $username,
			'password' => $password
		);
		$cek = $this->db->get_where($this->_table, $where);
		return $cek->num_rows();
	}

	public function show()
	{
		$data = $this->db->get($this->_table)->result_array();
		return $data;
	}

	public function read($key)
	{
		$data = $this->db->get_where($this->_table, $key);
		return $data;
	}

	public function get_user($username)
	{
		$user = $this->db->get_where($this->_table, ['nm_user' => $username])->row_array();
		return $user;
	}
}
