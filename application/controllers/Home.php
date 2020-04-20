<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('sungai_model');
		$this->load->model('relawan_model');
	}
	public function index()
	{
		$this->load->view('layout/header');
		$this->load->view('layout/home');
		$this->load->view('layout/footer');
	}
	public function sungai($id)
	{
		
		$data = $this->sungai_model->ambil_sungai($id);

		echo json_encode($data);
	}
	public function getSungai()
	{
		$data = $this->sungai_model->ambil_detail($this->input->post('sungai'));
		echo json_encode($data);
	}
	public function titik_sungai()
	{
		$data = $this->sungai_model->ambil_titik($this->input->post('sungai'));
		echo json_encode($data);
	}
	public function dasar_sungai($ab)
	{
		// $ab = $this->input->post('sungai');
		// $data['a']=$ab[1];
		// $this->load->view('auth/register_admin.php',$data);
		 $data = $this->sungai_model->ambil_dasar($ab);
		echo json_encode($data);
	}
	public function kel_dasar_sungai()
	{
		$data = $this->sungai_model->ambil_kel_dasar($this->input->post('sungai'));
		echo json_encode($data);
	}
	public function tampil_modal($id)
	{
		$data = $this->relawan_model->ambil_data($id);

		echo json_encode($data);
	}

	
}