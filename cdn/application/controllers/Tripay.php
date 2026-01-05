<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tripay extends CI_Controller {

	public function index(){
        //print_r($this->tripay->createPayment("INV123456","BRIVA",500000,["nama"=>"susanto","email"=>"dewabilly@gmail.com","nohp"=>"085691257411"]));
        print_r($this->tripay->cekPayment("DEV-T25450000006475FLJLK"));
	}

	function bayarpesanan(){
		$user = $this->func->getUser($_SESSION["usrid"],"semua");
		if($_POST["tipe"] == 1){
			$prod = $this->func->getProduk($_POST["produk"],"semua");
		}elseif($_POST["tipe"] == 2){
			$prod = $this->func->getPaket($_POST["produk"],"semua");
		}elseif($_POST["tipe"] == 3){
			$prod = $this->func->getLayanan($_POST["produk"],"semua");
		}else{
			
			exit;
		}
		$produk = [['sku'=>$prod->kode,'name'=>$prod->nama,'price'=> $prod->harga,'quantity'=>1]];
		$pembeli = ['nama'=>$user->nama,'email'=>"afdkstore@gmail.com",'nohp'=>$user->nohp];

		$inv = $_SESSION["usrid"].date("YmdHis");
		$data = array(
			"invoice"	=> $inv,
			"idproduk"	=> $prod->id,
			"tgl"	=> date("Y-m-d H:i:s"),
			"apdet"	=> date("Y-m-d H:i:s"),
			"status"	=> 0,
			"tripay_metode"	=> $_POST["metode"],
			"usrid"	=> $_SESSION["usrid"],
			"jenis"	=> $_POST["tipe"],
			"total"	=> $prod->harga
		);
		$this->db->insert("transaksi",$data);
		$trx = $this->db->insert_id();

		$res = $this->tripay->createPayment($trx,$_POST["metode"],$prod->harga,$pembeli,$produk);

		if($res->success == true){
			echo json_encode(array("success"=>true,"msg"=>"Success","token"=>$this->security->get_csrf_hash(),"invoice"=>$inv));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Gagal memproses pembayaran","token"=>$this->security->get_csrf_hash()));
		}
	}

	function webhook(){
		$json = file_get_contents("php://input");
		
		$callbackSignature = isset($_SERVER['HTTP_X_CALLBACK_SIGNATURE']) ? $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] : '';
		$signature = hash_hmac('sha256', $json, 'private_key_anda');

		if( $callbackSignature !== $signature ) {
			echo json_encode(array("success"=>false,"msg"=>"Forbidden Access"));
			exit();
		}

		$data = json_decode($json);
		$event = $_SERVER['HTTP_X_CALLBACK_EVENT'];

		if( $event == 'payment_status' ){
			if( $data->status == 'PAID' ){
				$datas = array(
                    "status"=> $data->status,
                    "paid_at"=> $data->paid_at,
                    "statusbayar"=> 1
                );
                $this->db->where("reference",$data->reference);
                $this->db->update("tripay",$datas);
				$tripay = $this->tripay->getTripay($data->reference,"semua","reference");

				$trx = array(
					"status"=> 1,
					"lunas"	=> date("Y-m-d H:i:s",$data->paid_at)
				);
				$this->db->where("invoice",$tripay->merchant_ref);
				$this->db->update("transaksi",$trx);
				$trx = $this->func->getTransaksi($tripay->merchant_ref,"semua","invoice");

				if($trx->afiliasi_id > 0){
					$afiliasi = $this->func->getAfiliasi($trx->afiliasi_id,"semua");
					$saldo = $this->func->getSaldo($afiliasi->usrid,"semua");

					$this->db->where("id",$trx->afiliasi_id);
					$this->db->update("afiliasi",["status"=>1,"masuk"=>date("Y-m-d H:i:s",$data->paid_at)]);

					$saldoakhir = $saldo->saldo + $afiliasi->nilai;
					$this->db->where("id",$saldo->id);
					$this->db->update("saldo",["saldo"=>$saldoakhir,"tgl"=>date("Y-m-d H:i:s")]);

					$sh = [
						"usrid"	=>$afiliasi->usrid,
						"tgl"	=>date("Y-m-d H:i:s"),
						"awal"	=>$saldo->saldo,
						"akhir"	=>$saldoakhir,
						"nilai"	=>$afiliasi->nilai,
						"catatan"=>"Penghasilan <b>afiliasi</b> dari pembelian oleh: <b>".strtoupper(strtolower($this->func->getUser($trx->usrid,"nama")))."</b>"
					];
					$this->db->insert("saldohistory",$sh);
				}
			}
		}
	}

	function tesdb(){
		$fields = $this->db->field_data('pembayaran');
		$result = new stdClass();
		foreach ($fields as $r){
			$nama = $r->name;
			if($r->type == "text"){
				$result->$nama = "";
			}elseif($r->type == "datetime"){
				$result->$nama = "0000-00-00 00:00:00";
			}elseif($r->type == "int"){
				$result->$nama = 0;
			}elseif($r->type == "bigint"){
				$result->$nama = 0;
			}else{
				$result->$nama = "data telah dihapus";
			}
		}
		print_r($result);
	}
}
