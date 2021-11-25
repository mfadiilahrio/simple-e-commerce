<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_sales extends CI_Model {

	function getSales($month = NULL, $year = NULL) {
		$this->db->select('SUM((SELECT SUM(booking_items.price * booking_items.qty) FROM booking_items WHERE booking_items.booking_id = bookings.id) + bookings.other_cost) as total');
		$this->db->where('bookings.booking_status', 'completed');
		if ($month != null && $year != null) {
			$this->db->where('MONTH(date)', $month);
			$this->db->where('YEAR(date)', $year);	
		}

		return $this->db->get('bookings')->row();
	}

}

/* End of file M_sales.php */
/* Location: ./application/models/M_sales.php */