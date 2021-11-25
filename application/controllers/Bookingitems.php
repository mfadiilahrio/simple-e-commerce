<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookingitems extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('m_base');
	}

	public function index()
	{
		
	}

	public function addtobookingitem()
	{
		$booking_id = $this->input->post('booking_id');
		$item_id = $this->input->post('item_id');

		$booking_item_where = array('booking_id' => $booking->id, 'item_id' => $item_id);

		$booking_item = $this->m_base->getWhere('booking_items', $booking_item_where);

		$booking_item_data = array(
			'booking_id' => $booking->id,
			'item_id' => $item_id
		);

		if ($booking_item != null) {
			$booking_item_data['qty'] = $booking_item->qty + 1;

			if ($this->m_base->updateData('booking_items', $booking_item_data, 'id', $booking_item->id)) {
				echo json_encode(array(
					'message' => 'Sukses menambahkan barang', 
					'result' => $this->getbookingtotalbytype($type)
				));
			} else {
				echo json_encode(array('error' => 'Error saat menambahkan barang'));
			}
		} else {
			if ($this->m_base->createData('booking_items', $booking_item_data)) {
				echo json_encode(array(
					'message' => 'Sukses menambahkan ke barang', 
					'result' => $this->getbookingtotalbytype($type)
				));
			} else {
				echo json_encode(array('error' => 'Error saat menambahkan barang'));
			}
		}
	}

}

/* End of file Bookingitems.php */
/* Location: ./application/controllers/Bookingitems.php */