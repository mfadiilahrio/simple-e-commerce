<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_cart extends CI_Model {

	function nonactivateCarts($data, $user_id) {
		$this->db->where('user_id', $user_id);
		if($this->db->update('carts', $data)) {
			return true;
		} else {
			return false;
		}
	}

	function getData($user_id) {
		$this->db->select('
			cart_items.*, 
			items.id as item_id, 
			items.name, 
			items.price, 
			items.image_url, 
			categories.name as category,
			brands.name as brand');
		$this->db->join('carts', 'carts.id = cart_items.cart_id', 'left');
		$this->db->join('items', 'items.id = cart_items.item_id', 'left');
		$this->db->join('categories', 'categories.id = items.category_id', 'left');
		$this->db->join('brands', 'brands.id = items.brand_id', 'left');
		$this->db->where('carts.user_id', $user_id);
		$this->db->where('carts.status', true);

		$datas = $this->db->get('cart_items')->result();

		foreach ($datas as $data) {
			if (($data->brand != null)) {
				$data->brand_name = $data->brand;	
			} else {
				$data->brand_name = '-';
			}
		}

		return $datas;
	}

	function updateQty($type, $parameter, $id) {
		$qty = ($type == 'increase') ? 'qty + 1' : 'qty - 1';

		$this->db->set('qty', $qty, false);
		$this->db->where($parameter, $id);
		if($this->db->update('cart_items')) {
			return true;
		} else {
			return false;
		}
	}

	function getTotalCartItems($user_id) {
		$this->db->select_sum('cart_items.qty');
		$this->db->join('cart_items', 'cart_items.cart_id = carts.id', 'left');
		$this->db->where('carts.user_id', $user_id);
		$this->db->where('carts.status', true);
		return $this->db->get('carts')->row();
	}

}

/* End of file M_cart.php */
/* Location: ./application/models/M_cart.php */