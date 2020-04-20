<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relawan extends CI_Controller {

  public function __construct(){
    parent::__construct();
    if(!isset($_SESSION['user'])){
      redirect(base_url('relawan'));
    }
    $this->load->model('relawan_model');
  }
  public function index(){

    $this->load->view('relawan/header');
    $this->load->view('relawan/input');
    $this->load->view('relawan/footer');
  }
  public function edit_simpan($id){
    $data['info'] = $this->relawan_model->ambil_data($id);
    $this->load->view('relawan/header');
    $this->load->view('relawan/edit_simpan', $data);
    $this->load->view('relawan/footer');
  }
  public function lihat(){
    $data['simpan'] = $this->relawan_model->ambil_simpan();
    $data['kirim'] = $this->relawan_model->ambil_kirim();
    $data['terima'] = $this->relawan_model->ambil_terima();
    $data['d_laporan'] = $this->relawan_model->d_laporan();
    $data['d_simpan'] = $this->relawan_model->d_simpan();
    $data['d_kirim'] = $this->relawan_model->d_kirim();
    $data['d_proses'] = $this->relawan_model->d_proses();     
    $data['d_terima'] = $this->relawan_model->d_terima();
    $data['d_tolak'] = $this->relawan_model->d_tolak();
    $this->load->view('relawan/header');
    $this->load->view('relawan/lihat', $data);
    $this->load->view('relawan/footer');
  }
  public function input(){
    if(!isset($_SESSION['user'])){
      redirect(base_url());
    }
    $a = 0;
    $banyak = $this->input->post('banyak');
    if(isset($_POST['kirim'])){
      if($this->input->post('banyak') > 0){
        for($i=0;$i<$banyak;$i++){
          if( ($this->input->post('temp'.$i) == null) && ($this->input->post('ec'.$i) == null) && ($this->input->post('tds'.$i) == null) && ($this->input->post('ph'.$i) == null) && ($this->input->post('do'.$i)== null) && ($this->input->post('bod'.$i)== null) && ($this->input->post('ecoli'.$i)== null)){
            $a = 1;
          }
        }
        if($a != 1){
          if($this->relawan_model->input_relawan_model() === true){
           $this->session->set_flashdata('msg', 
            '<div class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <h4>Selamat Data Berhasil Dikirim</h4>
            <p>Data Akan Diperiksa oleh Admin.</p>
            </div>');   
           $this->load->view('relawan/header');
           $this->load->view('relawan/input');
           $this->load->view('relawan/footer');
         }else{
          ?>
          <script>

            alert('Gagal Menambahkan !');

            history.go(-1);

          </script>
          <?php 
        }
      }else{
       ?>
       <script>

        alert('Data Tidak Boleh Kosong Semua');

        history.go(-1);

      </script>
      <?php 
    }
  }else{
   ?>
   <script>

    alert('Input Jumlah Ulangan Pengambilan Sampel Air Sungai Harus Lebih Dari 0 !');

    history.go(-1);

  </script>
  <?php 
}
}else if(isset($_POST['simpan'])){
  if($this->relawan_model->simpan_relawan_model() === true){
   $this->session->set_flashdata('msg', 
    '<div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <h4>Selamat data Berhasil disimpan</h4>
    <p>Data Akan Di Cek oleh Admin.</p>
    </div>');   
   redirect(base_url('relawan/lihat'));
 }else{
  ?>
  <script>

    alert('Gagal Menyimpan !');

    history.go(-1);

  </script>
  <?php 
}
}
}
public function proses_edit_simpan($id){
  if(!isset($_SESSION['user'])){
    redirect(base_url());
  }
  $a = 0;
  $banyak = $this->input->post('banyak');
  if(isset($_POST['kirim'])){ 
    for($i=0;$i<$banyak;$i++){
      if( ($this->input->post('temp'.$i) == null) && ($this->input->post('ec'.$i) == null) && ($this->input->post('tds'.$i) == null) && ($this->input->post('ph'.$i) == null) && ($this->input->post('do'.$i)== null) && ($this->input->post('bod'.$i)== null) && ($this->input->post('ecoli'.$i)== null)){
        $a = 1;
      }
    }
    if($a != 1){
      if($this->relawan_model->edit_simpan_relawan_model($id) === true){
       $this->session->set_flashdata('msg', 
        '<div class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <h4>Selamat data Berhasil dikirim</h4>
        <p>Data Akan Di Cek oleh Admin.</p>
        </div>');   
       redirect(base_url('relawan/lihat'));
     }else{
      ?>
      <script>

        alert('Gagal Edit !');

        history.go(-1);

      </script>
      <?php 
    }
  }else{
       ?>
       <script>

        alert('Data Tidak Boleh Kosong Semua');

        history.go(-1);

      </script>
      <?php 
    }
}else if(isset($_POST['cancel'])){

  ?>
  <script>

    history.go(-2);

  </script>
  <?php 

}
}
public function hapus_simpan($id){

  if($this->relawan_model->hapus_simpan_relawan_model($id) === true){
   $this->session->set_flashdata('msg', 
    '<div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <h4>Selamat data Berhasil dikirim</h4>
    <p>Data Akan Di Cek oleh Admin.</p>
    </div>');   
   redirect(base_url('relawan/lihat'));
 }else{
  ?>
  <script>

    alert('Gagal Hapus !');

    history.go(-1);

  </script>
  <?php 
}

}
public function hapus_kirim($id){

  if($this->relawan_model->hapus_kirim_relawan_model($id) === true){
   $this->session->set_flashdata('msg', 
    '<div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <h4>Selamat data Berhasil dikirim</h4>
    <p>Data Akan Di Cek oleh Admin.</p>
    </div>');   
   redirect(base_url('relawan/lihat'));
 }else{
  ?>
  <script>

    alert('Gagal Hapus !');

    history.go(-1);

  </script>
  <?php 
}

}
public function tampil_modal($id)
{
  $data = $this->relawan_model->ambil_data($id);

  echo json_encode($data);
}
public function cetak(){    
  ob_start();      
  $data['titiks'] = $this->relawan_model->ambil_terima();
  $data['detail'] = $this->relawan_model->ambil_detail();
  $this->load->view('relawan/print', $data);    
  $html = ob_get_contents();        
  ob_end_clean();                
  require_once('./assets/html2pdf/html2pdf.class.php');    
  $pdf = new HTML2PDF('P','A4','en');    
  $pdf->setDefaultFont('Arial');
  $pdf->WriteHTML($html);    
  $pdf->Output('Data Status Sungai.pdf', 'D');  
}

}