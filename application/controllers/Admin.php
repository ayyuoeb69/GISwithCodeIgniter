<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($_SESSION['admin'])){
			redirect(base_url('admin'));
		}
		$this->load->model('admin_model');
		$this->load->model('relawan_model');
		$this->load->library('cart'); 
		$this->load->model('sungai_model');
	}
	public function index()
	{
		$this->load->view('admin/header');
		$this->load->view('admin/footer');
	}
	public function relawan()
	{ 
		$data['info'] = $this->admin_model->ambil_relawan_data();
		$data['banyak'] = $this->admin_model->banyak_relawan_data();
		$data['list'] = $this->admin_model->list_relawan_data();
		$this->load->view('admin/header');
		$this->load->view('admin/relawan', $data);
		$this->load->view('admin/footer');
	}
	// public function prints()
	// {
	// 	$data['sungai'] = $this->admin_model->getSungai();
	// 	$data['titiks'] = $this->admin_model->getAllbytitik();
	// 	$data['detail'] = $this->admin_model->getKoor();
	// 	$this->load->view('admin/print', $data);
	// 	$this->load->view('admin/footer');
	// }
	public function peta()
	{
		$data['setuju'] = $this->admin_model->ambil_terima_data();
		$data['verif'] = $this->admin_model->ambil_verif_data();
		$data['titiks'] = $this->admin_model->getAllbytitik();
		$data['dasar'] = $this->admin_model->getDasarbyId();

		$data['sungai'] = $this->admin_model->getSungai();
		$this->load->view('admin/header');
		$this->load->view('admin/peta', $data);
		$this->load->view('admin/footer');
	}
	public function setuju_verif($id){
		if($this->admin_model->setuju_verif_model($id) === true){
			redirect(base_url('admin/peta'));
		}else{
			?>
			<script>

				alert('Gagal Menyetujui !');

				history.go(-1);

			</script>
			<?php 
		}
	}
	public function tolak_verif($id){
		if($this->admin_model->tolak_verif_model($id) === true){
			redirect(base_url('admin/peta'));
		}else{
			?>
			<script>

				alert('Gagal Menyetujui !');

				history.go(-1);

			</script>
			<?php 
		}
	}
	public function hapus_relawan($id){
		if($this->admin_model->hapus_relawan_model($id) === true){
			$this->session->set_flashdata('msg', 
				'<div class="alert alert-success alert-dismissible">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<h4>Selamat data Berhasil dikirim</h4>
				<p>Data Akan Di Cek oleh Admin.</p>
				</div>');   
			redirect(base_url('admin/relawan'));
		}else{
			?>
			<script>

				alert('Gagal Hapus !');

				history.go(-1);

			</script>
			<?php 
		}
	}
	public function titik_sungai(){

		$status = 'success';
			// $msg = $this->model_koordinatjalan->get_id_sungai($this->input->post('id_jalan'))->result();
			// $datajalan = $this->model_koordinatjalan->read($this->input->post('id_jalan'))->result();
		$titik = $this->admin_model->getAllbytitik();
		echo json_encode($titik);
	}
	public function dasar_sungai($id){

		$status = 'success';
		$dasar = $this->admin_model->getDasar($id);
		echo json_encode($dasar);
	}
	public function tambahdasarsungai()
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
		}else
		{
			if($this->cart->contents()==null){
				$data = array(
					'id'      => 1,
					'qty'     => 1,
					'price'   => 1,
					'jenis'      => 'sungai',
					'name'    => 1,
					'latitude'=> $this->input->post('latitude'),
					'longitude'=> $this->input->post('longitude')
				);

				$status = "success";
				$msg = "<div class='alert alert-success'>Data berhasil disimpan</div>";
			}else{
				$urut = 0;
				foreach ($this->cart->contents() as $sungai) {
					$urut++;
				}
				$data = array(
					'id'      => $urut + 1,
					'qty'     => 1,
					'price'   => 1,
					'jenis'      => 'sungai',
					'name'    => $urut + 1,
					'latitude'=> $this->input->post('latitude'),
					'longitude'=> $this->input->post('longitude')
				);


				$status = "success";
				$msg = "<div class='alert alert-success'>Data berhasil disimpan</div>";
			}
			$this->cart->insert($data);
			$this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
		}
	}
	function simpandasarsungai(){
		if (!$this->input->is_ajax_request()) {
			show_404();
		}else{
			
			if($this->cart->contents()==null){
				$msg = 'Koordinat Belum Diisi';
				$status = 'error';
			}else{
				$id_kel = 'dasar'.date("Ymd").rand();
				foreach ($this->cart->contents() as $koordinat) {
					$this->admin_model->tambah_dasar($koordinat['latitude'], $koordinat['longitude'], $id_kel);
				}

				$msg = "Data Berhasil Ditambahkan";
				$status = 'success';

			}

			$this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
		}
	}
	function hapusdaftarkoordinatjalan(){
		if (!$this->input->is_ajax_request()) {
			show_404();
		}else{
			$hapus = $this->cart->destroy();
			$status = 'success';
			$msg = 'data koordinat berhasil dihapus';

			$this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
		}
	}
	function lihat_titik(){
		$id = $this->input->post('id_kel');
		if (!$this->input->is_ajax_request()) {
			show_404();
		}else{
			$data = $this->admin_model->gettitik($id);
			echo json_encode($data);
		}
	}
	public function tampil_modal($id)
	{
		$data = $this->admin_model->ambil_data($id);

		echo json_encode($data);
	}
	public function tampil_edit_relawan($id)
	{
		$data = $this->admin_model->ambil_id_relawan_data($id);

		echo json_encode($data);
	}
	public function cetak(){    
		ob_start();      
		$data['sungai'] = $this->admin_model->getSungai();
		$data['titiks'] = $this->admin_model->getAllbytitik();
		$data['detail'] = $this->admin_model->getKoor();
		$this->load->view('admin/print', $data);    
		$html = ob_get_contents();        
		ob_end_clean();                
		require_once('./assets/html2pdf/html2pdf.class.php');    
		$pdf = new HTML2PDF('P','A4','en');    
		$pdf->setDefaultFont('Arial');
		$pdf->WriteHTML($html);    
		$pdf->Output('Data Status Sungai.pdf', 'D');  
	}
}