<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konfirmasi extends CI_Controller {

	public function index(){
		$this->load->view("headv2",array("titel"=>"Konfirmasi Pembayaran Pesanan"));
		$this->load->view("main/konfirmasi");
		$this->load->view("footv2");
	}

    function kirim(){
        if(isset($_POST["invoice"])){
            $bayar = $this->func->getBayar($this->func->clean($_POST["invoice"]),"semua","invoice");

            if($bayar->id > 0 AND $bayar->usrid_temp > 0 AND $bayar->usrid == 0){
				$config['upload_path'] = './cdn/konfirmasi/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
				$config['file_name'] = $bayar->usrid_temp.$bayar->id.date("YmdHis");

				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('bukti')){
					$error = $this->upload->display_errors();
                    redirect("konfirmasi?result=gagal&msg=".$error);
				}else{
					$upload_data = $this->upload->data();

					$filename = $upload_data['file_name'];
					$data = array(
						"tgl"		=> date("Y-m-d H:i:s"),
						"idbayar"	=> $bayar->id,
						"bukti"		=> $filename
					);
					$this->db->insert("konfirmasi",$data);

					redirect("konfirmasi?result=sukses");
				}
            }else{
                redirect("konfirmasi?result=gagal");
            }
        }else{
            redirect("konfirmasi?result=gagal");
        }
    }

}