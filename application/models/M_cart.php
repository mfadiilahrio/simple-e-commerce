<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_cart extends CI_Model {

	function nonactivateCarts($data, $user_id, $type) {
		$this->db->where('user_id', $user_id);
		$this->db->where('type', $type);
		if($this->db->update('carts', $data)) {
			return true;
		} else {
			return false;
		}
	}

	function getData($user_id, $type) {
		$this->db->select('
			cart_items.*, 
			items.id as item_id, 
			items.name, 
			items.price, 
			items.image_url, 
			brand_types.name as brand_type,
			brands.name as brand');
		$this->db->join('carts', 'carts.id = cart_items.cart_id', 'left');
		$this->db->join('items', 'items.id = cart_items.item_id', 'left');
		$this->db->join('brand_types', 'brand_types.id = items.brand_type_id', 'left');
		$this->db->join('brands', 'brands.id = brand_types.brand_id', 'left');
		$this->db->where('carts.user_id', $user_id);
		$this->db->where('carts.type', $type);
		$this->db->where('carts.status', true);

		$datas = $this->db->get('cart_items')->result();

		foreach ($datas as $data) {
			if (($data->brand != null && $data->brand_type != null)) {
				$data->brand_name = $data->brand.' '.$data->brand_type;	
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
		$this->db->where('carts.type', 'shopping');
		$this->db->where('carts.status', true);
		return $this->db->get('carts')->row();
	}

	function getTotalBookingCartItems($user_id) {
		$this->db->select_sum('cart_items.qty');
		$this->db->join('cart_items', 'cart_items.cart_id = carts.id', 'left');
		$this->db->where('carts.user_id', $user_id);
		$this->db->where('carts.type', 'booking');
		$this->db->where('carts.status', true);
		return $this->db->get('carts')->row();
	}

}

/* End of file M_cart.php */
/* Location: ./application/models/M_cart.php */