<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('auth_model');
		
	}
	public function index()
	{
		if(isset($_SESSION['user'])){
      redirect(base_url('relawan/index'));
    	}
				// $this->load->view('auth/register_admin.php');
	}
	public function login_relawan_view()
	{
		if(isset($_SESSION['user'])){
      redirect(base_url('relawan/index'));
    	}
		$this->load->view('layout/login_relawan.php');
	}
	public function login_admin_view()
	{
		// if(isset($_SESSION['admin'])){
  //     redirect(base_url('admin/index'));
  //   	}
		if(isset($_SESSION['admin'])){
			redirect(base_url('admin/peta'));
		}
		$this->load->view('layout/login_admin.php');
	}
	public function register_admin()
	{
		if($this->auth_model->register_admin_model() === true){
						redirect(base_url());
		}
	 }
	public function register_relawan()
	{
		if($this->auth_model->register_relawan_model() === true){
			?>
			<script>

				alert('Data Berhasil Ditambahkan !');


			</script>
			<?php

			redirect(base_url('admin/relawan'));
		}else{
			?>
			<script>

				alert('Gagal Menambahkan !');

				history.go(-1);
			</script>
			<?php	
		}
	}
	public function edit_relawan($id)
	{
		if($this->auth_model->edit_relawan_model($id) === true){
			?>
			<script>

				alert('Data Berhasil Diedit !');


			</script>
			<?php

			redirect(base_url('admin/relawan'));
		}else{
			?>
			<script>

				alert('Gagal Menambahkan !');

				history.go(-1);

			</script>
			<?php	
		}
	}
	public function login_relawan(){
		if($this->auth_model->login_relawan_model() === true){
			redirect(base_url('relawan/index'));

		}else{
			?>
			<script>

				alert('Login Gagal ! Username dan Password Tidak Cocok');

				history.go(-1);

			</script>
			<?php	
		}
	} 
	public function login_admin(){
		if($this->auth_model->login_admin_model() === true){
			redirect(base_url('admin/peta'));

		}else{
			?>
			<script>

				alert('Login Gagal ! Username dan Password Tidak Cocok');

				history.go(-1);

			</script>
			<?php	
		}
	} 
	public function logout_relawan(){
		$this->session->sess_destroy();
		redirect(base_url('relawan'));
	}
	public function logout_admin(){
		$this->session->sess_destroy();
		redirect(base_url('admin'));
	}
}