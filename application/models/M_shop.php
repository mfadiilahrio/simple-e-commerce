<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_shop extends CI_Model {

	function getShops() {
		$this->db->select('
			shops.*,
			areas.name as area_name');
		$this->db->join('areas', 'areas.id = shops.area_id', 'left');

		return $this->db->get_where('shops')->result();
	}

}

/* End of file M_shop.php */
/* Location: ./application/models/M_shop.php */