<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_base extends CI_Model {

	function getWhere($table_name, $where){		
		return $this->db->get_where($table_name, $where)->row();
	}

	function getListWhere($table_name, $where, $order = 'desc'){
		$this->db->order_by('id', $order);		
		return $this->db->get_where($table_name, $where)->result();
	}

	function updateData($table_name, $data, $parameter, $id) {
		$this->db->where($parameter, $id);
		if($this->db->update($table_name, $data)) {
			return true;
		} else {
			return false;
		}
	}

	function createData($table_name, $data) {
		if($this->db->insert($table_name, $data)) {
			return true;
		} else {
			return false;
		}
	}

	function deleteData($table_name, $where){
		if ($this->db->delete($table_name, $where)) {
			return true;
		} else {
			return false;
		}
	}

	function createDataWithInsertID($table_name, $data) {
		if($this->db->insert($table_name, $data)) {
			return$this->db->insert_id();
		} else {
			return false;
		}
	}

	function countData($table_name, $where) {
		foreach ($where as $key => $value) {
			$this->db->where($key, $value);	
		}
		$this->db->from($table_name);
		return $this->db->count_all_results();
	}

}

/* End of file m_base.php */
/* Location: ./application/models/m_base.php */