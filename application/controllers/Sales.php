<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

	var $page_name = "Penjualan"; 

	public function __construct()
	{
		parent::__construct();

		$this->load->model('m_base');
		$this->load->model('m_cart');
		$this->load->model('m_sales');
		$this->load->model('m_booking');
		$this->timeStamp = date("Y-m-d H:i:s", time());
	}

	public function index()
	{
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->session->flashdata('error');

		$month = $this->input->post('month');
		$year = $this->input->post('year');

		$data['month'] = ($month != null) ? $month : date("m");
		$data['year'] = ($year != null) ? $year : date("Y");

		$data['total_all_sales'] = $this->m_sales->getSales();

		if ($month != null && $year != null) {
			$data['total_sales'] = $this->m_sales->getSales($month, $year);
			$data['records'] = $this->m_booking->getBookings(array('bookings.booking_status' => 'completed'), $month, $year);
		} else {
			$data['total_sales'] = null;
			$data['records'] = null;
		}

		$data['page_name'] = $this->page_name;
		$this->header();
		$this->load->view('sales/index', $data);
		$this->footer();
	}

	public function printsales()
	{
		$month = $this->input->get('month');
		$year = $this->input->get('year');

		$data['month'] = ($month != null) ? $month : date("m");
		$data['year'] = ($year != null) ? $year : date("Y");

		$data['total_sales'] = $this->m_sales->getSales($month, $year);
		$data['records'] = $this->m_booking->getBookings(array('bookings.booking_status' => 'completed'), $month, $year);

		$this->load->view('sales/sales_print', $data);	
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

/* End of file Sales.php */
/* Location: ./application/controllers/Sales.php */