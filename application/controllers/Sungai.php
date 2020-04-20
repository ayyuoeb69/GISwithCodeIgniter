<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sungai extends CI_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->model('sungai_model');
        $this->load->library('cart'); 
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
                    $this->sungai_model->tambah_dasar($koordinat['latitude'], $koordinat['longitude'], $id_kel);
                }

                $msg = "Data Berhasil Ditambahkan";
                $status = 'success';

            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
        }
    }
}