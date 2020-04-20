<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
	public function __construct(){
		parent::__construct();
		
	}
	// public function register_admin_model(){

	// 		$data_user = [
	// 			'id_admin' => $this->input->post('id',true),
	// 			'nm_admin' => $this->input->post('nama',true),
	// 			'kd_sungai_sub' => $this->input->post('sungai',true),
	// 			'pass_admin' => password_hash($this->input->post('pass',true), PASSWORD_DEFAULT)

	// 		];
	// 		if($this->db->insert('md_admin',$data_user)){
	// 			return true;
	// 		}

	// }
	public function register_relawan_model(){

		$data_user = [
			'id_relawan' => $this->input->post('user',true),
			'nm_relawan' => $this->input->post('nama',true),
			'hp' => $this->input->post('hp',true),
			'kd_sungai_sub' => $_SESSION['sungai_adm'],
			'pass_relawan' => password_hash($this->input->post('pass',true), PASSWORD_DEFAULT)

		];
		if($this->db->insert('md_relawan',$data_user)){
			return true;
		}
		
	}

	public function edit_relawan_model($id){
		if($this->input->post('pass')==null){
			$data_user = [
				'id_relawan' => $this->input->post('user',true),
				'nm_relawan' => $this->input->post('nama',true),
				'hp' => $this->input->post('hp',true),
				'kd_sungai_sub' => $_SESSION['sungai_adm'],

			];
		}else{
			$data_user = [
				'id_relawan' => $this->input->post('user',true),
				'nm_relawan' => $this->input->post('nama',true),
				'hp' => $this->input->post('hp',true),
				'kd_sungai_sub' => $_SESSION['sungai_adm'],
				'pass_relawan' => password_hash($this->input->post('pass',true), PASSWORD_DEFAULT)

			];
		}
		$this->db->where('id_relawan',$id);
		if($this->db->update('md_relawan',$data_user)){
			return true;
		}
	}
	public function login_relawan_model(){
		$username = $this->input->post('user', true);
		$password = $this->input->post('pass', true);
		$this->db->where('id_relawan', $username);
		$query = $this->db->get('md_relawan')->row();
		if (!$query) return false;
		$hash = $query->pass_relawan;
		if (!password_verify($password, $hash)) return false;
		$_SESSION['user'] = $username;
		$_SESSION['nm_relawan'] = $query->nm_relawan;
		$_SESSION['sungai'] = $query->kd_sungai_sub;
      // Update last_login user
      // Jika username dan password benar maka return data user
		return true;    
	}
	public function login_admin_model(){
		$username = $this->input->post('user', true);
		$password = $this->input->post('pass', true);
		$this->db->where('id_admin', $username);
		$this->db->join('md_sungai', 'md_sungai.kd_sungai = md_admin.kd_sungai_sub');
		$query = $this->db->get('md_admin')->row();
		if (!$query) return false;
		$hash = $query->pass_admin;
		if (!password_verify($password, $hash)) return false;
		$_SESSION['admin'] = $username;
		$_SESSION['nm_sungai'] = $query->nm_sungai;
		$_SESSION['sungai_adm'] = $query->kd_sungai_sub;
      // Update last_login user
      // Jika username dan password benar maka return data user
		return true;    
	}
}