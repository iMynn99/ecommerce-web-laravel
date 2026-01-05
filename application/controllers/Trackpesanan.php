<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trackpesanan extends CI_Controller {

	public function index(){
		$this->load->view("headv2",["titel"=>"Cek Status Pesanan"]);
		$this->load->view("pesanan/tracking");
		$this->load->view("footv2");
	}

    function cek(){
        if(isset($_POST["orderid"])){
            $idbayar = $this->func->getBayar($this->func->clean($_POST["orderid"]),"id","invoice");
            $trx = $this->func->getTransaksi($idbayar,"semua","idbayar");

            if($trx->id > 0 AND $trx->usrid_temp > 0 AND $trx->usrid == 0){
                $this->session->set_userdata("usrid_temp",$trx->usrid_temp);
                echo json_encode(["success"=>true,"trxid"=>$trx->orderid,"token"=>$this->security->get_csrf_hash()]);
            }else{
                echo json_encode(["success"=>false,"token"=>$this->security->get_csrf_hash()]);
            }
        }else{
            echo json_encode(["success"=>false,"token"=>$this->security->get_csrf_hash()]);
        }
    }

}