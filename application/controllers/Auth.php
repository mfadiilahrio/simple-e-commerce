<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_base');
		$this->load->model('m_user');
	}

	public function index()
	{

		if($this->session->userdata('id') != null){
			redirect(base_url("booking"));
		}

		$data['success'] = $this->session->flashdata('success');
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->load->view('login/index', $data);
	}

	public function sign_up()
	{
		if($this->session->userdata('id') != null){
			$this->session->sess_destroy();
		}

		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$data['error'] = $this->session->flashdata('error');

		$this->load->view('register/index', $data);
	}

	function login()
	{
		$email   = $this->input->post('email');
		$password   = $this->input->post('password');

		$where = array(
			'email' => $email,
			'password' => md5($password)
		);

		if ($auth = $this->m_base->getWhere('auth', $where)) {

			if ($auth->status == false) {
				$this->session->set_flashdata('message', "Akun nonaktif");
				redirect('auth','refresh');
			} else {
				$where = array(
					'id' => $auth->user_id,
				);

				if($user = $this->m_base->getWhere('users', $where)) {

					$data_session = array(
						'id' 		=> $auth->id,
						'email'		=> $auth->email,
						'user_type' => $auth->user_type,
						'user_id'   => $user->id,
						'name'     	=> $user->name,
						'phone' 	=> $user->phone,
						'address'	=> $user->address,
						'postal_code'	=> $user->postal_code,
						'dob'		=> $user->dob,
						'user_type' => $auth->user_type
					);

					$this->session->set_userdata($data_session);
					$this->session->set_flashdata('success', "Hello ".$user->name." :)");

					if ($auth->user_type == 'customer') {
						redirect(base_url("shopping"));
					} else {
						redirect(base_url("booking"));
					}
				} else {

					$this->session->set_flashdata('message', "User tidak terdaftar");
					redirect('auth','refresh');

				}

			}

		} else {

			$this->session->set_flashdata('message', "Username atau Password salah");
			redirect('auth','refresh');

		}       
	}

	function register()
	{
		$email   = $this->input->post('email');
		$password   = $this->input->post('password');
		$password_confirmation   = $this->input->post('password_confirmation');

		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('password_confirmation', 'Konfirmasi Password', 'required');

		if($this->form_validation->run()) {
			if ($password != $password_confirmation) {
				$this->session->set_flashdata('message', "Password tidak sesuai dengan konfirmasi password");
				redirect('auth/sign_up','refresh');	
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
					'user_type' => 'customer'
				);
				if ($this->m_base->createData('auth', $data)) {
					$this->session->set_flashdata('success', 'Registrasi berhasil');
					redirect('auth','refresh'); 
				} else { $this->session->set_flashdata('message', 'Registrasi gagal'); }
			} else {
				$this->session->set_flashdata('message', 'Registrasi gagal');
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		redirect('auth/sign_up','refresh');     
	}
	
	function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('auth'));
	}

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */