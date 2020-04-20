<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sungai_model extends CI_Model {
	public function __construct(){
		parent::__construct();
		
	}
	public function tambah_dasar($latitude, $longitude, $id_kel){

        $data = array('kd_sungai_sub' => $_SESSION['sungai_adm'],
            'lat_koor_dasar'=>$latitude,
            'lng_koor_dasar'=>$longitude,
            'id_kel_dasar_sub' => $id_kel
        );
        if($query = $this->db->insert('d_koor_dasar', $data)){
            return $query;
        }else{
            die("salah"); 
        }
        
        
    }
        public function ambil_kel_dasar($id){
        return $this->db->query("SELECT COUNT(*),id_kel_dasar_sub FROM d_koor_dasar WHERE kd_sungai_sub = '$id' GROUP BY id_kel_dasar_sub")->result();
    }
    public function ambil_sungai($id){
        $this->db->where('kd_pulau_sub', $id);
        $this->db->order_by('nm_sungai', 'ASC');
        return $this->db->get('md_sungai')->result();
    }
    public function ambil_detail($id){
        $this->db->where('kd_sungai', $id);
        return $this->db->get('md_sungai')->row();
    }
    public function ambil_titik($id){
        $this->db->where('kd_sungai_sub', $id);
        $where = "( status_kirim=1 OR status_kirim=2 ) AND status_setuju=1";
        $this->db->where($where);
        return $this->db->get('d_kel_sungai')->result();
    }
    public function ambil_dasar($id){
        $this->db->join('md_sungai', 'md_sungai.kd_sungai = d_koor_dasar.kd_sungai_sub');
        // $this->db->where('kd_sungai_sub', $id);
        $this->db->where('id_kel_dasar_sub', $id);
        return $this->db->get('d_koor_dasar')->result();
    }
   
}