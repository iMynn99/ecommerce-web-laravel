<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assync extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$set = $this->func->getSetting("semua");
		$production = (strpos($set->midtrans_snap,"sandbox") == true) ? false : true;
		\Midtrans\Config::$serverKey = $set->midtrans_server;
		\Midtrans\Config::$isProduction = $production;
		\Midtrans\Config::$isSanitized = true;
		\Midtrans\Config::$is3ds = true;

		/*if($this->func->maintenis() == TRUE) {
			include(APPPATH.'views/maintenis.php');

			die();
		}*/
	}

	public function index(){
		redirect("404_notfound");
	}
	
	public function newtoken(){
		echo json_encode(["name"=>$this->security->get_csrf_token_name(),"token"=>$this->security->get_csrf_hash()]);
	}
	public function bayarulangpesanan(){
		if(isset($_POST["bayar"])){
			$bayar = $this->func->getBayar($_POST["bayar"],"semua");
			$orderId = $bayar->midtrans_id;
			$status = \Midtrans\Transaction::cancel($orderId);
			
			if($status){
				echo json_encode(["success"=>true]);
			}else{
				echo json_encode(["success"=>false]);
			}
		}else{
			echo json_encode(["success"=>false]);
		}
	}

	public function newprod(){ //HOME > PRODUK TERBARU
		$limit = (isset($_GET["display"])) ? intval($_GET["display"]) : 10;
		$this->db->select(
			'*,
			produk.deskripsi as deskripsi,
			toko.nama as namatoko,
			toko.id as idtoko,
			produk.id as id,
			produk.url as url,
			produk.nama as nama'
		);
		$this->db->from('produk');
		$this->db->join('toko', 'toko.id = produk.idtoko');
		$this->db->limit($limit);
		$this->db->order_by("produk.id","desc");
		$query = $this->db->get();

		foreach($query->result() as $res){
			$kota = explode(" ",$this->func->getKab($res->idkab,"nama"),2);
			$kota = $kota[1];
			$judul = $this->func->potong($res->nama,35,'...');
			$namatoko = $this->func->potong(strtoupper(strtolower($res->namatoko)),20,'...');
			$label = $this->func->getLabelCOD($res->cod);

			echo "
				<div class='kotak'>
					<a href='".site_url('produk/'.$res->url)."' class='kotak-atas'>
						<div class='kotak-img' style='background-image:url(\"".$this->func->getFoto($res->id,'utama')."\")'>
							<!--<img src='".$this->func->getFoto($res->id,'utama')."' />-->
						</div>
						<div class='kotak-detail'>
							<div class='kotak-judul'>".$judul."</div>
							<p class='kotak-harga'> Rp. ".$this->func->formUang($res->harga)."</p>
							".$label."
						</div>
					</a>
					<div class='kotak-toko'>
						<span class='kotak-namatoko'>".$namatoko."</span><br/>
						<span class='kotak-alamat'><i class='fa fa-map-marker'></i> ".$kota."</span>
					</div>
				</div>
			";
		}
	}

	public function pilihanprod(){ // HOME > PRODUK PILIHAN
		$limit = (isset($_GET["display"])) ? intval($_GET["display"]) : 10;
		$this->db->select(
			'*,
			produk.deskripsi as deskripsi,
			toko.nama as namatoko,
			toko.id as idtoko,
			produk.id as id,
			produk.url as url,
			produk.nama as nama'
		);
		$this->db->from('produk');
		$this->db->join('toko', 'toko.id = produk.idtoko');
		$this->db->limit($limit);
		$this->db->order_by('rand()');
		$query = $this->db->get();

		foreach($query->result() as $res){
			$kota = explode(" ",$this->func->getKab($res->idkab,"nama"),2);
			$kota = $kota[1];
			$judul = $this->func->potong($res->nama,35,'...');
			$namatoko = $this->func->potong(strtoupper(strtolower($res->namatoko)),20,'...');
			$label = $this->func->getLabelCOD($res->cod);

			echo "
				<div class='kotak'>
					<a href='".site_url('produk/'.$res->url)."' class='kotak-atas'>
						<div class='kotak-img' style='background-image:url(\"".$this->func->getFoto($res->id,'utama')."\")'>
							<!--<img src='".$this->func->getFoto($res->id,'utama')."' />-->
						</div>
						<div class='kotak-detail'>
							<div class='kotak-judul'>".$judul."</div>
							<p class='kotak-harga'> Rp. ".$this->func->formUang($res->harga)."</p>
							".$label."
						</div>
					</a>
					<div class='kotak-toko'>
						<span class='kotak-namatoko'>".$namatoko."</span><br/>
						<span class='kotak-alamat'><i class='fa fa-map-marker'></i> ".$kota."</span>
					</div>
				</div>
			";
		}
	}

	function slidepromo(){
		if(isset($_GET["token"])){
			$this->db->where("status",1);
			$this->db->where("(tgl_selesai >= '".date("Y-m-d H:i:s")."' AND tgl <= '".date("Y-m-d H:i:s")."')");
			$this->db->order_by("id","desc");
			$db = $this->db->get("promo");

			if($db->num_rows() > 0){
				echo "
						<ul id='slider' class='slider'>
					";

				foreach($db->result() as $res){
					echo "<li>
							<a href=\"#slide1\"><img class='full' src='".base_url("assets/img/promo/".$res->gambar)."' /></a>
							</li>";
				}

				echo "
						</ul>
					";

				echo "
					<script type='text/javascript'>
						$(function(){
							$('#slider').slippry()
						});
					</script>
				";
			}else{
				//echo "<img class='full' src='".base_url("assets/img/promo/default.jpg")."' />";
			}
		}else{
			redirect("404_notfound");
		}
	}

	function slidegambar(){
		if(isset($_GET["token"])){
			$id = $_GET["token"];
			$this->db->where("idproduk",$id);
			$this->db->order_by("jenis","desc");
			$db = $this->db->get("upload");

			if($db->num_rows() > 0){
				echo "
						<ul id='slider' class='slider'>
					";

				foreach($db->result() as $res){
					echo "<li>
							<a href=\"#slide1\"><img class='full' src='".base_url("assets/img/produk/".$res->nama)."' /></a>
							</li>";
				}

				echo "
						</ul>
					";

				echo "
					<script type='text/javascript'>
						$(function(){
							$('#slider').slippry()
						});
					</script>
				";
			}else{
				echo "<img class='full' src='".base_url("assets/img/produk/default.jpg")."' />";
			}
		}else{
			redirect("404_notfound");
		}
	}

	// NOTIF CHAT
	public function notifchat(){
		$usrid = (isset($_SESSION["usrid"])) ? $_SESSION["usrid"] : 0;
		$result = ["notif"=>0,"token"=> $this->security->get_csrf_hash()];
		if($usrid > 0){
			$this->db->select("id");
			$this->db->where("tujuan",$usrid);
			$this->db->where("baca",0);
			$db = $this->db->get("pesan");
			$result = ["notif"=>$db->num_rows(),"token"=> $this->security->get_csrf_hash()];
		}
		echo json_encode($result);
	}

	// BOOSTER
	public function booster(){
		$sebelum = (isset($_SESSION["boostr"])) ? $_SESSION["boostr"] : $this->session->set_userdata("boostr",1);
		$data = ["success"=>false,"token"=> $this->security->get_csrf_hash()];

		$this->db->order_by("RAND()");
		$this->db->limit(1);
		if($sebelum == 1){
			$db = $this->db->get("transaksiproduk");
			foreach($db->result() as $r){
				$nama = $this->func->getProduk($r->idproduk,"nama");
				if($nama != null){
					$data = array(
						"success"=> true,
						"foto"	=> $this->func->getFoto($r->idproduk),
						"user"	=> $this->func->clean($this->func->getProfil($r->usrid,"nama","usrid")),
						"produk"=> $this->func->clean($nama),
						"token"	=> $this->security->get_csrf_hash()
					);
				}
			}
			$_SESSION["boostr"] == 2;
		}else{
			$db = $this->db->get("booster");
			foreach($db->result() as $r){
				$nama = $this->func->getProduk($r->idproduk,"nama");
				if($nama != null){
					$data = array(
						"success"=> true,
						"foto"	=> $this->func->getFoto($r->idproduk),
						"user"	=> $this->func->getProfil($r->usrid,"nama","usrid"),
						"produk"=> $this->func->getProduk($r->idproduk,"nama"),
						"token"	=> $this->security->get_csrf_hash()
					);
				}
			}
			$_SESSION["boostr"] == 1;
		}
		echo json_encode($data);
	}
	
	// CEK VOUCHER
	public function cekvoucher(){
		if(isset($_POST["kode"])){
			$voc = $this->func->getVoucher($_POST["kode"],"semua","kode");
			
			if(is_object($voc)){
				$this->db->select("id");
				$this->db->where("voucher",$voc->id);
				$this->db->where("usrid",$_SESSION["usrid"]);
				$this->db->where("status <",2);
				$sudah = $this->db->get("pembayaran");

				$tgla = $this->func->ubahTgl("Ymd",$voc->mulai);
				$tglb = $this->func->ubahTgl("Ymd",$voc->selesai);
				if($tgla <= date("Ymd") AND $tglb >= date("Ymd") AND $sudah->num_rows() < $voc->peruser){
					$harga = intval($_POST["harga"]);
					$ongkir = intval($_POST["ongkir"]);
					if($voc->jenis == 1){
						if($voc->tipe == 2){
							$diskon = ($harga >= $voc->potonganmin) ? $voc->potongan : 0;
						}else{
							$diskon = ($harga >= $voc->potonganmin) ? $harga * ($voc->potongan/100) : 0;
						}
						$diskonmax = $diskon;
						$diskon = ($harga >= $diskon) ? $diskon : $harga;
					}elseif($voc->jenis == 2){
						if($voc->tipe == 2){
							$diskon = ($harga >= $voc->potonganmin) ? $voc->potongan : 0;
						}else{
							$diskon = ($harga >= $voc->potonganmin) ? $ongkir * ($voc->potongan/100) : 0;
						}
						$diskonmax = $diskon;
						$diskon = ($ongkir >= $diskon) ? $diskon : $ongkir;
					}else{
						$diskon = 0;
						$diskonmax = 0;
					}
					if($voc->potonganmaks != 0){
						$diskon = ($diskon < $voc->potonganmaks) ? $diskon : $voc->potonganmaks;
					}
					echo json_encode(["success"=>true,"diskon"=>$diskon,"diskonmax"=>$diskonmax,"token"=>$this->security->get_csrf_hash()]);
				}else{
					echo json_encode(["success"=>false,"token"=>$this->security->get_csrf_hash()]);
				}
			}else{
				echo json_encode(["success"=>false,"token"=>$this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false,"token"=>$this->security->get_csrf_hash()]);
		}
	}

	// MANAJEMEN TOKO
	public function daftarProduk($idtoko=0,$onboard="none"){
		$idtoko = ($idtoko!=0) ? $idtoko : $_SESSION["idtoko"];
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;

		$this->db->where("idtoko",$idtoko);
		$rows = $this->db->get("produk");
		$rows = $rows->num_rows();

		$this->db->from('produk');
		$this->db->where("idtoko",$idtoko);
		$this->db->order_by($orderby,"desc");
		$this->db->limit($perpage,($page-1)*$perpage);
		$injek["produk"] = $this->db->get();
		$injek["rows"] = $rows;
		$injek["perpage"] = $perpage;
		$injek["page"] = $page;
		$injek["onb"] = $onboard;
		$injek["kotak4"] = $onboard == "onboard" ? " kotak4" : "";

		/*
		print_r($injek["produk"]);
		*/

		//$this->load->view("head");
		$this->load->view("admin/daftarprodukassync",$injek);
		//$this->load->view("foot");
	}
	public function penjualan(){
		if(isset($_GET["status"])){
			$injek["page"] = (isset($_GET["page"])) ? $_GET["page"] : 1;
			if($_GET["status"] == "konfirm"){
				$this->load->view("admin/penjualankonfirm",$injek);
			}elseif($_GET["status"] == "dikemas"){
				$this->load->view("admin/penjualandikemas",$injek);
			}elseif($_GET["status"] == "dikirim"){
				$this->load->view("admin/penjualandikirim",$injek);
			}elseif($_GET["status"] == "selesai"){
				$this->load->view("admin/penjualanselesai",$injek);
			}elseif($_GET["status"] == "batal"){
				$this->load->view("admin/penjualanbatal",$injek);
			}else{
				redirect("404_notfound");
			}
		}else{
			redirect("404_notfound");
		}
	}
	public function accPenjualan(){
		if(isset($_GET["p"])){
			if($_GET["p"] == "terima"){
				if(isset($_POST["data"]) AND $_POST["data"] > 0){
					$this->db->where("id",$_POST["data"]);
					$this->db->update("transaksi",array("status"=>2,"tglupdate"=>date("Y-m-d H:i:s"),"kadaluarsa"=>date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s"). ' + 2 days'))));
					echo json_encode(array("success"=>true));
				}else{
					echo json_encode(array("success"=>false,"msg"=>"tidak ada produk yang dipilih,
					apabila kendala ini terjadi berulang silahkan hubungi customer service kami"));
				}
			}elseif($_GET["p"] == "tolak"){
				if(isset($_POST["data"]) AND $_POST["data"] > 0){
					$this->db->where("id",$_POST["data"]);
					$this->db->update("transaksi",array("status"=>4,"tglupdate"=>date("Y-m-d H:i:s"),"keterangan"=>"stok habis","selesai"=>date("Y-m-d H:i:s")));

					$trx = $this->func->getTransaksi($_POST["data"],"semua");

					// TOTAL SALDO
					$total = 0;
					$this->db->where("idtransaksi",$_POST["data"]);
					$dbs = $this->db->get("transaksiproduk");
					foreach($dbs->result() as $res){
						$total += $res->jumlah * $res->harga;
					}
					$saldojml = $total + $trx->ongkir;
					$usrid = $trx->usrid;
					$saldoawal = $this->func->getSaldo($usrid,"saldo","usrid");

					// UPDATE SALDO
					$saldo = $saldoawal + $saldojml;
					$this->db->where("usrid",$usrid);
					$this->db->update("saldo",array("saldo"=>$saldo));
					// SALDO DARI KE
					$data = array(
						"tgl"	=> date("Y-m-d H:i:s"),
						"usrid"	=> $usrid,
						"jenis"	=> 1,
						"jumlah"	=> $saldojml,
						"darike"	=> 4,
						"saldoawal"	=> $saldoawal,
						"saldoakhir"	=> $saldo,
						"sambung"	=> $_POST["data"]
					);
					$this->db->insert("saldohistory",$data);

					echo json_encode(array("success"=>true));
				}else{
					echo json_encode(array("success"=>false,"msg"=>"tidak ada produk yang dipilih,
					apabila kendala ini terjadi berulang silahkan hubungi customer service kami"));
				}
			}else{
				redirect("404_notfound");
			}
		}else{
			redirect("404_notfound");
		}
	}
	public function accPembatalan(){
		if(isset($_GET["p"])){
			if($_GET["p"] == "tolak"){
				if(isset($_POST["data"]) AND $_POST["data"] > 0){
					$this->db->where("id",$_POST["data"]);
					$this->db->update("transaksi",array("ajukanbatal"=>2));
					echo json_encode(array("success"=>true));
				}else{
					echo json_encode(array("success"=>false,"msg"=>"tidak ada produk yang dipilih,
					apabila kendala ini terjadi berulang silahkan hubungi customer service kami"));
				}
			}elseif($_GET["p"] == "terima"){
				if(isset($_POST["data"]) AND $_POST["data"] > 0){
					$this->db->where("id",$_POST["data"]);
					$this->db->update("transaksi",array("status"=>4,"tglupdate"=>date("Y-m-d H:i:s"),"keterangan"=>"dibatalkan oleh pembeli","selesai"=>date("Y-m-d H:i:s")));

					$trx = $this->func->getTransaksi($_POST["data"],"semua");

					// TOTAL SALDO
					$total = 0;
					$this->db->where("idtransaksi",$_POST["data"]);
					$dbs = $this->db->get("transaksiproduk");
					foreach($dbs->result() as $res){
						$total += $res->jumlah * $res->harga;
					}
					$saldojml = $total + $trx->ongkir;
					$usrid = $trx->usrid;
					$saldoawal = $this->func->getSaldo($usrid,"saldo","usrid");

					// UPDATE SALDO
					$saldo = $saldoawal + $saldojml;
					$this->db->where("usrid",$usrid);
					$this->db->update("saldo",array("saldo"=>$saldo));
					// SALDO DARI KE
					$data = array(
						"tgl"	=> date("Y-m-d H:i:s"),
						"usrid"	=> $usrid,
						"jenis"	=> 1,
						"jumlah"	=> $saldojml,
						"darike"	=> 4,
						"saldoawal"	=> $saldoawal,
						"saldoakhir"	=> $saldo,
						"sambung"	=> $_POST["data"]
					);
					$this->db->insert("saldohistory",$data);

					// UPDATE AFILIASI
					$this->db->where("idtransaksi",$trx->id);
					$this->db->update("afiliasi",["status"=>3]);

					echo json_encode(array("success"=>true));
				}else{
					echo json_encode(array("success"=>false,"msg"=>"tidak ada produk yang dipilih,
					apabila kendala ini terjadi berulang silahkan hubungi customer service kami"));
				}
			}else{
				redirect("404_notfound");
			}
		}else{
			redirect("404_notfound");
		}
	}

	public function upload(){
		if($_POST){

		}else{
			redirect("404_notfound");
		}
	}

	// MANAJEMEN SALDO
	function getHistoryTarik(){
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;

		$this->db->where("usrid",$_SESSION["usrid"]);
		$rows = $this->db->get("saldohistory");
		$rows = $rows->num_rows();

		$this->db->from('saldohistory');
		$this->db->where("usrid",$_SESSION["usrid"]);
		$this->db->order_by($orderby,"desc");
		$this->db->limit($perpage,($page-1)*$perpage);
		$injek["saldo"] = $this->db->get();
		$injek["rows"] = $rows;
		$injek["perpage"] = $perpage;
		$injek["page"] = $page;

		/*
		print_r($injek["produk"]);
		*/

		//$this->load->view("head");
		$this->load->view("admin/historysaldo",$injek);
	}
	function getHistoryTopup(){
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;

		$this->db->where("usrid",$_SESSION["usrid"]);
		$this->db->where("jenis",2);
		$rows = $this->db->get("saldotarik");
		$rows = $rows->num_rows();

		$this->db->from('saldotarik');
		$this->db->where("usrid",$_SESSION["usrid"]);
		$this->db->where("jenis",2);
		$this->db->order_by($orderby,"desc");
		$this->db->limit($perpage,($page-1)*$perpage);
		$injek["saldo"] = $this->db->get();
		$injek["rows"] = $rows;
		$injek["perpage"] = $perpage;
		$injek["page"] = $page;

		/*
		print_r($injek["produk"]);
		*/

		//$this->load->view("head");
		$this->load->view("admin/topupsaldo",$injek);
	}

	// BELI PRODUK
	function beliproduk(){
		if(isset($_POST["token"])){
			if(isset($_SESSION["usrid"])){
				$id = $this->func->decode($_POST["token"]);
				$push["id"] = $id;
				$this->db->where("id",$id);
				$db = $this->db->get("produk");
				$idtoko = 0;
				foreach($db->result() as $res){
					$push["nama"] = $res->nama;
					$push["berat"] = $res->berat;
					$push["harga"] = $res->harga;
					$push["idtoko"] = $res->idtoko;
					$idtoko = $res->idtoko;
				}

				/*$this->db->where("usrid",$_SESSION["usrid"]);
				$alamat = $this->db->get("alamat");
				$push["alamat"] = $alamat;
				$this->db->order_by("nama","asc");
				$push["provinsi"] = $this->db->get("prov");*/
				$idkab = $this->func->getToko($idtoko,"idkab");
				$push["kotatoko"] = $this->func->getKab($idkab,"rajaongkir");
			}else{
				$push = array();
			}

			$this->load->view("main/beli",$push);
		}else{
			redirect("404_notfound");
		}
	}
	function prosesbeli(){
		if(!isset($_SESSION["usrid"]) AND !isset($_SESSION["usrid_temp"])){
			$upline = (isset($_SESSION["aff"])) ? $_SESSION["aff"] : 0;
			$this->db->insert("usertemp",["tgl"=>date("Y-m-d H:i:s"),"upline"=>$upline]);
			$usrid = $this->db->insert_id();
			$this->session->set_userdata("usrid_temp",$usrid);
		}
		if($_POST AND (isset($_SESSION["usrid"]) || isset($_SESSION["usrid_temp"]))){
			$usrid = (isset($_SESSION["usrid"])) ? $_SESSION["usrid"] : $_SESSION["usrid_temp"];
			$jenis = (isset($_SESSION["usrid"])) ? 1 : 2;

			$prod = $this->func->getProduk($_POST["idproduk"],"semua");
			$level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : 0;
			$keterangan = (isset($_POST["keterangan"])) ? $_POST["keterangan"] : "";
			$variasi = (isset($_POST["variasi"])) ? $_POST["variasi"] : 0;
			$var = $this->func->getVariasi($variasi,"semua");
			$update = false;
			$id = 0;
			$harga = $_POST["harga"];

			// CEK KERANJANG
			$this->db->where("idproduk",$prod->id);
			$this->db->where("variasi",$variasi);
			$this->db->where("idtransaksi",0);
			if($jenis == 1){
				$this->db->where("usrid",$_SESSION["usrid"]);
			}else{
				$this->db->where("usrid_temp",$_SESSION["usrid_temp"]);
			}
			$db = $this->db->get("transaksiproduk");

			if($var->id > 0){
				//$harga = $var->harga > 0 ? $var->harga : $harga;
				if($level == 5){
					$harga = $var->hargadistri;
				}elseif($level == 4){
					$harga = $var->hargaagensp;
				}elseif($level == 3){
					$harga = $var->hargaagen;
				}elseif($level == 2){
					$harga = $var->hargareseller;
				}else{
					$harga = $var->harga;
				}

				if(intval($_POST["jumlah"]) > $var->stok){
					echo json_encode(array("success"=>false,"msg"=>"Stok tidak mencukupi, stok tersedia hanya ".$var->stok." pcs","token"=> $this->security->get_csrf_hash()));
					exit;
				}

				foreach($db->result() as $r){
					$jumlah = intval($_POST["jumlah"]) + $r->jumlah;
					if($jumlah > $var->stok){
						echo json_encode(array("success"=>false,"msg"=>"Stok tidak mencukupi, stok tersedia hanya ".$var->stok." pcs<br/>di keranjang belanja Anda sudah ada produk yang sama, setelah dijumlahkan melebihi stok yg tersedia saat ini","token"=> $this->security->get_csrf_hash()));
						exit;
					}else{
						$update = true;
						$id = $r->id;
					}
				}
			}else{
				if(intval($_POST["jumlah"]) > $prod->stok){
					echo json_encode(array("success"=>false,"msg"=>"Stok tidak mencukupi, stok tersedia hanya ".$prod->stok." pcs","token"=> $this->security->get_csrf_hash()));
					exit;
				}
				
				if($level == 5){
					$harga = $prod->hargadistri;
				}elseif($level == 4){
					$harga = $prod->hargaagensp;
				}elseif($level == 3){
					$harga = $prod->hargaagen;
				}elseif($level == 2){
					$harga = $prod->hargareseller;
				}else{
					$harga = $prod->harga;
				}

				foreach($db->result() as $r){
					$jumlah = intval($_POST["jumlah"]) + $r->jumlah;
					if($jumlah > $prod->stok){
						echo json_encode(array("success"=>false,"msg"=>"Stok tidak mencukupi, stok tersedia hanya ".$prod->stok." pcs<br/>di keranjang belanja Anda sudah ada produk yang sama, setelah dijumlahkan melebihi stok yg tersedia saat ini","token"=> $this->security->get_csrf_hash()));
						exit;
					}else{
						$update = true;
						$id = $r->id;
					}
				}
			}

			$total = intval($_POST["jumlah"]) * $harga;
			if($update == false){
				$data = array(
					"digital"	=> $prod->digital,
					"gudang"	=> $prod->gudang,
					"idproduk"	=> $_POST["idproduk"],
					"tgl"		=> date("Y-m-d H:i:s"),
					"jumlah"	=> $_POST["jumlah"],
					"harga"		=> $harga,
					"keterangan"=> $keterangan,
					"variasi"	=> $variasi,
					"idtransaksi"	=> 0
				);
				if($jenis == 1){
					$data["usrid"] = $usrid;
				}else{
					$data["usrid_temp"] = $_SESSION["usrid_temp"];
				}
				$this->db->insert("transaksiproduk",$data);
				$id = $this->db->insert_id();
			}else{
				$this->db->where("id",$id);
				$this->db->update("transaksiproduk",["jumlah"=>$jumlah,"harga"=>$_POST["harga"],"tgl"=>date("Y-m-d H:i:s"),"keterangan"=> $keterangan."\n".$r->keterangan]);
			}
			
			echo json_encode(array("success"=>true,"produk"=>$id,"total"=>$total,"token"=>$this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
		}
	}
	function hapuskeranjang(){
		if(isset($_POST["hapus"]) AND $_POST["hapus"] > 0){
			$id = $_POST["hapus"];

			$this->db->where("id",$id);
			$this->db->delete("transaksiproduk");

			echo json_encode(array("success"=>true));
		}else{
			echo json_encode(array("success"=>false));
		}
	}
	function updatekeranjang(){
		if(isset($_POST["update"]) AND $_POST["update"] > 0){
			$id = $_POST["update"];
			unset($_POST["update"]);

			$trx = $this->func->getTransaksiProduk($id,"semua");
			$stok = ($trx->variasi > 0) ? $this->func->getVariasi($trx->variasi,"stok") : $this->func->getProduk($trx->idproduk,"stok");
			if($stok >= intval($_POST["jumlah"])){
				$this->db->where("id",$id);
				$this->db->update("transaksiproduk",$_POST);

				echo json_encode(array("success"=>true,"token"=>$this->security->get_csrf_hash()));
			}else{
				$this->db->where("id",$id);
				$this->db->update("transaksiproduk",["jumlah"=>$stok]);

				echo json_encode(array("success"=>false,"msg"=>"Stok produk tidak mencukupi, maksimal pemesanan ".$stok."pcs","token"=>$this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Produk tidak tersedia","token"=>$this->security->get_csrf_hash()));
		}
	}
	function updatepesanan(){
		if(isset($_POST["id"]) AND isset($_POST["metode"])){
			$status = $_POST["metode"] == 1 ? 1 : 0;
			$status = isset($_POST["status"]) ? $_POST["status"] : $status;
			//$trx = $this->func->getTransaksi(intval($_POST["id"]),"semua","idbayar");

			$data = ["status"=>$status,"tglupdate"=>date("Y-m-d H:i:s")];
			$datas = ["status"=>$status,"metode_bayar"=>$_POST["metode"],"tglupdate"=>date("Y-m-d H:i:s")];
			if($status == 1 AND $_POST["metode"] == 1){
				$data["cod"] = 1;
				$data["biaya_cod"] = $_POST["biaya"];
				$datas["biaya_cod"] = $_POST["biaya"];
			}
			
			//$this->func->notifsukses($_POST["id"]);
			$this->db->where("id",intval($_POST["id"]));
			$this->db->update("pembayaran",$datas);
			
			$this->db->where("idbayar",intval($_POST["id"]));
			$this->db->update("transaksi",$data);
			
			echo json_encode(array("success"=>true,"token"=>$this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
		}
	}
	function bayaripaymu($order_id){
	}

	// WISHLIST
	function tambahwishlist($idproduk){
		if(isset($_SESSION["usrid"])){
			$this->db->where("idproduk",$idproduk);
			$this->db->where("usrid",$_SESSION["usrid"]);
			$row = $this->db->get("wishlist");

			if($row->num_rows() == 0){
				$data = array(
					"usrid"	=> $_SESSION["usrid"],
					"idproduk"	=> $idproduk,
					"tgl"	=> date("Y-m-d H:i:s"),
					"status"=> 1
				);
				$this->db->insert("wishlist",$data);

				echo json_encode(array("success"=>true,"msg"=>"berhasil ditambahkan ke wishlist","token"=> $this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"produk sudah ada dalam wishlist","token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"silahkan login terlebih dahulu untuk menyimpan produk kedalam wishlist","token"=> $this->security->get_csrf_hash()));
		}
	}
	function hapuswishlist(){
		if(isset($_POST["id"])){
			$idproduk = $_POST["id"];
			$this->db->where("idproduk",$idproduk);
			$this->db->where("usrid",$_SESSION["usrid"]);
			$this->db->delete("wishlist");

			echo json_encode(array("success"=>true,"msg"=>"berhasil dihapus dari wishlist","token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"gagal menghapus produk dari wishlist","token"=> $this->security->get_csrf_hash()));
		}
	}

	// TOPUP SALDO
	function topupsaldo(){
		if(isset($_POST)){
			$idbayar = $_SESSION["usrid"].date("YmdHis");
			$data = array(
				"status"=> 0,
				"jenis"	=> 2,
				"usrid"	=> $_SESSION["usrid"],
				"total"	=> $_POST["jumlah"],
				"tgl"	=> date("Y-m-d H:i:s"),
				"trxid"	=> $idbayar
			);
			$this->db->insert("saldotarik",$data);

			$idbayar = $this->func->arrEnc(array("trxid"=>$idbayar),"encode");
			echo json_encode(array("success"=>true,"idbayar"=>$idbayar,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"message"=>"forbidden","token"=> $this->security->get_csrf_hash()));
		}
	}
	function bataltopup(){
		if(isset($_POST["id"])){
			$this->db->where("id",$_POST["id"]);
			$this->db->update("saldotarik",["selesai"=>date("Y-m-d H:i:s"),"status"=>2]);

			echo json_encode(array("success"=>true));
		}else{
			echo json_encode(array("success"=>false,"message"=>"forbidden"));
		}
	}
	function topupipaymu($order_id){
		$set = $this->func->getSetting("semua");
		$bayar = $this->func->getSaldotarik($order_id,"semua");
		$total = $bayar->total;

		if((!empty($bayar) AND $bayar->ipaymu_link == "") OR (!empty($bayar) AND $bayar->ipaymu_link != "" AND $bayar->ipaymu_tipe != "")){
			$url = 'https://my.ipaymu.com/payment';
			$url = $set->ipaymu_url != "" ? $set->ipaymu_url : $url;
			$set = $this->func->getSetting("semua");
			$mobile = (isset($_GET["mobile"])) ? "&mobile=true" : "";
			$profil = $this->func->getProfil($bayar->usrid,"semua","usrid");
			$params = array(
				'key'      => $set->ipaymu, // API Key Merchant / Penjual
				'action'   => 'payment',
				'product'  => 'Order : #' . $bayar->trxid,
				'price'    => $total, // Total Harga
				'quantity' => 1,
				'reference_id' => $order_id,
				'comments' => 'Pembayaran topup saldo di '.$set->nama, // Optional           
				'ureturn'  => site_url("home/ipaymustatustopup").'?id_order='.$bayar->trxid.$mobile,
				'unotify'  => site_url("home/ipaymustatustopup").'?id_order='.$bayar->trxid.'&params=notify',
				'ucancel'  => site_url("home/ipaymustatustopup").'?id_order='.$bayar->trxid.'&params=cancel'.$mobile,
				'buyer_name' => $profil->nama,
				'buyer_phone' => $profil->nohp,
				'buyer_email' => $this->func->getUser($bayar->usrid,"username"),
				'format'   => 'json' // Format: xml / json. Default: xml 
			);
			$params_string = http_build_query($params);

			//open connection
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, count($params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			//execute post
			$request = curl_exec($ch);

			if ( $request === false ) {
				echo curl_error($ch);
			} else {
				
				$result = json_decode($request, true);
				if( isset($result['url']) ){
					$this->db->where("id",$order_id);
					$this->db->update("saldotarik",["ipaymu"=>$result['sessionID'],"ipaymu_link"=>$result['url']]);
					
					redirect($result['url'], 'refresh');
				} else {
					redirect("home/topupsaldo?inv=".$this->func->arrEnc(array("trxid"=>$bayar->trxid),"encode"));
					//$results = false;
					//echo "Request Error ". $result['Status'] .": ". $result['Keterangan'];
				}
			}

			//close connection
			curl_close($ch);
		}else if(!empty($bayar) AND $bayar->ipaymu_link != "" AND $bayar->ipaymu_tipe == ""){
			redirect($bayar->ipaymu_link, 'refresh');
			//echo "link lama";
		}else{
			redirect("home/topupsaldo?inv=".$this->func->arrEnc(array("trxid"=>$bayar->trxid),"encode"));
		}
	}
	
	// PRE ORDER
	function prosespreorder(){
		if(isset($_POST["idproduk"])){
			$total = (intval($_POST["harga"])*0.5)*intval($_POST["jumlah"]);
			$data = array(
				"tgl"	=> date("Y-m-d H:i:s"),
				"usrid"	=> $_SESSION["usrid"],
				"idproduk"	=> $_POST["idproduk"],
				"jumlah"	=> $_POST["jumlah"],
				"variasi"	=> $_POST["variasi"],
				"harga"	=> $_POST["harga"],
				"total"	=> $total,
				"transfer"	=> (intval($_POST["harga"])*0.5)*intval($_POST["jumlah"]),
				"kodebayar"	=> rand(100,999),
				"status"	=> 0,
				"invoice"	=> "PO".date("YmdHis")
			);
			$this->db->insert("preorder",$data);
			$idbayar = $this->db->insert_id();
				
			// UPDATE STOK PRODUK
			$this->db->where("id",$idbayar);
			$db = $this->db->get("preorder");
			foreach($db->result() as $r){
				if($r->variasi != 0){
					$var = $this->func->getVariasi($r->variasi,"semua","id");
					if($r->jumlah > $var->stok){
						echo json_encode(array("success"=>false,"message"=>"stok produk tidak mencukupi"));
						$stok = 0;
						exit;
					}else{
						$stok = $var->stok - $r->jumlah;
					}
					$variasi[] = $r->variasi;
					$stock[] = $stok;
					$stokawal[] = $var->stok;
					$jml[] = $r->jumlah;
					
					for($i=0; $i<count($variasi); $i++){
						$this->db->where("id",$variasi[$i]);
						$this->db->update("produkvariasi",["stok"=>$stock[$i],"tgl"=>date("Y-m-d H:i:s")]);
						
						$data = array(
							"usrid"	=> $_SESSION["usrid"],
							"stokawal" => $stokawal[$i],
							"stokakhir" => $stock[$i],
							"variasi" => $variasi[$i],
							"jumlah" => $jml[$i],
							"tgl"	=> date("Y-m-d H:i:s"),
							"idtransaksi" => $idbayar
						);
						$this->db->insert("historystok",$data);
					}
				}
			}

			$idbayar = $this->func->arrEnc(array("idbayar"=>$idbayar),"encode");
			echo json_encode(array("success"=>true,"inv"=>$idbayar,"total"=>$total));
		}else{
			echo json_encode(array("success"=>false,"message"=>"forbidden"));
		}
	}

	// REKENING
	public function getrekeningdrop($id=1,$drop="all"){
		if($id != null AND $id == $_SESSION["usrid"]){
			$this->db->where("usrid",$id);
			$db = $this->db->get("rekening");
			$asal = $id > 0 ? "asal" : "tujuan";
			$asal = $drop != "all" ? $drop : $asal;

			echo "<option value=''>Rekening ".$asal."</option>";
			foreach($db->result() as $res){
				echo "<option value='".$res->id."'>BANK ".$this->func->getBank($res->idbank,"nama")." - ".$res->norek." a/n ".$res->atasnama."</option>";
			}
			echo "<option value='tambah'>+ Tambah Baru</option>";
		}else{
			echo "<option value=''>ERROR</option>";
		}
	}
	public function tambahrekening(){
		if(isset($_POST["idbank"])){
			$data = array(
				"usrid"		=> $_SESSION["usrid"],
				"idbank"	=> $_POST["idbank"],
				"atasnama"	=> $_POST["atasnama"],
				"norek"		=> $_POST["norek"],
				"kcp"		=> $_POST["kcp"],
				"tgl"		=> date("Y-m-d H:i:s")
			);

			if(isset($_POST["id"]) AND $_POST["id"] > 0){
				$this->db->where("id",$_POST["id"]);
				$this->db->update("rekening",$data);
			}else{
				$this->db->insert("rekening",$data);
			}

			echo json_encode(array("success"=>true,"id"=>$this->db->insert_id(),"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}
	public function getrekening(){
		if(isset($_POST["rek"])){
			$rek = $this->func->getRekening($_POST["rek"],"semua");
			if(count((array)$rek) > 0 AND $rek->usrid == $_SESSION["usrid"]){
				echo json_encode(array("success"=>true,"id"=>$rek->id,"idbank"=>$rek->idbank,"norek"=>$rek->norek,"atasnama"=>$rek->atasnama,"kcp"=>$rek->kcp,"token"=> $this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}
	public function hapusRekening(){
		if(isset($_POST["rek"])){
			$this->db->where("id",$_POST["rek"]);
			$this->db->delete("rekening");

			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}

	// ALAMAT
	public function tambahalamat(){
		if(isset($_POST["idkec"])){
			if($_POST["status"] == 1){
				$this->db->where("usrid",$_SESSION["usrid"]);
				$this->db->update("alamat",array("status"=>0));
			}

			$data = array(
				"usrid"		=> $_SESSION["usrid"],
				"idkec"	=> $_POST["idkec"],
				"nama"	=> $_POST["nama"],
				"judul"		=> $_POST["judul"],
				"alamat"	=> $_POST["alamat"],
				"kodepos"	=> $_POST["kodepos"],
				"nohp"	=> $_POST["nohp"],
				"status" => $_POST["status"]
			);

			if(isset($_POST["id"]) AND $_POST["id"] > 0){
				$this->db->where("id",$_POST["id"]);
				$this->db->update("alamat",$data);
			}else{
				$this->db->insert("alamat",$data);
			}

			echo json_encode(array("success"=>true,"id"=>$this->db->insert_id(),"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}
	public function getalamat(){
		if(isset($_POST["rek"])){
			$rek = $this->func->getAlamat($_POST["rek"],"semua");
			if(count((array)$rek) > 0 AND $rek->usrid == $_SESSION["usrid"]){
				$kab = $this->func->getKec($rek->idkec,"idkab");
				$prov = $this->func->getKab($kab,"idprov");
				$data = array(
					"success"	=> true,
					"kab"	=> $kab,
					"prov"	=> $prov,
					"idkec"	=> $rek->idkec,
					"nama"	=> $rek->nama,
					"judul"		=> $rek->judul,
					"alamat"	=> $rek->alamat,
					"kodepos"	=> $rek->kodepos,
					"nohp"	=> $rek->nohp,
					"status" => $rek->status,
					"token"=> $this->security->get_csrf_hash()
				);
				echo json_encode($data);
			}else{
				echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}
	public function hapusAlamat(){
		if(isset($_POST["rek"])){
			$this->db->where("id",$_POST["rek"]);
			$this->db->delete("alamat");

			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}

	// PESAN KOTAK MASUK
	public function pesanmasuk(){
		if(isset($_SESSION["usrid"])){
			$this->db->where("(dari = 0 AND tujuan = ".$_SESSION["usrid"].") OR (dari = ".$_SESSION["usrid"]." AND tujuan = 0)");
			$this->db->limit(100);
			$db = $this->db->get("pesan");
			
			$this->db->where("tujuan",$_SESSION["usrid"]);
			$this->db->where("baca",0);
			$this->db->update("pesan",array("baca"=>1));
							
			$currdate = false;
			if($db->num_rows() > 0){
				$noe = 1;
				foreach($db->result() as $r){
					$centang = ($r->baca == 0) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-eye'></i>";
					$centang = ($r->tujuan == 0) ? $centang : "";
					$loc = ($r->tujuan == 0) ? "right" : "left";
					$tgl = '<br/><small>'.$this->func->ubahTgl("H:i",$r->tgl).' &nbsp'.$centang.'</small>';
				
					if($this->func->ubahTgl("d-m-Y",$r->tgl) != $currdate){
						echo '<div class="pesanwrap center">
								<div class="isipesan">'.$this->func->ubahTgl("d M Y",$r->tgl).'</div>
							</div>';
						$currdate = $this->func->ubahTgl("d-m-Y",$r->tgl);
					}
						
					if($r->idproduk > 0){
						$prod = $this->func->getProduk($r->idproduk,"semua");
						$level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : 0;
						if($level == 5){
							$harga = $prod->hargadistri;
						}elseif($level == 4){
							$harga = $prod->hargaagensp;
						}elseif($level == 3){
							$harga = $prod->hargaagen;
						}elseif($level == 2){
							$harga = $prod->hargareseller;
						}else{
							$harga = $prod->harga;
						}
						echo '
							<div class="pesanwrap '.$loc.'">
								<div class="isipesan cursor-pointer" onclick="window.location.href=\''.site_url("produk/".$prod->url).'\'">
									<div class="row">
										<div class="col-3 text-left">
											<img src="'.$this->func->getFoto($r->idproduk,"utama").'" style="max-width:100%;max-height:60px;border-radius:8px;" />
										</div>
										<div class="col-9 text-left">
											<div class="font-medium" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'.$prod->nama.'</div>
											<div class="" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Rp. '.$this->func->formUang($harga).' | '.$prod->stok.' tersedia</div>
										</div>
									</div>
								</div>
							</div>
						';
					}else{
						echo '<div class="pesanwrap '.$loc.'">
								<div class="isipesan"><b>'.$this->security->xss_clean($r->isipesan).'</b>'.$tgl.'</div>
							</div>';
					}
					$noe++;
				}
			}else{
				echo '
					<div class="pesanwrap center">
						<div class="isipesan">belum ada pesan</div>
					</div>';
			}
		}else{
			echo '
				<div class="pesanwrap center">
					<div class="isipesan">belum ada pesan</div>
				</div>';
		}
	}
	public function kirimpesan(){
		if(isset($_SESSION["usrid"]) AND intval($_SESSION["usrid"]) > 0 AND isset($_POST['isipesan'])){
			$this->db->select("tgl");
			$this->db->where("dari",$_SESSION["usrid"]);
			$this->db->limit(1);
			$this->db->order_by("id","DESC");
			$db = $this->db->get("pesan");
			$kirim = true;
			foreach($db->result() as $r){
				$selisih = date("YmdHis") - $this->func->ubahTgl("YmdHis",$r->tgl);
				if($selisih <= 20000000){
					$kirim = false;
				}
			}

			$idproduk = (isset($_POST["idproduk"])) ? intval($_POST["idproduk"]) : 0;
			$data = array(
				"isipesan"	=> $_POST["isipesan"],
				"idproduk"	=> $idproduk,
				"tujuan"	=> 0,
				"baca"		=> 0,
				"dari"		=> $_SESSION["usrid"],
				"tgl"		=> date("Y-m-d H:i:s")
			);
			$this->db->insert("pesan",$data);

			if($kirim == true){
				$data = array(
					"isipesan"	=> $this->func->globalset("autoreply"),
					"idproduk"	=> 0,
					"tujuan"	=> $_SESSION["usrid"],
					"baca"		=> 0,
					"dari"		=> 0,
					"tgl"		=> date("Y-m-d H:i:s")
				);
				$this->db->insert("pesan",$data);
			}

			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}

	// DISKUSI
	public function diskusi(){
		if(isset($_GET["cat"])){
			if($_GET["cat"] == "semua"){
				$this->load->view("admin/diskusisemua");
			}elseif($_GET["cat"] == "beli"){
				$this->load->view("admin/diskusipembeli");
			}elseif($_GET["cat"] == "toko"){
				$this->load->view("admin/diskusipenjual");
			}else{
				redirect("404_notfound");
			}
		}else{
			redirect("404_notfound");
		}
	}
	public function tambahdiskusi(){
		if($_POST){
			$_POST["usrid"] = $_SESSION["usrid"];
			$_POST["tgl"] = date("Y-m-d H:i:s");

			if($this->db->insert("diskusi",$_POST)){
				echo json_encode(array("success"=>true));
			}else{
				echo json_encode(array("success"=>false));
			}
		}else{
			echo json_encode(array("success"=>false));
		}
	}

	// UPDATE PROFIL TOKO
	function updateProfiltoko(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["nama"])){
				$this->db->where("id",$_SESSION["idtoko"]);
				$this->db->update("toko",$_POST);

				echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil"));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
		}
	}
	function aktifkankurir(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["push"])){
				$toko = $this->func->getToko($_SESSION["idtoko"],"kurir");
				$kurir = explode("|",$toko);
				$kurir[] = $_POST["push"];
				$push = implode("|",$kurir);

				$this->db->where("id",$_SESSION["idtoko"]);
				$this->db->update("toko",array("kurir"=>$push,"update"=>date("Y-m-d H:i:s")));

				echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil"));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
		}
	}
	function nonaktifkankurir(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["push"])){
				$toko = $this->func->getToko($_SESSION["idtoko"],"kurir");
				$kurir = explode("|",$toko);
				for($i=0; $i<count($kurir); $i++){
					if($_POST["push"] == $kurir[$i]) {
						unset($kurir[$i]);
					}
				}
				$push = implode("|",$kurir);

				$this->db->where("id",$_SESSION["idtoko"]);
				$this->db->update("toko",array("kurir"=>$push,"update"=>date("Y-m-d H:i:s")));

				echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil"));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
		}
	}

	// ULASAN_REVIEW
	function tambahUlasan(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["nilai"])){
				for($i=0; $i<count($_POST["nilai"]); $i++){
					$trx = $this->func->getTransaksi($_POST["orderid"][$i],"semua","orderid");
					$data = array(
						"usrid"	=> $_SESSION["usrid"],
						"idproduk"	=> $_POST["produk"][$i],
						"idtransaksi"	=> $trx->id,
						"nilai"	=> $_POST["nilai"][$i],
						"tgl"	=> date("Y-m-d H:i:s"),
						"keterangan"	=> $_POST["keterangan"][$i]
					);

					$this->db->insert("review",$data);
				}

				echo json_encode(array("success"=>true,"msg"=>"Berhasil brow!"));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
		}
	}

	// MANAJEMEN USER
	function updateProfil(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["nama"])){
				$nohp = intval($_POST["nohp"]);
				$no1 = substr($nohp,0,2) != "62" ? "62".$nohp : $nohp;
				$no2 = substr($nohp,0,2) != "62" ? "0".$nohp : "0".substr($nohp,2);

				$this->db->select("id");
				$this->db->where("id != ".$_SESSION["usrid"]." AND (nohp IN('".$no1."','".$no2."') OR username = '".$_POST["email"]."')");
				$db = $this->db->get("userdata");

				if($db->num_rows() == 0){
					$this->db->where("usrid",$_SESSION["usrid"]);
					$this->db->update("profil",array("nama"=>$_POST["nama"],"nohp"=>$_POST["nohp"],"kelamin"=>$_POST["kelamin"]));

					$this->db->where("id",$_SESSION["usrid"]);
					$this->db->update("userdata",array("username"=>$_POST["email"],"nama"=>$_POST["nama"]));

					echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil","token"=> $this->security->get_csrf_hash()));
				}else{
					echo json_encode(array("success"=>false,"msg"=>"Nomer Whatsapp atau Alamat Email sudah terdaftar, silahkan menggunakan nomer lain","token"=> $this->security->get_csrf_hash()));
				}
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!","token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!","token"=> $this->security->get_csrf_hash()));
		}
	}
	function updatePass(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["password"])){
				$this->db->where("id",$_SESSION["usrid"]);
				$this->db->update("userdata",array("password"=>$this->func->encode($_POST["password"])));

				echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil","token"=> $this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!","token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!","token"=> $this->security->get_csrf_hash()));
		}
	}

	// STATUS PESANAN
	public function pesanan(){
		if(isset($_GET["status"])){
			if($_GET["status"] == "belumbayar"){
				$this->load->view("admin/pesananbayar");
			}elseif($_GET["status"] == "dikemas"){
				$this->load->view("admin/pesanandikemas");
			}elseif($_GET["status"] == "selesai"){
				$this->load->view("admin/pesananselesai");
			}elseif($_GET["status"] == "dikirim"){
				$this->load->view("admin/pesanandikirim");
			}elseif($_GET["status"] == "batal"){
				$this->load->view("admin/pesananbatal");
			}elseif($_GET["status"] == "digital"){
				$this->load->view("admin/pesanandigital");
			}else{
				redirect("404_notfound");
			}
		}else{
			redirect("404_notfound");
		}
	}
	public function inputResi(){
		if(isset($_POST["pesanan"])){
			$this->db->where("id",$_POST["pesanan"]);
			$this->db->update("transaksi",array("resi"=>$_POST["resi"],"tglupdate"=>date("Y-m-d H:i:s"),"kadaluarsa"=>date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s"). ' + 7 days')),"kirim"=>date("Y-m-d H:i:s")));

			echo json_encode(array("success"=>true,"message"=>"Success!"));
		}else{
			echo json_encode(array("success"=>false,"message"=>"Forbidden Access"));
		}
	}
	public function terimaPesanan(){
		if(isset($_POST["pesanan"])){
			$this->db->where("id",$_POST["pesanan"]);
			if($this->db->update("transaksi",array("status"=>3,"tglupdate"=>date("Y-m-d H:i:s"),"selesai"=>date("Y-m-d H:i:s")))){
				$trx = $this->func->getTransaksi($_POST["pesanan"],"semua");
					
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

				echo json_encode(array("success"=>true,"message"=>"Success!"));
			}else{
				echo json_encode(array("success"=>false,"message"=>"Forbidden Access"));
			}
		}else{
			echo json_encode(array("success"=>false,"message"=>"Forbidden Access"));
		}
	}
	public function getTerimaPesanan(){
		if(isset($_GET["fid"])){
			$this->db->where("idbayar",$_GET["fid"]);
			$db = $this->db->get("pengiriman");

			if($db->num_rows > 1){ echo "<option value=''>Pilih Pesanan</option>"; }
			foreach($db->result() as $res){
				echo "<option value='".$res->id."'>".strtoupper($res->kurir." ".$res->paket." // ".$this->func->getToko($res->idtoko,"nama"))."</option>";
			}
		}else{
			echo "<option value=''>ERROR!</option>";
		}
	}
	public function batalkanPesanan($by="user"){
		if(isset($_POST["pesanan"])){
			$data = array(
				"tglupdate"	=> date("Y-m-d H:i:s"),
				"status"	=> 3
			);
			$this->db->where("id",$_POST["pesanan"]);
			if($this->db->update("pembayaran",$data)){
				if($by == "penjual"){
					/*$user = $this->func->getUser($_SESSION["usrid"],"semua");
					if($user->idtoko == $this->func->get*/
					// PENGEMBALIAN SALDO
					$this->func->notifbatal($_POST["pesanan"],1);
					$batal = "dibatalkan oleh penjual.";
				}else{
					$this->func->notifbatal($_POST["pesanan"],2);
					$batal = "dibatalkan oleh pembeli.";
				}
				$this->db->where("idbayar",$_POST["pesanan"]);
				$this->db->update("transaksi",array("status"=>4,"tglupdate"=>date("Y-m-d H:i:s"),"selesai"=>date("Y-m-d H:i:s"),"keterangan"=>$batal));
				
				// UPDATE STOK PRODUK
				$trx = $this->func->getTransaksi($_POST["pesanan"],"semua","idbayar");
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
							"usrid"	=> $_SESSION["usrid"],
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
							"usrid"	=> $_SESSION["usrid"],
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

				// TOTAL SALDO
				$saldojml = $this->func->getBayar($_POST["pesanan"],"saldo");
				$saldoawal = $this->func->getSaldo($trx->usrid,"saldo","usrid");

				if($saldojml > 0){
					// UPDATE SALDO
					$saldo = $saldoawal + $saldojml;
					$this->db->where("usrid",$trx->usrid);
					$this->db->update("saldo",array("saldo"=>$saldo));

					// SALDO TARIK
					$tgl = date("Y-m-d H:i:s");
					$data = [
						"usrid"	=> $trx->usrid,
						"trxid"	=> "TOPUP_".$trx->usrid.date("YmdHis"),
						"jenis"	=> 2,
						"status"=> 1,
						"selesai"	=> $tgl,
						"tgl"	=> $tgl,
						"total"	=> $saldojml,
						"metode"=> 1,
						"keterangan"=> "Pengembalian dana dari pembatalan #".$trx->orderid
					];
					$this->db->insert("saldotarik",$data);
					$topup = $this->db->insert_id();
					
					// SALDO DARI KE
					$data = array(
						"tgl"	=> $tgl,
						"usrid"	=> $trx->usrid,
						"jenis"	=> 1,
						"jumlah"	=> $saldojml,
						"darike"	=> 4,
						"saldoawal"	=> $saldoawal,
						"saldoakhir"=> $saldo,
						"sambung"	=> $topup
					);
					$this->db->insert("saldohistory",$data);
				}

				echo json_encode(array("success"=>true,"message"=>"berhasil membatalkan pesanan","token"=> $this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"message"=>"gagal membatalkan pesanan, coba ulangi beberapa saat lagi","token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"message"=>"Forbidden Access","token"=> $this->security->get_csrf_hash()));
		}
	}
	public function perpanjangPesanan(){
		if(isset($_POST["pesanan"])){
			$date = $this->func->getTransaksi($_POST["pesanan"],"kadaluarsa");
			$date = date('Y-m-d H:i:s', strtotime($date. ' + 2 days'));
			$this->db->where("id",$_POST["pesanan"]);
			if($this->db->update("transaksi",array("kadaluarsa" => $date))){
				echo json_encode(array("success"=>true,"message"=>"berhasil mengajukan pembatalan pesanan"));
			}else{
				echo json_encode(array("success"=>false,"message"=>"gagal membatalkan pesanan, coba ulangi beberapa saat lagi"));
			}
		}else{
			echo json_encode(array("success"=>false,"message"=>"Forbidden Access"));
		}
	}
	public function requestbatalkanPesanan(){
		if(isset($_POST["pesanan"])){
			$this->db->where("id",$_POST["pesanan"]);
			if($this->db->update("transaksi",array("ajukanbatal" => 1))){
				echo json_encode(array("success"=>true,"message"=>"berhasil mengajukan pembatalan pesanan"));
			}else{
				echo json_encode(array("success"=>false,"message"=>"gagal membatalkan pesanan, coba ulangi beberapa saat lagi"));
			}
		}else{
			echo json_encode(array("success"=>false,"message"=>"Forbidden Access"));
		}
	}
	public function lacakiriman(){
		if(isset($_GET["orderid"])){
			$trx = $this->func->getTransaksi($_GET["orderid"],"semua","orderid");
			$apikey = $this->func->globalset("rajaongkir");

			$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "waybill=".$trx->resi."&courier=".$this->func->getKurir($trx->kurir,"rajaongkir"),
			CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded",
				"key: ".$apikey
			),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				echo "<span class='cl1'>terjadi kendala saat menghubungi pihak ekspedisi, cobalah beberapa saat lagi</span>";
			}else{
				$response = json_decode($response);
				//print_r();
				if($response->rajaongkir->status->code == "200"){
					$respon = $response->rajaongkir->result->manifest;
					if($response->rajaongkir->result->delivered == true){
						echo "
							<div class='m-b-30'>
								Status: <b style='color:#28a745;'>PAKET TELAH DITERIMA</b><br/>
								Penerima: <b>".strtoupper(strtolower($response->rajaongkir->result->delivery_status->pod_receiver))."</b><br/>
								Tgl diterima: ".$this->func->ubahTgl("d M Y H:i",$response->rajaongkir->result->delivery_status->pod_date." ".$response->rajaongkir->result->delivery_status->pod_time)." WIB
							</div>
						";
					}else{
						echo "<div class='m-b-30'>Status: <b style='color:#c0392b;'>PAKET SEDANG DIKIRIM</b></div>";
					}

					echo "
						<div class='row p-tb-10' style='border-bottom: 1px solid #ccc;font-weight:bold;'>
							<div class='col-md-3'>TANGGAL</div>
							<div class='col-md-9'>STATUS</div>
						</div>
					";

					if($response->rajaongkir->result->delivered == true AND $response->rajaongkir->query->courier != "jne"){
						echo "
							<div class='row p-tb-10' style='border-bottom: 1px dashed #ccc;'>
								<div class='col-md-3'>
									<i>".$this->func->ubahTgl("d/m/Y H:i",$response->rajaongkir->result->delivery_status->pod_date." ".$response->rajaongkir->result->delivery_status->pod_time)."WIB</i>
								</div>
								<div class='col-md-9'>
									<i>Diterima oleh ".strtoupper(strtolower($response->rajaongkir->result->delivery_status->pod_receiver))."</i>
								</div>
							</div>
						";
					}

					for($i=0; $i<count($respon); $i++){
						//print_r($respon[$i])."<p/>";
						echo "
							<div class='row p-tb-10' style='border-bottom: 1px dashed #ccc;'>
								<div class='col-md-3'>
									<i>".$this->func->ubahTgl("d/m/Y H:i",$respon[$i]->manifest_date." ".$respon[$i]->manifest_time)." WIB</i>
								</div>
								<div class='col-md-9'>
									<i>".$respon[$i]->manifest_description."</i>
									<i>".$respon[$i]->city_name."</i>
								</div>
							</div>
						";
					}
				}else{
					echo "
						<div class='row p-tb-10' style='border-bottom: 1px dashed #ccc;'>
							<div class='col-md-12'>
								Nomor Resi tidak ditemukan, coba ulangi beberapa jam lagi sampai resi sudah update di sistem pihak ekspedisi.
							</div>
						</div>
					";
				}
			}
		}else{
			echo "<span class='label label-red'><i class='fa fa-exclamation-triangle'></i> terjadi kesalahan sistem, silahkan ualngi beberapa saat lagi.</span>";
		}
	}

	// AMBIL DATA DOMISILI
	public function getkab(){
		$id = (isset($_POST["id"])) ? $_POST["id"] : 0;
		$rajaongkir = $this->func->getProv($id,"rajaongkir");
		$this->db->where("idprov",$id);
		$this->db->order_by("tipe","DESC");
		$db = $this->db->get("kab");
			$result = "<option value=''>Pilih Kabupaten/Kota</option>/n";
		foreach($db->result() as $res){
			$result .= "<option  value='".$res->id."' data-rajaongkir='".$res->rajaongkir."'>".$res->tipe." ".$res->nama."</option>/n";
		}
		echo json_encode(array("rajaongkir"=>$rajaongkir,"html"=>$result,"token"=> $this->security->get_csrf_hash()));
	}
	public function getkec(){
		$id = (isset($_POST["id"])) ? $_POST["id"] : 0;
		$rajaongkir = $this->func->getKab($id,"rajaongkir");
		$this->db->where("idkab",$id);
		$db = $this->db->get("kec");
			$result = "<option value=''>Pilih Kecamatan</option>/n";
		foreach($db->result() as $res){
			$result .= "<option value='".$res->id."' data-rajaongkir='".$res->rajaongkir."'>".$res->nama."</option>/n";
		}
		echo json_encode(array("rajaongkir"=>$rajaongkir,"html"=>$result,"token"=> $this->security->get_csrf_hash()));
	}

	// CEK ONGKIR
	function cekcod(){
		$this->db->where("id",$_POST["paket"]);
		$db = $this->db->get("paket");
		$result = false;
		$set = $this->func->globalset("biaya_cod");
		$biaya = 0;

		foreach($db->result() as $r){
			if($r->cod == 1){
				$result = true;
				$biaya = ($set <= 100 AND $set > 0) ? intval($_POST["total"]) * ($set/100) : $set;
			}
		}

		echo json_encode(["result"=>$result,"biaya"=>round($biaya,0),"token"=> $this->security->get_csrf_hash()]);
	}
	function cekapiongkir(){
		$dari = (isset($_POST["dari"])) ? $_POST["dari"] : 0;
		$tujuan = (isset($_POST["tujuan"])) ? $_POST["tujuan"] : 0;
		$berat = (isset($_POST["berat"])) ? $_POST["berat"] : 1000;
		$kurir = (isset($_POST["kurir"])) ? $_POST["kurir"] : "indah";
		//RAJAONGKIR CONVERT KAB
		$dari = $this->func->getKab($dari,"rajaongkir");
		$tujuan = $this->func->getKec($tujuan,"rajaongkir");

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "origin=".$dari."&originType=city&destination=".$tujuan."&destinationType=subdistrict&weight=".$berat."&courier=".$kurir,
			CURLOPT_HTTPHEADER => array(
			"content-type: application/x-www-form-urlencoded",
			"key: ".$this->func->getSetting("rajaongkir")
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		if($err){
			print_r($err);
		}else{
			print_r($response);
		}
	}
	
	function cekresponongkir(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "origin=445&originType=city&destination=6162&destinationType=subdistrict&weight=10000&courier=indah",
			CURLOPT_HTTPHEADER => array(
			"content-type: application/x-www-form-urlencoded",
			"key: ".$this->func->getSetting("rajaongkir")
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		if($err){
			print_r($err);
		}else{
			print_r($response);
		}
	}

	// CEK URL
	public function cekurl(){
		if(isset($_POST["user"])){
			$this->db->where("url",$_POST["user"]);
			$db = $this->db->get("toko");

			if($db->num_rows() > 0){
				echo json_encode(array("success"=>false,"message"=>"alamat email sudah terdaftar"));
			}else{
				echo json_encode(array("success"=>true,"message"=>""));
			}

		}else{
			redirect("404_notfound");
		}
	}
}
