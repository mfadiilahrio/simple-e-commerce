<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

	var $page_name = "Pesanan"; 

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('m_base');
		$this->load->model('m_booking');
		$this->load->model('m_cart');
		$this->load->model('m_item');
		$this->load->model('m_shop');
		$this->load->model('m_user');
		$this->load->model('m_bankaccount');
		$this->timeStamp = date('Y-m-d H:i:s', time());
	}

	public function index()
	{
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');

		$id = $this->input->get('id');
		$print = ($this->input->get('print') != null) ? true : false;

		if ($id != null) {
			$data['bank_accounts'] = $this->m_bankaccount->getBankAccounts(array());
			$data['record'] = $this->m_booking->getBooking(array('bookings.id' => $id));
			$data['records'] = $this->m_booking->getBookingItems($id);
			$data['shops'] = $this->m_shop->getShops();

			$subtotal = 0;

			foreach ($data['records'] as $booking_item) {
				$subtotal = $subtotal + ($booking_item->price * $booking_item->qty);
			}

			$data['subtotal'] = $subtotal + $data['record']->other_cost;

			$view = ($print) ? 'booking/invoice_print' : 'booking/booking_detail';
		} else {
			if ($this->session->userdata('user_type') == 'customer') {
				$where['bookings.user_id'] = $this->session->userdata('user_id');
			} else {
				$where = array();
			}

			$data['records'] = $this->m_booking->getBookings($where);

			$view = 'booking/index';
		}

		$data['page_name'] = $this->page_name;

		if (!$print) {
			$this->header();
		}
		$this->load->view($view, $data);
		if (!$print) {
			$this->footer();
		}
	}

	public function createbooking()
	{
		$type   = $this->input->post('type');
		$area_id   = $this->input->post('area_id');
		$complaint   = $this->input->post('complaint');
		$date   = $this->input->post('date');
		$address   = $this->input->post('address');
		$postal_code   = $this->input->post('postal_code');
		$bank_account_id   = $this->input->post('bank_account_id');

		if ($type == 'shopping' || $type == 'booking') {
			$this->form_validation->set_rules('type', 'Tipe Pesanan', 'required');
			$this->form_validation->set_rules('area_id', 'Area', 'required|numeric');
			$this->form_validation->set_rules('address', 'Alamat', 'required');

			if ($type == 'booking') {
				$this->form_validation->set_rules('complaint', 'Keluhan', 'required');
				$this->form_validation->set_rules('date', 'Tanggal', 'required');
			} else {
				$this->form_validation->set_rules('bank_account_id', 'Metode pembayaran', 'required|numeric');
				$this->form_validation->set_rules('postal_code', 'Kode pos', 'required|numeric');
			}

			if($this->form_validation->run()) {

				$data = array(
					'user_id' => $this->session->userdata('user_id'),
					'service_id' => ($type == 'shopping') ? 1 : 2,
					'area_id' => $area_id,
					'type' => $type,
					'complaint' => ($type == 'booking') ? $complaint : NULL,
					'date' => ($type == 'shopping') ? $this->timeStamp : $date,
					'address' => $address,
					'phone' => $this->session->userdata('phone'),
					'postal_code' => $postal_code,
					'bank_account_id' => $bank_account_id
				);

				if ($booking_id = $this->m_base->createDataWithInsertID('bookings', $data)) {

					if ($type == 'booking') {
						$this->session->set_flashdata('success', 'Pesanan anda berhasil dibuat');
						redirect('booking','refresh');
					} else {
						$cart_items = $this->m_cart->getData($this->session->userdata('user_id'), $type);

						foreach ($cart_items as $cart_item) {
							$booking_item_data = array(
								'booking_id' => $booking_id,
								'item_id' => $cart_item->item_id,
								'price' => $cart_item->price,
								'qty' => $cart_item->qty
							);

							$this->m_base->createData('booking_items', $booking_item_data);

							$this->m_item->decreaseQty($cart_item->item_id, $cart_item->qty);
						}

						$cart_data = array(
							'status' => false
						);

						if ($this->m_cart->nonactivateCarts($cart_data, $this->session->userdata('user_id'), $type)) {
							$this->session->set_flashdata('success', 'Pesanan anda berhasil dibuat');
							redirect('booking','refresh'); 
						} else { 
							$this->session->set_flashdata('message', 'Gagal menghapus daftar keranjang anda'); 
						}
					}
				} else {
					$this->session->set_flashdata('message', 'Pesanan anda gagal dibuat');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect('cart?type='.$type,'refresh');
		} else {
			$this->session->set_flashdata('message', "Error saat membuat pesanan");
			redirect('cart?type=booking','refresh');
		}
	}

	public function adminupdatebookingstatus()
	{
		if ($this->session->userdata('user_type') == 'admin') {
			$user_id = $this->session->userdata('user_id');
			$id = $this->input->post('id');
			$type = $this->input->post('type');
			$booking_status = $this->input->post('booking_status');
			$shop_id = $this->input->post('shop_id');
			$other_cost = $this->input->post('other_cost');
			$other_cost_note = $this->input->post('other_cost_note');
			$awb_number = $this->input->post('awb_number');
			$payment_url = $this->input->post('payment_url');

			if ($user_id == null || $id == null || $type == null || $booking_status == null) {
				echo json_encode(array('error' => 'Error saat mengupdate status pesanan'));
			} else {
				if ($booking_status == 'confirmed') {
					if ($type == 'booking') {
						if ($shop_id == null) {
							echo json_encode(array('error' => 'Error saat mengupdate status pesanan'));
						} else {
							$data = array(
								'shop_id' => $shop_id,
								'booking_status' => $booking_status
							);
						}
					} else {
						$data = array(
							'booking_status' => $booking_status
						);
					}
				} else if ($booking_status == 'waiting_payment') {
					if ($type == 'booking') {
						$data = array(
							'other_cost' => $other_cost,
							'other_cost_note' => $other_cost_note,
							'booking_status' => $booking_status
						);
					} else {
						$data = array(
							'booking_status' => $booking_status
						);
					}	
				} else if ($booking_status == 'process' || $booking_status == 'completed' || $booking_status == 'canceled') {
					$data = array(
						'booking_status' => $booking_status
					);
				} else if ($booking_status == 'shipped') {
					$data = array(
						'shop_id' => $shop_id,
						'booking_status' => $booking_status,
						'awb_number' => $awb_number
					);
				}

				if ($this->m_base->updateData('bookings', $data, 'id', $id)) {
					echo json_encode(array('message' => 'Pesanan berhasil diupdate'));
				} else {
					echo json_encode(array('error' => 'Error saat mengupdate status pesanan'));
				}
			}
		} else {
			echo json_encode(array('error' => 'Akses ditolak'));
		}
	}

	public function addtobookingitems()
	{
		$booking_id = $this->input->post('booking_id');
		$user_id = $this->input->post('user_id');

		$cart_items = $this->m_cart->getData($user_id, 'booking');

		foreach ($cart_items as $cart_item) {
			$booking_item_data = array(
				'booking_id' => $booking_id,
				'item_id' => $cart_item->item_id,
				'price' => $cart_item->price,
				'qty' => $cart_item->qty
			);

			$this->m_base->createData('booking_items', $booking_item_data);

			$this->m_item->decreaseQty($cart_item->item_id, $cart_item->qty);
		}

		$cart_data = array(
			'status' => false
		);

		if ($this->m_cart->nonactivateCarts($cart_data, $user_id, 'booking')) {
			redirect('booking?id='.$booking_id,'refresh'); 
		} else { 
			$this->session->set_flashdata('message', 'Gagal menghapus daftar keranjang anda'); 
		}
	}

	public function customerupdatebookingstatus()
	{
		if ($this->session->userdata('user_type') == 'customer' || $this->session->userdata('user_type') == 'admin') {
			$user_id = $this->session->userdata('user_id');
			$id = $this->input->post('id');
			$booking_status = $this->input->post('booking_status');

			if ($user_id == null || $booking_status != 'completed') {
				echo json_encode(array('error' => 'Error saat mengupdate status pesanan'));
			} else {
				$data = array(
					'booking_status' => $booking_status
				);

				if ($this->m_base->updateData('bookings', $data, 'id', $id)) {
					echo json_encode(array('message' => 'Pesanan berhasil diupdate'));
				} else {
					echo json_encode(array('error' => 'Error saat mengupdate status pesanan'));
				}
			}
		} else {
			echo json_encode(array('error' => 'Akses ditolak'));
		}
	}

	public function uploadpaymentreceipt(){
		$id = $this->input->post('id');
		$bank_account_id = $this->input->post('bank_account_id');
		$type = $this->input->post('type');

		$this->form_validation->set_rules('id', 'ID', 'required|numeric');

		if($this->form_validation->run()) {
			$filename = 'payment_'.$id.'.png';

			$config['upload_path']          = './assets/images/payments/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['file_name']            = $filename;
			$config['overwrite']			= true;
			$config['max_size']             = 1024;
			//$config['max_width']            = 1024;
			//$config['max_height']           = 768;

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('image')) {
				$data = array(
					'booking_status' => 'checking_payment',
					'payment_url' => 'assets/images/payments/'.$filename,
				);

				if ($type == 'booking') {
					$data['bank_account_id'] = $bank_account_id;
				}

				if ($this->m_base->updateData('bookings', $data, 'id', $id)) {
					$this->session->set_flashdata('success', 'Bukti pembayaran berhasil diupload');
				} else {
					$this->session->set_flashdata('message', "Error saat mengupdate status pesanan");
				}
			} else {
				$this->session->set_flashdata('message', $this->upload->display_errors());
			}
			redirect('booking?id='.$id,'refresh');
		} else {
			$this->session->set_flashdata('error', validation_errors());
			redirect('booking?id=$'.$id,'refresh');
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


}

/* End of file Booking.php */
/* Location: ./application/controllers/Booking.php */