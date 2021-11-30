<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_booking extends CI_Model {

	function getBooking($where) {
		$this->db->select('
			bookings.*,
			u.name as user_name,
			u.phone as user_phone,
			auth.email as user_email,
			shops.name as shop_name,
			shops.phone as shop_phone,
			shops.address as shop_address,
			shops.postal_code as shop_postal_code,
			areas.name as area_name,
			banks.name as bank_name,
			bank_accounts.account_number,
			(SELECT SUM(booking_items.price * booking_items.qty) FROM booking_items WHERE booking_items.booking_id = bookings.id) + bookings.other_cost as total');
		$this->db->join('users u', 'u.id = bookings.user_id', 'left');
		$this->db->join('auth', 'auth.user_id = u.id', 'left');
		$this->db->join('shops', 'shops.id = bookings.shop_id', 'left');
		$this->db->join('areas', 'areas.id = bookings.area_id', 'left');
		$this->db->join('bank_accounts', 'bank_accounts.id = bookings.bank_account_id', 'left');
		$this->db->join('banks', 'banks.id = bank_accounts.bank_id', 'left');
		$this->db->order_by('bookings.id', 'desc');

		$data = $this->db->get_where('bookings', $where)->row();

		return $this->generateBookingStatus($data);
	}

	function getBookings($where, $month = NULL, $year = NULL) {
		$this->db->select('
			bookings.*,
			u.name as user_name,
			u.phone as user_phone,
			auth.email as user_email,
			shops.name as shop_name,
			shops.phone as shop_phone,
			shops.address as shop_address,
			shops.postal_code as shop_postal_code,
			areas.name as area_name,
			banks.name as bank_name,
			bank_accounts.account_number,
			(SELECT SUM(booking_items.price * booking_items.qty) FROM booking_items WHERE booking_items.booking_id = bookings.id) + bookings.other_cost as total');
		$this->db->join('users u', 'u.id = bookings.user_id', 'left');
		$this->db->join('auth', 'auth.user_id = u.id', 'left');
		$this->db->join('shops', 'shops.id = bookings.shop_id', 'left');
		$this->db->join('areas', 'areas.id = bookings.area_id', 'left');
		$this->db->join('bank_accounts', 'bank_accounts.id = bookings.bank_account_id', 'left');
		$this->db->join('banks', 'banks.id = bank_accounts.bank_id', 'left');
		$this->db->order_by("bookings.created_at", "desc");

		if ($month != null && $year != null) {
			$this->db->where('MONTH(bookings.date)', $month);
			$this->db->where('YEAR(bookings.date)', $year);	
		}

		$result = $this->db->get_where('bookings', $where)->result();

		foreach ($result as $row) {
			$this->generateBookingStatus($row);
		}

		return $result;
	}

	function getBookingItems($id) {
		$this->db->select('
			booking_items.*, 
			items.id as item_id, 
			items.name,
			items.image_url, 
			categories.name as category,
			brands.name as brand');
		$this->db->join('items', 'items.id = booking_items.item_id', 'left');
		$this->db->join('categories', 'categories.id = items.category_id', 'left');
		$this->db->join('brands', 'brands.id = items.brand_id', 'left');
		$this->db->where('booking_items.booking_id', $id);

		$datas = $this->db->get('booking_items')->result();

		foreach ($datas as $data) {
			if (($data->brand != null && $data->category != null)) {
				$data->brand_name = $data->brand;	
			} else {
				$data->brand_name = '-';
			}

			$data->subtotal = $data->price * $data->qty;
		}

		return $datas;
	}

	function generateBookingStatus($data) {

		// **Untuk booking urutan statusnya** 
		// 1. waiting_confirmation 
		// 2. confirmed  
		// 3. process 
		// 4. waiting_payment 
		// 5. checking_payment 
		// 6. completed

		// **Untuk shopping urutan statusnya** 
		// 1. waiting_confirmation 
		// 2. confirmed 
		// 3. waiting_payment 
		// 4. checking_payment 
		// 5. process 
		// 6. shipped 
		// 7. completed 

		if($data != null) {
			if ($data->booking_status == 'waiting_confirmation') {
				//1
				$data->booking_status_name = 'Menunggu Konfirmasi';
				$data->next_booking_status = 'waiting_payment';
				$data->next_booking_status_name = 'Konfirmasi';
			} else if ($data->booking_status == 'waiting_payment') {
				//2
				$data->booking_status_name = ($this->session->userdata('user_type') == 'customer') ? 'Belum Bayar' : 'Menunggu Pembayaran';
				$data->next_booking_status = 'checking_payment';
				$data->next_booking_status_name = 'Mengecek Pembayaran';
			} else if ($data->booking_status == 'checking_payment') {
				//3
				$data->booking_status_name = 'Mengecek Pembayaran';
				$data->next_booking_status = 'process';
				$data->next_booking_status_name = 'Cek Pembayaran';
			} else if ($data->booking_status == 'process') {
				//4
				$data->booking_status_name = 'Diproses';
				$data->next_booking_status = 'shipped';
				$data->next_booking_status_name = 'Kirim';
			} else if ($data->booking_status == 'shipped') {
				//5
				$data->booking_status_name = 'Dikirim';
				$data->next_booking_status = 'completed';
				$data->next_booking_status_name = 'Selesai';
			} else if ($data->booking_status == 'completed') {
				//6
				$data->booking_status_name = 'Selesai';
				$data->next_booking_status = null;
				$data->next_booking_status_name = null;
			} else if ($data->booking_status == 'canceled') {
				//7
				$data->booking_status_name = 'Dibatalkan';
				$data->next_booking_status = null;
				$data->next_booking_status_name = null;
			}

			return $data;
		}
	}
}

/* End of file M_booking.php */
/* Location: ./application/models/M_booking.php */