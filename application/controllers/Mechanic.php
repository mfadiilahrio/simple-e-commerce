<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mechanic extends CI_Controller {

	var $page_name = "Mekanik"; 

	public function __construct()
	{
		parent::__construct();

		$this->load->model('m_base');
		$this->load->model('m_user');
		$this->load->model('m_cart');
		$this->timeStamp = date("Y-m-d H:i:s", time());
	}

	public function index()
	{
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');

		$data['records'] = $this->m_user->getMechanics();

		$data['page_name'] = $this->page_name;
		$this->header();
		$this->load->view('mechanic/index', $data);
		$this->footer();
	}

	public function edit()
	{
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');

		$id = $this->input->get('id');

		if ($id == '') {
			$data['record'] = '';
		} else {
			$data['record'] = $this->m_base->getWhere('auth', array('id' => $id));
		}

		$data['page_name'] = $this->page_name;
		$this->header();
		$this->load->view('mechanic/edit', $data);
		$this->footer();
	}

	public function update()
	{
		$id = $this->input->post('id');
		$email   = $this->input->post('email');
		$password   = $this->input->post('password');
		$password_confirmation   = $this->input->post('password_confirmation');

		if ($id == '') {
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[auth.email]');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('password_confirmation', 'Konfirmasi Password', 'required');

			if($this->form_validation->run()) {
				if ($password != $password_confirmation) {
					$this->session->set_flashdata('message', "Password tidak sesuai dengan konfirmasi password");
					redirect('mechanic/edit?id='.$id,'refresh');	
				}

				$user_data = array(
					'name' => NULL,
					'phone' => NULL,
					'address' => NULL,
					'dob' => NULL
				);

				if ($user_id = $this->m_base->createDataWithInsertID('users', $user_data)) {
					$data = array(
						'email' => $email,
						'password' => md5($password),
						'user_id' => $user_id,
						'user_type' => 'mechanic'
					);
					if ($this->m_base->createData('auth', $data)) {
						$this->session->set_flashdata('success', 'Registrasi berhasil');
						redirect('mechanic','refresh'); 
					} else { $this->session->set_flashdata('message', 'Registrasi gagal'); }
				} else {
					$this->session->set_flashdata('message', 'Registrasi gagal');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}

			
		} else {
			if ($password != null && $password != $password_confirmation) {
				$this->session->set_flashdata('message', "Password tidak sesuai dengan konfirmasi password");
				redirect('mechanic/edit?id='.$id,'refresh');	
			} else {
				$data = array(
					'password' => md5($password)
				);

				if ($this->m_base->updateData('auth', $data, 'id', $id)) {
					$this->session->set_flashdata('success', 'Data berhasil diubah');
					redirect('mechanic','refresh'); 
				} else {
					$this->session->set_flashdata('error', 'Data gagal diubah');
				}
			}
		}

		redirect('mechanic/edit?id='.$id, 'refresh');
	}

	public function delete()
	{
		$id = $this->input->post('id');

		$data = array(
			'status' => false
		);

		if ($this->m_base->updateData('auth', $data, 'id', $id)) {
			$this->session->set_flashdata('success', 'Mekanik berhasil dihapus');
			echo json_encode(array('message' => 'Mekanik berhasil dihapus'));
		} else {
			$this->session->set_flashdata('error', 'Mekanik gagal dihapus');
			echo json_encode(array('error' => 'Error saat menghapus mekanik'));
		}
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

/* End of file Mechanic.php */
/* Location: ./application/controllers/Mechanic.php */