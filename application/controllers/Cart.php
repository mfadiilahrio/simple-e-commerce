<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

	var $page_name = "Keranjang"; 

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
		$data['message'] = $this->session->flashdata('message');

		$data['booking_id'] = $this->input->get('booking_id');
		$data['user_id'] = $this->input->get('user_id');
		$data['areas'] = $this->m_base->getListWhere('areas', array());
		$data['bank_accounts'] = $this->m_bankaccount->getBankAccounts(array());
		$data['records'] = $this->m_cart->getData($data['user_id']);

		$data['hide_content'] = true;

		$cart_total = $this->m_cart->getTotalCartItems($data['user_id']);
		if ($cart_total->qty > 0) {
			$data['hide_content'] = false; 
		}

		$data['subtotal'] = $this->getcartsubtotal($data['user_id']);

		$data['page_name'] = $this->page_name;
		$this->header();
		$this->load->view('cart/index', $data);
		$this->footer();
	}

	public function completebooking()
	{
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->session->flashdata('error');

		$data['areas'] = $this->m_base->getListWhere('areas', array());
		$data['bank_accounts'] = $this->m_bankaccount->getBankAccounts(array());

		$data['page_name'] = $this->page_name;
		$this->header();
		$this->load->view('cart/index', $data);
		$this->footer();
	}

	public function addtocart()
	{
		$user_id = $this->input->post('user_id');
		$item_id = $this->input->post('item_id');

		if ($user_id != null && $item_id != null) {
			$cart_where = array('user_id' => $user_id, 'status' => true);

			$cart = $this->m_base->getWhere('carts', $cart_where);

			if ($cart != null) {
				$this->addtocartitem($user_id, $item_id);
			} else {
				if ($this->m_base->createData('carts', $cart_where)) {
					$this->addtocartitem($user_id, $item_id);
				} else {
					echo json_encode(array('error' => 'Error saat menambahkan ke keranjang'));
				}
			}	
		} else {
			echo json_encode(array('error' => 'Error saat menambahkan ke keranjang'));	
		}
	}

	public function addtocartitem($user_id, $item_id)
	{
		$cart_where = array('user_id' => $user_id, 'status' => true);

		$cart = $this->m_base->getWhere('carts', $cart_where);

		$cart_item_where = array('cart_id' => $cart->id, 'item_id' => $item_id);

		$cart_item = $this->m_base->getWhere('cart_items', $cart_item_where);

		$cart_item_data = array(
			'cart_id' => $cart->id,
			'item_id' => $item_id
		);

		if ($cart_item != null) {
			$cart_item_data['qty'] = $cart_item->qty + 1;

			if ($this->m_base->updateData('cart_items', $cart_item_data, 'id', $cart_item->id)) {
				echo json_encode(array(
					'message' => 'Sukses menambahkan ke keranjang', 
					'result' => $this->m_cart->getTotalCartItems($user_id)
				));
			} else {
				echo json_encode(array('error' => 'Error saat menambahkan ke keranjang'));
			}
		} else {
			if ($this->m_base->createData('cart_items', $cart_item_data)) {
				echo json_encode(array(
					'message' => 'Sukses menambahkan ke keranjang', 
					'result' => $this->m_cart->getTotalCartItems($user_id)
				));
			} else {
				echo json_encode(array('error' => 'Error saat menambahkan ke keranjang'));
			}
		}
	}

	public function updatecartitem() {
		$user_id = $this->input->post('user_id');
		$id = $this->input->post('id');
		$item_id = $this->input->post('item_id');
		$type_update = $this->input->post('type_update');

		$item = $this->m_base->getWhere('items', array('id' => $item_id));
		$cart_item = $this->m_base->getWhere('cart_items', array('id' => $id));

		if ($id != null && $type_update != null && $item != null && $cart_item != null) {
			if ($type_update == 'decrease' && $cart_item->qty < 2) {
				echo json_encode(array('error' => 'Minimum kuantitas adalah 1'));
			} else if ($type_update == 'increase' && $cart_item->qty >= $item->qty) {
				echo json_encode(array('error' => 'Stok habis'));
			} else {
				if ($this->m_cart->updateQty($type_update, 'id', $id)) {
					$result = array(
						'subtotal' => $this->getcartsubtotal($user_id), 
						'cartItem' => $this->m_base->getWhere('cart_items', array('id' => $id)),
						'cartTotal' => $this->m_cart->getTotalCartItems($user_id)
					);

					echo json_encode(array(
						'message' => 'Sukses update qty', 
						'result' => $result
					));
				} else {
					echo json_encode(array('error' => 'Error saat mengupdate qty'));
				}
			}
		} else {
			echo json_encode(array('error' => 'Request kosong, error saat mengupdate qty'));
		}
	}

	public function deletecartitem() {
		$id = $this->input->post('id');
		$user_id = $this->input->post('user_id');

		if ($id != null) {
			if ($this->m_base->deleteData('cart_items', array('id' => $id))) {
				
				$hide_content = true;

				$cart_total = $this->m_cart->getTotalCartItems($user_id);

				if ($cart_total->qty > 0) {
					$hide_content = false; 
				}

				$result = array(
					'subtotal' => $this->getcartsubtotal($user_id),
					'cartTotal' => $this->m_cart->getTotalCartItems($user_id),
					'hideContent' => $hide_content
				);

				echo json_encode(array(
					'message' => 'Sukses menambahkan ke keranjang', 
					'result' => $result
				));
			} else {
				echo json_encode(array('error' => 'Error saat menghapus dari keranjang'));
			}
		} else {
			echo json_encode(array('error' => 'Error saat menghapus dari keranjang'));
		}
	}

	public function getcartsubtotal($user_id) {
		$data = $this->m_cart->getData($user_id);

		$subtotal = 0;

		foreach ($data as $record) {
			$subtotal = $subtotal + ($record->price * $record->qty);
		}

		return number_format($subtotal, 0, ",", ".");
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

/* End of file Cart.php */
/* Location: ./application/controllers/Cart.php */