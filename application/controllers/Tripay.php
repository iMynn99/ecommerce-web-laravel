<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load the Tripay library
$this->load->library('tripay');


class Tripay extends CI_Controller {

	public function index(){
        //print_r($this->tripay->createPayment("INV123456","BRIVA",500000,["nama"=>"susanto","email"=>"dewabilly@gmail.com","nohp"=>"085691257411"]));
        //print_r($this->tripay->cekPayment("DEV-T25450000006475FLJLK"));
	}

	function bayarpesanan(){
		$user = (isset($_SESSION["usrid"])) ? $this->func->getUser($_SESSION["usrid"],"semua") : $this->func->getUserTemp($_SESSION["usrid_temp"],"semua");
		if(isset($_POST["metode"])){
			$trx = $_POST["bayar"];
			$set = $this->func->globalset("semua");
			$byr = $this->func->getBayar($trx,"semua");
			$trs = $this->func->getTransaksi($trx,"semua","idbayar");
			$this->db->where("idtransaksi",$trs->id);
			$db = $this->db->get("transaksiproduk");
			$produk = [['sku'=>$byr->invoice,'name'=>"Pembayaran Invoice #".$byr->invoice,'price'=> $byr->transfer,'quantity'=>1]];
			$email = (isset($user->username) AND $user->username != "") ? $user->username : $set->email;
			$user->nohp = ($user->nohp != "") ? $user->nohp : $set->wasap;
			$user->nama = ($user->nama != "") ? $user->nama : "user-".$user->id.$user->nohp;
			$pembeli = ['nama'=>$user->nama,'email'=>$email,'nohp'=>$user->nohp];

			/*
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
			*/

			$res = $this->tripay->createPayment($trx,$_POST["metode"],$byr->transfer,$pembeli,$produk);

			if($res->success == true){
				echo json_encode(array("success"=>true,"msg"=>"Success","token"=>$this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Gagal memproses pembayaran","token"=>$this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Gagal memproses pembayaran","token"=>$this->security->get_csrf_hash()));
		}
	}

	function webhook(){
		$json = file_get_contents("php://input");
		$set = $this->func->globalset("semua");
		
		$callbackSignature = isset($_SERVER['HTTP_X_CALLBACK_SIGNATURE']) ? $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] : '';
		$signature = hash_hmac('sha256', $json, $set->tripay_privatekey);

		if( $callbackSignature !== $signature ) {
			echo json_encode(array("success"=>false,"msg"=>"Forbidden Access"));
			exit();
		}

		$data = json_decode($json);
		$event = $_SERVER['HTTP_X_CALLBACK_EVENT'];

		if( $event == 'payment_status'){
			if( $data->status == 'PAID'){
				$datas = array(
                    "status"=> $data->status,
                    "paid_at"=> $data->paid_at,
					"webhook_response"=> $json,
                    "statusbayar"=> 1
                );
                $this->db->where("reference",$data->reference);
                $this->db->update("tripay",$datas);
				$tripay = $this->tripay->getTripay($data->reference,"semua","reference");
				$byr = $this->func->getBayar($data->reference,"semua","tripay_ref");

				$trx = array(
					"status"=> 1,
					"tglupdate"	=> date("Y-m-d H:i:s",$data->paid_at)
				);
				$this->db->where("id",$byr->id);
				$this->db->update("pembayaran",$trx);
				$stat = ($byr->digital == 1) ? 3 : 1;
				$trx = array(
					"status"=> $stat,
					"tglupdate"	=> date("Y-m-d H:i:s",$data->paid_at)
				);
				$this->db->where("idbayar",$byr->id);
				$this->db->update("transaksi",$trx);

				$trx = $this->func->getTransaksi($byr->id,"semua","idbayar");
				if($stat == 3){
					$this->db->where("idtransaksi",$trx->id);
					$db = $this->db->get("afiliasi");
					foreach($db->result() as $r){
						$saldo = $this->func->getSaldo($r->usrid,"semua");
						$saldototal = $saldo->saldo + $r->jumlah;
						$tgl = date("Y-m-d H:i:s");
						$data = [
							"usrid"	=> $r->usrid,
							"trxid"	=> "TOPUP_".$r->usrid.date("YmdHis"),
							"jenis"	=> 2,
							"status"=> 1,
							"selesai"	=> $tgl,
							"tgl"	=> $tgl,
							"total"	=> $r->jumlah,
							"metode"=> 1,
							"keterangan"=> "Pencairan komisi dari transaksi #".$trx->orderid
						];
						$this->db->insert("saldotarik",$data);
						$topup = $this->db->insert_id();

						$data2 = [
							"tgl"	=> $tgl,
							"usrid"	=> $r->usrid,
							"jenis"	=> 1,
							"jumlah"=> $r->jumlah,
							"darike"=> 1,
							"sambung"	=> $topup,
							"saldoawal"	=> $saldo->saldo,
							"saldoakhir"=> $saldototal
						];
						$this->db->insert("saldohistory",$data2);

						$this->db->where("id",$saldo->id);
						$this->db->update("saldo",["apdet"=>$tgl,"saldo"=>$saldototal]);
						
						$this->db->where("id",$r->id);
						$this->db->update("afiliasi",["status"=>2,"cair"=>date("Y-m-d H:i:s"),"saldotarik"=>$topup]);
					}
				}else{
					$this->db->where("idtransaksi",$trx->id);
					$this->db->update("afiliasi",["status"=>1]);
				}

				/*
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
				*/
			}
			echo json_encode(["success"=>true,"payment_status"=>$data->status]);
		}else{
			echo json_encode(["success"=>false,"msg"=>"transaction not found"]);
		}
	}

	function tes(){
		$this->tripay->getmetode();
	}
}