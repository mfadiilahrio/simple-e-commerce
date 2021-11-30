<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	var $page_name = "Profil"; 

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('m_base');
		$this->load->model('m_cart');
		$this->load->model('m_user');
		$this->timeStamp = date('Y-m-d H:i:s', time());
	}

	public function index()
	{
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->session->flashdata('error');
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		$user = $this->m_user->getProfile();

		if ($user != null) {
			$this->session->unset_userdata('name');
			$this->session->unset_userdata('phone');
			$this->session->unset_userdata('address');
			$this->session->unset_userdata('postal_code');
			$this->session->unset_userdata('dob');

			$data_session = array(
				'name'     		=> $user->name,
				'phone' 		=> $user->phone,
				'address'		=> $user->address,
				'postal_code'	=> $user->postal_code,
				'dob'			=> $user->dob
			);

			$this->session->set_userdata($data_session);

			$data['page_name'] = $this->page_name;
			$this->header();
			$this->load->view('profile/index', $data);
			$this->footer();
		} else {
			$this->session->sess_destroy();
			redirect(base_url('auth'));
		}
	}

	public function update()
	{
		$name = $this->input->post('name');
		$password = $this->input->post('password');
		$phone = $this->input->post('phone');
		$address = $this->input->post('address');
		$postal_code = $this->input->post('postal_code');
		$dob = $this->input->post('dob');

		$this->form_validation->set_rules('name', 'Nama', 'required');
		$this->form_validation->set_rules('phone', 'No Telp', 'required');
		$this->form_validation->set_rules('address', 'Alamat', 'required');
		$this->form_validation->set_rules('postal_code', 'Kode Pos', 'required');
		$this->form_validation->set_rules('dob', 'Tanggal Lahir', 'required');

		if($this->form_validation->run()) {
			$data = array(
				'name' => $name,
				'phone' => $phone,
				'address' => $address,
				'postal_code' => $postal_code,
				'dob' => $dob
			);
			
			if ($this->m_base->updateData('users', $data, 'id', $this->session->userdata('user_id'))) {
				
				if ($password != null) {
					$this->m_base->updateData('auth', array('password' => md5($password)), 'id', $this->session->userdata('id'));
				}

				$this->session->set_flashdata('success', 'Data berhasil diubah');
			} else {
				$this->session->set_flashdata('error', 'Data gagal diubah');
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}

		redirect('profile','refresh');
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

/* End of file Profile.php */
/* Location: ./application/controllers/Profile.php */