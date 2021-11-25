<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookingservice extends CI_Controller {

	var $page_name = "Booking Servis"; 

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('m_base');
		$this->load->model('m_cart');
		$this->load->model('m_bankaccount');
		$this->load->model('m_booking');
		$this->timeStamp = date('Y-m-d H:i:s', time());
	}

	public function index()
	{
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->session->flashdata('error');

		if ($this->input->get('booking_id') != null) {
			$data['record'] = $this->m_booking->getBooking(array('bookings.id' => $this->input->get('booking_id')));
		} else {
			$data['record'] = null;
		}
		$data['areas'] = $this->m_base->getListWhere('areas', array());
		$data['brands'] = $this->m_base->getListWhere('brands', array(), 'asc');
		$data['booking_cart_total'] = $this->m_cart->getTotalBookingCartItems($this->session->userdata('user_id'));

		$data['page_name'] = $this->page_name;
		$this->header();
		$this->load->view('bookingservice/index', $data);
		$this->footer();
	}

	public function getbrandtypebybrandid()
	{
		$data = $this->m_base->getListWhere(
			'brand_types',
			array(
				'brand_id' => $this->input->get('brand_id')
			)
		);

		echo json_encode($data);
	}

	public function getitemsbybrandtypeid()
	{
		$items = $this->m_base->getListWhere(
			'items',
			array(
				'brand_type_id' => $this->input->get('brand_type_id')
			)
		);

		$common_items = $this->m_base->getListWhere(
			'items',
			array(
				'brand_type_id' => NULL
			)
		);

		$merged_items = array_merge($items, $common_items);

		echo json_encode($merged_items);
	}

	public function header()
	{
		if($this->session->userdata('id') == null){
			redirect(base_url("auth"));
		}

		if($this->session->userdata('user_id') != null){
			$data['cart_total'] = $this->m_cart->getTotalCartItems($this->session->userdata('user_id'));
			$data['booking_cart_total'] = $this->m_cart->getTotalBookingCartItems($this->session->userdata('user_id'));
		}
		
		$this->load->view('templates/header', $data);
	}

	public function footer()
	{
		$this->load->view('templates/footer');
	}

}

/* End of file Bookingservice */
/* Location: ./application/controllers/Bookingservice */