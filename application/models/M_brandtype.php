<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_brandtype extends CI_Model {

	function getbrandtypes() {
		$this->db->select('
			brand_types.*,
			brands.name as brand_name');
		$this->db->join('brands', 'brands.id = brand_types.brand_id', 'left');
		$this->db->where('brand_types.status', true);
		$this->db->order_by('id', 'desc');

		return $this->db->get('brand_types')->result();
	}

	function getbrandtype($id) {
		$this->db->select('
			brand_types.*,
			brands.name as brand_name');
		$this->db->join('brands', 'brands.id = brand_types.brand_id', 'left');
		$this->db->where('brand_types.id', $id);

		return $this->db->get('brand_types')->row();
	}	

}

/* End of file M_brandtype.php */
/* Location: ./application/models/M_brandtype.php */