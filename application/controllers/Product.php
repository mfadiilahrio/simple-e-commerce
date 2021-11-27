<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	var $page_name = "Produk"; 

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('m_base');
		$this->load->model('m_item');
		$this->load->model('m_cart');
		$this->timeStamp = date('Y-m-d H:i:s', time());
	}

	public function index()
	{
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');

		$data['records'] = $this->m_item->getitems();

		$data['page_name'] = $this->page_name;
		$this->header();
		$this->load->view('product/index', $data);
		$this->footer();
	}

	public function edit()
	{
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->session->flashdata('error');

		$id = $this->input->get('id');

		if ($id == '') {
			$data['record'] = '';
		} else {
			$where = array(
				'items.id' => $id
			);
			$data['record'] = $this->m_item->getitem($where);
		}

		$data['brands'] = $this->m_base->getListWhere('brands', array(), 'asc');
		$data['categories'] = $this->m_base->getListWhere('categories', array(), 'asc');

		$data['page_name'] = $this->page_name;
		$this->header();
		$this->load->view('product/edit', $data);
		$this->footer();
	}

	public function update()
	{
		$id = $this->input->post('id');
		$brand_id = $this->input->post('brand_id');
		$category_id = $this->input->post('category_id');
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		$price = $this->input->post('price');
		$qty = $this->input->post('qty');
		
		$this->form_validation->set_rules('name', 'Nama', 'required');
		$this->form_validation->set_rules('description', 'Deskripsi', 'required');
		$this->form_validation->set_rules('brand_id', 'Merek', 'numeric|required');
		$this->form_validation->set_rules('category_id', 'Kategori', 'numeric|required');
		$this->form_validation->set_rules('price', 'Harga', 'required');
		$this->form_validation->set_rules('qty', 'Qty', 'required');

		if($this->form_validation->run()) {
			$data = array(
				'name' => $name,
				'description' => $description,
				'brand_id' => $brand_id,
				'category_id' => $category_id,
				'price' => $price,
				'qty' => $qty
			);

			if ($id == '') {
				if ($item_id = $this->m_base->createDataWithInsertID('items', $data)) {
					$this->uploadItemImage($item_id);
					$this->session->set_flashdata('success', 'Data berhasil ditambahkan');
				} else {
					$this->session->set_flashdata('error', 'Data gagal ditambahkan');
				}
			} else {
				if ($this->m_base->updateData('items', $data, 'id', $id)) {
					$this->uploadItemImage($id);
					$this->session->set_flashdata('success', 'Data berhasil diubah');
				} else {
					$this->session->set_flashdata('error', 'Data gagal diubah');
				}
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
			redirect('product/edit?id='.$id,'refresh');
		}
		redirect('product','refresh');

	}

	public function delete()
	{
		$id = $this->input->post('id');

		$data = array(
			'status' => false
		);

		if ($this->m_base->updateData('items', $data, 'id', $id)) {
			$this->session->set_flashdata('success', 'Produk berhasil dihapus');
			echo json_encode(array('message' => 'Produk berhasil dihapus'));
		} else {
			$this->session->set_flashdata('error', 'Produk gagal dihapus');
			echo json_encode(array('error' => 'Error saat menghapus produk'));
		}
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

	private function uploadItemImage($id)
	{
		if (isset($_FILES["image"]["name"])) {
			$filename = 'item_'.$id.'.png';

			$config['upload_path']          = './assets/images/items/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['file_name']            = $filename;
			$config['overwrite']			= true;
			$config['max_size']             = 1024;

			$this->load->library('upload', $config);

			$this->upload->do_upload('image');
		}

		$image_data = array(
			'image_url' => 'assets/images/items/'.$filename
		);

		$this->m_base->updateData('items', $image_data, 'id', $id);
	}

}

/* End of file Product.php */
/* Location: ./application/controllers/Product.php */