<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjobs extends CI_Controller {

	public function __construct() 
	{
		parent::__construct();
	}

	public function index(){
		redirect("home/_404");
	}

	public function update_notif(){
		$this->db->where("status",0);
		$this->db->limit(10);
		$this->db->order_by("id DESC");
		$db = $this->db->get("notifikasi");

		$result = [];
		$resul = [];
		foreach($db->result() as $r){
			if($r->jenis == 1){
				$hasil = $this->func->sendEmailOK($r->tujuan,$r->judul,$r->pesan,$r->subyek,$r->pengirim);
				$result[] = array("id"=>$r->id,"sukses"=>$hasil,"jenis"=>"email");
				if($hasil == true){
					$this->db->where("id",$r->id);
					$this->db->update("notifikasi",["proses"=>date("Y-m-d H:i:s"),"status"=>1]);
				}else{
					$this->db->where("id",$r->id);
					$this->db->update("notifikasi",["proses"=>date("Y-m-d H:i:s"),"status"=>2]);
				}
			}elseif($r->jenis == 2){
				$img = ($r->judul != "") ? $r->judul : null;
				$hasil = $this->func->sendWAOK($r->tujuan,$r->pesan,$img);
				$result[] = array("id"=>$r->id,"sukses"=>$hasil,"jenis"=>"whatsapp");
				if($hasil == true){
					$this->db->where("id",$r->id);
					$this->db->update("notifikasi",["proses"=>date("Y-m-d H:i:s"),"status"=>1]);
				}else{
					$this->db->where("id",$r->id);
					$this->db->update("notifikasi",["proses"=>date("Y-m-d H:i:s"),"status"=>2]);
				}
			}elseif($r->jenis == 3){
				$hasil = $this->func->notifMobile($r->judul,$r->pesan,"",$r->tujuan);
				$result[] = array("id"=>$r->id,"sukses"=>$hasil,"jenis"=>"push notification");
				if($hasil == true){
					$this->db->where("id",$r->id);
					$this->db->update("notifikasi",["proses"=>date("Y-m-d H:i:s"),"status"=>1]);
				}else{
					$this->db->where("id",$r->id);
					$this->db->update("notifikasi",["proses"=>date("Y-m-d H:i:s"),"status"=>2]);
				}
			}
		}

		$this->db->where("status",0);
		$this->db->where("rilis <=",date("Y-m-d H:i:s"));
		$this->db->limit(5);
		$db = $this->db->get("broadcast");
		$set = $this->func->globalset("semua");

		foreach($db->result() as $r){
			$img = ($r->gambar != "") ? base_url("cdn/promo/".$r->gambar) : null;
			$imgsrc = ($r->gambar != "") ? "<img src='".$img."' style='max-width:100%;max-height:240px;' /><br/>" : null;

			// SEMUA USER
			//$this->db->select("id");
			$this->db->where("nohp !=","");
			$this->db->or_where("username !=","");
			$usr = $this->db->get("userdata");

			// PERNAH BELI
			$this->db->select("usrid");
			$this->db->where("status",1);
			$this->db->group_by("usrid");
			$db = $this->db->get("pembayaran");
			$pernah = [0];
			foreach($db->result() as $p){
				$pernah[] = $p->usrid;
			}

			// TRANSAKSI DI KERANJANG
			$this->db->select("usrid");
			$this->db->where("idtransaksi",0);
			$this->db->group_by("usrid");
			$trx = $this->db->get("transaksiproduk");
			$krj = [0];
			foreach($trx->result() as $t){
				$krj[] = $t->usrid;
			}

			// KIRIM EMAIL & WA
			if($this->func->demo() == false){
				if($r->jenis < 3){
					foreach($usr->result() as $res){
						if($r->tujuan == 0 OR ($r->tujuan == 1 AND in_array($res->id,$pernah)) OR ($r->tujuan == 2 AND !in_array($res->id,$pernah)) OR ($r->tujuan == 3 AND in_array($res->id,$krj))){
							if($r->jenis < 2 AND $res->username != ""){
								$hasil = $this->func->sendEmail($res->username,$r->judul,$imgsrc.$r->isi,"Promo ".$set->nama);
								$resul[] = array("id"=>$r->id,"usrid"=>$res->id,"sukses"=>$hasil);
							}elseif($r->jenis != 1 AND $res->nohp != ""){
								$hasil = $this->func->sendWA($res->nohp,"*".$r->judul."*\n".$r->isi."*\n\n"."Cek promonya langsung di website dan aplikasi ".$set->nama,$img);
								$resul[] = array("id"=>$r->id,"usrid"=>$res->id,"sukses"=>$hasil);
							}
						}
					}
				}
			}

			// KIRIM PUSH NOTIF
			if($r->jenis == 0 || $r->jenis == 3){
				//$resul[] = $img;
				if($r->tujuan == 0){
					$resul[] = $this->func->notifMobile($r->judul,$r->isi,"",null,$img);
				}else{
					foreach($usr->result() as $res){
						if(($r->tujuan == 1 AND in_array($res->id,$pernah)) OR ($r->tujuan == 2 AND !in_array($res->id,$pernah)) OR ($r->tujuan == 3 AND in_array($res->id,$krj))){
							$resul[] = $this->func->notifMobile($r->judul,$r->isi,"",$res->id,$img);
						}
					}
				}
			}
			$this->db->where("id",$r->id);
			$this->db->update("broadcast",["log"=>json_encode($resul),"status"=>1]);
		}

		echo json_encode(["broadcast"=>$resul,"kirimnotif"=>$result]);
	}

	public function update_pesanan(){
		// PESANAN
		$this->db->where("status",0);
		$this->db->where("kadaluarsa <",date("Y-m-d H:i:s"));
		$this->db->limit(10);
		$db = $this->db->get("pembayaran");
		$no = 1;
		foreach($db->result() as $r){
			//echo $no.". ".$this->func->getProfil($r->usrid,"nama","usrid")." / #".$r->invoice." / ".$this->func->formUang($r->total)." / exp. ".$this->func->ubahTgl("D, d M Y - H:i:s",$r->kadaluarsa);

			$this->db->where("idbayar",$r->id);
			$this->db->update("transaksi",array("status"=>4,"selesai"=>date("Y-m-d H:i:s"),"keterangan"=>"dibatalkan oleh sistem, karena melewati batas waktu pembayaran"));
			$this->db->where("id",$r->id);
			$this->db->update("pembayaran",array("status"=>3,"tglupdate"=>date("Y-m-d H:i:s")));
			$this->func->notifbatal($r->id,1);

			// JIKA SALDO DIGUNAKAN
			if($r->saldo > 0){
				$saldo = $this->func->getSaldo($r->usrid,"semua","usrid",true);
				$saldototal = $saldo->saldo + $r->saldo;
				$tgl = date("Y-m-d H:i:s");
				$data = [
					"usrid"	=> $r->usrid,
					"trxid"	=> "TOPUP_".$r->usrid.date("YmdHis"),
					"jenis"	=> 2,
					"status"=> 1,
					"selesai"	=> $tgl,
					"tgl"	=> $tgl,
					"total"	=> $r->saldo,
					"metode"=> 1,
					"keterangan"=> "pengembalian dana dari pembatalan transaksi"
				];
				$this->db->insert("saldotarik",$data);
				$topup = $this->db->insert_id();

				$data2 = [
					"tgl"	=> $tgl,
					"usrid"	=> $r->usrid,
					"jenis"	=> 1,
					"jumlah"=> $r->saldo,
					"darike"=> 1,
					"sambung"	=> $topup,
					"saldoawal"	=> $saldo->saldo,
					"saldoakhir"=> $saldototal
				];
				$this->db->insert("saldohistory",$data2);

				if($saldo->id == 0){
					$this->db->insert("saldo",["usrid"=>$r->usrid,"apdet"=>$tgl,"saldo"=>$saldototal]);
				}else{
					$this->db->where("id",$saldo->id);
					$this->db->update("saldo",["apdet"=>$tgl,"saldo"=>$saldototal]);
				}
			}
				
			// UPDATE STOK PRODUK
			$trx = $this->func->getTransaksi($r->id,"semua","idbayar");
			$this->db->where("idtransaksi",$trx->id);
			$db = $this->db->get("transaksiproduk");
			$nos = 1;
			foreach($db->result() as $r){
				$pro = $this->func->getProduk($r->idproduk,"semua");
				if($r->variasi != 0){
					$var = $this->func->getVariasi($r->variasi,"semua","id");
					$stok = $var->stok + $r->jumlah;
					$prostok = $pro->stok + $r->jumlah;
					$this->db->where("id",$r->idproduk);
					$this->db->update("produk",["stok"=>$prostok,"tglupdate"=>date("Y-m-d H:i:s")]);

					$this->db->where("id",$r->variasi);
					$this->db->update("produkvariasi",["stok"=>$stok,"tgl"=>date("Y-m-d H:i:s")]);
					
					$data = array(
						"usrid"	=> $trx->usrid,
						"stokawal" => $var->stok,
						"stokakhir" => $stok,
						"variasi" => $r->variasi,
						"jumlah" => $r->jumlah,
						"tgl"	=> date("Y-m-d H:i:s"),
						"idtransaksi" => $trx->id
					);
					$this->db->insert("historystok",$data);
				}else{
					$stok = $pro->stok + $r->jumlah;
					$this->db->where("id",$r->idproduk);
					$this->db->update("produk",["stok"=>$stok,"tglupdate"=>date("Y-m-d H:i:s")]);

					$data = array(
						"usrid"	=> $trx->usrid,
						"stokawal" => $pro->stok,
						"stokakhir" => $stok,
						"variasi" => 0,
						"jumlah" => $r->jumlah,
						"tgl"	=> date("Y-m-d H:i:s"),
						"idtransaksi" => $trx->id
					);
					$this->db->insert("historystok",$data);
				}
			}

			// UPDATE AFILIASI
			$this->db->where("idtransaksi",$trx->id);
			$this->db->update("afiliasi",["status"=>3]);
			
			$no++;
		}

		// TRANSAKSI
		$this->db->where("status",2);
		$trx = $this->db->get("transaksi");
		foreach($trx->result() as $r){
			$tgl1 = new DateTime($r->kirim);
			$tgl2 = new DateTime(date("Y-m-d"));
			$d = $tgl2->diff($tgl1)->days + 1;
			echo $d." hari<br/>";
			$hari = 4;
			if($this->func->getKurir($r->kurir,"jenis") == 2){
			    $this->db->where("paket",$r->paket);
			    $this->db->order_by("estimasi","DESC");
			    $this->db->limit(1);
			    $pkt = $this->db->get("kurircustom");
			    foreach($pkt->result() as $ps){
			        $hari = $ps->estimasi;
			    }
			}
			$haris = $hari+3;
			if($r->resi != '' AND $d >= $hari){
				$this->db->where("id",$r->id);
				$this->db->update("transaksi",["status"=>3,"selesai"=>date("Y-m-d H:i:s")]);
				$this->cairKomisi($r->id,$r->orderid);
			}elseif($r->resi == '' AND $d >= $haris){
				$this->db->where("id",$r->id);
				$this->db->update("transaksi",["status"=>3,"selesai"=>date("Y-m-d H:i:s")]);
				$this->cairKomisi($r->id,$r->orderid);
			}
		}
		
		// TOPUP SALDO
		$this->db->where("status",0);
		$this->db->where("UNIX_TIMESTAMP(tgl) <",time()-(48*60*60));
		$this->db->update("saldotarik",["selesai"=>date("Y-m-d H:i:s"),"status"=>2]);

		// UPDATE KERANJANG DOBEL
		$this->tes();

		// UPDATE STOK VARIASI
		$this->updateStok();
	}

	private function tes(){
		$this->db->where("idtransaksi",0);
		$db = $this->db->get("transaksiproduk");
		$dup = [];
		$duplicate = [];
		foreach($db->result() as $r){
			if(isset($dup[$r->usrid."_".$r->idproduk."_".$r->variasi])){
				$duplicate[] = $r->id; //$dup[$r->usrid."_".$r->idproduk."_".$r->variasi]." <= ".
			}else{
				$dup[$r->usrid."_".$r->idproduk."_".$r->variasi] = $r->id;
			}
		}
		for($i=0; $i<count($duplicate); $i++){
			echo $duplicate[$i]."<br/>";
			$this->db->where("id",$duplicate[$i]);
			$this->db->delete("transaksiproduk");
		}
	}

	// UPDATE AFILIASI
	private function cairKomisi($idtransaksi,$trx){
		$this->db->where("idtransaksi",$idtransaksi);
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
				"keterangan"=> "Pencairan komisi dari transaksi #".$trx
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
	}

	// UPDATE STOK PRODUK
	private function updateStok(){
		$db = $this->db->get("produk");
		foreach($db->result() as $r){
			$this->db->where("idproduk",$r->id);
			$dbs = $this->db->get("produkvariasi");
			if($dbs->num_rows() > 0){
				$stok = 0;
				foreach($dbs->result() as $v){
					$stok += $v->stok;
				}
				$this->db->where("id",$r->id);
				$this->db->update("produk",["stok"=>$stok]);
				echo "berhasil update produk <b>".$r->nama."</b>, STOK: <b>".$stok."</b><br/>";
			}
		}
	}
}