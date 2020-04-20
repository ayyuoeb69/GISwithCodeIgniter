<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {
	public function __construct(){
		parent::__construct();
		
	}
	public function ambil_relawan_data(){
		$sungai = $_SESSION['sungai_adm'];
		$query = $this->db->query("SELECT d_kel_sungai.id_relawan_sub as 'id_relawan_sub', count(*) as 'banyak', IFNULL(sum(case when d_kel_sungai.status_kirim = '0' then 1 else 0 end),0) as 'simpan',IFNULL(sum(case when d_kel_sungai.status_kirim = '1' then 1 else 0 end),0) as 'kirim',IFNULL(sum(case when d_kel_sungai.status_kirim = '1' and d_kel_sungai.status_setuju = '0' then 1 else 0 end),0) as 'verif',IFNULL(sum(case when d_kel_sungai.status_kirim = '1' and d_kel_sungai.status_setuju = '1' then 1 else 0 end),0) as 'terima',IFNULL(sum(case when d_kel_sungai.status_kirim = '1' and d_kel_sungai.status_setuju = '2' then 1 else 0 end),0) as 'tolak',d_kel_sungai.id_relawan_sub, md_relawan.nm_relawan as 'nm_relawan',md_relawan.id_relawan as 'id_relawan'  FROM `d_kel_sungai` LEFT JOIN `md_relawan` ON d_kel_sungai.id_relawan_sub = md_relawan.id_relawan WHERE d_kel_sungai.kd_sungai_sub = '$sungai' GROUP BY d_kel_sungai.id_relawan_sub ");
		return $query->result();
	}
	public function banyak_relawan_data(){
		$sungai = $_SESSION['sungai_adm'];
		$query = $this->db->query("SELECT COUNT(*) as total FROM `md_relawan` WHERE kd_sungai_sub = '$sungai'");
		return $query->row();
	}
	public function list_relawan_data(){
		$sungai = $_SESSION['sungai_adm'];
		$query = $this->db->query("SELECT * FROM `md_relawan` WHERE kd_sungai_sub = '$sungai'");
		return $query->result();
	}
	public function hapus_relawan_model($id){
		$this->db->join('d_kel_sungai', 'd_kel_sungai.id_kel_sungai = d_titik_sungai.id_kel_sungai_sub');
		$this->db->where('id_relawan_sub', $id);
		$this->db->delete('d_kel_sungai');
		$this->db->where('id_relawan_sub', $id);
		$this->db->delete('d_kel_sungai');
		$this->db->where('id_relawan', $id);
		$this->db->delete('md_relawan');
		return true;
	}
	public function ambil_verif_data(){
		$sungai = $_SESSION['sungai_adm'];
		$this->db->where('kd_sungai_sub',$sungai);
		$where = "( status_kirim=1 OR status_kirim=2 ) AND status_setuju=0";
		$this->db->where($where);
		$this->db->order_by('waktu_tambah','desc');
		$query = $this->db->get_where('d_kel_sungai');
		return $query->result();
	}
	public function ambil_terima_data(){
		$sungai = $_SESSION['sungai_adm'];
		$this->db->where('kd_sungai_sub',$sungai);
		$where = "( status_kirim=1 OR status_kirim=2 ) AND status_setuju=1";
		$this->db->where($where);
		$this->db->order_by('waktu_tambah','desc');
		$query = $this->db->get('d_kel_sungai');
		return $query->result();
	}
	public function ambil_id_relawan_data($id){
		$this->db->where('id_relawan',$id);
		$query = $this->db->get('md_relawan');
		return $query->row();
	}
	public function setuju_verif_model($id){
		$timestamp = date('Y-m-d G:i:s');
		$data = [
			'status_setuju' => 1,
			'waktu_setuju' => $timestamp 
		];
		$this->db->where('id_kel_sungai', $id);
		$this->db->update('d_kel_sungai',$data);
		$ids = $_SESSION['sungai_adm'];
		$status = $this->status_sungai();
		$data_status = [
			'status_sungai' => $status,
		];
		$this->db->where('kd_sungai', $ids);
		$this->db->update('md_sungai',$data_status);
		return true;
	}
	public function tolak_verif_model($id){
		$data = [
			'status_setuju' => 2
		];
		$this->db->where('id_kel_sungai', $id);
		$this->db->update('d_kel_sungai',$data);
		$ids = $_SESSION['sungai_adm'];
		$status = $this->status_sungai();
		$data_status = [
			'status_sungai' => $status,
		];
		$this->db->where('kd_sungai', $ids);
		$this->db->update('md_sungai',$data_status);
		return true;
	}
	public function getAllbytitik(){
		$sungai = $_SESSION['sungai_adm'];
		$this->db->where('kd_sungai_sub', $sungai);
		$where = "( d_kel_sungai.status_kirim=1 OR d_kel_sungai.status_kirim=2 ) AND d_kel_sungai.status_setuju=1";
		$this->db->where($where);
		return $this->db->get('d_kel_sungai')->result();
	}
	public function ambil_data($id){

		$this->db->join('d_kel_sungai', 'd_kel_sungai.id_kel_sungai = d_titik_sungai.id_kel_sungai_sub');
		$this->db->where('d_titik_sungai.id_kel_sungai_sub', $id);
		$query = $this->db->get('d_titik_sungai');
		$a = $query->result();
		foreach ($a as $k) {
			if($k->id_kel_sungai_sub!=$id){
				return false;
			}else{
				return $a;
			}
		}
	}
	public function getDasar($id){
		$sungai = $_SESSION['sungai_adm'];
		$this->db->where('kd_sungai_sub', $sungai);
		$this->db->where('id_kel_dasar_sub', $id);
		return $this->db->get('d_koor_dasar')->result();
	}
	public function getDasarbyId(){
		$sungai = $_SESSION['sungai_adm'];
		return $this->db->query("SELECT COUNT(*),id_kel_dasar_sub FROM d_koor_dasar WHERE kd_sungai_sub = '$sungai' GROUP BY id_kel_dasar_sub")->result();
	}
	public function getKoor(){
		$sungai = $_SESSION['sungai_adm'];
		$this->db->join('d_kel_sungai', 'd_kel_sungai.id_kel_sungai = d_titik_sungai.id_kel_sungai_sub');
		$this->db->where('kd_sungai_sub', $sungai);
		return $this->db->get('d_titik_sungai')->result();
	}
	public function getSungai(){
		$sungai = $_SESSION['sungai_adm'];
		$this->db->where('kd_sungai', $sungai);
		return $this->db->get('md_sungai')->row();
	}
	public function gettitik($id){
		$sungai = $_SESSION['sungai_adm'];
		$this->db->where('kd_sungai_sub', $sungai);
		$this->db->where('id_kel_sungai', $id);
		return $this->db->get('d_kel_sungai')->row();
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
	public function status_sungai(){
		$idss = $_SESSION['sungai_adm'];
		$this->db->join('d_kel_sungai', 'd_kel_sungai.id_kel_sungai = d_titik_sungai.id_kel_sungai_sub');
		$this->db->where('d_kel_sungai.kd_sungai_sub', $idss);
		$this->db->where('d_kel_sungai.status_setuju', 1);
		$query = $this->db->get('d_titik_sungai')->result();
		print_r($query);
		if($query[0] != null){
			$max_temp = $query[0]->temperatur;
			$max_ec = $query[0]->ec;
			$max_tds = $query[0]->tds;
			$max_ph = $query[0]->ph;
			$max_do = $query[0]->do;
			$max_bod = $query[0]->bod;
			$max_ecoli = $query[0]->ecoli;
			$min_temp = $query[0]->temperatur;
			$min_ec = $query[0]->ec;
			$min_tds = $query[0]->tds;
			$min_ph = $query[0]->ph;
			$min_do = $query[0]->do;
			$min_bod = $query[0]->bod;
			$min_ecoli = $query[0]->ecoli;
			$ave_temp = 0;
			$ave_ec = 0;
			$ave_tds = 0;
			$ave_ph = 0;
			$ave_do = 0;
			$ave_bod = 0;
			$ave_ecoli = 0;
			$banyak_temp = 0;
			$banyak_ec = 0;
			$banyak_tds = 0;
			$banyak_ph = 0;
			$banyak_do = 0;
			$banyak_bod = 0;
			$banyak_ecoli = 0;
			$i=1;
			foreach ($query as $row) {
			//Inisialisasi

				$temperatur = $row->temperatur;
				$ec = $row->ec;
				$tds = $row->tds;
				$ph = $row->ph;
				$do = $row->do;
				$bod = $row->bod;
				$ecoli = $row->ecoli;
				//Maksimum
				if($temperatur != null){
					if($temperatur > $max_temp){
						$max_temp = $temperatur;
					}
				}
				if($ec != null){
					if($ec > $max_ec){
						$max_ec = $ec;
					}
				}
				if($tds != null){
					if($tds > $max_tds){
						$max_tds = $tds;
					}
				}
				if($ph != null){
					if($ph > $max_ph){
						$max_ph = $ph;
					}
				}
				if($do != null){
					if($do > $max_do){
						$max_do = $do;
					}
				}
				if($bod != null){
					if($bod > $max_bod){
						$max_bod = $bod;
					}
				}
				if($ecoli != null){
					if($ecoli > $max_ecoli){
						$max_ecoli = $ecoli;
					}
				}
			//Minimum
				if($temperatur != null){
					if($temperatur < $min_temp){
						$min_temp = $temperatur;
					}
				}
				if($ec != null){
					if($ec < $min_ec){
						$min_ec = $ec;
					}
				}
				if($tds != null){
					if($tds < $min_tds){
						$min_tds = $tds;
					}
				}
				if($ph != null){
					if($ph < $min_ph){
						$min_ph = $ph;
					}
				}
				if($do != null){
					if($do < $min_do){
						$min_do = $do;
					}
				}
				if($bod != null){
					if($bod < $min_bod){
						$min_bod = $bod;
					}
				}
				if($ecoli != null){
					if($ecoli < $min_ecoli){
						$min_ecoli = $ecoli;
					}
				}
			//Jumlah
				if($temperatur != null){
					$ave_temp = $ave_temp + $temperatur; 
				}
				if($ec != null){
					$ave_ec = $ave_ec + $ec; 
				}
				if($tds != null){
					$ave_tds = $ave_tds + $tds; 
				}
				if($do != null){
					$ave_do = $ave_do + $do; 
				}
				if($bod != null){
					$ave_bod = $ave_bod + $bod; 
				}
				if($ph != null){
					$ave_ph = $ave_ph + $ph; 
				}
				if($ecoli != null){
					$ave_ecoli = $ave_ecoli + $ecoli; 
				}
				if($temperatur != null){
					$banyak_temp = $banyak_temp+1;
				}
				if($ec != null){
					$banyak_ec = $banyak_ec+1;
				}
				if($tds != null){
					$banyak_tds=$banyak_tds+1;
				}
				if($ph != null){
					$banyak_ph=$banyak_ph+1;
				}
				if($do != null){
					$banyak_do = $banyak_do+1;
				}
				if($bod != null){
					$banyak_bod=$banyak_bod+1;
				}
				if($ecoli != null){
					$banyak_ecoli=$banyak_ecoli+1;

				}
				$i++;
			}
			
			if($ave_temp != 0 && $banyak_temp != 0){
				$rata_temp = $ave_temp / $banyak_temp;
			}else{
				$rata_temp =  0;
			}
			if($ave_ec!= 0 && $banyak_ec != 0){
				$rata_ec = $ave_ec / $banyak_ec;
			}else{
				$rata_ec =  0;
			}
			if($ave_tds != 0&& $banyak_tds != 0){
				$rata_tds = $ave_tds / $banyak_tds;
			}else{
				$rata_tds =  0;
			}
			if($ave_do!= 0 && $banyak_do != 0){
				$rata_do = $ave_do / $banyak_do;
			}else{
				$rata_do =  0;
			}
			if($ave_bod != 0&& $banyak_bod != 0){
				$rata_bod = $ave_bod / $banyak_bod;
			}else{
				$rata_bod =  0;
			}
			if($ave_ph != 0 && $banyak_ph!= 0){
				$rata_ph = $ave_ph / $banyak_ph;
			}else{
				$rata_ph =  0;
			}
			if($ave_ecoli!= 0 && $banyak_ecoli!= 0){
				$rata_ecoli = $ave_ecoli / $banyak_ecoli;
			}else{
				$rata_ecoli =  null;
			}

		//Skoring Sungai Gajah Wong
		// TDS
			if($max_tds<=1000){
				$skor_max_tds = 0; 	
			}else{
				$skor_max_tds = -1;
			}
			if($min_tds<=1000){
				$skor_min_tds = 0; 	
			}else{
				$skor_min_tds = -1;
			}
			if($rata_tds<=1000){
				$skor_rata_tds = 0; 	
			}else{
				$skor_rata_tds = -3;
			}


		// Temperature
			if(($max_temp<($rata_temp+3))&&($max_temp>($rata_temp-3))){
				$skor_max_temp = 0; 	
			}else{
				$skor_max_temp = -1;
			}
			if(($min_temp<($rata_temp+3))&&($min_temp>($rata_temp-3))){
				$skor_min_temp = 0; 	
			}else{
				$skor_min_temp = -1;
			}
			if(($rata_temp<($rata_temp+3))&&($rata_temp>($rata_temp-3))){
				$skor_rata_temp = 0; 	
			}else{
				$skor_rata_temp = -3;
			}


		// pH
			if(($max_ph<=9)&&($max_ph>=6)){
				$skor_max_ph = 0; 	
			}else{
				$skor_max_ph = -2;
			}
			if(($min_ph<=9)&&($min_ph>=6)){
				$skor_min_ph = 0; 	
			}else{
				$skor_min_ph = -2;
			}
			if(($rata_ph<=9)&&($rata_ph>=6)){
				$skor_rata_ph = 0; 	
			}else{
				$skor_rata_ph= -6;
			}


		//BOD
			if($max_bod<=6){
				$skor_max_bod = 0; 	
			}else{
				$skor_max_bod = -2;
			}
			if($min_bod<=6){
				$skor_min_bod = 0; 	
			}else{
				$skor_min_bod = -2;
			}
			if($rata_bod<=6){
				$skor_rata_bod = 0; 	
			}else{
				$skor_rata_bod = -6;
			}

		//EColi
			if($max_ecoli<=10000){
				$skor_max_ecoli = 0; 	
			}else{
				$skor_max_ecoli = -3;
			}
			if($min_ecoli<=10000){
				$skor_min_ecoli = 0; 	
			}else{
				$skor_min_ecoli = -3;
			}
			if($rata_ecoli<=10000){
				$skor_rata_ecoli = 0; 	
			}else{
				$skor_rata_ecoli = -9;
			}
			if($_SESSION['sungai_adm'] == 's_gajah'){


		//Do
				if($max_do>=4){
					$skor_max_do = 0; 	
				}else{
					$skor_max_do = -2;
				}
				if($min_do>=4){
					$skor_min_do = 0; 	
				}else{
					$skor_min_do = -2;
				}
				if($rata_do>=4){
					$skor_rata_do = 0; 	
				}else{
					$skor_rata_do = -6;
				}

			}elseif(($_SESSION['sungai_adm'] == 's_kambaniru') || ($_SESSION['sungai_adm'] == 's_brantas')){


		//Do
				if($max_do>=3){
					$skor_max_do = 0; 	
				}else{
					$skor_max_do = -2;
				}
				if($min_do>=3){
					$skor_min_do = 0; 	
				}else{
					$skor_min_do = -2;
				}
				if($rata_do>=3){
					$skor_rata_do = 0; 	
				}else{
					$skor_rata_do = -6;
				}

			}



			$skor_tds = $skor_max_tds + $skor_min_tds + $skor_rata_tds;
			$skor_temp = $skor_max_temp + $skor_min_temp + $skor_rata_temp;
			$skor_ph = $skor_max_ph + $skor_min_ph + $skor_rata_ph;
			$skor_bod = $skor_max_bod + $skor_min_bod + $skor_rata_bod;
			$skor_do = $skor_max_do + $skor_min_do + $skor_rata_do;
			$skor_ecoli = $skor_max_ecoli + $skor_min_ecoli + $skor_rata_ecoli;
			$skor = $skor_tds+$skor_temp+$skor_ph+$skor_bod+$skor_do+$skor_ecoli;

		}else{
			$skor = 0;
		}
		// return $rata_temp;
	}
}
//SELECT * FROM `d_titik_sungai` JOIN `d_kel_sungai` ON `d_kel_sungai`.`id_kel_sungai` = `d_titik_sungai`.`id_kel_sungai_sub` WHERE `d_kel_sungai`.`kd_sungai_sub` = 's_gajah' AND (`d_kel_sungai`.`status_kirim` = 1 OR `d_kel_sungai`.`status_kirim` = 2 ) AND `d_kel_sungai`.`status_setuju` = 1 