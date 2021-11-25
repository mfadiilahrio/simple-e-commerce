<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

	function getDataById($id) {
		$this->db->select('users.*');
		$this->db->where('users.id',$id);
		$this->db->limit(1,0);
		return $this->db->get('users')->row();
	}

	function getData() {
		$this->db->select('users.*');
		$this->db->order_by('users.id', 'desc');
		return $this->db->get('users')->result();
	}

	function getProfile() {
		$this->db->select('users.*, auth.email, auth.user_type');
		$this->db->join('auth', 'auth.user_id = users.id');
		$this->db->where('users.id', $this->session->userdata('user_id'));

		return $this->db->get('users')->row();
	}

	function getMechanics() {
		$this->db->select('users.*, auth.id as auth_id, auth.email');
		$this->db->join('auth', 'auth.user_id = users.id');
		$this->db->where('auth.user_type', 'mechanic');
		$this->db->where('auth.status', true);
		$this->db->order_by('auth.id', 'desc');

		return $this->db->get('users')->result();
	}
}

/* End of file m_user.php */
/* Location: ./application/models/m_user.php */