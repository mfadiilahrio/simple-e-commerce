<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shopping extends CI_Controller {

	var $page_name = "Belanja"; 

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('m_base');
		$this->load->model('m_cart');
		$this->load->model('m_bankaccount');
		$this->timeStamp = date('Y-m-d H:i:s', time());
	}

	public function index()
	{
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->session->flashdata('error');

		$data['brands'] = $this->m_base->getListWhere('brands', array(), 'asc');

		$data['page_name'] = $this->page_name;
		$this->header();
		$this->load->view('shopping/index', $data);
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
		
		$data = array();

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

/* End of file Shopping.php */
/* Location: ./application/controllers/Shopping.php */