<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relawan_model extends CI_Model {
	public function __construct(){
		parent::__construct();
		
	}
	public function ambil_simpan(){
		$this->db->order_by('waktu_tambah', 'desc');
		$query = $this->db->get_where('d_kel_sungai', array('status_kirim' => 0, 'id_relawan_sub' => $_SESSION['user']));
		return $query->result();
	}
	public function ambil_kirim(){
		$this->db->order_by('waktu_tambah', 'desc');
		$id = $_SESSION['user'];

		$where = "( status_kirim=1 OR status_kirim=2 )";
		$this->db->where($where);
		$where2 = "( status_setuju=0 OR status_setuju=2 )";
		$this->db->where($where2);
		$this->db->where('id_relawan_sub',$id);
		$query = $this->db->get_where('d_kel_sungai');
		return $query->result();
	}
	public function ambil_terima(){
		$this->db->order_by('waktu_tambah', 'desc');
		$id = $_SESSION['user'];

		$where = "( status_kirim=1 OR status_kirim=2 )";
		$this->db->where($where);
		$this->db->where('status_setuju',1);
		$this->db->where('id_relawan_sub',$id);
		$query = $this->db->get_where('d_kel_sungai');
		return $query->result();
	}
	public function ambil_detail(){
		$id = $_SESSION['user'];
		$this->db->join('d_kel_sungai', 'd_kel_sungai.id_kel_sungai = d_titik_sungai.id_kel_sungai_sub');
		$where = "( status_kirim=1 OR status_kirim=2 )";
		$this->db->where($where);
		$this->db->where('status_setuju',1);
		$this->db->where('id_relawan_sub',$id);
		$query = $this->db->get_where('d_titik_sungai');
		return $query->result();
	}
	public function ambil_data_row($id){
		$query = $this->db->get_where('d_kel_sungai', array('id_kel_sungai' => $id));
		return $query->row();
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

	public function d_laporan(){
		$this->db->select('COUNT(id_relawan_sub) as total');
		$this->db->where(array('id_relawan_sub' => $_SESSION['user']));
		return $this->db->get('d_kel_sungai')->row();

	}
	public function d_simpan(){
		$this->db->select('COUNT(id_relawan_sub) as total');
		$this->db->where(array('status_kirim' => 0, 'id_relawan_sub' => $_SESSION['user']));
		return $this->db->get('d_kel_sungai')->row();

	}
	public function d_kirim(){
		$id = $_SESSION['user'];
		$this->db->select('COUNT(id_relawan_sub) as total');
		$this->db->where('id_relawan_sub',$id);
		$where = "( status_kirim=1 OR status_kirim=2 )";
		$this->db->where($where);
		return $this->db->get('d_kel_sungai')->row();

	}
	public function d_proses(){
		$id = $_SESSION['user'];
		$this->db->select('COUNT(id_relawan_sub) as total');
		$this->db->where('id_relawan_sub',$id);
		$where = "( status_kirim=1 OR status_kirim=2 ) AND status_setuju=0";
		$this->db->where($where);
		return $this->db->get('d_kel_sungai')->row();

	}
	public function d_terima(){
		$id = $_SESSION['user'];
		$this->db->select('COUNT(id_relawan_sub) as total');
		$this->db->where('id_relawan_sub',$id);
		$where = "( status_kirim=1 OR status_kirim=2 ) AND status_setuju=1";
		$this->db->where($where);
		return $this->db->get('d_kel_sungai')->row();

	}
	public function d_tolak(){
		$id = $_SESSION['user'];
		$this->db->select('COUNT(id_relawan_sub) as total');
		$this->db->where('id_relawan_sub',$id);
		$where = "( status_kirim=1 OR status_kirim=2 ) AND status_setuju=2";
		$this->db->where($where);
		return $this->db->get('d_kel_sungai')->row();

	}
	public function input_relawan_model(){
		$id = "trx".$_SESSION['user'].date("Ymd").rand();
		$banyak = $this->input->post('banyak');
		$config['upload_path'] = './assets/upload';    
		$config['allowed_types'] = 'jpg|png|jpeg';    
		$config['max_size']  = '5000';    
		$config['remove_space'] = TRUE;
		$this->load->library('upload', $config);
		if($this->upload->do_upload('foto')){
			$foto = array('result' => 'success', 'foto' => $this->upload->data(), 'error' => '');
			if($this->upload->do_upload('lampiran')){				
				$lamp = array('result' => 'success', 'lamp' => $this->upload->data(), 'error' => '');
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'id_kel_sungai' => $id,
					'kd_sungai_sub' => $_SESSION['sungai'],
					'id_relawan_sub' => $_SESSION['user'],
					'keterangan' => $this->input->post('ket'),
					'foto' => $foto['foto']['file_name'],
					'titik_lat' => $this->input->post('lat'),
					'titik_lng' => $this->input->post('lng'),
					'lampiran' => $lamp['lamp']['file_name'],
					'status_kirim' => 1,
					'banyak' => $banyak

				];
				$this->db->insert('d_kel_sungai',$data_user);
				for($i=0;$i<$banyak;$i++){

					$data = [
						'temperatur' => str_replace(',', '.',$this->input->post('temp'.$i)),
						'ec' => str_replace(',', '.',$this->input->post('ec'.$i)),
						'tds' => str_replace(',', '.',$this->input->post('tds'.$i)),
						'ph' => str_replace(',', '.',$this->input->post('ph'.$i)),
						'do' => str_replace(',', '.',$this->input->post('do'.$i)),
						'bod' => str_replace(',', '.',$this->input->post('bod'.$i)),
						'ecoli' => str_replace(',', '.',$this->input->post('ecoli'.$i)),
						'id_kel_sungai_sub' => $id,
						
					];
					$this->db->insert('d_titik_sungai',$data);
				}
				$status = $this->status($id);
				$data_status = [
					'status_titik' => $status,
				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_status);
				return true;
				
			}else{
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'id_kel_sungai' => $id,
					'kd_sungai_sub' => $_SESSION['sungai'],
					'id_relawan_sub' => $_SESSION['user'],
					'keterangan' => $this->input->post('ket'),
					'foto' => $foto['foto']['file_name'],
					'status_kirim' => 1,
					'titik_lat' => $this->input->post('lat'),
					'titik_lng' => $this->input->post('lng'),
					'banyak' => $banyak

				];
				$this->db->insert('d_kel_sungai',$data_user);
				for($i=0;$i<$banyak;$i++){
					$data = [
						'temperatur' => str_replace(',', '.',$this->input->post('temp'.$i)),
						'ec' => str_replace(',', '.',$this->input->post('ec'.$i)),
						'tds' => str_replace(',', '.',$this->input->post('tds'.$i)),
						'ph' => str_replace(',', '.',$this->input->post('ph'.$i)),
						'do' => str_replace(',', '.',$this->input->post('do'.$i)),
						'bod' => str_replace(',', '.',$this->input->post('bod'.$i)),
						'ecoli' => str_replace(',', '.',$this->input->post('ecoli'.$i)),
						'id_kel_sungai_sub' => $id,
						
					];
					$this->db->insert('d_titik_sungai',$data);
				}
				$status = $this->status($id);
				$data_status = [
					'status_titik' => $status,
				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_status);
				return true;
			}
		}else{
			if($this->upload->do_upload('lampiran')){				
				$lamp = array('result' => 'success', 'lamp' => $this->upload->data(), 'error' => '');
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'id_kel_sungai' => $id,
					'kd_sungai_sub' => $_SESSION['sungai'],
					'id_relawan_sub' => $_SESSION['user'],
					'keterangan' => $this->input->post('ket'),
					'lampiran' => $lamp['lamp']['file_name'],
					'titik_lat' => $this->input->post('lat'),
					'titik_lng' => $this->input->post('lng'),
					'status_kirim' => 1,
					'banyak' => $banyak

				];
				$this->db->insert('d_kel_sungai',$data_user);
				for($i=0;$i<$banyak;$i++){
					$data = [
						'temperatur' => str_replace(',', '.',$this->input->post('temp'.$i)),
						'ec' => str_replace(',', '.',$this->input->post('ec'.$i)),
						'tds' => str_replace(',', '.',$this->input->post('tds'.$i)),
						'ph' => str_replace(',', '.',$this->input->post('ph'.$i)),
						'do' => str_replace(',', '.',$this->input->post('do'.$i)),
						'bod' => str_replace(',', '.',$this->input->post('bod'.$i)),
						'ecoli' => str_replace(',', '.',$this->input->post('ecoli'.$i)),
						'id_kel_sungai_sub' => $id,
						
					];
					$this->db->insert('d_titik_sungai',$data);
				}
				$status = $this->status($id);
				$data_status = [
					'status_titik' => $status,
				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_status);
				return true;
			}else{
				
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'id_kel_sungai' => $id,
					'kd_sungai_sub' => $_SESSION['sungai'],
					'id_relawan_sub' => $_SESSION['user'],
					'keterangan' => $this->input->post('ket'),
					'titik_lat' => $this->input->post('lat'),
					'titik_lng' => $this->input->post('lng'),
					'status_kirim' => 1,
					'banyak' => $banyak

				];
				$this->db->insert('d_kel_sungai',$data_user);
				for($i=0;$i<$banyak;$i++){
					$data = [
						'temperatur' => str_replace(',', '.',$this->input->post('temp'.$i)),
						'ec' => str_replace(',', '.',$this->input->post('ec'.$i)),
						'tds' => str_replace(',', '.',$this->input->post('tds'.$i)),
						'ph' => str_replace(',', '.',$this->input->post('ph'.$i)),
						'do' => str_replace(',', '.',$this->input->post('do'.$i)),
						'bod' => str_replace(',', '.',$this->input->post('bod'.$i)),
						'ecoli' => str_replace(',', '.',$this->input->post('ecoli'.$i)),
						'id_kel_sungai_sub' => $id
					];
					$this->db->insert('d_titik_sungai',$data);
				}
				
				$status = $this->status($id);
				$data_status = [
					'status_titik' => $status,
				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_status);
				if($this->input->post('lat_m')!=null && $this->input->post('lng_m')!=null){
					$data_u = [
						'titik_lat' => $this->input->post('lat_m'),
						'titik_lng' => $this->input->post('lng_m'),
					];
					$this->db->where('id_kel_sungai', $id);
					$this->db->update('d_kel_sungai',$data_u);
				}
				return true;
			}
		}
	}
	public function status($id){
		$banyak = $this->input->post('banyak');
		$this->db->where('id_kel_sungai_sub', $id);
		$query = $this->db->get('d_titik_sungai')->result();
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
		}
		if($ave_temp != 0&& $banyak_temp != 0){
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
		//Rata -rata
		
		
		
		
		
		
		
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
		if($_SESSION['sungai'] == 's_gajah'){
			

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

		}elseif(($_SESSION['sungai'] == 's_kambaniru') || ($_SESSION['sungai'] == 's_brantas')){


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
		$skor = $skor_tds + $skor_temp + $skor_ph + $skor_bod + $skor_do + $skor_ecoli;
		if($skor >= 0){
			$status_sungai = "Biru";
		}else if($skor >= -10 && $skor <= -1){
			$status_sungai = "Kuning";
		}else if($skor >= -30 && $skor <= -11){
			$status_sungai = "Orange";
		}else if($skor <= -31){
			$status_sungai = "Merah";
		}
	
	return $status_sungai;
	}
	public function edit_simpan_relawan_model($id){

		$config['upload_path'] = './assets/upload';    
		$config['allowed_types'] = 'jpg|png|jpeg';    
		$config['max_size']  = '5000';    
		$config['remove_space'] = TRUE;
		$timestamp = date('Y-m-d G:i:s');
		$i = 0;
		$this->db->where('id_kel_sungai_sub', $id);
		$query=$this->db->get('d_titik_sungai')->result();
		$this->load->library('upload', $config);
		if($this->upload->do_upload('foto')){
			$foto = array('result' => 'success', 'foto' => $this->upload->data(), 'error' => '');
			if($this->upload->do_upload('lampiran')){				
				$lamp = array('result' => 'success', 'lamp' => $this->upload->data(), 'error' => '');
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'keterangan' => $this->input->post('ket'),
					'foto' => $foto['foto']['file_name'],
					'lampiran' => $lamp['lamp']['file_name'],
					'status_kirim' => 2,
					'status_setuju'=>0,
					'waktu_tambah' => $timestamp,
					'titik_lat' => $this->input->post('lat_m'),
					'titik_lng' => $this->input->post('lng_m'),

				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_user);
				
				foreach ($query as $row) {
					
					$data = [
						'temperatur' => $this->input->post('temp'.$i),
						'ec' => $this->input->post('ec'.$i),
						'tds' => $this->input->post('tds'.$i),
						'ph' => $this->input->post('ph'.$i),
						'do' => $this->input->post('do'.$i),
						'bod' => $this->input->post('bod'.$i),
						'ecoli' => $this->input->post('ecoli'.$i),
					];
					$id_titik=$this->input->post('id_titik'.$i);
					$this->db->where('id_titik_sungai', $id_titik);
					$this->db->update('d_titik_sungai',$data);
					$i++;
				}
				$status = $this->status($id);
				$data_status = [
					'status_titik' => $status,
				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_status);
				return true;
				
			}else{
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'keterangan' => $this->input->post('ket'),
					'foto' => $foto['foto']['file_name'],
					'status_kirim' => 2,
					'status_setuju'=>0,
					'waktu_tambah' => $timestamp,
					'titik_lat' => $this->input->post('lat_m'),
					'titik_lng' => $this->input->post('lng_m'),
				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_user);
				foreach ($query as $row) {
					$data = [
						'temperatur' => $this->input->post('temp'.$i),
						'ec' => $this->input->post('ec'.$i),
						'tds' => $this->input->post('tds'.$i),
						'ph' => $this->input->post('ph'.$i),
						'do' => $this->input->post('do'.$i),
						'bod' => $this->input->post('bod'.$i),
						'ecoli' => $this->input->post('ecoli'.$i),
					];
					$id_titik=$this->input->post('id_titik'.$i);
					$this->db->where('id_titik_sungai', $id_titik);
					$this->db->update('d_titik_sungai',$data);
					$i++;
				}
				$status = $this->status($id);
				$data_status = [
					'status_titik' => $status,
				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_status);
				return true;
				
			}
		}else{
			if($this->upload->do_upload('lampiran')){				
				$lamp = array('result' => 'success', 'lamp' => $this->upload->data(), 'error' => '');
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'keterangan' => $this->input->post('ket'),
					'lampiran' => $lamp['lamp']['file_name'],
					'status_kirim' => 2,
					'status_setuju'=>0,
					'waktu_tambah' => $timestamp,
					'titik_lat' => $this->input->post('lat_m'),
					'titik_lng' => $this->input->post('lng_m'),
				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_user);
				foreach ($query as $row) {
					$data = [
						'temperatur' => $this->input->post('temp'.$i),
						'ec' => $this->input->post('ec'.$i),
						'tds' => $this->input->post('tds'.$i),
						'ph' => $this->input->post('ph'.$i),
						'do' => $this->input->post('do'.$i),
						'bod' => $this->input->post('bod'.$i),
						'ecoli' => $this->input->post('ecoli'.$i),
					];
					$id_titik=$this->input->post('id_titik'.$i);
					$this->db->where('id_titik_sungai', $id_titik);
					$this->db->update('d_titik_sungai',$data);
					$i++;
				}
				$status = $this->status($id);
				$data_status = [
					'status_titik' => $status,
				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_status);
				return true;
				
			}else{
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'keterangan' => $this->input->post('ket'),
					'status_kirim' => 2,
					'status_setuju'=>0,
					'waktu_tambah' => $timestamp,
					'titik_lat' => $this->input->post('lat_m'),
					'titik_lng' => $this->input->post('lng_m'),
				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_user);
				foreach ($query as $row) {
					$data = [
						'temperatur' => $this->input->post('temp'.$i),
						'ec' => $this->input->post('ec'.$i),
						'tds' => $this->input->post('tds'.$i),
						'ph' => $this->input->post('ph'.$i),
						'do' => $this->input->post('do'.$i),
						'bod' => $this->input->post('bod'.$i),
						'ecoli' => $this->input->post('ecoli'.$i),
					];
					$id_titik=$this->input->post('id_titik'.$i);
					$this->db->where('id_titik_sungai', $id_titik);
					$this->db->update('d_titik_sungai',$data);
					$i++;
				}
				$status = $this->status($id);
				$data_status = [
					'status_titik' => $status,
				];
				$this->db->where('id_kel_sungai', $id);
				$this->db->update('d_kel_sungai',$data_status);
				return true;
				
			}
		}
	}
	public function hapus_simpan_relawan_model($id){
		$this->db->where('id_kel_sungai_sub', $id);
		$this->db->delete('d_titik_sungai');
		$this->db->where('id_kel_sungai', $id);
		$this->db->delete('d_kel_sungai');
		
		return true;
	}
	public function hapus_kirim_relawan_model($id){
		$this->db->where('id_kel_sungai_sub', $id);
		$this->db->delete('d_titik_sungai');
		$this->db->where('id_kel_sungai', $id);
		$this->db->delete('d_kel_sungai');
		
		return true;
	}
	public function simpan_relawan_model(){
		$id = "trx".$_SESSION['user'].date("Ymd").rand();
		$banyak = $this->input->post('banyak');
		$config['upload_path'] = './assets/upload';    
		$config['allowed_types'] = 'jpg|png|jpeg';    
		$config['max_size']  = '5000';    
		$config['remove_space'] = TRUE;
		$this->load->library('upload', $config);
		if($this->upload->do_upload('foto')){
			$foto = array('result' => 'success', 'foto' => $this->upload->data(), 'error' => '');
			if($this->upload->do_upload('lampiran')){				
				$lamp = array('result' => 'success', 'lamp' => $this->upload->data(), 'error' => '');
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'id_kel_sungai' => $id,
					'kd_sungai_sub' => $_SESSION['sungai'],
					'id_relawan_sub' => $_SESSION['user'],
					'keterangan' => $this->input->post('ket'),
					'foto' => $foto['foto']['file_name'],
					'titik_lat' => $this->input->post('lat_m'),
					'titik_lng' => $this->input->post('lng_m'),
					'lampiran' => $lamp['lamp']['file_name'],
					'status_kirim' => 0,
					'banyak' => $banyak
				];
				$this->db->insert('d_kel_sungai',$data_user);
				
				for($i=0;$i<$banyak;$i++){
					$data = [
						'temperatur' => $this->input->post('temp'.$i),
						'ec' => $this->input->post('ec'.$i),
						'tds' => $this->input->post('tds'.$i),
						'ph' => $this->input->post('ph'.$i),
						'do' => $this->input->post('do'.$i),
						'bod' => $this->input->post('bod'.$i),
						'ecoli' => $this->input->post('ecoli'.$i),
						'id_kel_sungai_sub' => $id,
					];
					$this->db->insert('d_titik_sungai',$data);
				}
				return true;
			}else{
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'id_kel_sungai' => $id,
					'kd_sungai_sub' => $_SESSION['sungai'],
					'id_relawan_sub' => $_SESSION['user'],
					'keterangan' => $this->input->post('ket'),
					'foto' => $foto['foto']['file_name'],
					'status_kirim' => 0,
					'titik_lat' => $this->input->post('lat_m'),
					'titik_lng' => $this->input->post('lng_m'),
					'banyak' => $banyak
				];
				$this->db->insert('d_kel_sungai',$data_user);
				for($i=0;$i<$banyak;$i++){
					$data = [
						'temperatur' => $this->input->post('temp'.$i),
						'ec' => $this->input->post('ec'.$i),
						'tds' => $this->input->post('tds'.$i),
						'ph' => $this->input->post('ph'.$i),
						'do' => $this->input->post('do'.$i),
						'bod' => $this->input->post('bod'.$i),
						'ecoli' => $this->input->post('ecoli'.$i),
						'id_kel_sungai_sub' => $id,
					];
					$this->db->insert('d_titik_sungai',$data);
				}
				return true;
				
			}
		}else{
			if($this->upload->do_upload('lampiran')){				
				$lamp = array('result' => 'success', 'lamp' => $this->upload->data(), 'error' => '');
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'id_kel_sungai' => $id,
					'kd_sungai_sub' => $_SESSION['sungai'],
					'id_relawan_sub' => $_SESSION['user'],
					'keterangan' => $this->input->post('ket'),
					'lampiran' => $lamp['lamp']['file_name'],
					'titik_lat' => $this->input->post('lat_m'),
					'titik_lng' => $this->input->post('lng_m'),
					'status_kirim' => 0,
					'banyak' => $banyak
				];
				$this->db->insert('d_kel_sungai',$data_user);
				for($i=0;$i<$banyak;$i++){
					$data = [
						'temperatur' => $this->input->post('temp'.$i),
						'ec' => $this->input->post('ec'.$i),
						'tds' => $this->input->post('tds'.$i),
						'ph' => $this->input->post('ph'.$i),
						'do' => $this->input->post('do'.$i),
						'bod' => $this->input->post('bod'.$i),
						'ecoli' => $this->input->post('ecoli'.$i),
						'id_kel_sungai_sub' => $id,
					];
					$this->db->insert('d_titik_sungai',$data);
				}
				return true;
			}else{
				$data_user = [
					'penanda' => $this->input->post('tanda'),
					'id_kel_sungai' => $id,
					'kd_sungai_sub' => $_SESSION['sungai'],
					'id_relawan_sub' => $_SESSION['user'],
					'keterangan' => $this->input->post('ket'),
					'titik_lat' => $this->input->post('lat_m'),
					'titik_lng' => $this->input->post('lng_m'),
					'status_kirim' => 0,
					'banyak' => $banyak
				];
				$this->db->insert('d_kel_sungai',$data_user);
				for($i=0;$i<$banyak;$i++){
					$data = [
						'temperatur' => $this->input->post('temp'.$i),
						'ec' => $this->input->post('ec'.$i),
						'tds' => $this->input->post('tds'.$i),
						'ph' => $this->input->post('ph'.$i),
						'do' => $this->input->post('do'.$i),
						'bod' => $this->input->post('bod'.$i),
						'ecoli' => $this->input->post('ecoli'.$i),
						'id_kel_sungai_sub' => $id,
					];
					$this->db->insert('d_titik_sungai',$data);
				}
				return true;
			}
		}
	}
	
}
// 