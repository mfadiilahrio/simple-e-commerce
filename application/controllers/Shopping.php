<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shopping extends CI_Controller {

	var $page_name = "Belanja"; 

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('m_base');
		$this->load->model('m_item');
		$this->load->model('m_cart');
		$this->load->model('m_bankaccount');
		$this->timeStamp = date('Y-m-d H:i:s', time());
	}

	public function index()
	{
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->session->flashdata('error');

		$data['records'] = $this->m_item->getitems();
		$data['categories'] = $this->m_base->getListWhere('categories', array(), 'asc');

		$data['page_name'] = $this->page_name;
		$this->header();
		$this->load->view('shopping/index', $data);
		$this->footer();
	}

	public function getitemsbycategoryid()
	{
		$category_id = $this->input->get('category_id');
		if(is_numeric($category_id)) {
			$items = $this->m_item->getitems($category_id);
		} else {
			$items = $this->m_item->getitems();
		}

		echo json_encode($items);
	}

	public function header()
	{
		if($this->session->userdata('id') == null){
			redirect(base_url("auth"));
		}
		
		$data = array();

		if($this->session->userdata('user_id') != null){
			$data['cart_total'] = $this->m_cart->getTotalCartItems($this->session->userdata('user_id'));
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