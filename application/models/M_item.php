<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_item extends CI_Model {

	function getitems() {
		$this->db->select('
			items.*,
			brand_types.name as brand_type_name,
			brands.name as brand_name');
		$this->db->join('brand_types', 'brand_types.id = items.brand_type_id', 'left');
		$this->db->join('brands', 'brands.id = brand_types.brand_id', 'left');
		$this->db->where('items.status', true);
		$this->db->order_by('id', 'desc');

		return $this->db->get_where('items')->result();
	}

	function getitem($where) {
		$this->db->select('
			items.*,
			brand_types.name as brand_type_name,
			brands.id as brand_id,
			brands.name as brand_name');
		$this->db->join('brand_types', 'brand_types.id = items.brand_type_id', 'left');
		$this->db->join('brands', 'brands.id = brand_types.brand_id', 'left');

		return $this->db->get_where('items', $where)->row();
	}

	function decreaseQty($id, $qty) {
		$this->db->set('qty', "qty - $qty", false);
		$this->db->where('id', $id);
		if($this->db->update('items')) {
			return true;
		} else {
			return false;
		}
	}

}

/* End of file M_item.php */
/* Location: ./application/models/M_item.php */