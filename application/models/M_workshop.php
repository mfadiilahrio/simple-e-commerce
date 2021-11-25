<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_workshop extends CI_Model {

	function getWorkshops() {
		$this->db->select('
			workshops.*,
			areas.name as area_name');
		$this->db->join('areas', 'areas.id = workshops.area_id', 'left');

		return $this->db->get_where('workshops')->result();
	}

}

/* End of file M_workshop.php */
/* Location: ./application/models/M_workshop.php */